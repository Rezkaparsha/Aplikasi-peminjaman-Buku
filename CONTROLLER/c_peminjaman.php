=<?php

require_once __DIR__ . "/../MODEL/m_peminjaman.php";

$peminjamanModel = new Peminjaman();
$aksi = $_GET['aksi'] ?? '';

if ($aksi === 'daftar') {
    $dataPeminjaman = $peminjamanModel->getAll();

    require_once __DIR__ . "/../VIEW/ADMIN/daftarPeminjaman.php";
    exit;
}

if ($aksi === 'detail') {
    $idPeminjaman = (int)($_GET['id_peminjaman'] ?? 0);

    if ($idPeminjaman <= 0) {
        header("Location: ../VIEW/ADMIN/daftarPeminjaman.php");
        exit;
    }

    $dataPeminjaman = $peminjamanModel->getById($idPeminjaman);
    $dataDetail = $peminjamanModel->getDetail($idPeminjaman);

    if (!$dataPeminjaman) {
        echo "Data peminjaman tidak ditemukan.";
        exit;
    }

    require_once __DIR__ . "/../VIEW/ADMIN/detailPeminjaman.php";
    exit;
}

if ($aksi === 'ajukan') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: ../VIEW/SISWA/daftarBuku.php");
        exit;
    }

    $idUser = (int)($_POST['id_user'] ?? 0);
    $idBuku = (int)($_POST['id_buku'] ?? 0);
    $tanggalPengembalian = $_POST['tanggal_pengembalian'] ?? '';

    if (
        $idUser <= 0 ||
        $idBuku <= 0 ||
        $tanggalPengembalian === ''
    ) {
        echo "Data peminjaman tidak lengkap.";
        exit;
    }

    $tanggalPinjam = date('Y-m-d');

    $idPeminjaman = $peminjamanModel->insertPeminjaman(
        $idUser,
        $tanggalPinjam
    );

    if (!$idPeminjaman) {
        echo "Gagal membuat peminjaman.";
        exit;
    }

    $hasilDetail = $peminjamanModel->insertDetail(
        $idPeminjaman,
        $idBuku,
        $tanggalPengembalian
    );

    if (!$hasilDetail) {
        echo "Gagal menyimpan detail peminjaman.";
        exit;
    }

    header("Location: ../VIEW/SISWA/peminjamanSaya.php");
    exit;
}

if ($aksi === 'setujui') {
    $idPeminjaman = (int)($_GET['id_peminjaman'] ?? 0);

    if ($idPeminjaman <= 0) {
        header("Location: ../VIEW/ADMIN/daftarPeminjaman.php");
        exit;
    }

    $dataPeminjaman = $peminjamanModel->getById($idPeminjaman);

    if (!$dataPeminjaman) {
        echo "Data peminjaman tidak ditemukan.";
        exit;
    }

    if ($dataPeminjaman['status'] !== 'Diajukan') {
        echo "Peminjaman ini sudah diproses.";
        exit;
    }

    if ($peminjamanModel->updateStatus($idPeminjaman, 'Dipinjam')) {
        header("Location: ../VIEW/ADMIN/daftarPeminjaman.php");
        exit;
    }

    echo "Gagal menyetujui peminjaman.";
    exit;
}

if ($aksi === 'tolak') {
    $idPeminjaman = (int)($_GET['id_peminjaman'] ?? 0);

    if ($idPeminjaman <= 0) {
        header("Location: ../VIEW/ADMIN/daftarPeminjaman.php");
        exit;
    }

    $dataPeminjaman = $peminjamanModel->getById($idPeminjaman);

    if (!$dataPeminjaman) {
        echo "Data peminjaman tidak ditemukan.";
        exit;
    }

    if ($dataPeminjaman['status'] !== 'Diajukan') {
        echo "Peminjaman ini sudah diproses.";
        exit;
    }

    if ($peminjamanModel->updateStatus($idPeminjaman, 'Ditolak')) {
        header("Location: ../VIEW/ADMIN/daftarPeminjaman.php");
        exit;
    }

    echo "Gagal menolak peminjaman.";
    exit;
}

header("Location: ../VIEW/ADMIN/daftarPeminjaman.php");
exit;