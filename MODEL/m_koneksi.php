<?php
class koneksi
{

    private $host = "localhost",
        $username = "root",
        $pass = "",
        $dbname = "db_perpustakaan";

    public $koneksi;

    function __construct()
    {
        $this->koneksi = mysqli_connect(
            $this->host,
            $this->username,
            $this->pass,
            $this->dbname
        );



        if (!$this->koneksi) {
            die("koneski database gagal" . mysqli_connect_error());
        }
    }

    public function getkoneksi(){
        return $this->koneksi;
    }
}