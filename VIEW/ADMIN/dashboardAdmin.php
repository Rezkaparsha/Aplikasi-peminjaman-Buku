<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Proteksi Akses Admin
if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'admin') {
    $kembali = $_SESSION['last_page_siswa'] ?? '/Aplikasi Peminjaman Buku/CONTROLLER/c_peminjaman.php?aksi=dashboard_siswa';
    header("Location: " . $kembali);
    exit;
}

// Simpan URL lokasi controller aktif saat ini
$_SESSION['last_page_admin'] = $_SERVER['REQUEST_URI'];

// Panggil Model yang dibutuhkan
require_once __DIR__ . "/../../MODEL/m_buku.php";
require_once __DIR__ . "/../../MODEL/m_users.php";
require_once __DIR__ . "/../../MODEL/m_peminjaman.php";

$bukuModel = new Buku();
$userModel = new Users();
$peminjamanModel = new M_Peminjaman();
$totalBelumKembali = $peminjamanModel->getJumlahBelumKembali();

// Ambil total data untuk ditampilkan di kartu statistik
// (Gunakan error control operator @ atau null coalescing jika array kosong)
$totalBuku = count($bukuModel->getAllBuku() ?? []);
$totalUser = count($userModel->getAll() ?? []);
$totalPeminjaman = count($peminjamanModel->getSemuaPeminjaman() ?? []);

// Jika ada session nama user, kita tampilkan. Jika tidak, pakai default Admin.
$namaAdmin = $_SESSION['nama'] ?? 'Administrator';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Perpustakaan Online</title>
    <style>
        /* Reset & Base Styles */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* 1. Kunci ukuran Layar Utama agar tidak bisa di-scroll ke mana pun */
        html,
        body {
            height: 100vh;
            width: 100vw;
            margin: 0;
            padding: 0;
            overflow: hidden;
            /* Mencegah scrollbar utama muncul */
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f6;
        }

        body {
            display: flex;
        }

        /* 2. Main Content mengisi sisa area layar tanpa melebihi batas */
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            height: 100vh;
            overflow: hidden;
            /* Mengunci konten utama agar tidak keluar layar */
        }

        /* 3. Container utama dibuat responsif dan hanya konten di dalamnya yang di-scroll jika panjang */
        .container {
            padding: 25px;
            flex: 1;
            overflow-y: auto;
            /* Hanya scroll ke bawah jika isi tabel panjang */
            overflow-x: hidden;
            /* Hilangkan scroll samping kanan-kiri */
        }

        /* 4. Card Container */
        .card {
            background: #ffffff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
            width: 100%;
        }

        /* 5. Mencegah Tabel Memaksa Layar Melebar ke Kanan */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            /* Scroll horizontal hanya aktif di dalam area tabel saja jika terpaksa */
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            min-width: 100%;
            /* UBAH min-width: 800px/900px menjadi 100% agar pas dengan card */
        }

        body {
            background-color: #f4f7f6;
            color: #333;
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styling */
        .sidebar {
            width: 250px;
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
        }

        .sidebar-menu li a:hover,
        .sidebar-menu li a.active {
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

        /* Main Content Styling */
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .topbar {
            background-color: #fff;
            padding: 15px 30px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .topbar h2 {
            font-size: 20px;
            color: #2c3e50;
        }

        .user-profile {
            font-size: 14px;
            color: #7f8c8d;
        }

        /* Dashboard Container */
        .content {
            padding: 30px;
            flex: 1;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background-color: #fff;
            padding: 25px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 4px solid #bdc3c7;
        }

        .stat-card.blue {
            border-bottom-color: #3498db;
        }

        .stat-card.green {
            border-bottom-color: #2ecc71;
        }

        .stat-card.orange {
            border-bottom-color: #e67e22;
        }

        .stat-card.purple {
            border-bottom-color: #9b59b6;
        }

        .stat-info h4 {
            color: #7f8c8d;
            font-size: 14px;
            margin-bottom: 5px;
            font-weight: 500;
        }

        .stat-info h1 {
            color: #2c3e50;
            font-size: 28px;
        }

        .stat-icon {
            font-size: 35px;
            opacity: 0.7;
        }

        /* Quick Action Section */
        .quick-actions h3 {
            margin-bottom: 15px;
            color: #2c3e50;
        }

        .action-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 15px;
        }

        .action-card {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            text-decoration: none;
            color: #333;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            transition: 0.3s;
            border: 1px solid #eee;
        }

        .action-card:hover {
            transform: translateY(-3px);
            border-color: #3498db;
            box-shadow: 0 4px 8px rgba(52, 152, 219, 0.15);
        }

        .action-card h4 {
            color: #2980b9;
            margin-bottom: 5px;
        }

        .action-card p {
            font-size: 13px;
            color: #7f8c8d;
        }
    </style>
</head>

<body>

    <?php include __DIR__ . "/../TEMPLATE/SideBarAdmin.php"; ?>

    <!-- MAIN CONTENT -->
    <div class="main-content">
        <!-- TOPBAR -->
        <div class="topbar">
            <h2>Dashboard</h2>
            <div class="user-profile">
                Halo, <strong><?= htmlspecialchars($namaAdmin) ?></strong> (Admin)
            </div>
        </div>

        <!-- CONTENT -->
        <div class="content">

            <!-- STATS CARDS -->
            <div class="stats-grid">
                <div class="stat-card blue">
                    <div class="stat-info">
                        <h4>Total Buku</h4>
                        <h1><?= $totalBuku ?></h1>
                    </div>
                    <div class="stat-icon">📚</div>
                </div>

                <div class="stat-card green">
                    <div class="stat-info">
                        <h4>Pengguna Aktif</h4>
                        <h1><?= $totalUser ?></h1>
                    </div>
                    <div class="stat-icon">👥</div>
                </div>

                <div class="stat-card orange">
                    <div class="stat-info">
                        <h4>Transaksi Peminjaman</h4>
                        <h1><?= $totalPeminjaman ?></h1>
                    </div>
                    <div class="stat-icon">🔄</div>
                </div>

                <div class="stat-card purple">
                    <div class="stat-info">
                        <h4>Buku Belum Kembali</h4>
                        <h1><?= $totalBelumKembali ?></h1> <!-- Nanti bisa dihubungkan ke query spesifik -->
                    </div>
                    <div class="stat-icon">⚠️</div>
                </div>
            </div>

            <!-- QUICK ACTIONS -->
            <div class="quick-actions">
                <h3>Tindakan Cepat</h3>
                <div class="action-grid">
                    <a href="tambahBuku.php" class="action-card">
                        <h4>+ Tambah Buku Baru</h4>
                        <p>Input data buku baru ke dalam katalog perpustakaan.</p>
                    </a>
                    <a href="daftarPeminjaman.php" class="action-card">
                        <h4>Cek Peminjaman Menunggu</h4>
                        <p>Tinjau dan setujui permintaan peminjaman dari siswa.</p>
                    </a>
                    <a href="tambahUser.php" class="action-card">
                        <h4>+ Daftarkan User</h4>
                        <p>Tambahkan akun untuk siswa atau staf guru baru.</p>
                    </a>
                </div>
            </div>

        </div>
    </div>

</body>

</html>