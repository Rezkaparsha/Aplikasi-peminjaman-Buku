<?php
require_once __DIR__ . "/../../MODEL/m_kategori.php";

$kategoriModel = new Kategori();
$daftarKategori = $kategoriModel->getAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Kategori - Perpustakaan Online</title>
    <style>
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
        }

        /* Card Card Wrapper */
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
            font-size: 13px;
        }
        .btn-edit:hover { background-color: #2980b9; }

        .btn-delete {
            background-color: #e74c3c;
            color: white;
            padding: 6px 12px;
            font-size: 13px;
        }
        .btn-delete:hover { background-color: #c0392b; }

        .btn-back {
            background-color: #95a5a6;
            color: white;
            margin-top: 20px;
        }
        .btn-back:hover { background-color: #7f8c8d; }

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
            font-size: 14px;
        }
        .data-table th {
            background-color: #f8f9fa;
            color: #2c3e50;
            font-weight: 600;
        }
        .data-table tr:hover {
            background-color: #fcfcfc;
        }
        .text-center {
            text-align: center;
            color: #7f8c8d;
            padding: 20px;
        }
    </style>
</head>
<body>

    <!-- Sidebar Admin -->
    <?php include __DIR__ . "/../TEMPLATE/SideBarAdmin.php"; ?>

    <div class="main-content">
        <!-- Topbar -->
        <div class="topbar">
            <h2>Kelola Kategori</h2>
            <div style="font-size: 14px; color: #7f8c8d;">Panel Admin</div>
        </div>

        <!-- Main Container -->
        <div class="container">
            <div class="card">
                
                <div class="page-header">
                    <div>
                        <h3>Daftar Kategori Buku</h3>
                        <p>Kelola kategori buku yang tersedia di perpustakaan dengan mudah.</p>
                    </div>
                    <div>
                        <a href="tambahKategori.php" class="btn btn-primary">+ Tambah Kategori</a>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th style="width: 5%;">No</th>
                                <th style="width: 25%;">Nama Kategori</th>
                                <th style="width: 35%;">Deskripsi</th>
                                <th style="width: 15%;">Status</th>
                                <th style="width: 20%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($daftarKategori)): ?>
                                <?php $no = 1; ?>
                                <?php foreach ($daftarKategori as $kategori): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><strong><?= htmlspecialchars($kategori['nama_kategori']) ?></strong></td>
                                        <td><?= htmlspecialchars($kategori['deskripsi'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($kategori['status'] ?? 'Aktif') ?></td>
                                        <td>
                                            <a href="../../CONTROLLER/c_kategori.php?aksi=edit&id_kategori=<?= $kategori['id_kategori'] ?>" class="btn btn-edit">Edit</a>
                                            <a href="../../CONTROLLER/c_kategori.php?aksi=hapus&id_kategori=<?= $kategori['id_kategori'] ?>" class="btn btn-delete" onclick="return confirm('Yakin ingin menghapus kategori ini?')">Hapus</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center">Belum ada data kategori.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            </div>

            <a href="dashboardAdmin.php" class="btn btn-back">← Kembali ke Dashboard</a>
        </div>
    </div>

</body>
</html>