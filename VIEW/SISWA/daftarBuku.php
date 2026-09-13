<?php
session_start();

// Proteksi Halaman: Jika belum login, lempar ke halaman Login AUTH
if (!isset($_SESSION['id_user'])) {
    header("Location: /Aplikasi Peminjaman Buku/VIEW/AUTH/login.php");
    exit;
}

// Ambil ID User aktif dari session login
$id_user = (int)$_SESSION['id_user'];

// Menggunakan Model m_buku.php (Sama seperti Admin, tanpa query SQL langsung di View)
require_once __DIR__ . "/../../MODEL/m_buku.php";

$bukuModel = new Buku();
$allBuku = $bukuModel->getAllBuku();

// Filter hanya buku yang memiliki stok lebih dari 0 untuk tampilan siswa
$daftarBuku = array_filter($allBuku, function($buku) {
    return (int)$buku['stok'] > 0;
});
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Buku - Perpustakaan</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { 
            background-color: #f8f9fa; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .card-buku {
            transition: transform 0.2s, box-shadow 0.2s;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
        }
        .card-buku:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.08);
        }
        
        /* Container dan Properti Cover Buku Pas Tanpa Terpotong */
        .cover-container {
            height: 240px;
            background-color: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            padding: 12px;
        }
        .cover-buku {
            max-height: 100%;
            max-width: 100%;
            width: auto;
            height: auto;
            object-fit: contain;
            border-radius: 6px;
            box-shadow: 0 3px 8px rgba(0,0,0,0.12);
        }
        
        .buku-checkbox {
            transform: scale(1.4);
            cursor: pointer;
        }
        .sticky-bottom-bar {
            position: fixed;
            bottom: 0;
            left: 260px; /* Menyesuaikan lebar Sidebar Siswa */
            right: 0;
            background: #ffffff;
            box-shadow: 0 -4px 20px rgba(0,0,0,0.1);
            padding: 15px 30px;
            z-index: 1000;
            display: none;
        }
        @media (max-width: 768px) {
            .sticky-bottom-bar {
                left: 0;
            }
        }
    </style>
</head>
<body>

<!-- WADAH FLEXBOX UTAMA (SIDEBAR + KONTEN) -->
<div class="d-flex min-vh-100">
    
    <!-- PANGGIL SIDEBAR SISWA -->
    <?php include __DIR__ . "/../TEMPLATE/SideBarSiswa.php"; ?>

    <!-- AREA KONTEN KATALOG -->
    <div class="flex-grow-1 p-4 p-md-5 overflow-auto" style="max-height: 100vh;">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold text-dark mb-1"><i class="fa-solid fa-book-open text-primary me-2"></i>Katalog Buku</h3>
                <p class="text-muted mb-0">Pilih buku yang ingin Anda pinjam, lalu ajukan peminjaman.</p>
            </div>
            <a href="/Aplikasi Peminjaman Buku/VIEW/SISWA/peminjaman.php" class="btn btn-outline-primary rounded-pill px-4">
                <i class="fa-solid fa-list-check me-2"></i>Peminjaman Saya
            </a>
        </div>

        <!-- Alert Notifikasi -->
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
                <i class="fa-solid fa-circle-exclamation me-2"></i><?= htmlspecialchars($_SESSION['error']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <!-- FORM PENGAJUAN PEMINJAMAN -->
        <form action="/Aplikasi Peminjaman Buku/CONTROLLER/c_peminjaman.php?aksi=tambah" method="POST" id="formPeminjaman">
            
            <div class="row g-4 pb-5">
                <?php if (!empty($daftarBuku)): ?>
                    <?php $index = 0; foreach ($daftarBuku as $buku): ?>
                        <div class="col-12 col-sm-6 col-md-4 col-xl-3">
                            <div class="card card-buku h-100 shadow-sm">
                                
                                <!-- Container Cover Buku dengan Penyesuaian Ukuran -->
                                <div class="cover-container">
                                    <?php if (!empty($buku['cover'])): ?>
                                        <img src="/Aplikasi Peminjaman Buku/ASSETS/COVER/<?= htmlspecialchars($buku['cover']) ?>" class="cover-buku" alt="Cover Buku">
                                    <?php else: ?>
                                        <i class="fa-solid fa-book-open fa-3x text-secondary opacity-50"></i>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="card-body d-flex flex-column p-3">
                                    <h6 class="fw-bold mb-1 text-truncate" title="<?= htmlspecialchars($buku['judul_buku']) ?>">
                                        <?= htmlspecialchars($buku['judul_buku']) ?>
                                    </h6>
                                    <p class="text-muted small mb-1">Penulis: <?= htmlspecialchars($buku['penulis'] ?? '-') ?></p>
                                    <p class="text-muted small mb-2">Tahun: <?= htmlspecialchars($buku['tahun_terbit'] ?? '-') ?></p>
                                    
                                    <div class="mt-auto border-top pt-3 d-flex justify-content-between align-items-center">
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1">
                                            Stok: <?= $buku['stok'] ?>
                                        </span>
                                        
                                        <!-- Checkbox Pilihan -->
                                        <div class="form-check m-0">
                                            <input class="form-check-input buku-checkbox" type="checkbox" 
                                                   name="id_buku[<?= $index ?>]" 
                                                   value="<?= $buku['id_buku'] ?>" 
                                                   id="check_<?= $buku['id_buku'] ?>"
                                                   onchange="toggleJumlah(this, <?= $index ?>)">
                                        </div>
                                    </div>
                                    
                                    <!-- Input Jumlah (Akan Muncul Jika Dicentang) -->
                                    <div class="mt-3" id="container_jumlah_<?= $index ?>" style="display: none;">
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text bg-light">Jumlah</span>
                                            <input type="number" name="jumlah[<?= $index ?>]" id="input_jumlah_<?= $index ?>" 
                                                   class="form-control text-center" value="1" min="1" max="<?= $buku['stok'] ?>" disabled>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    <?php $index++; endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center py-5">
                        <i class="fa-solid fa-box-open text-muted fa-4x mb-3 opacity-50"></i>
                        <h5 class="text-muted fw-bold">Katalog buku sedang kosong.</h5>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Sticky Bottom Bar Melayang -->
            <div class="sticky-bottom-bar" id="bottomBar">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0 fw-bold text-primary">
                            <i class="fa-solid fa-basket-shopping me-2"></i><span id="totalDipilih">0</span> Jenis Buku Dipilih
                        </h5>
                        <small class="text-muted">Pastikan buku yang dipilih sudah benar.</small>
                    </div>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 fw-bold" onclick="return confirm('Ajukan peminjaman sekarang?')">
                        Ajukan Peminjaman <i class="fa-solid fa-paper-plane ms-2"></i>
                    </button>
                </div>
            </div>

        </form>
    </div>
</div>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function toggleJumlah(checkbox, index) {
        const container = document.getElementById('container_jumlah_' + index);
        const inputJumlah = document.getElementById('input_jumlah_' + index);
        
        if (checkbox.checked) {
            container.style.display = 'block';
            inputJumlah.disabled = false;
        } else {
            container.style.display = 'none';
            inputJumlah.disabled = true;
            inputJumlah.value = 1;
        }
        
        updateTotalBar();
    }

    function updateTotalBar() {
        const checkboxes = document.querySelectorAll('.buku-checkbox:checked');
        const bottomBar = document.getElementById('bottomBar');
        const totalText = document.getElementById('totalDipilih');
        
        totalText.innerText = checkboxes.length;
        bottomBar.style.display = checkboxes.length > 0 ? 'block' : 'none';
    }
</script>
</body>
</html>