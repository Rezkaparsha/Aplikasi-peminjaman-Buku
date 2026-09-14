<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Jika bukan admin, tendang kembali ke halaman terakhirnya
if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'admin') {
    // Cek apakah ada histori halaman sebelumnya. Jika ada, kembalikan ke sana. Jika tidak, lempar ke halaman siswa.
    $kembali = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/Aplikasi Peminjaman Buku/VIEW/SISWA/daftarBuku.php';
    header("Location: " . $kembali);
    exit;
}


require_once __DIR__ . "/../../MODEL/m_buku.php";

$bukuModel = new Buku();
$daftarBuku = $bukuModel->getAllBuku();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Buku - Perpustakaan Online</title>
    <style>
        /* Reset & Base Styles */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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
            overflow: hidden; /* Mencegah overflow horizontal pada body */
        }
        
        .topbar {
            background-color: #fff;
            padding: 15px 30px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
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
        .btn-primary:hover { background-color: #27ae60; }
        
        .btn-edit {
            background-color: #3498db;
            color: white;
            padding: 6px 12px;
            font-size: 12px;
        }
        .btn-edit:hover { background-color: #2980b9; }

        .btn-delete {
            background-color: #e74c3c;
            color: white;
            padding: 6px 12px;
            font-size: 12px;
        }
        .btn-delete:hover { background-color: #c0392b; }

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
            min-width: 900px; /* Agar tabel tidak terlalu berdempetan */
        }
        .data-table th, .data-table td {
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
        
        /* Image Style */
        .cover-img {
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            object-fit: cover;
            width: 50px;
            height: 70px;
        }

        .text-center {
            text-align: center;
            color: #7f8c8d;
            padding: 30px;
        }

        .total-data {
            margin-top: 20px;
            font-size: 14px;
            color: #7f8c8d;
        }
    </style>
</head>
<body>

    <!-- Panggil Sidebar -->
    <?php include __DIR__ . "/../TEMPLATE/SideBarAdmin.php"; ?>

    <div class="main-content">
        <!-- Topbar -->
        <div class="topbar">
            <h2>Kelola Buku</h2>
            <div style="font-size: 14px; color: #7f8c8d;">Panel Admin</div>
        </div>

        <!-- Main Container -->
        <div class="container">
            <div class="card">
                
                <div class="page-header">
                    <div>
                        <h3>Koleksi Buku</h3>
                        <p>Daftar lengkap semua buku yang tersedia di perpustakaan.</p>
                    </div>
                    <div>
                        <a href="tambahBuku.php" class="btn btn-primary">+ Tambah Buku</a>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>No/th>
                                <th>Cover</th>
                                <th>Judul Buku</th>
                                <th>Penulis</th>
                                <th>Penerbit</th>
                                <th>Tahun</th>
                                <th>Kategori</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($daftarBuku)): ?>
                                <?php $no = 1; ?>
                                <?php foreach ($daftarBuku as $buku): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td>
                                            <?php if (!empty($buku['cover'])): ?>
                                                <img src="../../ASSETS/COVER/<?= htmlspecialchars($buku['cover']) ?>" alt="Cover" class="cover-img">
                                            <?php else: ?>
                                                <span style="color: #bdc3c7; font-size: 11px;">No Cover</span>
                                            <?php endif; ?>
                                        </td>
                                        <td style="font-weight: 600; color: #2c3e50;"><?= htmlspecialchars($buku['judul_buku']) ?></td>
                                        <td><?= htmlspecialchars($buku['penulis'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($buku['nama_penerbit'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($buku['tahun_terbit'] ?? '-') ?></td>
                                        <td>
                                            <span style="background: #ecf0f1; padding: 4px 8px; border-radius: 4px; font-size: 12px;">
                                                <?= htmlspecialchars($buku['nama_kategori'] ?? '-') ?>
                                            </span>
                                        </td>
                                        <td style="white-space: nowrap;">Rp <?= number_format((int) $buku['harga_buku'], 0, ',', '.') ?></td>
                                        <td>
                                            <strong style="color: <?= (int)$buku['stok'] > 0 ? '#2ecc71' : '#e74c3c' ?>;">
                                                <?= htmlspecialchars($buku['stok']) ?>
                                            </strong>
                                        </td>
                                        <td>
                                            <div class="action-group">
                                                <a href="../../CONTROLLER/c_buku.php?aksi=edit&id_buku=<?= $buku['id_buku'] ?>" class="btn btn-edit">Edit</a>
                                                <a href="../../CONTROLLER/c_buku.php?aksi=hapus&id_buku=<?= $buku['id_buku'] ?>" class="btn btn-delete" onclick="return confirm('Yakin ingin menghapus buku ini?')">Hapus</a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="10" class="text-center">Belum ada buku dalam koleksi.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <?php if (!empty($daftarBuku)): ?>
                    <p class="total-data">Menampilkan <strong><?= count($daftarBuku) ?></strong> data buku</p>
                <?php endif; ?>

            </div>
        </div>
    </div>

</body>
</html>