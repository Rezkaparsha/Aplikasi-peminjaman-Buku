<?php

require_once __DIR__ . "/m_koneksi.php";

class Kategori
{
    private $koneksi;

    public $id_kategori;
    public $nama_kategori;
    public $deskripsi;
    public $status;

    public function __construct()
    {
        $db = new Koneksi();
        $this->koneksi = $db->getKoneksi();
    }

    public function getAll()
    {
        $query = "SELECT id_kategori, nama_kategori, deskripsi, status
                  FROM kategori
                  ORDER BY nama_kategori ASC";

        $result = $this->koneksi->query($query);
        $data = [];

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }

        return $data;
    }

    public function getById($id_kategori)
    {
        $stmt = $this->koneksi->prepare(
            "SELECT id_kategori, nama_kategori, deskripsi, status
             FROM kategori
             WHERE id_kategori = ?"
        );

        if (!$stmt) {
            return null;
        }

        $stmt->bind_param("i", $id_kategori);
        $stmt->execute();

        $data = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $data;
    }

    public function getAktif()
    {
        $stmt = $this->koneksi->prepare(
            "SELECT id_kategori, nama_kategori, deskripsi, status
             FROM kategori
             WHERE status = 'aktif'
             ORDER BY nama_kategori ASC"
        );

        if (!$stmt) {
            return [];
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        $stmt->close();

        return $data;
    }

    public function insert()
    {
        $stmt = $this->koneksi->prepare(
            "INSERT INTO kategori (nama_kategori, deskripsi, status)
             VALUES (?, ?, ?)"
        );

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param(
            "sss",
            $this->nama_kategori,
            $this->deskripsi,
            $this->status
        );

        $hasil = $stmt->execute();
        $stmt->close();

        return $hasil;
    }

    public function update()
    {
        $stmt = $this->koneksi->prepare(
            "UPDATE kategori
             SET nama_kategori = ?, deskripsi = ?, status = ?
             WHERE id_kategori = ?"
        );

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param(
            "sssi",
            $this->nama_kategori,
            $this->deskripsi,
            $this->status,
            $this->id_kategori
        );

        $hasil = $stmt->execute();
        $stmt->close();

        return $hasil;
    }

    public function delete($id_kategori)
    {
        $stmt = $this->koneksi->prepare(
            "DELETE FROM kategori WHERE id_kategori = ?"
        );

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("i", $id_kategori);
        $hasil = $stmt->execute();
        $stmt->close();

        return $hasil;
    }
}