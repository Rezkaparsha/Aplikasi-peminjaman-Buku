<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../MODEL/m_users.php";

class UserController
{
    private $user;

    public function __construct()
    {
        $this->user = new Users();
    }

    // TAMPILKAN SEMUA USER
    public function index()
    {
        return $this->user->getAll();
    }

    // DETAIL USER
    public function detail($id_user)
    {
        return $this->user->getById($id_user);
    }

    // TAMBAH USER
    public function tambah($nis_nip, $nama_lengkap, $kelas, $username, $password, $role)
    {
        $this->user->nis_nip      = $nis_nip;
        $this->user->nama_lengkap = $nama_lengkap;
        $this->user->kelas        = $kelas;
        $this->user->username     = $username;
        $this->user->password     = password_hash($password, PASSWORD_DEFAULT);
        $this->user->role         = $role;

        return $this->user->insert();
    }

    // UPDATE USER (tanpa password)
    public function update($id_user, $nis_nip, $nama_lengkap, $kelas, $username, $role)
    {
        $this->user->id_user      = $id_user;
        $this->user->nis_nip      = $nis_nip;
        $this->user->nama_lengkap = $nama_lengkap;
        $this->user->kelas        = $kelas;
        $this->user->username     = $username;
        $this->user->role         = $role;

        return $this->user->update();
    }

    // UPDATE PASSWORD
    public function updatePassword($id_user, $password)
    {
        $this->user->id_user  = $id_user;
        $this->user->password = password_hash($password, PASSWORD_DEFAULT);

        return $this->user->updatePassword();
    }

    // HAPUS USER DENGAN TANGKAPAN ERROR (TRY-CATCH)
    public function hapus($id_user)
    {
        try {
            return $this->user->delete($id_user);
        } catch (mysqli_sql_exception $e) {
            // Tangkap Error Code 1451 (Foreign Key Constraint Fail)
            if ($e->getCode() === 1451) {
                $_SESSION['error'] = "User tidak dapat dihapus karena memiliki riwayat peminjaman buku!";
            } else {
                $_SESSION['error'] = "Gagal menghapus user: " . $e->getMessage();
            }
            return false;
        }
    }
}


// =============================================================
// HANDLER REQUEST
// =============================================================
$controller = new UserController();
$aksi = $_GET['aksi'] ?? '';

switch ($aksi) {

    // ---------------------------------------------------------
    // 1. PROSES TAMBAH USER
    // ---------------------------------------------------------
    case 'tambah':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $hasil = $controller->tambah(
                $_POST['nis_nip'] ?? '',
                $_POST['nama_lengkap'] ?? '',
                $_POST['kelas'] ?? '',
                $_POST['username'] ?? '',
                $_POST['password'] ?? '',
                $_POST['role'] ?? 'siswa'
            );

            if ($hasil) {
                $_SESSION['success'] = "User berhasil ditambahkan!";
            } else {
                $_SESSION['error'] = "Gagal menambahkan user baru.";
            }
        }
        header("Location: /Aplikasi Peminjaman Buku/VIEW/ADMIN/daftarUser.php");
        exit;

    // ---------------------------------------------------------
    // 2. PROSES UPDATE USER
    // ---------------------------------------------------------
    case 'update':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $hasil = $controller->update(
                (int) ($_POST['id_user'] ?? 0),
                $_POST['nis_nip'] ?? '',
                $_POST['nama_lengkap'] ?? '',
                $_POST['kelas'] ?? '',
                $_POST['username'] ?? '',
                $_POST['role'] ?? 'siswa'
            );

            if ($hasil) {
                $_SESSION['success'] = "Data user berhasil diperbarui!";
            } else {
                $_SESSION['error'] = "Gagal memperbarui data user.";
            }
        }
        header("Location: /Aplikasi Peminjaman Buku/VIEW/ADMIN/daftarUser.php");
        exit;

    // ---------------------------------------------------------
    // 3. PROSES UPDATE PASSWORD (ADMIN & SISWA)
    // ---------------------------------------------------------
    case 'updatePassword':
        if (!isset($_SESSION['id_user'])) {
            header("Location: /Aplikasi Peminjaman Buku/VIEW/login.php");
            exit;
        }

        // Update Password oleh Admin via Form Admin
        if (isset($_POST['id_user']) && !empty($_POST['id_user'])) {
            $controller->updatePassword(
                (int) $_POST['id_user'],
                $_POST['password']
            );
            $_SESSION['success'] = "Password user berhasil diperbarui!";
            header("Location: /Aplikasi Peminjaman Buku/VIEW/ADMIN/daftarUser.php");
            exit;
        }

        // Update Password Mandiri oleh Siswa via Profil
        $id_user       = (int) $_SESSION['id_user'];
        $password_lama = $_POST['password_lama'] ?? '';
        $password_baru = $_POST['password_baru'] ?? '';
        $konfirmasi    = $_POST['konfirmasi_password_baru'] ?? '';

        if (empty($password_lama) || empty($password_baru) || empty($konfirmasi)) {
            $_SESSION['error'] = "Semua bidang password wajib diisi!";
            header("Location: /Aplikasi Peminjaman Buku/VIEW/SISWA/profil.php");
            exit;
        }

        if ($password_baru !== $konfirmasi) {
            $_SESSION['error'] = "Konfirmasi password baru tidak cocok!";
            header("Location: /Aplikasi Peminjaman Buku/VIEW/SISWA/profil.php");
            exit;
        }

        $userCurrent = $controller->detail($id_user);
        if (!$userCurrent || !password_verify($password_lama, $userCurrent['password'])) {
            $_SESSION['error'] = "Password saat ini (lama) tidak sesuai!";
            header("Location: /Aplikasi Peminjaman Buku/VIEW/SISWA/profil.php");
            exit;
        }

        $hasil = $controller->updatePassword($id_user, $password_baru);
        if ($hasil) {
            $_SESSION['success'] = "Password Anda berhasil diperbarui!";
        } else {
            $_SESSION['error'] = "Gagal memperbarui password. Silakan coba lagi.";
        }

        header("Location: /Aplikasi Peminjaman Buku/VIEW/SISWA/profil.php");
        exit;

    // ---------------------------------------------------------
    // 4. PROSES HAPUS USER
    // ---------------------------------------------------------
    case 'hapus':
        $id_user = (int) ($_GET['id_user'] ?? 0);
        if ($id_user > 0) {
            $statusHapus = $controller->hapus($id_user);
            if ($statusHapus) {
                $_SESSION['success'] = "User berhasil dihapus.";
            }
        }
        header("Location: /Aplikasi Peminjaman Buku/VIEW/ADMIN/daftarUser.php");
        exit;

    // ---------------------------------------------------------
    // DEFAULT REDIRECT
    // ---------------------------------------------------------
    default:
        header("Location: /Aplikasi Peminjaman Buku/VIEW/ADMIN/daftarUser.php");
        exit;
}