<?php
// WAJIB: Jalankan session agar $_SESSION bisa berfungsi
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../MODEL/m_penerbit.php";

$penerbitModel = new Penerbit();
$aksi = $_GET['aksi'] ?? '';

// TAMBAH PENERBIT
if ($aksi === 'tambah') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: ../VIEW/ADMIN/tambahPenerbit.php");
        exit;
    }

    $namaPenerbit = trim($_POST['nama_penerbit'] ?? '');
    $alamat       = trim($_POST['alamat'] ?? '');
    $kota         = trim($_POST['kota'] ?? '');
    $telepon      = trim($_POST['telepon'] ?? '');
    $email        = trim($_POST['email'] ?? '');

    if ($namaPenerbit === '') {
        $_SESSION['error'] = "Nama penerbit wajib diisi.";
        header("Location: ../VIEW/ADMIN/daftarPenerbit.php");
        exit;
    }

    $penerbitModel->nama_penerbit = $namaPenerbit;
    $penerbitModel->alamat        = $alamat;
    $penerbitModel->kota          = $kota;
    $penerbitModel->telepon       = $telepon;
    $penerbitModel->email         = $email;

    if ($penerbitModel->insert()) {
        $_SESSION['success'] = "Penerbit berhasil ditambahkan.";
    } else {
        $_SESSION['error'] = "Gagal menambahkan penerbit.";
    }
    header("Location: ../VIEW/ADMIN/daftarPenerbit.php");
    exit;
}

// EDIT PENERBIT
elseif ($aksi === 'edit') {
    $idPenerbit = (int) ($_GET['id_penerbit'] ?? 0);

    if ($idPenerbit <= 0) {
        header("Location: ../VIEW/ADMIN/daftarPenerbit.php");
        exit;
    }

    $dataPenerbit = $penerbitModel->getById($idPenerbit);

    if (!$dataPenerbit) {
        $_SESSION['error'] = "Data penerbit tidak ditemukan.";
        header("Location: ../VIEW/ADMIN/daftarPenerbit.php");
        exit;
    }

    require_once __DIR__ . "/../VIEW/ADMIN/editPenerbit.php";
    exit;
}

// UPDATE PENERBIT
elseif ($aksi === 'update') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: ../VIEW/ADMIN/daftarPenerbit.php");
        exit;
    }

    $idPenerbit   = (int) ($_POST['id_penerbit'] ?? 0);
    $namaPenerbit = trim($_POST['nama_penerbit'] ?? '');
    $alamat       = trim($_POST['alamat'] ?? '');
    $kota         = trim($_POST['kota'] ?? '');
    $telepon      = trim($_POST['telepon'] ?? '');
    $email        = trim($_POST['email'] ?? '');

    if ($idPenerbit <= 0 || $namaPenerbit === '') {
        $_SESSION['error'] = "Data penerbit tidak valid.";
        header("Location: ../VIEW/ADMIN/daftarPenerbit.php");
        exit;
    }

    $penerbitModel->id_penerbit   = $idPenerbit;
    $penerbitModel->nama_penerbit = $namaPenerbit;
    $penerbitModel->alamat        = $alamat;
    $penerbitModel->kota          = $kota;
    $penerbitModel->telepon       = $telepon;
    $penerbitModel->email         = $email;

    if ($penerbitModel->update()) {
        $_SESSION['success'] = "Data penerbit berhasil diperbarui.";
    } else {
        $_SESSION['error'] = "Gagal memperbarui penerbit.";
    }
    header("Location: ../VIEW/ADMIN/daftarPenerbit.php");
    exit;
}

// HAPUS PENERBIT
elseif ($aksi === 'hapus') {
    $idPenerbit = (int) ($_GET['id_penerbit'] ?? 0);

    if ($idPenerbit > 0) {
        // Cek keterhubungan dengan buku
        if ($penerbitModel->isUsedInBuku($idPenerbit)) {
            $_SESSION['error'] = "Penerbit tidak dapat dihapus karena masih terhubung dengan data buku di perpustakaan!";
        } else {
            $hapus = $penerbitModel->delete($idPenerbit);
            if ($hapus) {
                $_SESSION['success'] = "Penerbit berhasil dihapus.";
            } else {
                $_SESSION['error'] = "Gagal menghapus penerbit.";
            }
        }
    } else {
        $_SESSION['error'] = "ID Penerbit tidak valid.";
    }
    
    header("Location: ../VIEW/ADMIN/daftarPenerbit.php");
    exit;
}

// AKSI TIDAK DIKENAL
else {
    header("Location: ../VIEW/ADMIN/daftarPenerbit.php");
    exit;
}
?>