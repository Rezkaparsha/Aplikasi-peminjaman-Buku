<?php

class M_Peminjaman
{
    private $koneksi;

    public function __construct($koneksi)
    {
        $this->koneksi = $koneksi;
        $this->koneksi->set_charset("utf8mb4");
    }


    // =========================================================
    // 1. TAMBAH PENGAJUAN PEMINJAMAN
    // =========================================================
    //
    // $daftarBuku:
    //
    // [
    //     [
    //         'id_buku' => 1,
    //         'jumlah' => 2
    //     ],
    //     [
    //         'id_buku' => 5,
    //         'jumlah' => 1
    //     ]
    // ]
    //
    // =========================================================

    public function tambahPeminjaman($id_user, $daftarBuku)
    {
        if (empty($id_user)) {
            return [
                'status' => false,
                'pesan' => 'User tidak ditemukan.'
            ];
        }

        if (empty($daftarBuku) || !is_array($daftarBuku)) {
            return [
                'status' => false,
                'pesan' => 'Belum ada buku yang dipilih.'
            ];
        }

        $this->koneksi->begin_transaction();

        try {

            // =====================================================
            // CEK DAN GABUNG BUKU YANG SAMA
            // =====================================================

            $bukuGabungan = [];

            foreach ($daftarBuku as $buku) {

                if (
                    !isset($buku['id_buku']) ||
                    !isset($buku['jumlah'])
                ) {
                    throw new Exception(
                        'Data buku tidak lengkap.'
                    );
                }

                $id_buku = (int) $buku['id_buku'];
                $jumlah = (int) $buku['jumlah'];

                if ($id_buku <= 0) {
                    throw new Exception(
                        'ID buku tidak valid.'
                    );
                }

                if ($jumlah <= 0) {
                    throw new Exception(
                        'Jumlah buku minimal 1.'
                    );
                }

                if (isset($bukuGabungan[$id_buku])) {
                    $bukuGabungan[$id_buku] += $jumlah;
                } else {
                    $bukuGabungan[$id_buku] = $jumlah;
                }
            }


            // =====================================================
            // CEK SEMUA BUKU
            // =====================================================

            foreach ($bukuGabungan as $id_buku => $jumlah) {

                $stmt = $this->koneksi->prepare(
                    "SELECT
                        id_buku,
                        judul_buku,
                        stok
                     FROM buku
                     WHERE id_buku = ?
                     FOR UPDATE"
                );

                if (!$stmt) {
                    throw new Exception(
                        'Gagal menyiapkan pengecekan buku.'
                    );
                }

                $stmt->bind_param(
                    "i",
                    $id_buku
                );

                $stmt->execute();

                $result = $stmt->get_result();
                $dataBuku = $result->fetch_assoc();

                $stmt->close();

                if (!$dataBuku) {
                    throw new Exception(
                        "Buku dengan ID {$id_buku} tidak ditemukan."
                    );
                }

                if ((int) $dataBuku['stok'] < $jumlah) {
                    throw new Exception(
                        'Stok buku "' .
                            $dataBuku['judul_buku'] .
                            '" tidak mencukupi. Stok tersedia: ' .
                            $dataBuku['stok'] .
                            '.'
                    );
                }
            }


            // =====================================================
            // SIMPAN HEADER PEMINJAMAN
            // =====================================================

            $tanggal_pinjam = date('Y-m-d');
            $status = 'Diajukan';

            $stmt = $this->koneksi->prepare(
                "INSERT INTO peminjaman
                (
                    id_user,
                    tanggal_pinjam,
                    status
                )
                VALUES (?, ?, ?)"
            );

            if (!$stmt) {
                throw new Exception(
                    'Gagal menyiapkan data peminjaman.'
                );
            }

            $stmt->bind_param(
                "iss",
                $id_user,
                $tanggal_pinjam,
                $status
            );

            if (!$stmt->execute()) {
                throw new Exception(
                    'Gagal menyimpan pengajuan peminjaman.'
                );
            }

            $id_peminjaman = $this->koneksi->insert_id;

            $stmt->close();


            // =====================================================
            // SIMPAN DETAIL PEMINJAMAN
            // =====================================================

            $statusDetail = 'Diajukan';

            $stmtDetail = $this->koneksi->prepare(
                "INSERT INTO detail_peminjaman
                (
                    id_peminjaman,
                    id_buku,
                    jumlah_buku,
                    tanggal_pengembalian,
                    tanggal_dikembalikan,
                    status
                )
                VALUES (?, ?, ?, NULL, NULL, ?)"
            );

            if (!$stmtDetail) {
                throw new Exception(
                    'Gagal menyiapkan detail peminjaman.'
                );
            }

            foreach ($bukuGabungan as $id_buku => $jumlah) {

                $stmtDetail->bind_param(
                    "iiis",
                    $id_peminjaman,
                    $id_buku,
                    $jumlah,
                    $statusDetail
                );

                if (!$stmtDetail->execute()) {
                    throw new Exception(
                        'Gagal menyimpan detail buku.'
                    );
                }
            }

            $stmtDetail->close();


            // =====================================================
            // SEMUA BERHASIL
            // =====================================================

            $this->koneksi->commit();

            return [
                'status' => true,
                'pesan' => 'Pengajuan peminjaman berhasil dibuat.',
                'id_peminjaman' => $id_peminjaman
            ];
        } catch (Exception $e) {

            $this->koneksi->rollback();

            return [
                'status' => false,
                'pesan' => $e->getMessage()
            ];
        }
    }


    // =========================================================
    // 2. AMBIL SEMUA PEMINJAMAN MILIK SISWA
    // =========================================================

    public function getPeminjamanByUser($id_user)
    {
        $sql = "
            SELECT
                p.id_peminjaman,
                p.id_user,
                p.tanggal_pinjam,
                p.status,

                u.nis,
                u.nama_lengkap,

                COUNT(dp.id_detail) AS jumlah_jenis_buku,

                COALESCE(
                    SUM(dp.jumlah_buku),
                    0
                ) AS total_buku

            FROM peminjaman p

            INNER JOIN users u
                ON p.id_user = u.id_user

            LEFT JOIN detail_peminjaman dp
                ON p.id_peminjaman = dp.id_peminjaman

            WHERE p.id_user = ?

            GROUP BY
                p.id_peminjaman,
                p.id_user,
                p.tanggal_pinjam,
                p.status,
                u.nis,
                u.nama_lengkap

            ORDER BY p.id_peminjaman DESC
        ";

        $stmt = $this->koneksi->prepare($sql);

        if (!$stmt) {
            return [];
        }

        $stmt->bind_param(
            "i",
            $id_user
        );

        $stmt->execute();

        $result = $stmt->get_result();

        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        $stmt->close();

        return $data;
    }


    // =========================================================
    // 3. AMBIL SEMUA PEMINJAMAN UNTUK ADMIN
    // =========================================================

    public function getSemuaPeminjaman()
    {
        $sql = "
            SELECT
                p.id_peminjaman,
                p.id_user,
                p.tanggal_pinjam,
                p.status,

                u.nis,
                u.nama_lengkap,

                COUNT(dp.id_detail) AS jumlah_jenis_buku,

                COALESCE(
                    SUM(dp.jumlah_buku),
                    0
                ) AS total_buku

            FROM peminjaman p

            INNER JOIN users u
                ON p.id_user = u.id_user

            LEFT JOIN detail_peminjaman dp
                ON p.id_peminjaman = dp.id_peminjaman

            GROUP BY
                p.id_peminjaman,
                p.id_user,
                p.tanggal_pinjam,
                p.status,
                u.nis,
                u.nama_lengkap

            ORDER BY p.id_peminjaman DESC
        ";

        $result = $this->koneksi->query($sql);

        if (!$result) {
            return [];
        }

        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        return $data;
    }


    // =========================================================
    // 4. AMBIL DETAIL SATU PEMINJAMAN
    // =========================================================

    public function getDetailPeminjaman($id_peminjaman)
    {
        $sql = "
            SELECT
                p.id_peminjaman,
                p.id_user,
                p.tanggal_pinjam,
                p.status AS status_peminjaman,

                u.nis,
                u.nama_lengkap,

                dp.id_detail,
                dp.id_buku,
                dp.jumlah_buku,
                dp.tanggal_pengembalian,
                dp.tanggal_dikembalikan,
                dp.status AS status_detail,

                b.judul_buku,
                b.harga_buku,
                b.stok,
                b.cover

            FROM peminjaman p

            INNER JOIN users u
                ON p.id_user = u.id_user

            INNER JOIN detail_peminjaman dp
                ON p.id_peminjaman = dp.id_peminjaman

            INNER JOIN buku b
                ON dp.id_buku = b.id_buku

            WHERE p.id_peminjaman = ?

            ORDER BY dp.id_detail ASC
        ";

        $stmt = $this->koneksi->prepare($sql);

        if (!$stmt) {
            return [];
        }

        $stmt->bind_param(
            "i",
            $id_peminjaman
        );

        $stmt->execute();

        $result = $stmt->get_result();

        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        $stmt->close();

        return $data;
    }


    // =========================================================
    // 5. AMBIL SATU PEMINJAMAN
    // =========================================================

    public function getPeminjamanById($id_peminjaman)
    {
        $sql = "
            SELECT
                p.id_peminjaman,
                p.id_user,
                p.tanggal_pinjam,
                p.status,

                u.nis,
                u.nama_lengkap

            FROM peminjaman p

            INNER JOIN users u
                ON p.id_user = u.id_user

            WHERE p.id_peminjaman = ?
        ";

        $stmt = $this->koneksi->prepare($sql);

        if (!$stmt) {
            return null;
        }

        $stmt->bind_param(
            "i",
            $id_peminjaman
        );

        $stmt->execute();

        $result = $stmt->get_result();

        $data = $result->fetch_assoc();

        $stmt->close();

        return $data;
    }


    // =========================================================
    // 6. SETUJUI PEMINJAMAN
    // =========================================================
    //
    // Ketika admin menyetujui:
    //
    // peminjaman       : Diajukan -> Dipinjam
    // detail            : Diajukan -> Dipinjam
    // stok buku         : berkurang
    // tanggal kembali   : diisi
    //
    // =========================================================

    public function setujuiPeminjaman(
        $id_peminjaman,
        $tanggal_pengembalian
    ) {
        if (empty($tanggal_pengembalian)) {
            return [
                'status' => false,
                'pesan' => 'Tanggal pengembalian harus diisi.'
            ];
        }

        $this->koneksi->begin_transaction();

        try {

            // =====================================================
            // LOCK DATA PEMINJAMAN
            // =====================================================

            $stmt = $this->koneksi->prepare(
                "SELECT
                    id_peminjaman,
                    status
                 FROM peminjaman
                 WHERE id_peminjaman = ?
                 FOR UPDATE"
            );

            if (!$stmt) {
                throw new Exception(
                    'Gagal mengambil data peminjaman.'
                );
            }

            $stmt->bind_param(
                "i",
                $id_peminjaman
            );

            $stmt->execute();

            $result = $stmt->get_result();

            $peminjaman = $result->fetch_assoc();

            $stmt->close();

            if (!$peminjaman) {
                throw new Exception(
                    'Peminjaman tidak ditemukan.'
                );
            }

            if ($peminjaman['status'] !== 'Diajukan') {
                throw new Exception(
                    'Peminjaman sudah diproses.'
                );
            }


            // =====================================================
            // AMBIL DETAIL + LOCK STOK BUKU
            // =====================================================

            $stmt = $this->koneksi->prepare(
                "SELECT
                    dp.id_detail,
                    dp.id_buku,
                    dp.jumlah_buku,

                    b.judul_buku,
                    b.stok

                 FROM detail_peminjaman dp

                 INNER JOIN buku b
                    ON dp.id_buku = b.id_buku

                 WHERE dp.id_peminjaman = ?

                 FOR UPDATE"
            );

            if (!$stmt) {
                throw new Exception(
                    'Gagal mengambil detail peminjaman.'
                );
            }

            $stmt->bind_param(
                "i",
                $id_peminjaman
            );

            $stmt->execute();

            $result = $stmt->get_result();

            $detail = [];

            while ($row = $result->fetch_assoc()) {
                $detail[] = $row;
            }

            $stmt->close();

            if (empty($detail)) {
                throw new Exception(
                    'Detail buku tidak ditemukan.'
                );
            }


            // =====================================================
            // CEK SEMUA STOK
            // =====================================================

            foreach ($detail as $item) {

                if (
                    (int) $item['stok'] <
                    (int) $item['jumlah_buku']
                ) {
                    throw new Exception(
                        'Stok buku "' .
                            $item['judul_buku'] .
                            '" tidak mencukupi.'
                    );
                }
            }


            // =====================================================
            // KURANGI STOK
            // =====================================================

            $stmtStok = $this->koneksi->prepare(
                "UPDATE buku
                 SET stok = stok - ?
                 WHERE id_buku = ?
                   AND stok >= ?"
            );

            if (!$stmtStok) {
                throw new Exception(
                    'Gagal menyiapkan update stok.'
                );
            }

            foreach ($detail as $item) {

                $jumlah = (int) $item['jumlah_buku'];
                $id_buku = (int) $item['id_buku'];

                $stmtStok->bind_param(
                    "iii",
                    $jumlah,
                    $id_buku,
                    $jumlah
                );

                if (!$stmtStok->execute()) {
                    throw new Exception(
                        'Gagal mengurangi stok buku.'
                    );
                }

                if ($stmtStok->affected_rows === 0) {
                    throw new Exception(
                        'Stok buku tidak mencukupi.'
                    );
                }
            }

            $stmtStok->close();


            // =====================================================
            // UPDATE DETAIL
            // =====================================================

            $statusDetail = 'Dipinjam';

            $stmt = $this->koneksi->prepare(
                "UPDATE detail_peminjaman
                 SET
                    tanggal_pengembalian = ?,
                    status = ?
                 WHERE id_peminjaman = ?"
            );

            if (!$stmt) {
                throw new Exception(
                    'Gagal memperbarui detail peminjaman.'
                );
            }

            $stmt->bind_param(
                "ssi",
                $tanggal_pengembalian,
                $statusDetail,
                $id_peminjaman
            );

            if (!$stmt->execute()) {
                throw new Exception(
                    'Gagal memperbarui detail peminjaman.'
                );
            }

            $stmt->close();


            // =====================================================
            // UPDATE STATUS PEMINJAMAN
            // =====================================================

            $statusPeminjaman = 'Dipinjam';

            $stmt = $this->koneksi->prepare(
                "UPDATE peminjaman
                 SET status = ?
                 WHERE id_peminjaman = ?"
            );

            if (!$stmt) {
                throw new Exception(
                    'Gagal memperbarui status peminjaman.'
                );
            }

            $stmt->bind_param(
                "si",
                $statusPeminjaman,
                $id_peminjaman
            );

            if (!$stmt->execute()) {
                throw new Exception(
                    'Gagal memperbarui status peminjaman.'
                );
            }

            $stmt->close();


            // =====================================================
            // COMMIT
            // =====================================================

            $this->koneksi->commit();

            return [
                'status' => true,
                'pesan' => 'Peminjaman berhasil disetujui.'
            ];
        } catch (Exception $e) {

            $this->koneksi->rollback();

            return [
                'status' => false,
                'pesan' => $e->getMessage()
            ];
        }
    }


    // =========================================================
    // 7. TOLAK PEMINJAMAN
    // =========================================================

    public function tolakPeminjaman($id_peminjaman)
    {
        $stmt = $this->koneksi->prepare(
            "UPDATE peminjaman
             SET status = 'Ditolak'
             WHERE id_peminjaman = ?
               AND status = 'Diajukan'"
        );

        if (!$stmt) {
            return [
                'status' => false,
                'pesan' => 'Gagal menyiapkan penolakan.'
            ];
        }

        $stmt->bind_param(
            "i",
            $id_peminjaman
        );

        if (!$stmt->execute()) {

            $stmt->close();

            return [
                'status' => false,
                'pesan' => 'Gagal menolak peminjaman.'
            ];
        }

        if ($stmt->affected_rows === 0) {

            $stmt->close();

            return [
                'status' => false,
                'pesan' =>
                'Peminjaman tidak ditemukan atau sudah diproses.'
            ];
        }

        $stmt->close();

        return [
            'status' => true,
            'pesan' => 'Peminjaman berhasil ditolak.'
        ];
    }


    // =========================================================
    // 8. CEK KEPEMILIKAN PEMINJAMAN
    // =========================================================

    public function cekKepemilikanPeminjaman(
        $id_peminjaman,
        $id_user
    ) {
        $stmt = $this->koneksi->prepare(
            "SELECT id_peminjaman
             FROM peminjaman
             WHERE id_peminjaman = ?
               AND id_user = ?"
        );

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param(
            "ii",
            $id_peminjaman,
            $id_user
        );

        $stmt->execute();

        $result = $stmt->get_result();

        $ada = $result->num_rows > 0;

        $stmt->close();

        return $ada;
    }
}
