<?php

session_start();

require_once 'm_users.php';

class AuthController
{
    private $users;

    public function __construct()
    {
        $this->users = new Users();
    }


    // PROSES LOGIN

    public function login($username, $password)
    {
        $user = $this->users->getByUsername($username);

        if (!$user) {
            return [
                'status' => false,
                'pesan' => 'Username tidak ditemukan'
            ];
        }

        /*
         * Untuk sementara menyesuaikan dummy database.
         *
         * Jika nanti password sudah menggunakan password_hash(),
         * bagian ini diganti menjadi password_verify().
         */

        if ($password !== $user['password']) {
            return [
                'status' => false,
                'pesan' => 'Password salah'
            ];
        }

        $_SESSION['id_user'] = $user['id_user'];
        $_SESSION['nis'] = $user['nis'];
        $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        return [
            'status' => true,
            'pesan' => 'Login berhasil',
            'role' => $user['role']
        ];
    }


    // LOGOUT

    public function logout()
    {
        session_unset();
        session_destroy();

        return true;
    }


    // CEK LOGIN

    public function sudahLogin()
    {
        return isset($_SESSION['id_user']);
    }


    // CEK ROLE

    public function cekRole($role)
    {
        return isset($_SESSION['role'])
            && $_SESSION['role'] === $role;
    }
}