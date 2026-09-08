<?php

session_start();

require_once __DIR__ . "/../MODEL/m_koneksi.php";
require_once __DIR__ . "/../MODEL/m_peminjaman.php";

// CEK LOGIN
if (!isset($_SESSION['id_user'])) {
    header("Location: ../VIEW/login.php");
    exit;
}

// BUAT KONEKSI DATABASE
$db = new koneksi();
$koneksi = $db->getkoneksi();

// BUAT OBJECT MODEL PEMINJAMAN
$model = new M_Peminjaman($koneksi);

// AMBIL AKSI
$aksi = $_GET['aksi'] ?? '';

// SISWA
// 1. SISWA MENGAJUKAN PEMINJAMAN
if ($aksi === 'tambah') {

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: ../VIEW/SISWA/daftarBuku.php");
        exit;
    }

    $id_user = $_SESSION['id_user'];
    $id_buku = $_POST['id_buku'] ?? [];
    $jumlah = $_POST['jumlah'] ?? [];
    $daftarBuku = [];

    // BENTUK DATA BUKU
    if (is_array($id_buku) && is_array($jumlah)) {
        foreach ($id_buku as $index => $id) {
            if (!isset($jumlah[$index])) {
                continue;
            }

            $daftarBuku[] = [
                'id_buku' => (int) $id,
                'jumlah' => (int) $jumlah[$index]
            ];
        }
    }

    // SIMPAN PEMINJAMAN
    $hasil = $model->tambahPeminjaman(
        $id_user,
        $daftarBuku
    );

    // HASIL
    if ($hasil['status']) {
        header(
            "Location: ../VIEW/SISWA/detailPeminjaman.php?id_peminjaman="
            . $hasil['id_peminjaman']
        );
        exit;
    } else {
        $_SESSION['error'] = $hasil['pesan'];
        header(
            "Location: ../VIEW/SISWA/daftarBuku.php"
        );
        exit;
    }
}

// 2. LIHAT DETAIL PEMINJAMAN SISWA
if ($aksi === 'detail') {

    $id_peminjaman = isset($_GET['id_peminjaman'])
        ? (int) $_GET['id_peminjaman']
        : 0;

    if ($id_peminjaman <= 0) {
        header("Location: ../VIEW/SISWA/peminjaman.php");
        exit;
    }

    $id_user = $_SESSION['id_user'];

    // Pastikan peminjaman milik siswa
    if (
        !$model->cekKepemilikanPeminjaman(
            $id_peminjaman,
            $id_user
        )
    ) {
        $_SESSION['error'] =
            'Anda tidak memiliki akses ke peminjaman ini.';

        header(
            "Location: ../VIEW/SISWA/peminjaman.php"
        );
        exit;
    }

    $dataPeminjaman =
        $model->getPeminjamanById(
            $id_peminjaman
        );

    $detailPeminjaman =
        $model->getDetailPeminjaman(
            $id_peminjaman
        );

    require_once __DIR__ .
        "/../VIEW/SISWA/detailPeminjaman.php";

    exit;
}

// CEK ROLE ADMIN
if (
    !isset($_SESSION['role']) ||
    $_SESSION['role'] !== 'admin'
) {
    header("Location: ../VIEW/SISWA/peminjaman.php");
    exit;
}

// 3. ADMIN MELIHAT SEMUA PEMINJAMAN
if ($aksi === 'admin') {

    $dataPeminjaman =
        $model->getSemuaPeminjaman();

    require_once __DIR__ .
        "/../VIEW/ADMIN/daftarPeminjaman.php";

    exit;
}

// 4. ADMIN MELIHAT DETAIL
if ($aksi === 'detailAdmin') {

    $id_peminjaman = isset($_GET['id_peminjaman'])
        ? (int) $_GET['id_peminjaman']
        : 0;

    if ($id_peminjaman <= 0) {
        header(
            "Location: ../VIEW/ADMIN/daftarPeminjaman.php"
        );
        exit;
    }

    $dataPeminjaman =
        $model->getPeminjamanById(
            $id_peminjaman
        );

    $detailPeminjaman =
        $model->getDetailPeminjaman(
            $id_peminjaman
        );

    if (!$dataPeminjaman) {
        $_SESSION['error'] =
            'Data peminjaman tidak ditemukan.';

        header(
            "Location: ../VIEW/ADMIN/daftarPeminjaman.php"
        );
        exit;
    }

    require_once __DIR__ .
        "/../VIEW/ADMIN/detailPeminjaman.php";

    exit;
}

// 5. ADMIN MENYETUJUI PEMINJAMAN
if ($aksi === 'setujui') {

    $id_peminjaman = isset($_POST['id_peminjaman'])
        ? (int) $_POST['id_peminjaman']
        : 0;

    $tanggal_pengembalian =
        $_POST['tanggal_pengembalian'] ?? '';

    if ($id_peminjaman <= 0) {
        $_SESSION['error'] =
            'ID peminjaman tidak valid.';

        header(
            "Location: ../VIEW/ADMIN/daftarPeminjaman.php"
        );
        exit;
    }

    $hasil =
        $model->setujuiPeminjaman(
            $id_peminjaman,
            $tanggal_pengembalian
        );

    if ($hasil['status']) {
        $_SESSION['success'] =
            $hasil['pesan'];
    } else {
        $_SESSION['error'] =
            $hasil['pesan'];
    }

    header(
        "Location: ../VIEW/ADMIN/detailPeminjaman.php?id_peminjaman="
        . $id_peminjaman
    );

    exit;
}

// 6. ADMIN MENOLAK PEMINJAMAN
if ($aksi === 'tolak') {

    $id_peminjaman = isset($_POST['id_peminjaman'])
        ? (int) $_POST['id_peminjaman']
        : 0;

    if ($id_peminjaman <= 0) {
        $_SESSION['error'] =
            'ID peminjaman tidak valid.';

        header(
            "Location: ../VIEW/ADMIN/daftarPeminjaman.php"
        );
        exit;
    }

    $hasil =
        $model->tolakPeminjaman(
            $id_peminjaman
        );

    if ($hasil['status']) {
        $_SESSION['success'] =
            $hasil['pesan'];
    } else {
        $_SESSION['error'] =
            $hasil['pesan'];
    }

    header(
        "Location: ../VIEW/ADMIN/detailPeminjaman.php?id_peminjaman="
        . $id_peminjaman
    );

    exit;
}

// AKSI TIDAK DITEMUKAN
header(
    "Location: ../VIEW/SISWA/peminjaman.php"
);

exit;