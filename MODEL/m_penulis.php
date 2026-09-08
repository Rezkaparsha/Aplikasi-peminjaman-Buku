<?php

require_once __DIR__ . "/m_koneksi.php";

class Penulis
{
    private $koneksi;

    public $id_penulis;
    public $nama_penulis;

    public function __construct()
    {
        $db = new Koneksi();
        $this->koneksi = $db->getKoneksi();
    }

    // MENAMPILKAN SEMUA PENULIS
    public function getAll()
    {
        $query = "
            SELECT
                id_penulis,
                nama_penulis
            FROM penulis
            ORDER BY nama_penulis ASC
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

    // MENCARI PENULIS BERDASARKAN ID
    public function getById($id_penulis)
    {
        $stmt = $this->koneksi->prepare("
            SELECT
                id_penulis,
                nama_penulis
            FROM penulis
            WHERE id_penulis = ?
        ");

        if (!$stmt) {
            return null;
        }

        $stmt->bind_param("i", $id_penulis);
        $stmt->execute();

        $result = $stmt->get_result();
        $data = $result->fetch_assoc();

        $stmt->close();

        return $data;
    }

    // MENAMBAHKAN PENULIS
    public function insert()
    {
        $stmt = $this->koneksi->prepare("
            INSERT INTO penulis
            (nama_penulis)
            VALUES (?)
        ");

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param(
            "s",
            $this->nama_penulis
        );

        $hasil = $stmt->execute();

        if ($hasil) {
            $this->id_penulis = $stmt->insert_id;
        }

        $stmt->close();

        return $hasil;
    }

    // UPDATE PENULIS
    public function update()
    {
        $stmt = $this->koneksi->prepare("
            UPDATE penulis
            SET nama_penulis = ?
            WHERE id_penulis = ?
        ");

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param(
            "si",
            $this->nama_penulis,
            $this->id_penulis
        );

        $hasil = $stmt->execute();

        $stmt->close();

        return $hasil;
    }

    // HAPUS PENULIS
    public function delete($id_penulis)
    {
        $stmt = $this->koneksi->prepare("
            DELETE FROM penulis
            WHERE id_penulis = ?
        ");

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param(
            "i",
            $id_penulis
        );

        $hasil = $stmt->execute();

        $stmt->close();

        return $hasil;
    }
}
?>