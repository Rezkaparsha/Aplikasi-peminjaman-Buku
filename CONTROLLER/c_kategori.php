<?php
// WAJIB: Jalankan session agar $_SESSION bisa berfungsi
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../MODEL/m_kategori.php";

$kategoriModel = new Kategori();
$aksi = $_GET['aksi'] ?? '';

// 1. TAMBAH KATEGORI
if ($aksi === 'tambah') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: ../VIEW/ADMIN/tambahKategori.php");
        exit;
    }

    $namaKategori = trim($_POST['nama_kategori'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $status = $_POST['status'] ?? 'aktif';

    if ($namaKategori === '') {
        $_SESSION['error'] = "Nama kategori wajib diisi.";
        header("Location: ../VIEW/ADMIN/daftarKategori.php");
        exit;
    }

    if (!in_array($status, ['aktif', 'nonaktif'], true)) {
        $status = 'aktif';
    }

    $kategoriModel->nama_kategori = $namaKategori;
    $kategoriModel->deskripsi = $deskripsi;
    $kategoriModel->status = $status;

    if ($kategoriModel->insert()) {
        $_SESSION['success'] = "Kategori berhasil ditambahkan.";
    } else {
        $_SESSION['error'] = "Gagal menambahkan kategori.";
    }
    header("Location: ../VIEW/ADMIN/daftarKategori.php");
    exit;
}

// 2. TAMPILKAN FORM EDIT
elseif ($aksi === 'edit') {
    $idKategori = (int)($_GET['id_kategori'] ?? 0);

    if ($idKategori <= 0) {
        header("Location: ../VIEW/ADMIN/daftarKategori.php");
        exit;
    }

    $dataKategori = $kategoriModel->getById($idKategori);

    if (!$dataKategori) {
        $_SESSION['error'] = "Data kategori tidak ditemukan.";
        header("Location: ../VIEW/ADMIN/daftarKategori.php");
        exit;
    }

    require_once __DIR__ . "/../VIEW/ADMIN/editKategori.php";
    exit;
}

// 3. PROSES UPDATE KATEGORI
elseif ($aksi === 'update') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: ../VIEW/ADMIN/daftarKategori.php");
        exit;
    }

    $idKategori = (int)($_POST['id_kategori'] ?? 0);
    $namaKategori = trim($_POST['nama_kategori'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $status = $_POST['status'] ?? 'aktif';

    if ($idKategori <= 0 || $namaKategori === '') {
        $_SESSION['error'] = "Data kategori tidak valid.";
        header("Location: ../VIEW/ADMIN/daftarKategori.php");
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
        $_SESSION['success'] = "Data kategori berhasil diperbarui.";
    } else {
        $_SESSION['error'] = "Gagal memperbarui kategori.";
    }
    header("Location: ../VIEW/ADMIN/daftarKategori.php");
    exit;
}

// 4. HAPUS KATEGORI
elseif ($aksi === 'hapus') {
    $idKategori = (int)($_GET['id_kategori'] ?? 0);

    if ($idKategori > 0) {
        // Cek keterhubungan dengan buku
        if ($kategoriModel->isUsedInBuku($idKategori)) {
            $_SESSION['error'] = "Kategori tidak dapat dihapus karena masih terhubung dengan data buku di perpustakaan!";
        } else {
            $hapus = $kategoriModel->delete($idKategori);
            if ($hapus) {
                $_SESSION['success'] = "Kategori berhasil dihapus.";
            } else {
                $_SESSION['error'] = "Gagal menghapus kategori.";
            }
        }
    } else {
        $_SESSION['error'] = "ID Kategori tidak valid.";
    }

    header("Location: ../VIEW/ADMIN/daftarKategori.php");
    exit;
}

// 5. DEFAULT
else {
    header("Location: ../VIEW/ADMIN/daftarKategori.php");
    exit;
}
?>