<?php
require_once __DIR__ . "/../MODEL/m_penulis.php";

$penulisModel = new Penulis();
$aksi = $_GET['aksi'] ?? '';

// TAMBAH PENULIS
if ($aksi === 'tambah') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: ../VIEW/ADMIN/tambahPenulis.php");
        exit;
    }

    $namaPenulis = trim($_POST['nama_penulis'] ?? '');

    if ($namaPenulis === '') {
        echo "Nama penulis wajib diisi.";
        exit;
    }

    $penulisModel->nama_penulis = $namaPenulis;

    if ($penulisModel->insert()) {
        header("Location: ../VIEW/ADMIN/daftarPenulis.php");
        exit;
    } else {
        echo "Gagal menambahkan penulis.";
        exit;
    }
}

// EDIT PENULIS
elseif ($aksi === 'edit') {
    $idPenulis = (int) ($_GET['id_penulis'] ?? 0);

    if ($idPenulis <= 0) {
        header("Location: ../VIEW/ADMIN/daftarPenulis.php");
        exit;
    }

    $dataPenulis = $penulisModel->getById($idPenulis);

    if (!$dataPenulis) {
        echo "Data penulis tidak ditemukan.";
        exit;
    }

    require_once __DIR__ . "/../VIEW/ADMIN/editPenulis.php";
    exit;
}

// UPDATE PENULIS
elseif ($aksi === 'update') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: ../VIEW/ADMIN/daftarPenulis.php");
        exit;
    }

    $idPenulis   = (int) ($_POST['id_penulis'] ?? 0);
    $namaPenulis = trim($_POST['nama_penulis'] ?? '');

    if ($idPenulis <= 0 || $namaPenulis === '') {
        echo "Data penulis tidak valid.";
        exit;
    }

    $penulisModel->id_penulis   = $idPenulis;
    $penulisModel->nama_penulis = $namaPenulis;

    if ($penulisModel->update()) {
        header("Location: ../VIEW/ADMIN/daftarPenulis.php");
        exit;
    } else {
        echo "Gagal memperbarui penulis.";
        exit;
    }
}

// HAPUS PENULIS
elseif ($aksi === 'hapus') {
    $idPenulis = (int) ($_GET['id_penulis'] ?? 0);

    if ($idPenulis <= 0) {
        header("Location: ../VIEW/ADMIN/daftarPenulis.php");
        exit;
    }

    if ($penulisModel->delete($idPenulis)) {
        header("Location: ../VIEW/ADMIN/daftarPenulis.php");
        exit;
    } else {
        echo "
            <h3>Penulis tidak dapat dihapus.</h3>
            <p>Penulis mungkin masih digunakan oleh salah satu buku.</p>
            <a href='../VIEW/ADMIN/daftarPenulis.php'>Kembali</a>
        ";
        exit;
    }
}

// AKSI TIDAK DIKENAL
else {
    header("Location: ../VIEW/ADMIN/daftarPenulis.php");
    exit;
}
?>