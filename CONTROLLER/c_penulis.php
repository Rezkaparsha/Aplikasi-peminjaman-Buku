<?php
// WAJIB: Jalankan session agar $_SESSION['error'] / $_SESSION['success'] bisa tersimpan
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../MODEL/m_penulis.php";

$penulisModel = new Penulis();
$aksi = $_GET['aksi'] ?? '';

// 1. TAMBAH PENULIS
if ($aksi === 'tambah') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: ../VIEW/ADMIN/tambahPenulis.php");
        exit;
    }

    $namaPenulis = trim($_POST['nama_penulis'] ?? '');

    if ($namaPenulis === '') {
        $_SESSION['error'] = "Nama penulis wajib diisi.";
        header("Location: ../VIEW/ADMIN/daftarPenulis.php");
        exit;
    }

    $penulisModel->nama_penulis = $namaPenulis;

    if ($penulisModel->insert()) {
        $_SESSION['success'] = "Penulis berhasil ditambahkan.";
    } else {
        $_SESSION['error'] = "Gagal menambahkan penulis.";
    }
    header("Location: ../VIEW/ADMIN/daftarPenulis.php");
    exit;
}

// 2. EDIT PENULIS (Menampilkan Form)
elseif ($aksi === 'edit') {
    $idPenulis = (int) ($_GET['id_penulis'] ?? 0);

    if ($idPenulis <= 0) {
        header("Location: ../VIEW/ADMIN/daftarPenulis.php");
        exit;
    }

    $dataPenulis = $penulisModel->getById($idPenulis);

    if (!$dataPenulis) {
        $_SESSION['error'] = "Data penulis tidak ditemukan.";
        header("Location: ../VIEW/ADMIN/daftarPenulis.php");
        exit;
    }

    require_once __DIR__ . "/../VIEW/ADMIN/editPenulis.php";
    exit;
}

// 3. UPDATE PENULIS (Memproses Form Edit)
elseif ($aksi === 'update') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: ../VIEW/ADMIN/daftarPenulis.php");
        exit;
    }

    $idPenulis   = (int) ($_POST['id_penulis'] ?? 0);
    $namaPenulis = trim($_POST['nama_penulis'] ?? '');

    if ($idPenulis <= 0 || $namaPenulis === '') {
        $_SESSION['error'] = "Data penulis tidak valid.";
        header("Location: ../VIEW/ADMIN/daftarPenulis.php");
        exit;
    }

    $penulisModel->id_penulis   = $idPenulis;
    $penulisModel->nama_penulis = $namaPenulis;

    if ($penulisModel->update()) {
        $_SESSION['success'] = "Data penulis berhasil diperbarui.";
    } else {
        $_SESSION['error'] = "Gagal memperbarui penulis.";
    }
    header("Location: ../VIEW/ADMIN/daftarPenulis.php");
    exit;
}

// 4. AKSI HAPUS PENULIS
elseif ($aksi === 'hapus') {
    $id_penulis = (int)($_GET['id_penulis'] ?? 0);

    if ($id_penulis > 0) {
        // Cek terlebih dahulu apakah penulis ini digunakan oleh buku
        if ($penulisModel->isUsedInBuku($id_penulis)) {
            $_SESSION['error'] = "Penulis tidak dapat dihapus karena masih terhubung dengan data buku di perpustakaan!";
        } else {
            $hapus = $penulisModel->delete($id_penulis);
            if ($hapus) {
                $_SESSION['success'] = "Penulis berhasil dihapus.";
            } else {
                $_SESSION['error'] = "Gagal menghapus penulis.";
            }
        }
    } else {
        $_SESSION['error'] = "ID Penulis tidak valid.";
    }

    header("Location: ../VIEW/ADMIN/daftarPenulis.php");
    exit;
}

// 5. AKSI TIDAK DIKENAL
else {
    header("Location: ../VIEW/ADMIN/daftarPenulis.php");
    exit;
}
?>