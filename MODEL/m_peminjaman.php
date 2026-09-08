<?php

require_once __DIR__ . "/m_koneksi.php";

class Peminjaman
{
    private $koneksi;

    public function __construct()
    {
        $db = new Koneksi();
        $this->koneksi = $db->getKoneksi();
    }

    public function getAll()
    {
        $query = "
            SELECT
                p.id_peminjaman,
                p.id_user,
                u.nis,
                u.nama_lengkap,
                p.tanggal_pinjam,
                p.status
            FROM peminjaman p
            INNER JOIN users u
                ON p.id_user = u.id_user
            ORDER BY p.id_peminjaman DESC
        ";

        $result = $this->koneksi->query($query);
        $data = [];

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }

        return $data;
    }

    public function getById($id_peminjaman)
    {
        $stmt = $this->koneksi->prepare("
            SELECT
                p.id_peminjaman,
                p.id_user,
                u.nis,
                u.nama_lengkap,
                p.tanggal_pinjam,
                p.status
            FROM peminjaman p
            INNER JOIN users u
                ON p.id_user = u.id_user
            WHERE p.id_peminjaman = ?
        ");

        if (!$stmt) {
            return null;
        }

        $stmt->bind_param("i", $id_peminjaman);
        $stmt->execute();

        $data = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $data;
    }

    public function getDetail($id_peminjaman)
    {
        $stmt = $this->koneksi->prepare("
            SELECT
                dp.id_detail,
                dp.id_peminjaman,
                dp.id_buku,
                b.judul_buku,
                b.harga_buku,
                dp.tanggal_pengembalian,
                dp.tanggal_dikembalikan,
                dp.status
            FROM detail_peminjaman dp
            INNER JOIN buku b
                ON dp.id_buku = b.id_buku
            WHERE dp.id_peminjaman = ?
            ORDER BY dp.id_detail ASC
        ");

        if (!$stmt) {
            return [];
        }

        $stmt->bind_param("i", $id_peminjaman);
        $stmt->execute();

        $result = $stmt->get_result();
        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        $stmt->close();

        return $data;
    }

    public function insertPeminjaman($id_user, $tanggal_pinjam)
    {
        $stmt = $this->koneksi->prepare("
            INSERT INTO peminjaman
            (id_user, tanggal_pinjam, status)
            VALUES (?, ?, 'Diajukan')
        ");

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("is", $id_user, $tanggal_pinjam);

        $hasil = $stmt->execute();

        if ($hasil) {
            $idPeminjaman = $stmt->insert_id;
        } else {
            $idPeminjaman = false;
        }

        $stmt->close();

        return $idPeminjaman;
    }

    public function insertDetail(
        $id_peminjaman,
        $id_buku,
        $tanggal_pengembalian
    ) {
        $stmt = $this->koneksi->prepare("
            INSERT INTO detail_peminjaman
            (
                id_peminjaman,
                id_buku,
                tanggal_pengembalian,
                status
            )
            VALUES (?, ?, ?, 'Dipinjam')
        ");

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param(
            "iis",
            $id_peminjaman,
            $id_buku,
            $tanggal_pengembalian
        );

        $hasil = $stmt->execute();
        $stmt->close();

        return $hasil;
    }

    public function updateStatus($id_peminjaman, $status)
    {
        $stmt = $this->koneksi->prepare("
            UPDATE peminjaman
            SET status = ?
            WHERE id_peminjaman = ?
        ");

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("si", $status, $id_peminjaman);

        $hasil = $stmt->execute();
        $stmt->close();

        return $hasil;
    }

    public function updateStatusDetail($id_detail, $status)
    {
        $stmt = $this->koneksi->prepare("
            UPDATE detail_peminjaman
            SET status = ?
            WHERE id_detail = ?
        ");

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("si", $status, $id_detail);

        $hasil = $stmt->execute();
        $stmt->close();

        return $hasil;
    }

    public function getByUser($id_user)
    {
        $stmt = $this->koneksi->prepare("
            SELECT
                p.id_peminjaman,
                p.id_user,
                p.tanggal_pinjam,
                p.status
            FROM peminjaman p
            WHERE p.id_user = ?
            ORDER BY p.id_peminjaman DESC
        ");

        if (!$stmt) {
            return [];
        }

        $stmt->bind_param("i", $id_user);
        $stmt->execute();

        $result = $stmt->get_result();
        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        $stmt->close();

        return $data;
    }

    public function getDetailById($id_detail)
    {
        $stmt = $this->koneksi->prepare("
            SELECT
                dp.id_detail,
                dp.id_peminjaman,
                dp.id_buku,
                b.judul_buku,
                b.harga_buku,
                dp.tanggal_pengembalian,
                dp.tanggal_dikembalikan,
                dp.status
            FROM detail_peminjaman dp
            INNER JOIN buku b
                ON dp.id_buku = b.id_buku
            WHERE dp.id_detail = ?
        ");

        if (!$stmt) {
            return null;
        }

        $stmt->bind_param("i", $id_detail);
        $stmt->execute();

        $data = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $data;
    }

    public function updateTanggalDikembalikan($id_detail, $tanggal_dikembalikan)
    {
        $stmt = $this->koneksi->prepare("
            UPDATE detail_peminjaman
            SET
                tanggal_dikembalikan = ?,
                status = 'Dikembalikan'
            WHERE id_detail = ?
        ");

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param(
            "si",
            $tanggal_dikembalikan,
            $id_detail
        );

        $hasil = $stmt->execute();
        $stmt->close();

        return $hasil;
    }
}