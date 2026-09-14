<?php


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Jika bukan siswa, tendang kembali ke halaman terakhirnya
if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'siswa') {
    // Cek apakah ada histori halaman sebelumnya. Jika ada, kembalikan ke sana. Jika tidak, lempar ke dashboard admin.
    $kembali = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/Aplikasi Peminjaman Buku/VIEW/ADMIN/dashboardAdmin.php';
    header("Location: " . $kembali);
    exit;
}
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../../MODEL/m_koneksi.php";

// Proteksi Halaman: Wajib Login
if (!isset($_SESSION['id_user'])) {
    header("Location: /Aplikasi Peminjaman Buku/VIEW/AUTH/login.php");
    exit;
}

$db = new Koneksi();
$koneksi = $db->getKoneksi();

$id_user = (int)$_SESSION['id_user'];

// Ambil data user terbaru dari database
$stmt = $koneksi->prepare("SELECT id_user, nis_nip, username, nama_lengkap, role FROM users WHERE id_user = ?");
$stmt->bind_param("i", $id_user);
$stmt->execute();
$userData = $stmt->get_result()->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - Aplikasi Perpustakaan</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { 
            background-color: #f8f9fa; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .card-custom {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        }
        .avatar-circle {
            width: 80px;
            height: 80px;
            background-color: #2563eb;
            color: white;
            font-size: 2rem;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin: 0 auto 15px;
        }
    </style>
</head>
<body>

<div class="d-flex min-vh-100">
    
    <!-- PANGGIL SIDEBAR SISWA -->
    <?php include __DIR__ . "/../TEMPLATE/SideBarSiswa.php"; ?>

    <!-- AREA KONTEN UTAMA -->
    <div class="flex-grow-1 p-4 p-md-5 overflow-auto" style="max-height: 100vh;">
        
        <div class="mb-4">
            <h3 class="fw-bold text-dark mb-1"><i class="fa-solid fa-user-gear text-primary me-2"></i>Profil Saya</h3>
            <p class="text-muted mb-0">Kelola informasi akun dan perbarui kata sandi Anda.</p>
        </div>

        <!-- Alert Notifikasi Session -->
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
                <i class="fa-solid fa-triangle-exclamation me-2"></i><?= htmlspecialchars($_SESSION['error']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i><?= htmlspecialchars($_SESSION['success']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <div class="row g-4">
            <!-- Informasi Akun (Kiri) -->
            <div class="col-lg-5">
                <div class="card card-custom p-4 text-center h-100">
                    <div class="avatar-circle">
                        <?= strtoupper(substr($userData['nama_lengkap'] ?? 'S', 0, 1)) ?>
                    </div>
                    <h5 class="fw-bold mb-1"><?= htmlspecialchars($userData['nama_lengkap'] ?? '-') ?></h5>
                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-3 py-1 rounded-pill w-auto mx-auto mb-4">
                        <?= strtoupper(htmlspecialchars($userData['role'] ?? 'SISWA')) ?>
                    </span>

                    <div class="text-start border-top pt-3">
                        <div class="mb-3">
                            <label class="text-muted small d-block">NIS / NIP</label>
                            <span class="fw-semibold text-dark fs-6"><?= htmlspecialchars($userData['nis_nip'] ?? '-') ?></span>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted small d-block">Username</label>
                            <span class="fw-semibold text-dark fs-6"><?= htmlspecialchars($userData['username'] ?? '-') ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Ubah Password (Kanan) -->
            <div class="col-lg-7">
                <div class="card card-custom p-4 h-100">
                    <h5 class="fw-bold mb-3 border-bottom pb-2"><i class="fa-solid fa-key me-2 text-warning"></i>Ubah Password</h5>
                    
                    <form action="/Aplikasi Peminjaman Buku/CONTROLLER/c_user.php?aksi=updatePassword" method="POST">
                        
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Password Saat Ini</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-lock"></i></span>
                                <input type="password" name="password_lama" class="form-control" placeholder="Masukkan password lama" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Password Baru</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-key"></i></span>
                                <input type="password" name="password_baru" class="form-control" placeholder="Masukkan password baru" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-semibold">Konfirmasi Password Baru</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="fa-solid fa-check-double"></i></span>
                                <input type="password" name="konfirmasi_password_baru" class="form-control" placeholder="Ulangi password baru" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">
                            <i class="fa-solid fa-floppy-disk me-2"></i>Simpan Perubahan Password
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>