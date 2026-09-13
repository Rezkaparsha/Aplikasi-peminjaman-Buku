<?php
session_start();

require_once __DIR__ . "/../../MODEL/m_peminjaman.php";

// Proteksi Halaman: Jika belum login, lempar ke halaman Login AUTH
if (!isset($_SESSION['id_user'])) {
    header("Location: /Aplikasi Peminjaman Buku/VIEW/AUTH/login.php");
    exit;
}
// Ambil ID User aktif dari session login
$id_user = (int)$_SESSION['id_user'];

// Inisialisasi Model
$modelPeminjaman = new M_Peminjaman();
$dataPeminjaman = $modelPeminjaman->getPeminjamanByUser($id_user);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peminjaman Saya - Aplikasi Perpustakaan</title>
    
    <!-- Bootstrap 5 CSS untuk Tampilan Modern -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        }
        .table > :not(caption) > * > * {
            padding: 1rem 0.75rem;
            vertical-align: middle;
        }
        .badge-status {
            font-size: 0.85rem;
            padding: 0.5em 0.85em;
            border-radius: 20px;
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="container py-5">
    
    <!-- Header Navigasi & Judul -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1"><i class="fa-solid fa-book-bookmark text-primary me-2"></i>Peminjaman Saya</h3>
            <p class="text-muted mb-0">Pantau status pengajuan dan riwayat peminjaman buku Anda</p>
        </div>
        <a href="/Aplikasi Peminjaman Buku/VIEW/SISWA/daftarBuku.php" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="fa-solid fa-arrow-left me-2"></i>Kembali ke Daftar Buku
        </a>
    </div>

    <!-- Alert Notifikasi Session -->
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
            <i class="fa-solid fa-triangle-exclamation me-2"></i><?= htmlspecialchars($_SESSION['error']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i><?= htmlspecialchars($_SESSION['success']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <!-- Card Tabel Riwayat Peminjaman -->
    <div class="card p-4">
        <?php if (!empty($dataPeminjaman)): ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 50px;">No</th>
                            <th>ID Transaksi</th>
                            <th>NIS / NIP</th>
                            <th>Nama Peminjam</th>
                            <th>Tanggal Pinjam</th>
                            <th class="text-center">Jumlah Jenis Buku</th>
                            <th class="text-center">Total Unit Buku</th>
                            <th class="text-center">Status</th>
                            <th class="text-center" style="width: 120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($dataPeminjaman as $row): ?>
                            <tr>
                                <td class="text-center text-muted fw-bold"><?= $no++ ?></td>
                                <td>
                                    <span class="fw-semibold text-primary">#PMJ-<?= htmlspecialchars($row['id_peminjaman']) ?></span>
                                </td>
                                <td><?= htmlspecialchars($row['nis']) ?></td>
                                <td><span class="fw-semibold"><?= htmlspecialchars($row['nama_lengkap']) ?></span></td>
                                <td>
                                    <i class="fa-regular fa-calendar-days text-muted me-1"></i>
                                    <?= date('d M Y', strtotime($row['tanggal_pinjam'])) ?>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-light text-dark border"><?= htmlspecialchars($row['jumlah_jenis_buku']) ?> Jenis</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-light text-dark border"><?= htmlspecialchars($row['total_buku']) ?> Bks</span>
                                </td>
                                <td class="text-center">
                                    <?php 
                                        $status = $row['status'];
                                        $badgeClass = 'bg-secondary';
                                        $icon = 'fa-clock';

                                        if ($status === 'Diajukan') {
                                            $badgeClass = 'bg-warning text-dark';
                                            $icon = 'fa-spinner';
                                        } elseif ($status === 'Dipinjam') {
                                            $badgeClass = 'bg-info text-white';
                                            $icon = 'fa-book-reader';
                                        } elseif ($status === 'Dikembalikan') {
                                            $badgeClass = 'bg-success text-white';
                                            $icon = 'fa-circle-check';
                                        } elseif ($status === 'Ditolak') {
                                            $badgeClass = 'bg-danger text-white';
                                            $icon = 'fa-circle-xmark';
                                        }
                                    ?>
                                    <span class="badge badge-status <?= $badgeClass ?>">
                                        <i class="fa-solid <?= $icon ?> me-1"></i><?= htmlspecialchars($status) ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="/Aplikasi Peminjaman Buku/CONTROLLER/c_peminjaman.php?aksi=detail&id_peminjaman=<?= $row['id_peminjaman'] ?>" 
                                       class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                        <i class="fa-solid fa-eye me-1"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <!-- Tampilan Jika Belum Ada Data Transaksi -->
            <div class="text-center py-5">
                <i class="fa-solid fa-folder-open text-muted mb-3" style="font-size: 3.5rem;"></i>
                <h5 class="fw-bold text-secondary">Belum Ada Transaksi Peminjaman</h5>
                <p class="text-muted">Anda belum mengajukan peminjaman buku apa pun saat ini.</p>
                <a href="/Aplikasi Peminjaman Buku/VIEW/SISWA/daftarBuku.php" class="btn btn-primary rounded-pill px-4 mt-2">
                    <i class="fa-solid fa-plus me-2"></i>Pinjam Buku Sekarang
                </a>
            </div>
        <?php endif; ?>
    </div>

</div>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>