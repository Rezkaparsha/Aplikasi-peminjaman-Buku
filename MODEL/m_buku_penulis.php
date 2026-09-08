<?php

require_once __DIR__ . "/m_koneksi.php";

class BukuPenulis
{
    private $koneksi;

    public $id_buku;
    public $id_penulis;

    public function __construct()
    {
        $db = new Koneksi();
        $this->koneksi = $db->getKoneksi();
    }

    // MENAMPILKAN SEMUA RELASI BUKU DAN PENULIS
    public function getAll()
    {
        $query = "
            SELECT
                buku_penulis.id_buku,
                buku.judul_buku,
                buku_penulis.id_penulis,
                penulis.nama_penulis
            FROM buku_penulis
            INNER JOIN buku
                ON buku_penulis.id_buku = buku.id_buku
            INNER JOIN penulis
                ON buku_penulis.id_penulis = penulis.id_penulis
            ORDER BY buku.judul_buku ASC
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

    // MENCARI SEMUA PENULIS DARI SATU BUKU
    public function getPenulisByBuku($id_buku)
    {
        $stmt = $this->koneksi->prepare("
            SELECT
                penulis.id_penulis,
                penulis.nama_penulis
            FROM buku_penulis
            INNER JOIN penulis
                ON buku_penulis.id_penulis = penulis.id_penulis
            WHERE buku_penulis.id_buku = ?
            ORDER BY penulis.nama_penulis ASC
        ");

        if (!$stmt) {
            return [];
        }

        $stmt->bind_param("i", $id_buku);
        $stmt->execute();

        $result = $stmt->get_result();
        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        $stmt->close();

        return $data;
    }

    // MENCARI SEMUA BUKU DARI SATU PENULIS
    public function getBukuByPenulis($id_penulis)
    {
        $stmt = $this->koneksi->prepare("
            SELECT
                buku.id_buku,
                buku.judul_buku
            FROM buku_penulis
            INNER JOIN buku
                ON buku_penulis.id_buku = buku.id_buku
            WHERE buku_penulis.id_penulis = ?
            ORDER BY buku.judul_buku ASC
        ");

        if (!$stmt) {
            return [];
        }

        $stmt->bind_param("i", $id_penulis);
        $stmt->execute();

        $result = $stmt->get_result();
        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        $stmt->close();

        return $data;
    }

    // MENAMBAHKAN PENULIS KE BUKU
    public function insert()
    {
        $stmt = $this->koneksi->prepare("
            INSERT INTO buku_penulis
            (id_buku, id_penulis)
            VALUES (?, ?)
        ");

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param(
            "ii",
            $this->id_buku,
            $this->id_penulis
        );

        $hasil = $stmt->execute();
        $stmt->close();

        return $hasil;
    }

    // MENGHAPUS SATU RELASI
    public function delete($id_buku, $id_penulis)
    {
        $stmt = $this->koneksi->prepare("
            DELETE FROM buku_penulis
            WHERE id_buku = ?
            AND id_penulis = ?
        ");

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param(
            "ii",
            $id_buku,
            $id_penulis
        );

        $hasil = $stmt->execute();
        $stmt->close();

        return $hasil;
    }

    // MENGHAPUS SEMUA PENULIS DARI SATU BUKU
    public function deleteByBuku($id_buku)
    {
        $stmt = $this->koneksi->prepare("
            DELETE FROM buku_penulis
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