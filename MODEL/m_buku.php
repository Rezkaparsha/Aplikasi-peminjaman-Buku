<?php

require_once __DIR__ . "/m_koneksi.php";

class Buku
{
    private $koneksi;

    public $id_buku;
    public $id_kategori;
    public $id_penerbit;
    public $judul_buku;
    public $tahun_terbit;
    public $harga_buku;
    public $stok;
    public $cover;

    public function __construct()
    {
        $db = new Koneksi();
        $this->koneksi = $db->getKoneksi();
    }

    // GET SEMUA BUKU
    public function getAllBuku()
    {
        $query = "
            SELECT
                buku.id_buku,
                buku.id_kategori,
                buku.id_penerbit,
                buku.judul_buku,
                buku.tahun_terbit,
                buku.harga_buku,
                buku.stok,
                buku.cover,

                kategori.nama_kategori,
                penerbit.nama_penerbit,

                GROUP_CONCAT(
                    DISTINCT penulis.nama_penulis
                    ORDER BY penulis.nama_penulis ASC
                    SEPARATOR ', '
                ) AS penulis

            FROM buku

            LEFT JOIN kategori
                ON buku.id_kategori = kategori.id_kategori

            LEFT JOIN penerbit
                ON buku.id_penerbit = penerbit.id_penerbit

            LEFT JOIN buku_penulis
                ON buku.id_buku = buku_penulis.id_buku

            LEFT JOIN penulis
                ON buku_penulis.id_penulis = penulis.id_penulis

            GROUP BY
                buku.id_buku,
                buku.id_kategori,
                buku.id_penerbit,
                buku.judul_buku,
                buku.tahun_terbit,
                buku.harga_buku,
                buku.stok,
                buku.cover,
                kategori.nama_kategori,
                penerbit.nama_penerbit

            ORDER BY buku.id_buku DESC
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

    // GET BUKU BERDASARKAN ID
    public function getBukuById($id_buku)
    {
        $stmt = $this->koneksi->prepare("
            SELECT
                id_buku,
                id_kategori,
                id_penerbit,
                judul_buku,
                tahun_terbit,
                harga_buku,
                stok,
                cover
            FROM buku
            WHERE id_buku = ?
        ");

        if (!$stmt) {
            return null;
        }

        $stmt->bind_param("i", $id_buku);
        $stmt->execute();

        $result = $stmt->get_result();
        $data = $result->fetch_assoc();

        $stmt->close();

        return $data;
    }

    // TAMBAH BUKU
    public function insert()
    {
        $stmt = $this->koneksi->prepare("
            INSERT INTO buku
            (
                id_kategori,
                id_penerbit,
                judul_buku,
                tahun_terbit,
                harga_buku,
                stok,
                cover
            )
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param(
            "iisiiis",
            $this->id_kategori,
            $this->id_penerbit,
            $this->judul_buku,
            $this->tahun_terbit,
            $this->harga_buku,
            $this->stok,
            $this->cover
        );

        $hasil = $stmt->execute();

        if ($hasil) {
            $this->id_buku = $stmt->insert_id;
        }

        $stmt->close();

        return $hasil;
    }

    // UPDATE BUKU
    public function update()
    {
        $stmt = $this->koneksi->prepare("
            UPDATE buku
            SET
                id_kategori = ?,
                id_penerbit = ?,
                judul_buku = ?,
                tahun_terbit = ?,
                harga_buku = ?,
                stok = ?,
                cover = ?
            WHERE id_buku = ?
        ");

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param(
            "iisiiisi",
            $this->id_kategori,
            $this->id_penerbit,
            $this->judul_buku,
            $this->tahun_terbit,
            $this->harga_buku,
            $this->stok,
            $this->cover,
            $this->id_buku
        );

        $hasil = $stmt->execute();
        $stmt->close();

        return $hasil;
    }

    // HAPUS BUKU
    public function delete($id_buku)
    {
        $stmt = $this->koneksi->prepare("
            DELETE FROM buku
            WHERE id_buku = ?
        ");

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("i", $id_buku);

        $hasil = $stmt->execute();
        $stmt->close();

        return $hasil;
    }
}
?>