<?php

// Panggil Model yang dibutuhkan untuk statistik/info sidebar
require_once __DIR__ . "/../../MODEL/m_buku.php";
require_once __DIR__ . "/../../MODEL/m_users.php";
require_once __DIR__ . "/../../MODEL/m_peminjaman.php";

$bukuModel = new Buku();
$userModel = new Users();
$peminjamanModel = new M_Peminjaman();

$totalBuku = count($bukuModel->getAllBuku() ?? []);
$totalUser = count($userModel->getAll() ?? []);
$totalPeminjaman = count($peminjamanModel->getSemuaPeminjaman() ?? []);

$namaAdmin = $_SESSION['nama_lengkap'] ?? $_SESSION['nama'] ?? 'Administrator';
$currentPage = $_GET['aksi'] ?? basename($_SERVER['PHP_SELF']);
?>

<!-- Style khusus Sidebar Admin jika belum dimuat -->
<style>
    .sidebar {
        width: 250px;
        min-height: 100vh;
        background-color: #2c3e50;
        color: #ecf0f1;
        display: flex;
        flex-direction: column;
    }
    .sidebar-header {
        padding: 20px;
        text-align: center;
        background-color: #1a252f;
        border-bottom: 1px solid #34495e;
    }
    .sidebar-header h3 {
        font-size: 18px;
        letter-spacing: 1px;
        color: #fff;
    }
    .sidebar-menu {
        list-style: none;
        padding: 15px 0;
        flex: 1;
    }
    .sidebar-menu li a {
        display: block;
        padding: 12px 20px;
        color: #bdc3c7;
        text-decoration: none;
        transition: all 0.3s;
        border-left: 4px solid transparent;
        font-size: 14px;
    }
    .sidebar-menu li a:hover, .sidebar-menu li a.active {
        background-color: #34495e;
        color: #fff;
        border-left: 4px solid #3498db;
    }
    .sidebar-footer {
        padding: 15px 20px;
        background-color: #1a252f;
    }
    .btn-logout {
        display: block;
        width: 100%;
        padding: 10px;
        text-align: center;
        background-color: #e74c3c;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        font-weight: bold;
        transition: 0.3s;
    }
    .btn-logout:hover {
        background-color: #c0392b;
    }
</style>

<div class="sidebar">
    <div class="sidebar-header">
        <h3>📖 PerpusApp</h3>
        <small style="color: #7f8c8d; font-size: 11px;">Panel Admin</small>
    </div>
    
    <ul class="sidebar-menu">
        <li>
            <a href="/Aplikasi Peminjaman Buku/VIEW/ADMIN/dashboardAdmin.php" 
               class="<?= ($currentPage === 'dashboardAdmin.php') ? 'active' : '' ?>">
               📊 Dashboard
            </a>
        </li>
        <li>
            <a href="/Aplikasi Peminjaman Buku/CONTROLLER/c_peminjaman.php?aksi=admin" 
               class="<?= ($currentPage === 'admin' || $currentPage === 'detailAdmin') ? 'active' : '' ?>">
               🔄 Kelola Peminjaman
            </a>
        </li>
        <li>
            <a href="/Aplikasi Peminjaman Buku/VIEW/ADMIN/daftarBuku.php" 
               class="<?= ($currentPage === 'daftarBuku.php') ? 'active' : '' ?>">
               📚 Kelola Buku
            </a>
        </li>
        <li>
            <a href="/Aplikasi Peminjaman Buku/VIEW/ADMIN/daftarKategori.php" 
               class="<?= ($currentPage === 'daftarKategori.php') ? 'active' : '' ?>">
               🏷️ Kategori Buku
            </a>
        </li>
        <li>
            <a href="/Aplikasi Peminjaman Buku/VIEW/ADMIN/daftarPenerbit.php" 
               class="<?= ($currentPage === 'daftarPenerbit.php') ? 'active' : '' ?>">
               🏢 Penerbit
            </a>
        </li>
        <li>
            <a href="/Aplikasi Peminjaman Buku/VIEW/ADMIN/daftarPenulis.php" 
               class="<?= ($currentPage === 'daftarPenulis.php') ? 'active' : '' ?>">
               ✍️ Penulis
            </a>
        </li>
        <li>
            <a href="/Aplikasi Peminjaman Buku/VIEW/ADMIN/daftarUser.php" 
               class="<?= ($currentPage === 'daftarUser.php') ? 'active' : '' ?>">
               👥 Kelola User
            </a>
        </li>
        <!-- Link Histori Transaksi mengarah ke Controller -->
        <li>
            <a href="/Aplikasi Peminjaman Buku/CONTROLLER/c_peminjaman.php?aksi=histori" 
               class="<?= ($currentPage === 'histori') ? 'active' : '' ?>">
               📜 Histori Transaksi & Denda
            </a>
        </li>
    </ul>

    <div class="sidebar-footer">
        <a href="/Aplikasi Peminjaman Buku/CONTROLLER/c_login.php?aksi=logout" 
           class="btn-logout" 
           onclick="return confirm('Apakah Anda yakin ingin logout?')">
           Logout
        </a>
    </div>
</div>