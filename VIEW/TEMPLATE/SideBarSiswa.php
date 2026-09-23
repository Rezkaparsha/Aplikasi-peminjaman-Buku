<?php
// Pastikan session sudah berjalan
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ambil data nama/user jika tersedia
$namaSiswa = $_SESSION['nama_lengkap'] ?? $_SESSION['nama'] ?? 'Siswa Perpustakaan';
$nisSiswa  = $_SESSION['nis_nip'] ?? $_SESSION['nis'] ?? '-';

// Dapatkan nama file yang sedang diakses untuk menentukan menu aktif (active link)
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<!-- FontAwesome Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    .sidebar-siswa {
        width: 280px;
        min-width: 280px;
        min-height: 100vh;
        background: #1e293b; /* Dark Slate Blue */
        color: #f8fafc;
        display: flex;
        flex-direction: column;
        box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        z-index: 100;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .sidebar-siswa .brand-section {
        padding: 24px 20px;
        background-color: #0f172a;
        border-bottom: 1px solid #334155;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .sidebar-siswa .brand-icon {
        font-size: 22px;
        background: #2563eb;
        color: #ffffff;
        width: 42px;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
    }

    .sidebar-siswa .brand-text h3 {
        font-size: 18px;
        font-weight: 700;
        color: #ffffff;
        margin: 0;
        line-height: 1.2;
    }

    .sidebar-siswa .brand-text small {
        color: #94a3b8;
        font-size: 11px;
        font-weight: 500;
    }

    .sidebar-siswa .nav-menu {
        padding: 20px 12px;
        flex: 1;
        overflow-y: auto;
    }

    .sidebar-siswa .menu-header {
        font-size: 11px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        padding: 0 12px 10px 12px;
        display: block;
    }

    .sidebar-siswa .sidebar-menu {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .sidebar-siswa .sidebar-menu li {
        margin-bottom: 4px;
    }

    .sidebar-siswa .sidebar-menu li a {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 16px;
        color: #94a3b8;
        text-decoration: none;
        border-radius: 8px;
        font-size: 13.5px;
        font-weight: 500;
        transition: all 0.2s ease-in-out;
        white-space: nowrap;
    }

    .sidebar-siswa .sidebar-menu li a i {
        width: 20px;
        font-size: 1.1rem;
        text-align: center;
    }

    .sidebar-siswa .sidebar-menu li a:hover {
        background-color: #334155;
        color: #ffffff;
    }

    .sidebar-siswa .sidebar-menu li a.active {
        background-color: #2563eb;
        color: #ffffff;
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }

    .sidebar-siswa .sidebar-footer {
        padding: 16px 15px 20px 15px;
        background-color: #0f172a;
        border-top: 1px solid #334155;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .sidebar-siswa .user-profile-card {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px;
        background: #1e293b;
        border-radius: 8px;
        border: 1px solid #334155;
    }

    .sidebar-siswa .user-avatar {
        width: 38px;
        height: 38px;
        background: #38bdf8;
        color: #0f172a;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 15px;
        flex-shrink: 0;
    }

    .sidebar-siswa .user-details {
        overflow: hidden;
    }

    .sidebar-siswa .user-name {
        font-size: 13px;
        font-weight: 600;
        color: #f8fafc;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .sidebar-siswa .user-subtext {
        font-size: 11px;
        color: #94a3b8;
    }

    .sidebar-siswa .btn-logout {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        padding: 10px;
        text-align: center;
        background-color: #ef4444;
        color: #ffffff;
        text-decoration: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 13px;
        transition: 0.2s ease-in-out;
        border: none;
        cursor: pointer;
    }

    .sidebar-siswa .btn-logout:hover {
        background-color: #dc2626;
    }
</style>

<div class="sidebar-siswa">
    <!-- Brand / Logo Aplikasi -->
    <div class="brand-section">
        <div class="brand-icon">
            <i class="fa-solid fa-book-bookmark"></i>
        </div>
        <div class="brand-text">
            <h3>E-PERPUS</h3>
            <small>Panel Siswa</small>
        </div>
    </div>

    <!-- Menu Navigasi Utama Siswa -->
    <div class="nav-menu">
        <span class="menu-header">Menu Utama</span>
        
        <ul class="sidebar-menu">
            <li>
                <a href="/Aplikasi Peminjaman Buku/VIEW/SISWA/dashboardSiswa.php" 
                   class="<?= ($currentPage === 'dashboardSiswa.php') ? 'active' : '' ?>">
                    <i class="fa-solid fa-house"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="/Aplikasi Peminjaman Buku/VIEW/SISWA/daftarBuku.php" 
                   class="<?= ($currentPage === 'daftarBuku.php') ? 'active' : '' ?>">
                    <i class="fa-solid fa-book-open"></i>
                    <span>Katalog Buku</span>
                </a>
            </li>
            <li>
                <a href="/Aplikasi Peminjaman Buku/VIEW/SISWA/peminjaman.php" 
                   class="<?= ($currentPage === 'peminjaman.php') ? 'active' : '' ?>">
                    <i class="fa-solid fa-bookmark"></i>
                    <span>Peminjaman Saya</span>
                </a>
            </li>
            <li>
                <a href="/Aplikasi Peminjaman Buku/VIEW/SISWA/profil.php" 
                   class="<?= ($currentPage === 'profil.php') ? 'active' : '' ?>">
                    <i class="fa-solid fa-user-gear"></i>
                    <span>Profil Saya</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Info Profile & Tombol Logout di Footer -->
    <div class="sidebar-footer">
        <div class="user-profile-card">
            <div class="user-avatar">
                <?= strtoupper(substr($namaSiswa, 0, 1)) ?>
            </div>
            <div class="user-details">
                <div class="user-name" title="<?= htmlspecialchars($namaSiswa) ?>">
                    <?= htmlspecialchars($namaSiswa) ?>
                </div>
                <div class="user-subtext">
                    NIS: <?= htmlspecialchars($nisSiswa) ?>
                </div>
            </div>
        </div>

        <a href="/Aplikasi Peminjaman Buku/CONTROLLER/c_login.php?aksi=logout" 
           class="btn-logout" 
           onclick="return confirm('Apakah Anda yakin ingin keluar dari sistem?')">
            <i class="fa-solid fa-right-from-bracket"></i>
            <span>Logout</span>
        </a>
    </div>
</div>