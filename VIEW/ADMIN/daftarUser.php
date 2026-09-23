<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Proteksi Akses Admin: Arahkan ke login utama jika tidak ada session admin
if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'admin') {
    header("Location: /Aplikasi Peminjaman Buku/VIEW/AUTH/login.php");
    exit;
}

// 2. Simpan URL lokasi halaman view ini
$_SESSION['last_page_admin'] = $_SERVER['REQUEST_URI'];

// 3. Panggil Model Users Langsung di View
require_once __DIR__ . "/../../MODEL/m_users.php";
$userModel = new Users();
$daftarUser = $userModel->getAll();

// ==========================================
// LOGIKA PAGINATION (10 Data per Halaman)
// ==========================================
$limit = 10;
$total_data = count($daftarUser);
$total_pages = ceil($total_data / $limit);

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
if ($page > $total_pages && $total_pages > 0) $page = $total_pages;

$offset = ($page - 1) * $limit;

// Potong array data sesuai halaman aktif
$userPaginated = array_slice($daftarUser, $offset, $limit);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar User - Perpustakaan Online</title>
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

        /* Main Content Layout */
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
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

        .container {
            padding: 30px;
            flex: 1;
            overflow-y: auto;
        }

        /* Card Container */
        .card {
            background: #ffffff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #ecf0f1;
            padding-bottom: 15px;
        }

        .page-header h3 {
            color: #2c3e50;
            font-size: 22px;
        }

        .page-header p {
            color: #7f8c8d;
            font-size: 13px;
            margin-top: 4px;
        }

        /* Buttons */
        .btn {
            padding: 10px 18px;
            border-radius: 5px;
            font-size: 14px;
            font-weight: bold;
            text-decoration: none;
            cursor: pointer;
            transition: background 0.3s ease;
            display: inline-block;
            text-align: center;
        }

        .btn-primary {
            background-color: #2ecc71;
            color: white;
        }

        .btn-primary:hover {
            background-color: #27ae60;
        }

        .btn-edit {
            background-color: #3498db;
            color: white;
            padding: 6px 12px;
            font-size: 12px;
        }

        .btn-edit:hover {
            background-color: #2980b9;
        }

        .btn-delete {
            background-color: #e74c3c;
            color: white;
            padding: 6px 12px;
            font-size: 12px;
        }

        .btn-delete:hover {
            background-color: #c0392b;
        }

        .action-group {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
        }

        /* Table Styling */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            min-width: 800px;
        }

        .data-table th,
        .data-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ecf0f1;
            font-size: 13px;
        }

        .data-table th {
            background-color: #f8f9fa;
            color: #2c3e50;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12px;
            white-space: nowrap;
        }

        .data-table tr:hover {
            background-color: #fcfcfc;
        }

        /* Role Badges */
        .badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            color: white;
            text-transform: capitalize;
            display: inline-block;
            text-align: center;
        }

        .badge-admin {
            background-color: #e74c3c;
        }

        .badge-siswa {
            background-color: #3498db;
        }

        .badge-guru {
            background-color: #f1c40f;
            color: #333;
        }

        .badge-default {
            background-color: #95a5a6;
        }

        .text-center {
            text-align: center;
            color: #7f8c8d;
            padding: 30px;
        }

        .table-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .total-data {
            font-size: 14px;
            color: #7f8c8d;
        }

        /* Pagination Styling */
        .pagination {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            list-style: none;
            gap: 5px;
        }

        .pagination a {
            padding: 8px 12px;
            text-decoration: none;
            background-color: #f8f9fa;
            color: #2c3e50;
            border: 1px solid #ccd1d1;
            border-radius: 4px;
            font-size: 13px;
            transition: 0.3s;
        }

        .pagination a.active {
            background-color: #2ecc71;
            color: white;
            border-color: #2ecc71;
        }

        .pagination a:hover:not(.active) {
            background-color: #ecf0f1;
        }
    </style>
</head>

<body>

    <!-- Sidebar Admin -->
    <?php include __DIR__ . "/../TEMPLATE/SideBarAdmin.php"; ?>

    <div class="main-content">
        <!-- Topbar -->
        <div class="topbar">
            <h2>Kelola Data User</h2>
            <div style="font-size: 14px; color: #7f8c8d;">Panel Admin</div>
        </div>

        <!-- Main Container -->
        <div class="container">
            <div class="card">

                <div class="page-header">
                    <div>
                        <h3>Daftar Pengguna Sistem</h3>
                        <p>Kelola data akun siswa, guru, dan admin yang memiliki akses ke aplikasi perpustakaan.</p>
                    </div>
                    <div>
                        <a href="/Aplikasi Peminjaman Buku/VIEW/ADMIN/tambahUser.php" class="btn btn-primary">+ Tambah User</a>
                    </div>
                </div>

                <!-- ALERT NOTIFIKASI SEMENTARA -->
                <?php if (isset($_SESSION['error'])): ?>
                    <div style="background-color: #fdeaea; color: #c0392b; padding: 12px 16px; border-radius: 6px; margin-bottom: 20px; border-left: 4px solid #e74c3c; font-size: 14px; font-weight: bold; display: flex; align-items: center; gap: 10px;">
                        <span>⚠️</span>
                        <div><?= $_SESSION['error']; ?></div>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['success'])): ?>
                    <div style="background-color: #e8f8f5; color: #27ae60; padding: 12px 16px; border-radius: 6px; margin-bottom: 20px; border-left: 4px solid #2ecc71; font-size: 14px; font-weight: bold; display: flex; align-items: center; gap: 10px;">
                        <span>✅</span>
                        <div><?= $_SESSION['success']; ?></div>
                    </div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>

                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th style="width: 5%;">No</th>
                                <th style="width: 15%;">NIS/NIP</th>
                                <th style="width: 25%;">Nama Lengkap</th>
                                <th style="width: 15%;">Kelas</th>
                                <th style="width: 20%;">Username</th>
                                <th style="width: 10%;">Role</th>
                                <th style="width: 10%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($userPaginated)): ?>
                                <?php $no = $offset + 1; ?>
                                <?php foreach ($userPaginated as $user): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><strong><?= htmlspecialchars($user['nis_nip'] ?? '-') ?></strong></td>
                                        <td style="color: #2c3e50; font-weight: 500;"><?= htmlspecialchars($user['nama_lengkap']) ?></td>
                                        <td><?= htmlspecialchars($user['kelas'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($user['username']) ?></td>
                                        <td>
                                            <?php
                                            $roleClass = 'badge-default';
                                            if (strtolower($user['role']) === 'admin') $roleClass = 'badge-admin';
                                            elseif (strtolower($user['role']) === 'siswa') $roleClass = 'badge-siswa';
                                            elseif (strtolower($user['role']) === 'guru') $roleClass = 'badge-guru';
                                            ?>
                                            <span class="badge <?= $roleClass ?>">
                                                <?= htmlspecialchars($user['role']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="action-group">
                                                <a href="/Aplikasi Peminjaman Buku/VIEW/ADMIN/editUser.php?id_user=<?= $user['id_user'] ?>" class="btn btn-edit">Edit</a>
                                                <a href="/Aplikasi Peminjaman Buku/CONTROLLER/c_user.php?aksi=hapus&id_user=<?= $user['id_user'] ?>" class="btn btn-delete" onclick="return confirm('Yakin ingin menghapus user ini?')">Hapus</a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center">Belum ada user terdaftar.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <?php if (!empty($daftarUser)): ?>
                    <div class="table-footer">
                        <p class="total-data">Menampilkan <strong><?= count($userPaginated) ?></strong> dari <strong><?= $total_data ?></strong> total pengguna</p>

                        <!-- KONTROL PAGINATION -->
                        <?php if ($total_pages > 1): ?>
                            <ul class="pagination">
                                <?php if ($page > 1): ?>
                                    <li><a href="?page=<?= $page - 1 ?>">« Prev</a></li>
                                <?php endif; ?>

                                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                    <li>
                                        <a href="?page=<?= $i ?>" class="<?= ($i === $page) ? 'active' : '' ?>">
                                            <?= $i ?>
                                        </a>
                                    </li>
                                <?php endfor; ?>

                                <?php if ($page < $total_pages): ?>
                                    <li><a href="?page=<?= $page + 1 ?>">Next »</a></li>
                                <?php endif; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>

</body>

</html>