<?php

require_once 'm_koneksi.php';

class Users
{
    private $koneksi;

    public $id_user;
    public $nis;
    public $nama_lengkap;
    public $username;
    public $password;
    public $role;

    public function __construct()
    {
        $db = new Koneksi();
        $this->koneksi = $db->getKoneksi();
    }

    // MENAMPILKAN SEMUA USER
    public function getAll()
    {
        $query = "SELECT * FROM users ORDER BY id_user DESC";

        $result = $this->koneksi->query($query);
        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        return $data;
    }

    // MENCARI USER BERDASARKAN ID
    public function getById($id_user)
    {
        $stmt = $this->koneksi->prepare(
            "SELECT * FROM users WHERE id_user = ?"
        );

        $stmt->bind_param("i", $id_user);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    // MENCARI USER BERDASARKAN USERNAME
    public function getByUsername($username)
    {
        $stmt = $this->koneksi->prepare(
            "SELECT * FROM users WHERE username = ?"
        );

        $stmt->bind_param("s", $username);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    // MENCARI USER BERDASARKAN NIS
    public function getByNis($nis)
    {
        $stmt = $this->koneksi->prepare(
            "SELECT * FROM users WHERE nis = ?"
        );

        $stmt->bind_param("s", $nis);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    // TAMBAH USER
    public function insert()
    {
        $stmt = $this->koneksi->prepare(
            "INSERT INTO users
            (nis, nama_lengkap, username, password, role)
            VALUES (?, ?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "sssss",
            $this->nis,
            $this->nama_lengkap,
            $this->username,
            $this->password,
            $this->role
        );

        return $stmt->execute();
    }

    // UPDATE USER
    public function update()
    {
        $stmt = $this->koneksi->prepare(
            "UPDATE users SET
                nis = ?,
                nama_lengkap = ?,
                username = ?,
                role = ?
             WHERE id_user = ?"
        );

        $stmt->bind_param(
            "ssssi",
            $this->nis,
            $this->nama_lengkap,
            $this->username,
            $this->role,
            $this->id_user
        );

        return $stmt->execute();
    }

    // UPDATE PASSWORD
    public function updatePassword()
    {
        $stmt = $this->koneksi->prepare(
            "UPDATE users SET password = ?
             WHERE id_user = ?"
        );

        $stmt->bind_param(
            "si",
            $this->password,
            $this->id_user
        );

        return $stmt->execute();
    }

    // HAPUS USER
    public function delete($id_user)
    {
        $stmt = $this->koneksi->prepare(
            "DELETE FROM users WHERE id_user = ?"
        );

        $stmt->bind_param("i", $id_user);

        return $stmt->execute();
    }
}
?>