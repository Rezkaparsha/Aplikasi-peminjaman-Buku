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

// Pastikan session berjalan untuk mengambil pesan success/error
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($dataPeminjaman)) {
    header("Location: ../../CONTROLLER/c_peminjaman.php?aksi=admin");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Peminjaman - Perpustakaan Online</title>
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

        /* Alerts */
        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .alert-success {
            background-color: #e8f8f5;
            color: #1abc9c;
            border-left: 4px solid #1abc9c;
        }

        .alert-error {
            background-color: #fdeaea;
            color: #e74c3c;
            border-left: 4px solid #e74c3c;
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
            min-width: 900px;
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

        /* Status Badges */
        .badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            color: white;
            text-transform: uppercase;
            display: inline-block;
            text-align: center;
            min-width: 80px;
        }

        .badge-warning {
            background-color: #f39c12;
        }

        /* Diajukan */
        .badge-primary {
            background-color: #3498db;
        }

        /* Dipinjam */
        .badge-success {
            background-color: #2ecc71;
        }

        /* Dikembalikan */
        .badge-danger {
            background-color: #e74c3c;
        }

        /* Ditolak */
        .badge-default {
            background-color: #95a5a6;
        }

        /* Button */
        .btn-detail {
            background-color: #9b59b6;
            color: white;
            padding: 6px 12px;
            font-size: 12px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: 0.3s;
        }

        .btn-detail:hover {
            background-color: #8e44ad;
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
            <h2>Kelola Peminjaman</h2>
            <div style="font-size: 14px; color: #7f8c8d;">Panel Admin</div>
        </div>

        <!-- Main Container -->
        <div class="container">

            <!-- Notifikasi Pesan -->
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <?= htmlspecialchars($_SESSION['success']) ?>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error">
                    <?= htmlspecialchars($_SESSION['error']) ?>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <div class="card">

                <div class="page-header">
                    <div>
                        <h3>Daftar Transaksi Peminjaman</h3>
                        <p>Tinjau dan kelola pengajuan peminjaman buku dari siswa.</p>
                    </div>
                </div>

                <?php if (!empty($dataPeminjaman)): ?>
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">No</th>
                                    <th style="width: 10%;">ID TRX</th>
                                    <th style="width: 10%;">NIS</th>
                                    <th style="width: 20%;">Nama Peminjam</th>
                                    <th style="width: 15%;">Tgl Pinjam</th>
                                    <th style="width: 10%;">Jml Jenis</th>
                                    <th style="width: 10%;">Total Buku</th>
                                    <th style="width: 10%;">Status</th>
                                    <th style="width: 10%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                <?php foreach ($dataPeminjaman as $peminjaman): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><strong>#<?= htmlspecialchars($peminjaman['id_peminjaman']) ?></strong></td>
                                        <td><?= htmlspecialchars($peminjaman['nis']) ?></td>
                                        <td style="font-weight: 500; color: #2c3e50;"><?= htmlspecialchars($peminjaman['nama_lengkap']) ?></td>
                                        <td>
                                            <?= date('d M Y', strtotime($peminjaman['tanggal_pinjam'])) ?>
                                        </td>
                                        <td><?= htmlspecialchars($peminjaman['jumlah_jenis_buku']) ?> Jenis</td>
                                        <td><?= htmlspecialchars($peminjaman['total_buku']) ?> Buku</td>
                                        <td>
                                            <?php
                                            // Logika warna status
                                            $status = strtolower($peminjaman['status']);
                                            $badgeClass = 'badge-default';

                                            if ($status === 'diajukan') $badgeClass = 'badge-warning';
                                            elseif ($status === 'dipinjam') $badgeClass = 'badge-primary';
                                            elseif ($status === 'dikembalikan') $badgeClass = 'badge-success';
                                            elseif ($status === 'ditolak') $badgeClass = 'badge-danger';
                                            ?>
                                            <span class="badge <?= $badgeClass ?>">
                                                <?= htmlspecialchars($peminjaman['status']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="/Aplikasi Peminjaman Buku/CONTROLLER/c_peminjaman.php?aksi=detailAdmin&id_peminjaman=<?= $peminjaman['id_peminjaman'] ?>" class="btn-detail">
                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center">
                        <span style="font-size: 40px; display: block; margin-bottom: 10px;">📋</span>
                        Belum ada pengajuan peminjaman saat ini.
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>

</body>

</html>