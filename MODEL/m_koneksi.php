<?php

class Koneksi
{
    private $host = "localhost";
    private $username = "root";
    private $pass = "";
    private $dbname = "db_perpustakaan";

    public $koneksi;

    public function __construct()
    {
        $this->koneksi = mysqli_connect(
            $this->host,
            $this->username,
            $this->pass,
            $this->dbname
        );

        if (!$this->koneksi) {
            die("Koneksi database gagal: " . mysqli_connect_error());
        }

        $this->koneksi->set_charset("utf8mb4");
    }

    public function getKoneksi()
    {
        return $this->koneksi;
    }
}