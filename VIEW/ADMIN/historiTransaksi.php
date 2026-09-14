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

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Proteksi Admin
if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'admin') {
    header("Location: /Aplikasi Peminjaman Buku/VIEW/AUTH/login.php");
    exit;
}

// Cek apakah data histori sudah dikirim dari controller
if (!isset($dataHistori)) {
    header("Location: /Aplikasi Peminjaman Buku/CONTROLLER/c_peminjaman.php?aksi=histori");
    exit;
}

// Inisialisasi variabel di luar HTML agar terdeteksi di seluruh scope dokumen
$totalSemuaDenda = 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histori Transaksi - Panel Admin</title>
    
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: #f4f7f6; color: #333; display: flex; min-height: 100vh; }
        
        .main-content { flex: 1; display: flex; flex-direction: column; overflow: hidden; }
        .topbar { background-color: #fff; padding: 15px 30px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); display: flex; justify-content: space-between; align-items: center; }
        .topbar h2 { font-size: 20px; color: #2c3e50; }
        
        .container { padding: 30px; flex: 1; overflow-y: auto; }
        .card { background: #ffffff; padding: 25px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05); }
        
        .page-header { margin-bottom: 20px; border-bottom: 2px solid #ecf0f1; padding-bottom: 15px; }
        .page-header h3 { color: #2c3e50; font-size: 22px; }
        .page-header p { color: #7f8c8d; font-size: 13px; margin-top: 4px; }
        
        .table-responsive { width: 100%; overflow-x: auto; }
        .data-table { width: 100%; border-collapse: collapse; margin-top: 10px; min-width: 1000px; }
        .data-table th, .data-table td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #ecf0f1; font-size: 13px; }
        .data-table th { background-color: #f8f9fa; color: #2c3e50; font-weight: 600; text-transform: uppercase; font-size: 12px; white-space: nowrap; }
        .data-table tr:hover { background-color: #fcfcfc; }
        
        .badge { padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: bold; color: white; display: inline-block; white-space: nowrap; }
        .badge-success { background-color: #2ecc71; }
        .badge-danger { background-color: #e74c3c; }
        .badge-warning { background-color: #f39c12; }
    </style>
</head>
<body>

    <!-- Sidebar Admin -->
    <?php include __DIR__ . "/../TEMPLATE/SideBarAdmin.php"; ?>

    <div class="main-content">
        <div class="topbar">
            <h2>Laporan & Histori Transaksi</h2>
            <div style="font-size: 14px; color: #7f8c8d;">Panel Admin</div>
        </div>

        <div class="container">
            <div class="card">
                <div class="page-header">
                    <h3>Rekap Pengembalian Buku & Denda</h3>
                    <p>Catatan fisik seluruh transaksi peminjaman buku yang telah selesai diproses.</p>
                </div>

                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>ID PMJ</th>
                                <th>Peminjam (NIS)</th>
                                <th>Buku</th>
                                <th>Tgl Pinjam</th>
                                <th>Tgl Kembali Real</th>
                                <th>Keterangan Denda</th>
                                <th>Nominal Denda</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($dataHistori)): ?>
                                <?php $no = 1; ?>
                                <?php foreach ($dataHistori as $row): ?>
                                    <?php $totalSemuaDenda += (int)$row['denda']; ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td style="font-weight: bold; color: #3498db;">#<?= htmlspecialchars($row['id_peminjaman']) ?></td>
                                        <td>
                                            <strong><?= htmlspecialchars($row['nama_siswa']) ?></strong><br>
                                            <span style="font-size: 11px; color: #7f8c8d;"><?= htmlspecialchars($row['nis']) ?></span>
                                        </td>
                                        <td><?= htmlspecialchars($row['judul_buku']) ?></td>
                                        <td><?= date('d M Y', strtotime($row['tanggal_pinjam'])) ?></td>
                                        <td><?= date('d M Y', strtotime($row['tanggal_dikembalikan'])) ?></td>
                                        <td>
                                            <?php if ($row['denda'] > 0): ?>
                                                <span class="badge badge-danger"><?= htmlspecialchars($row['jenis_denda']) ?></span>
                                            <?php else: ?>
                                                <span class="badge badge-success">Tepat Waktu</span>
                                            <?php endif; ?>
                                        </td>
                                        <td style="font-weight: bold; color: <?= $row['denda'] > 0 ? '#e74c3c' : '#2ecc71' ?>;">
                                            Rp <?= number_format($row['denda'], 0, ',', '.') ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" style="text-align: center; padding: 30px; color: #7f8c8d;">Belum ada histori transaksi pengembalian buku.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <?php if (!empty($dataHistori)): ?>
                    <div style="margin-top: 20px; padding: 15px; background: #fdfefe; border: 1px solid #e5e8e8; border-radius: 5px; text-align: right;">
                        <span style="font-size: 14px; color: #7f8c8d;">Total Keseluruhan Denda Terkumpul:</span>
                        <h3 style="color: #c0392b; display: inline-block; margin-left: 10px;">Rp <?= number_format($totalSemuaDenda, 0, ',', '.') ?></h3>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>

</body>
</html>