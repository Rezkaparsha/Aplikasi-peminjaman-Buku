<?php
session_start();

require_once __DIR__ . "/../MODEL/m_koneksi.php";

$db = new Koneksi();
$koneksi = $db->getKoneksi();

$aksi = $_GET['aksi'] ?? '';


// 1. FITUR REGISTRASI SISWA

if ($aksi === 'register') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: /Aplikasi Peminjaman Buku/VIEW/AUTH/register.php");
        exit;
    }

    $nis_nip = trim($_POST['nis_nip'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $nama_lengkap = trim($_POST['nama_lengkap'] ?? '');
    $kelas = trim($_POST['kelas'] ?? '');
    $password = $_POST['password'] ?? '';
    $konfirmasi_password = $_POST['konfirmasi_password'] ?? '';

    // Validasi Kelengkapan Input
    if (empty($nis_nip) || empty($username) || empty($nama_lengkap) || empty($kelas) || empty($password)) {
        $_SESSION['error'] = "Semua bidang form wajib diisi!";
        header("Location: /Aplikasi Peminjaman Buku/VIEW/AUTH/register.php");
        exit;
    }

    // Validasi Kesesuaian Password
    if ($password !== $konfirmasi_password) {
        $_SESSION['error'] = "Konfirmasi password tidak cocok!";
        header("Location: /Aplikasi Peminjaman Buku/VIEW/AUTH/register.php");
        exit;
    }

    // Cek Apakah NIS/NIP Atau Username Sudah Terdaftar
    $stmtCek = $koneksi->prepare("SELECT id_user FROM users WHERE `nis_nip` = ? OR `username` = ?");
    $stmtCek->bind_param("ss", $nis_nip, $username);
    $stmtCek->execute();
    $resCek = $stmtCek->get_result();

    if ($resCek->num_rows > 0) {
        $stmtCek->close();
        $_SESSION['error'] = "NIS/NIP atau Username sudah terdaftar dalam sistem!";
        header("Location: /Aplikasi Peminjaman Buku/VIEW/AUTH/register.php");
        exit;
    }
    $stmtCek->close();

    // Enkripsi Password & Simpan ke Tabel users (Include Username)
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    $role = 'siswa';

    $stmtInsert = $koneksi->prepare("INSERT INTO users (`nis_nip`, `username`, nama_lengkap, kelas , password, role) VALUES (?, ?, ?, ?, ?, ?)");
    $stmtInsert->bind_param("ssssss", $nis_nip, $username, $nama_lengkap, $kelas , $hashed_password, $role);

    if ($stmtInsert->execute()) {
        $stmtInsert->close();
        $_SESSION['success'] = "Pendaftaran berhasil! Silakan login menggunakan NIS/NIP Anda.";
        header("Location: /Aplikasi Peminjaman Buku/VIEW/AUTH/login.php");
        exit;
    } else {
        $stmtInsert->close();
        $_SESSION['error'] = "Gagal mendaftarkan akun. Silakan coba lagi.";
        header("Location: /Aplikasi Peminjaman Buku/VIEW/AUTH/register.php");
        exit;
    }
}


// 2. FITUR LOGIN USER (ADMIN & SISWA)

elseif ($aksi === 'login') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: /Aplikasi Peminjaman Buku/VIEW/AUTH/login.php");
        exit;
    }

    $nis_nip = trim($_POST['nis_nip'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($nis_nip) || empty($password)) {
        $_SESSION['error'] = "NIS/NIP dan Password wajib diisi!";
        header("Location: /Aplikasi Peminjaman Buku/VIEW/AUTH/login.php");
        exit;
    }

    // Login bisa menggunakan NIS/NIP atau Username
    $stmt = $koneksi->prepare("SELECT id_user, `nis_nip`, username, nama_lengkap, password, role FROM users WHERE `nis_nip` = ? OR `username` = ?");
    $stmt->bind_param("ss", $nis_nip, $nis_nip);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($user && (password_verify($password, $user['password']) || md5($password) === $user['password'] || $password === $user['password'])) {
        $_SESSION['id_user'] = $user['id_user'];
        $_SESSION['nis_nip'] = $user['nis_nip'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
        $_SESSION['role'] = strtolower($user['role']);

        if ($_SESSION['role'] === 'admin') {
            header("Location: /Aplikasi Peminjaman Buku/VIEW/ADMIN/dashboardAdmin.php");
        } else {
            header("Location: /Aplikasi Peminjaman Buku/VIEW/SISWA/dashboardSiswa.php");
        }
        exit;
    } else {
        $_SESSION['error'] = "NIS/NIP/Username atau Password salah!";
        header("Location: /Aplikasi Peminjaman Buku/VIEW/AUTH/login.php");
        exit;
    }
}


// 3. FITUR LOGOUT

elseif ($aksi === 'logout') {
    session_unset();
    session_destroy();
    session_start();
    $_SESSION['success'] = "Anda telah berhasil keluar (logout).";
    header("Location: /Aplikasi Peminjaman Buku/VIEW/AUTH/login.php");
    exit;
}

// Pengarahan Default
else {
    header("Location: /Aplikasi Peminjaman Buku/VIEW/AUTH/login.php");
    exit;
}
?>