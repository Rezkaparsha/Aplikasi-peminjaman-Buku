<?php
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
            header("Location: ../VIEW/ADMIN/daftarUser.php");
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
            header("Location: ../VIEW/ADMIN/daftarUser.php");
            exit;

        case 'updatePassword':
            $controller->updatePassword(
                (int) $_POST['id_user'],
                $_POST['password']
            );
            header("Location: ../VIEW/ADMIN/daftarUser.php");
            exit;

        case 'hapus':
            $id_user = (int) $_GET['id_user'];
            $controller->hapus($id_user);
            header("Location: ../VIEW/ADMIN/daftarUser.php");
            exit;
    }
}
