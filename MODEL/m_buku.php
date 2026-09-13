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
                b.id_buku,
                b.id_kategori,
                b.id_penerbit,
                b.judul_buku,
                b.tahun_terbit,
                b.harga_buku,
                b.stok,
                b.cover,

                k.nama_kategori,
                p.nama_penerbit,

                COALESCE(
                    GROUP_CONCAT(
                        DISTINCT pen.nama_penulis
                        ORDER BY pen.nama_penulis ASC
                        SEPARATOR ', '
                    ),
                    '-'
                ) AS penulis

            FROM buku b

            LEFT JOIN kategori k
                ON b.id_kategori = k.id_kategori

            LEFT JOIN penerbit p
                ON b.id_penerbit = p.id_penerbit

            LEFT JOIN buku_penulis bp
                ON b.id_buku = bp.id_buku

            LEFT JOIN penulis pen
                ON bp.id_penulis = pen.id_penulis

            GROUP BY
                b.id_buku,
                b.id_kategori,
                b.id_penerbit,
                b.judul_buku,
                b.tahun_terbit,
                b.harga_buku,
                b.stok,
                b.cover,
                k.nama_kategori,
                p.nama_penerbit

            ORDER BY b.id_buku DESC
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

        $id_buku = (int) $id_buku;

        $stmt->bind_param("i", $id_buku);
        $stmt->execute();

        $result = $stmt->get_result();

        $data = $result->fetch_assoc();

        $stmt->close();

        return $data ?: null;
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

        $id_buku = (int) $id_buku;

        $stmt->bind_param("i", $id_buku);

        $hasil = $stmt->execute();

        $stmt->close();

        return $hasil;
    }
}