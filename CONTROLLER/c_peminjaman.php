<?php

session_start();

require_once __DIR__ . "/../MODEL/m_koneksi.php";
require_once __DIR__ . "/../MODEL/m_peminjaman.php";

$db = new Koneksi();
$koneksi = $db->getKoneksi();
$model = new M_Peminjaman();

$aksi = $_GET['aksi'] ?? '';


// CEK LOGIN

if (!isset($_SESSION['id_user'])) {
    header("Location: /Aplikasi Peminjaman Buku/VIEW/AUTH/login.php");
    exit;
}


// ROUTING DASHBOARD SISWA & KATALOG (AKSES SISWA)

if ($aksi === 'dashboard_siswa') {
    require_once __DIR__ . "/../VIEW/SISWA/dashboardSiswa.php";
    exit;
}

if ($aksi === 'katalog') {
    require_once __DIR__ . "/../VIEW/SISWA/daftarBuku.php";
    exit;
}

if ($aksi === 'peminjaman_siswa') {
    require_once __DIR__ . "/../VIEW/SISWA/peminjaman.php";
    exit;
}


// TAMBAH PEMINJAMAN - SISWA

if ($aksi === 'tambah') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: /Aplikasi Peminjaman Buku/CONTROLLER/c_peminjaman.php?aksi=katalog");
        exit;
    }

    $id_user = (int)$_SESSION['id_user'];
    $id_buku = $_POST['id_buku'] ?? [];
    $jumlah = $_POST['jumlah'] ?? [];
    $daftarBuku = [];

    if (is_array($id_buku) && is_array($jumlah)) {
        foreach ($id_buku as $index => $id) {
            if (!isset($jumlah[$index])) {
                continue;
            }
            $daftarBuku[] = [
                'id_buku' => (int)$id,
                'jumlah' => (int)$jumlah[$index]
            ];
        }
    }

    $hasil = $model->tambahPeminjaman($id_user, $daftarBuku);

    if ($hasil['status']) {
        $_SESSION['success'] = $hasil['pesan'];
        header("Location: /Aplikasi Peminjaman Buku/CONTROLLER/c_peminjaman.php?aksi=detail&id_peminjaman=" . $hasil['id_peminjaman']);
        exit;
    }

    $_SESSION['error'] = $hasil['pesan'];
    header("Location: /Aplikasi Peminjaman Buku/CONTROLLER/c_peminjaman.php?aksi=katalog");
    exit;
}


// DETAIL SISWA

if ($aksi === 'detail') {
    $id_peminjaman = (int)($_GET['id_peminjaman'] ?? 0);

    if ($id_peminjaman <= 0) {
        header("Location: /Aplikasi Peminjaman Buku/CONTROLLER/c_peminjaman.php?aksi=peminjaman_siswa");
        exit;
    }

    $id_user = (int)$_SESSION['id_user'];

    if (!$model->cekKepemilikanPeminjaman($id_peminjaman, $id_user)) {
        $_SESSION['error'] = 'Anda tidak memiliki akses ke peminjaman ini.';
        header("Location: /Aplikasi Peminjaman Buku/CONTROLLER/c_peminjaman.php?aksi=peminjaman_siswa");
        exit;
    }

    $dataPeminjaman = $model->getPeminjamanById($id_peminjaman);
    $detailPeminjaman = $model->getDetailPeminjaman($id_peminjaman);

    require_once __DIR__ . "/../VIEW/SISWA/detailPeminjaman.php";
    exit;
}


// SETELAH INI KHUSUS ADMIN

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: /Aplikasi Peminjaman Buku/CONTROLLER/c_peminjaman.php?aksi=dashboard_siswa");
    exit;
}


// ROUTING DASHBOARD ADMIN

if ($aksi === 'dashboard_admin') {
    require_once __DIR__ . "/../VIEW/ADMIN/dashboardAdmin.php";
    exit;
}


// DAFTAR PEMINJAMAN ADMIN

if ($aksi === 'admin') {
    $dataPeminjaman = $model->getSemuaPeminjaman();
    require_once __DIR__ . "/../VIEW/ADMIN/daftarPeminjaman.php";
    exit;
}


// DETAIL PEMINJAMAN ADMIN

if ($aksi === 'detailAdmin') {
    $id_peminjaman = (int)($_GET['id_peminjaman'] ?? 0);

    if ($id_peminjaman <= 0) {
        header("Location: /Aplikasi Peminjaman Buku/CONTROLLER/c_peminjaman.php?aksi=admin");
        exit;
    }

    $dataPeminjaman = $model->getPeminjamanById($id_peminjaman);
    $detailPeminjaman = $model->getDetailPeminjaman($id_peminjaman);

    if (!$dataPeminjaman) {
        $_SESSION['error'] = 'Data peminjaman tidak ditemukan.';
        header("Location: /Aplikasi Peminjaman Buku/CONTROLLER/c_peminjaman.php?aksi=admin");
        exit;
    }

    require_once __DIR__ . "/../VIEW/ADMIN/detailPeminjaman.php";
    exit;
}


// SETUJUI

if ($aksi === 'setujui') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: /Aplikasi Peminjaman Buku/CONTROLLER/c_peminjaman.php?aksi=admin");
        exit;
    }

    $id_peminjaman = (int)($_POST['id_peminjaman'] ?? 0);
    $tanggal_pengembalian = $_POST['tanggal_pengembalian'] ?? '';

    $hasil = $model->setujuiPeminjaman($id_peminjaman, $tanggal_pengembalian);

    if ($hasil['status']) {
        $_SESSION['success'] = $hasil['pesan'];
    } else {
        $_SESSION['error'] = $hasil['pesan'];
    }

    header("Location: /Aplikasi Peminjaman Buku/CONTROLLER/c_peminjaman.php?aksi=detailAdmin&id_peminjaman=" . $id_peminjaman);
    exit;
}


// TOLAK
elseif ($aksi === 'tolak') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: /Aplikasi Peminjaman Buku/CONTROLLER/c_peminjaman.php?aksi=admin");
        exit;
    }

    $id_peminjaman = (int)($_POST['id_peminjaman'] ?? 0);
    $alasan_penolakan = trim($_POST['alasan_penolakan'] ?? '');

    $hasil = $model->tolakPeminjaman($id_peminjaman, $alasan_penolakan);

    if ($hasil['status']) {
        $_SESSION['success'] = $hasil['pesan'];
    } else {
        $_SESSION['error'] = $hasil['pesan'];
    }

    header("Location: /Aplikasi Peminjaman Buku/CONTROLLER/c_peminjaman.php?aksi=detailAdmin&id_peminjaman=" . $id_peminjaman);
    exit;
}


// PROSES PENGEMBALIAN BUKU
elseif ($aksi === 'prosesKembali') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: /Aplikasi Peminjaman Buku/CONTROLLER/c_peminjaman.php?aksi=admin");
        exit;
    }

    $id_peminjaman = isset($_POST['id_peminjaman']) ? (int)$_POST['id_peminjaman'] : 0;
    
    // Ambil array kondisi dan denda dari form yang baru
    $kondisi_buku = $_POST['kondisi'] ?? [];
    $denda_tambahan = $_POST['denda_tambahan'] ?? [];

    $hasil = $model->kembalikanBuku($id_peminjaman, $kondisi_buku, $denda_tambahan);

    if ($hasil['status']) {
        $_SESSION['success'] = $hasil['pesan'];
    } else {
        $_SESSION['error'] = $hasil['pesan'];
    }

    header("Location: /Aplikasi Peminjaman Buku/CONTROLLER/c_peminjaman.php?aksi=detailAdmin&id_peminjaman=" . $id_peminjaman);
    exit;
}


// HISTORI TRANSAKSI ADMIN

elseif ($aksi === 'histori') {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        header("Location: /Aplikasi Peminjaman Buku/VIEW/AUTH/login.php");
        exit;
    }

    $dataHistori = $model->getHistoriTransaksi();
    require_once __DIR__ . "/../VIEW/ADMIN/historiTransaksi.php";
    exit;
}


// DEFAULT REDIRECT SEBAGAI ADMIN

header("Location: /Aplikasi Peminjaman Buku/CONTROLLER/c_peminjaman.php?aksi=dashboard_admin");
exit;
