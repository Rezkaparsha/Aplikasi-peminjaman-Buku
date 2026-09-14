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

require_once __DIR__ . "/../../MODEL/m_penulis.php";

$penulisModel = new Penulis();
$daftarPenulis = $penulisModel->getAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Penulis - Perpustakaan Online</title>
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
            overflow: hidden;
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

        .btn-back {
            background-color: #95a5a6;
            color: white;
            margin-top: 20px;
        }
        .btn-back:hover { background-color: #7f8c8d; }

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
        }
        .data-table tr:hover {
            background-color: #fcfcfc;
        }

        .text-center {
            text-align: center;
            color: #7f8c8d;
            padding: 30px;
        }
    </style>
</head>
<body>

    <!-- Sidebar Admin -->
    <?php include __DIR__ . "/../TEMPLATE/SideBarAdmin.php"; ?>

    <div class="main-content">
        <!-- Topbar -->
        <div class="topbar">
            <h2>Kelola Penulis</h2>
            <div style="font-size: 14px; color: #7f8c8d;">Panel Admin</div>
        </div>

        <!-- Main Container -->
        <div class="container">
            <div class="card">
                
                <div class="page-header">
                    <div>
                        <h3>Daftar Penulis Buku</h3>
                        <p>Kelola data pengarang dari buku-buku yang ada di perpustakaan.</p>
                    </div>
                    <div>
                        <a href="tambahPenulis.php" class="btn btn-primary">+ Tambah Penulis</a>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th style="width: 10%;">No</th>
                                <th style="width: 60%;">Nama Penulis</th>
                                <th style="width: 30%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($daftarPenulis)): ?>
                                <?php $no = 1; ?>
                                <?php foreach ($daftarPenulis as $penulis): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><strong><?= htmlspecialchars($penulis['nama_penulis']) ?></strong></td>
                                        <td>
                                            <div class="action-group">
                                                <a href="../../CONTROLLER/c_penulis.php?aksi=edit&id_penulis=<?= $penulis['id_penulis'] ?>" class="btn btn-edit">Edit</a>
                                                <a href="../../CONTROLLER/c_penulis.php?aksi=hapus&id_penulis=<?= $penulis['id_penulis'] ?>" class="btn btn-delete" onclick="return confirm('Yakin ingin menghapus penulis ini?')">Hapus</a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="text-center">Belum ada data penulis.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            </div>

            <a href="daftarBuku.php" class="btn btn-back">← Kembali ke Daftar Buku</a>
        </div>
    </div>

</body>
</html>