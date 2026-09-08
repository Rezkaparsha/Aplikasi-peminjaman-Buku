<?php

require_once __DIR__ . "/../MODEL/m_kategori.php";

$kategoriModel = new Kategori();
$aksi = $_GET['aksi'] ?? '';

if ($aksi === 'tambah') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: ../VIEW/ADMIN/tambahKategori.php");
        exit;
    }

    $namaKategori = trim($_POST['nama_kategori'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $status = $_POST['status'] ?? 'aktif';

    if ($namaKategori === '') {
        echo "Nama kategori wajib diisi.";
        exit;
    }

    if (!in_array($status, ['aktif', 'nonaktif'], true)) {
        $status = 'aktif';
    }

    $kategoriModel->nama_kategori = $namaKategori;
    $kategoriModel->deskripsi = $deskripsi;
    $kategoriModel->status = $status;

    if ($kategoriModel->insert()) {
        header("Location: ../VIEW/ADMIN/daftarKategori.php");
        exit;
    }

    echo "Gagal menambahkan kategori.";
    exit;
}

if ($aksi === 'edit') {
    $idKategori = (int)($_GET['id_kategori'] ?? 0);

    if ($idKategori <= 0) {
        header("Location: ../VIEW/ADMIN/daftarKategori.php");
        exit;
    }

    $dataKategori = $kategoriModel->getById($idKategori);

    if (!$dataKategori) {
        echo "Data kategori tidak ditemukan.";
        exit;
    }

    require_once __DIR__ . "/../VIEW/ADMIN/editKategori.php";
    exit;
}

if ($aksi === 'update') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: ../VIEW/ADMIN/daftarKategori.php");
        exit;
    }

    $idKategori = (int)($_POST['id_kategori'] ?? 0);
    $namaKategori = trim($_POST['nama_kategori'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $status = $_POST['status'] ?? 'aktif';

    if ($idKategori <= 0 || $namaKategori === '') {
        echo "Data kategori tidak valid.";
        exit;
    }

    if (!in_array($status, ['aktif', 'nonaktif'], true)) {
        $status = 'aktif';
    }

    $kategoriModel->id_kategori = $idKategori;
    $kategoriModel->nama_kategori = $namaKategori;
    $kategoriModel->deskripsi = $deskripsi;
    $kategoriModel->status = $status;

    if ($kategoriModel->update()) {
        header("Location: ../VIEW/ADMIN/daftarKategori.php");
        exit;
    }

    echo "Gagal memperbarui kategori.";
    exit;
}

if ($aksi === 'hapus') {
    $idKategori = (int)($_GET['id_kategori'] ?? 0);

    if ($idKategori <= 0) {
        header("Location: ../VIEW/ADMIN/daftarKategori.php");
        exit;
    }

    if ($kategoriModel->delete($idKategori)) {
        header("Location: ../VIEW/ADMIN/daftarKategori.php");
        exit;
    }

    echo "<h3>Kategori tidak dapat dihapus.</h3>
          <p>Kategori mungkin masih digunakan oleh salah satu buku.</p>
          <a href='../VIEW/ADMIN/daftarKategori.php'>Kembali</a>";
    exit;
}

header("Location: ../VIEW/ADMIN/daftarKategori.php");
exit;