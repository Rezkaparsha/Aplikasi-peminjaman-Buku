<?php
require_once __DIR__ . "/../MODEL/m_penerbit.php";

$penerbitModel = new Penerbit();
$aksi = $_GET['aksi'] ?? '';

// TAMBAH PENERBIT
if ($aksi === 'tambah') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: ../VIEW/ADMIN/tambahPenerbit.php");
        exit;
    }

    $namaPenerbit = trim($_POST['nama_penerbit'] ?? '');
    $alamat       = trim($_POST['alamat'] ?? '');
    $kota         = trim($_POST['kota'] ?? '');
    $telepon      = trim($_POST['telepon'] ?? '');
    $email        = trim($_POST['email'] ?? '');

    if ($namaPenerbit === '') {
        echo "Nama penerbit wajib diisi.";
        exit;
    }

    $penerbitModel->nama_penerbit = $namaPenerbit;
    $penerbitModel->alamat        = $alamat;
    $penerbitModel->kota          = $kota;
    $penerbitModel->telepon       = $telepon;
    $penerbitModel->email         = $email;

    if ($penerbitModel->insert()) {
        header("Location: ../VIEW/ADMIN/daftarPenerbit.php");
        exit;
    } else {
        echo "Gagal menambahkan penerbit.";
        exit;
    }
}

// EDIT PENERBIT
elseif ($aksi === 'edit') {
    $idPenerbit = (int) ($_GET['id_penerbit'] ?? 0);

    if ($idPenerbit <= 0) {
        header("Location: ../VIEW/ADMIN/daftarPenerbit.php");
        exit;
    }

    $dataPenerbit = $penerbitModel->getById($idPenerbit);

    if (!$dataPenerbit) {
        echo "Data penerbit tidak ditemukan.";
        exit;
    }

    require_once __DIR__ . "/../VIEW/ADMIN/editPenerbit.php";
    exit;
}

// UPDATE PENERBIT
elseif ($aksi === 'update') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: ../VIEW/ADMIN/daftarPenerbit.php");
        exit;
    }

    $idPenerbit   = (int) ($_POST['id_penerbit'] ?? 0);
    $namaPenerbit = trim($_POST['nama_penerbit'] ?? '');
    $alamat       = trim($_POST['alamat'] ?? '');
    $kota         = trim($_POST['kota'] ?? '');
    $telepon      = trim($_POST['telepon'] ?? '');
    $email        = trim($_POST['email'] ?? '');

    if ($idPenerbit <= 0 || $namaPenerbit === '') {
        echo "Data penerbit tidak valid.";
        exit;
    }

    $penerbitModel->id_penerbit   = $idPenerbit;
    $penerbitModel->nama_penerbit = $namaPenerbit;
    $penerbitModel->alamat        = $alamat;
    $penerbitModel->kota          = $kota;
    $penerbitModel->telepon       = $telepon;
    $penerbitModel->email         = $email;

    if ($penerbitModel->update()) {
        header("Location: ../VIEW/ADMIN/daftarPenerbit.php");
        exit;
    } else {
        echo "Gagal memperbarui penerbit.";
        exit;
    }
}

// HAPUS PENERBIT
elseif ($aksi === 'hapus') {
    $idPenerbit = (int) ($_GET['id_penerbit'] ?? 0);

    if ($idPenerbit <= 0) {
        header("Location: ../VIEW/ADMIN/daftarPenerbit.php");
        exit;
    }

    if ($penerbitModel->delete($idPenerbit)) {
        header("Location: ../VIEW/ADMIN/daftarPenerbit.php");
        exit;
    } else {
        echo "
            <h3>Penerbit tidak dapat dihapus.</h3>
            <p>Penerbit mungkin masih digunakan oleh salah satu buku.</p>
            <a href='../VIEW/ADMIN/daftarPenerbit.php'>Kembali</a>
        ";
        exit;
    }
}

// AKSI TIDAK DIKENAL
else {
    header("Location: ../VIEW/ADMIN/daftarPenerbit.php");
    exit;
}
?>