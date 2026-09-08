<?php

require_once 'm_users.php';

class UsersController
{
    private $users;

    public function __construct()
    {
        $this->users = new Users();
    }

    // MENAMPILKAN SEMUA USER

    public function index()
    {
        return $this->users->getAll();
    }


    // DETAIL USER

    public function detail($id_user)
    {
        return $this->users->getById($id_user);
    }


    // TAMBAH USER

    public function tambah(
        $nis,
        $nama_lengkap,
        $username,
        $password,
        $role
    ) {
        $this->users->nis = $nis;
        $this->users->nama_lengkap = $nama_lengkap;
        $this->users->username = $username;

        // Password di-hash sebelum disimpan
        $this->users->password = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        $this->users->role = $role;

        return $this->users->insert();
    }


    // UPDATE USER

    public function update(
        $id_user,
        $nis,
        $nama_lengkap,
        $username,
        $role
    ) {
        $this->users->id_user = $id_user;
        $this->users->nis = $nis;
        $this->users->nama_lengkap = $nama_lengkap;
        $this->users->username = $username;
        $this->users->role = $role;

        return $this->users->update();
    }


    // UPDATE PASSWORD

    public function updatePassword(
        $id_user,
        $password
    ) {
        $this->users->id_user = $id_user;

        $this->users->password = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        return $this->users->updatePassword();
    }


    // HAPUS USER

    public function hapus($id_user)
    {
        return $this->users->delete($id_user);
    }
}