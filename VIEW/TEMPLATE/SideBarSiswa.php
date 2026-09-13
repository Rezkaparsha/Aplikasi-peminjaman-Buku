<?php
// Pastikan session sudah berjalan
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ambil data nama/user jika tersedia
$namaSiswa = $_SESSION['nama_lengkap'] ?? 'Siswa Perpustakaan';
$nisSiswa = $_SESSION['nis_nip'] ?? $_SESSION['nis'] ?? '-';

// Dapatkan nama file yang sedang diakses untuk menentukan menu aktif (active link)
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<!-- FontAwesome & Bootstrap Icons CSS (jika belum di-load di file utama) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    .sidebar-siswa {
        width: 260px;
        min-height: 100vh;
        background: #1e293b; /* Dark Slate Blue */
        color: #f8fafc;
        display: flex;
        flex-direction: column;
        box-shadow: 4px 0 10px rgba(0,0,0,0.05);
    }
    .sidebar-siswa .brand-section {
        padding: 20px 24px;
        border-bottom: 1px solid #334155;
    }
    .sidebar-siswa .user-profile-section {
        padding: 16px 24px;
        background: #0f172a;
        border-bottom: 1px solid #334155;
    }
    .sidebar-siswa .nav-menu {
        padding: 16px 12px;
        flex: 1;
    }
    .sidebar-siswa .nav-link-item {
        display: flex;
        align-items: center;
        padding: 12px 16px;
        color: #94a3b8;
        text-decoration: none;
        border-radius: 8px;
        font-weight: 500;
        margin-bottom: 4px;
        transition: all 0.2s ease;
    }
    .sidebar-siswa .nav-link-item:hover {
        background: #334155;
        color: #ffffff;
    }
    .sidebar-siswa .nav-link-item.active {
        background: #2563eb; /* Primary Blue */
        color: #ffffff;
    }
    .sidebar-siswa .nav-link-item i {
        width: 24px;
        font-size: 1.1rem;
        margin-right: 12px;
    }
    .sidebar-siswa .logout-section {
        padding: 16px 12px;
        border-top: 1px solid #334155;
    }
    .sidebar-siswa .btn-logout {
        color: #ef4444;
        background: rgba(239, 68, 68, 0.1);
    }
    .sidebar-siswa .btn-logout:hover {
        background: #dc2626;
        color: #ffffff;
    }
</style>

<div class="sidebar-siswa">
    <!-- Brand / Logo Aplikasi -->
    <div class="brand-section d-flex align-items-center">
        <i class="fa-solid fa-book-bookmark text-primary fs-3 me-3"></i>
        <div>
            <h6 class="fw-bold mb-0 text-white">E-PERPUS</h6>
            <small class="text-muted" style="font-size: 11px;">Panel Siswa</small>
        </div>
    </div>

    <!-- Info User (Siswa) -->
    <div class="user-profile-section d-flex align-items-center">
        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; font-weight: bold;">
            <?= strtoupper(substr($namaSiswa, 0, 1)) ?>
        </div>
        <div class="overflow-hidden">
            <span class="d-block fw-semibold text-truncate text-white" style="font-size: 14px;" title="<?= htmlspecialchars($namaSiswa) ?>">
                <?= htmlspecialchars($namaSiswa) ?>
            </span>
            <small class="text-muted d-block" style="font-size: 12px;">NIS/NIP: <?= htmlspecialchars($nisSiswa) ?></small>
        </div>
    </div>

    <!-- Menu Navigasi Utama Siswa -->
    <div class="nav-menu">
        <small class="text-uppercase fw-bold text-muted px-3 mb-2 d-block" style="font-size: 10px; letter-spacing: 0.5px;">Menu Utama</small>
        
        <!-- Link Katalog Buku / Form Pinjam -->
        <a href="/Aplikasi Peminjaman Buku/VIEW/SISWA/daftarBuku.php" 
           class="nav-link-item <?= ($currentPage === 'daftarBuku.php') ? 'active' : '' ?>">
            <i class="fa-solid fa-book-open"></i>
            <span>Katalog Buku</span>
        </a>

        <!-- Link Riwayat Peminjaman Saya -->
        <a href="/Aplikasi Peminjaman Buku/VIEW/SISWA/peminjaman.php" 
           class="nav-link-item <?= ($currentPage === 'peminjaman.php' || $currentPage === 'detailPeminjaman.php') ? 'active' : '' ?>">
            <i class="fa-solid fa-list-check"></i>
            <span>Peminjaman Saya</span>
        </a>

        <!-- Link Profil / Pengaturan Akun -->
        <a href="/Aplikasi Peminjaman Buku/VIEW/SISWA/profil.php" 
           class="nav-link-item <?= ($currentPage === 'profil.php') ? 'active' : '' ?>">
            <i class="fa-solid fa-user-gear"></i>
            <span>Profil Saya</span>
        </a>
    </div>

    <!-- Tombol Logout -->
    <div class="logout-section">
        <a href="/Aplikasi Peminjaman Buku/CONTROLLER/c_login.php?aksi=logout" 
           class="nav-link-item btn-logout" 
           onclick="return confirm('Apakah Anda yakin ingin keluar dari sistem?')">
            <i class="fa-solid fa-right-from-bracket"></i>
            <span>Keluar / Logout</span>
        </a>
    </div>
</div>