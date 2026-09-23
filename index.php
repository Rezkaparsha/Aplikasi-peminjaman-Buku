<?php
session_start();

// Jika belum login, arahkan ke form login
if (!isset($_SESSION['id_user'])) {
    header("Location: /Aplikasi Peminjaman Buku/VIEW/AUTH/login.php");
    exit;
}

// Jika sudah login, cek rolenya dan arahkan ke dashboard masing-masing
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: /Aplikasi Peminjaman Buku/VIEW/ADMIN/dashboardAdmin.php");
    } else {
        header("Location: /Aplikasi Peminjaman Buku/VIEW/SISWA/daftarBuku.php");
    }
    exit;
}
?>