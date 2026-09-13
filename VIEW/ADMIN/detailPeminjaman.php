<?php
// Pastikan data dikirim dari controller
if (!isset($dataPeminjaman) || !isset($detailPeminjaman)) {
    header("Location: /Aplikasi Peminjaman Buku/CONTROLLER/c_peminjaman.php?aksi=admin");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Peminjaman - Perpustakaan Online</title>
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

        /* Card Setup */
        .card {
            background: #ffffff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
            margin-bottom: 25px;
        }
        .card-header {
            border-bottom: 2px solid #ecf0f1;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .card-header h3 {
            color: #2c3e50;
            font-size: 18px;
        }

        /* Info Grid */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }
        .info-box p {
            color: #7f8c8d;
            font-size: 13px;
            margin-bottom: 5px;
        }
        .info-box h4 {
            color: #2c3e50;
            font-size: 16px;
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
            font-size: 14px;
        }
        .data-table th {
            background-color: #f8f9fa;
            color: #2c3e50;
            font-weight: 600;
        }
        .cover-img {
            width: 50px;
            height: 70px;
            object-fit: cover;
            border-radius: 4px;
        }

        /* Action Forms */
        .action-area {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            display: flex;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
        }
        
        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .form-group label {
            font-weight: 600;
            font-size: 13px;
            color: #34495e;
        }
        .form-control {
            padding: 10px;
            border: 1px solid #ccd1d1;
            border-radius: 5px;
            font-size: 14px;
            width: 100%;
        }

        .btn {
            padding: 10px 18px;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .btn-success { background-color: #2ecc71; color: white; }
        .btn-success:hover { background-color: #27ae60; }
        .btn-primary { background-color: #3498db; color: white; }
        .btn-primary:hover { background-color: #2980b9; }
        .btn-danger { background-color: #e74c3c; color: white; }
        .btn-danger:hover { background-color: #c0392b; }
        .btn-back { background-color: #95a5a6; color: white; margin-top: 20px;}
        .btn-back:hover { background-color: #7f8c8d; }

        /* Status Badge */
        .badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            color: white;
            text-transform: uppercase;
        }
        .badge-warning { background-color: #f39c12; }
        .badge-info { background-color: #3498db; }
        .badge-success { background-color: #2ecc71; }
        .badge-danger { background-color: #e74c3c; }
    </style>
</head>
<body>

    <!-- Sidebar Admin -->
    <?php include __DIR__ . "/../TEMPLATE/SideBarAdmin.php"; ?>

    <div class="main-content">
        <div class="topbar">
            <h2>Detail Peminjaman</h2>
            <div style="font-size: 14px; color: #7f8c8d;">Panel Admin</div>
        </div>

        <div class="container">

            <!-- NOTIFIKASI -->
            <?php if (isset($_SESSION['success'])): ?>
                <div style="background: #e8f8f5; color: #1abc9c; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                    <?= htmlspecialchars($_SESSION['success']) ?>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div style="background: #fdeaea; color: #e74c3c; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                    <?= htmlspecialchars($_SESSION['error']) ?>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <!-- INFORMASI UTAMA TRANSAKSI -->
            <div class="card">
                <div class="card-header">
                    <h3>Informasi Transaksi #<?= htmlspecialchars($dataPeminjaman['id_peminjaman']) ?></h3>
                </div>
                <div class="info-grid">
                    <div class="info-box">
                        <p>Status Peminjaman</p>
                        <?php 
                            $status = strtolower($dataPeminjaman['status']);
                            $badgeClass = 'badge-info';
                            if ($status === 'diajukan') $badgeClass = 'badge-warning';
                            elseif ($status === 'dipinjam') $badgeClass = 'badge-info';
                            elseif ($status === 'dikembalikan') $badgeClass = 'badge-success';
                            elseif ($status === 'ditolak') $badgeClass = 'badge-danger';
                        ?>
                        <h4><span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($dataPeminjaman['status']) ?></span></h4>
                    </div>
                    <div class="info-box">
                        <p>Nama Peminjam</p>
                        <h4><?= htmlspecialchars($dataPeminjaman['nama_lengkap']) ?></h4>
                    </div>
                    <div class="info-box">
                        <p>NIS Siswa</p>
                        <h4><?= htmlspecialchars($dataPeminjaman['nis']) ?></h4>
                    </div>
                    <div class="info-box">
                        <p>Tanggal Pengajuan</p>
                        <h4><?= date('d M Y', strtotime($dataPeminjaman['tanggal_pinjam'])) ?></h4>
                    </div>
                </div>
            </div>

            <!-- TABEL DAFTAR BUKU YANG DIPINJAM -->
            <div class="card">
                <div class="card-header">
                    <h3>Daftar Buku yang Dipinjam</h3>
                </div>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Cover</th>
                                <th>Judul Buku</th>
                                <th>Jumlah</th>
                                <th>Target Pengembalian</th>
                                <th>Status Item</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($detailPeminjaman)): ?>
                                <?php $no = 1; foreach ($detailPeminjaman as $item): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td>
                                            <?php if (!empty($item['cover'])): ?>
                                                <img src="/Aplikasi Peminjaman Buku/ASSETS/COVER/<?= htmlspecialchars($item['cover']) ?>" class="cover-img" alt="Cover">
                                            <?php else: ?>
                                                <span style="color: #bdc3c7; font-size: 11px;">No Cover</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><strong><?= htmlspecialchars($item['judul_buku']) ?></strong></td>
                                        <td><?= htmlspecialchars($item['jumlah_buku']) ?> Buku</td>
                                        <td>
                                            <?= !empty($item['tanggal_pengembalian']) ? date('d M Y', strtotime($item['tanggal_pengembalian'])) : '-' ?>
                                        </td>
                                        <td><?= htmlspecialchars($item['status_detail']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" style="text-align: center;">Tidak ada detail buku ditemukan.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- FORM PERSETUJUAN ADMIN (MUNCUL JIKA STATUS MASIH 'Diajukan') -->
            <?php if ($dataPeminjaman['status'] === 'Diajukan'): ?>
                <div class="card" style="border-left: 4px solid #f39c12;">
                    <div class="card-header">
                        <h3>Aksi / Validasi Admin</h3>
                    </div>
                    <div class="action-area">
                        <!-- Form Setujui -->
                        <form action="/Aplikasi Peminjaman Buku/CONTROLLER/c_peminjaman.php?aksi=setujui" method="POST" style="display: flex; gap: 15px; align-items: flex-end; flex: 1;">
                            <input type="hidden" name="id_peminjaman" value="<?= htmlspecialchars($dataPeminjaman['id_peminjaman']) ?>">
                            <div class="form-group" style="margin: 0;">
                                <label>Target Tanggal Pengembalian</label>
                                <input type="date" name="tanggal_pengembalian" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-success" onclick="return confirm('Setujui peminjaman ini?')">✔ Setujui Peminjaman</button>
                        </form>

                        <!-- Form Tolak -->
                        <form action="/Aplikasi Peminjaman Buku/CONTROLLER/c_peminjaman.php?aksi=tolak" method="POST" style="margin: 0;">
                            <input type="hidden" name="id_peminjaman" value="<?= htmlspecialchars($dataPeminjaman['id_peminjaman']) ?>">
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin ingin menolak pengajuan ini?')">✖ Tolak</button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>

            <!-- FORM PENGEMBALIAN BUKU (MUNCUL JIKA STATUS 'Dipinjam') -->
            <?php if ($dataPeminjaman['status'] === 'Dipinjam'): ?>
                <div class="card" style="border-left: 4px solid #3498db;">
                    <div class="card-header">
                        <h3>Proses Pengembalian Buku</h3>
                    </div>
                    <div class="action-area" style="background-color: #ebf5fb; border-color: #d6eaf8;">
                        <form action="/Aplikasi Peminjaman Buku/CONTROLLER/c_peminjaman.php?aksi=prosesKembali" method="POST" style="display: flex; gap: 20px; align-items: flex-end; width: 100%;">
                            <input type="hidden" name="id_peminjaman" value="<?= htmlspecialchars($dataPeminjaman['id_peminjaman']) ?>">
                            
                            <div class="form-group" style="flex: 1;">
                                <label>Kondisi Buku (Opsional jika Rusak/Hilang)</label>
                                <select name="jenis_denda" class="form-control">
                                    <option value="">-- Normal / Tidak Ada Kerusakan --</option>
                                    <option value="Buku Rusak">Buku Rusak</option>
                                    <option value="Buku Hilang">Buku Hilang</option>
                                </select>
                            </div>

                            <div class="form-group" style="flex: 1;">
                                <label>Nominal Denda Tambahan (Rp)</label>
                                <input type="number" name="jumlah_denda" class="form-control" value="0" placeholder="0">
                            </div>

                            <button type="submit" class="btn btn-primary" onclick="return confirm('Pastikan buku fisik sudah Anda terima. Yakin selesaikan transaksi ini?');">
                                Selesaikan & Kembalikan
                            </button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>

            <a href="/Aplikasi Peminjaman Buku/CONTROLLER/c_peminjaman.php?aksi=admin" class="btn btn-back">← Kembali ke Daftar Peminjaman</a>

        </div>
    </div>

</body>
</html>