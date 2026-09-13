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
    public function tambah($nis, $nama_lengkap, $username, $password, $role)
    {
        $this->user->nis          = $nis;
        $this->user->nama_lengkap = $nama_lengkap;
        $this->user->username     = $username;
        $this->user->password     = password_hash($password, PASSWORD_DEFAULT);
        $this->user->role         = $role;

        return $this->user->insert();
    }

    // UPDATE USER (tanpa password)
    public function update($id_user, $nis, $nama_lengkap, $username, $role)
    {
        $this->user->id_user      = $id_user;
        $this->user->nis          = $nis;
        $this->user->nama_lengkap = $nama_lengkap;
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

    // HAPUS USER
    public function hapus($id_user)
    {
        return $this->user->delete($id_user);
    }
}


// =============================================================
// HANDLER REQUEST
// =============================================================
$controller = new UserController();

if (isset($_GET['aksi'])) {
    switch ($_GET['aksi']) {
        case 'tambah':
            $controller->tambah(
                $_POST['nis'],
                $_POST['nama_lengkap'],
                $_POST['username'],
                $_POST['password'],
                $_POST['role']
            );
            header("Location: /Aplikasi Peminjaman Buku/VIEW/ADMIN/daftarUser.php");
            exit;

        case 'edit':
            $id_user  = (int) $_GET['id_user'];
            $dataUser = $controller->detail($id_user);
            if (!$dataUser) die("Data user tidak ditemukan.");
            require_once __DIR__ . "/../VIEW/ADMIN/editUser.php";
            break;

        case 'update':
            $controller->update(
                (int) $_POST['id_user'],
                $_POST['nis'],
                $_POST['nama_lengkap'],
                $_POST['username'],
                $_POST['role']
            );
            header("Location: /Aplikasi Peminjaman Buku/VIEW/ADMIN/daftarUser.php");
            exit;

        case 'updatePassword':
            // 1. Cek Sesi User
            if (!isset($_SESSION['id_user'])) {
                header("Location: /Aplikasi Peminjaman Buku/VIEW/AUTH/login.php");
                exit;
            }

            // Aksi Update Password oleh Admin via Form Admin (jika ada input id_user di POST)
            if (isset($_POST['id_user']) && !empty($_POST['id_user'])) {
                $controller->updatePassword(
                    (int) $_POST['id_user'],
                    $_POST['password']
                );
                $_SESSION['success'] = "Password user berhasil diperbarui!";
                header("Location: /Aplikasi Peminjaman Buku/VIEW/ADMIN/daftarUser.php");
                exit;
            }

            // Aksi Update Password Mandiri oleh Siswa via Halaman Profil
            $id_user = (int) $_SESSION['id_user'];
            $password_lama = $_POST['password_lama'] ?? '';
            $password_baru = $_POST['password_baru'] ?? '';
            $konfirmasi    = $_POST['konfirmasi_password_baru'] ?? '';

            // Validasi Input
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

            // Verifikasi Password Lama
            $userCurrent = $controller->detail($id_user);
            if (!$userCurrent || !password_verify($password_lama, $userCurrent['password'])) {
                $_SESSION['error'] = "Password saat ini (lama) tidak sesuai!";
                header("Location: /Aplikasi Peminjaman Buku/VIEW/SISWA/profil.php");
                exit;
            }

            // Eksekusi Update Password
            $hasil = $controller->updatePassword($id_user, $password_baru);

            if ($hasil) {
                $_SESSION['success'] = "Password Anda berhasil diperbarui!";
            } else {
                $_SESSION['error'] = "Gagal memperbarui password. Silakan coba lagi.";
            }

            header("Location: /Aplikasi Peminjaman Buku/VIEW/SISWA/profil.php");
            exit;

        case 'hapus':
            $id_user = (int) $_GET['id_user'];
            $controller->hapus($id_user);
            header("Location: /Aplikasi Peminjaman Buku/VIEW/ADMIN/daftarUser.php");
            exit;
    }
}