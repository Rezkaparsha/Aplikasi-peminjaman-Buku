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

<style>
    .sidebar {
        width: 280px; 
        min-width: 280px;
        min-height: 100vh;
        background: #1e293b; 
        color: #f8fafc;
        display: flex;
        flex-direction: column;
        box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        z-index: 100;
    }

    .sidebar-header {
        padding: 24px 20px;
        text-align: left;
        background-color: #0f172a;
        border-bottom: 1px solid #334155;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    /* PERBAIKAN CONTAINER LOGO */
    .sidebar-header .brand-icon {
        width: 45px;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background-color: #ffffff; /* Diubah menjadi putih agar logo lebih menonjol */
        overflow: hidden; /* Mencegah gambar keluar dari border radius */
        flex-shrink: 0; /* Mencegah ikon mengecil */
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }

    /* PERBAIKAN UKURAN GAMBAR LOGO */
    .sidebar-header .brand-icon img {
        width: 100%;
        height: 100%;
        object-fit: cover; /* Membuat gambar proporsional memenuhi kotak */
        object-position: center;
        padding: 2px; /* Memberi sedikit jarak (margin dalam) agar logo tidak mentok ke garis */
    }

    .sidebar-header h3 {
        font-size: 17px;
        font-weight: 700;
        color: #ffffff;
        margin: 0;
        line-height: 1.2;
        white-space: nowrap;
    }

    .sidebar-header small {
        color: #94a3b8;
        font-size: 12px;
        font-weight: 500;
    }

    .sidebar-menu {
        list-style: none;
        padding: 15px 10px;
        flex: 1;
        overflow-y: auto;
    }

    .sidebar-menu li {
        margin-bottom: 4px;
    }

    .sidebar-menu li a {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 16px;
        color: #94a3b8;
        text-decoration: none;
        transition: all 0.2s ease-in-out;
        border-radius: 8px;
        font-size: 13.5px;
        font-weight: 500;
        white-space: nowrap; 
    }

    .sidebar-menu li a:hover {
        background-color: #334155;
        color: #ffffff;
    }

    .sidebar-menu li a.active {
        background-color: #2563eb;
        color: #ffffff;
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }

    .sidebar-footer {
        padding: 15px 15px 20px 15px;
        background-color: #0f172a;
        border-top: 1px solid #334155;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .user-info-card {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px;
        background: #1e293b;
        border-radius: 8px;
    }

    .user-avatar {
        width: 35px;
        height: 35px;
        background: #38bdf8;
        color: #0f172a;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 14px;
    }

    .user-details {
        overflow: hidden;
    }

    .user-name {
        font-size: 13px;
        font-weight: 600;
        color: #f8fafc;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .user-role {
        font-size: 11px;
        color: #38bdf8;
    }

    .btn-logout {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        padding: 10px;
        text-align: center;
        background-color: #ef4444;
        color: white;
        text-decoration: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 13px;
        transition: 0.2s;
    }

    .btn-logout:hover {
        background-color: #dc2626;
    }
</style>

<div class="sidebar">
    <div class="sidebar-header">
        <div class="brand-icon">
            <img src="/Aplikasi Peminjaman Buku/ASSETS/logoApp.jpg" alt="Logo">
        </div>
        <div>
            <h3>Perpustakaan</h3>
            <small>Panel Admin</small>
        </div>
    </div>

    <ul class="sidebar-menu">
        <li>
            <a href="/Aplikasi Peminjaman Buku/VIEW/ADMIN/dashboardAdmin.php"
                class="<?= ($currentPage === 'dashboardAdmin.php') ? 'active' : '' ?>">
                <span>📊</span> Dashboard
            </a>
        </li>
        <li>
            <a href="/Aplikasi Peminjaman Buku/VIEW/ADMIN/daftarPeminjaman.php"
                class="<?= ($currentPage === 'daftarPeminjaman.php' || $currentPage === 'detailPeminjaman.php') ? 'active' : '' ?>">
                <span>🔄</span> Kelola Peminjaman
            </a>
        </li>
        <li>
            <a href="/Aplikasi Peminjaman Buku/VIEW/ADMIN/daftarBuku.php"
                class="<?= ($currentPage === 'daftarBuku.php' || $currentPage === 'tambahBuku.php' || $currentPage === 'editBuku.php') ? 'active' : '' ?>">
                <span>📚</span> Kelola Buku
            </a>
        </li>
        <li>
            <a href="/Aplikasi Peminjaman Buku/VIEW/ADMIN/daftarKategori.php"
                class="<?= ($currentPage === 'daftarKategori.php' || $currentPage === 'tambahKategori.php' || $currentPage === 'editKategori.php') ? 'active' : '' ?>">
                <span>🏷️</span> Kategori Buku
            </a>
        </li>
        <li>
            <a href="/Aplikasi Peminjaman Buku/VIEW/ADMIN/daftarPenerbit.php"
                class="<?= ($currentPage === 'daftarPenerbit.php' || $currentPage === 'tambahPenerbit.php' || $currentPage === 'editPenerbit.php') ? 'active' : '' ?>">
                <span>🏢</span> Penerbit
            </a>
        </li>
        <li>
            <a href="/Aplikasi Peminjaman Buku/VIEW/ADMIN/daftarPenulis.php"
                class="<?= ($currentPage === 'daftarPenulis.php' || $currentPage === 'tambahPenulis.php' || $currentPage === 'editPenulis.php') ? 'active' : '' ?>">
                <span>✍️</span> Penulis
            </a>
        </li>
        <li>
            <a href="/Aplikasi Peminjaman Buku/VIEW/ADMIN/daftarUser.php"
                class="<?= ($currentPage === 'daftarUser.php') ? 'active' : '' ?>">
                <span>👥</span> Kelola User
            </a>
        </li>
        <li>
            <a href="/Aplikasi Peminjaman Buku/VIEW/ADMIN/historiTransaksi.php"
                class="<?= ($currentPage === 'historiTransaksi.php') ? 'active' : '' ?>">
                <span>📜</span> Histori Transaksi & Denda
            </a>
        </li>
    </ul>

    <div class="sidebar-footer">
        <div class="user-info-card">
            <div class="user-avatar">
                <?= strtoupper(substr($namaAdmin, 0, 1)) ?>
            </div>
            <div class="user-details">
                <div class="user-name"><?= htmlspecialchars($namaAdmin) ?></div>
                <div class="user-role">Administrator</div>
            </div>
        </div>

        <a href="/Aplikasi Peminjaman Buku/CONTROLLER/c_login.php?aksi=logout"
            class="btn-logout"
            onclick="return confirm('Apakah Anda yakin ingin logout?')">
            🚪 Logout
        </a>
    </div>
</div>