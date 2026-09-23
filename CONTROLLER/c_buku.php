<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../MODEL/m_buku.php";
require_once __DIR__ . "/../MODEL/m_buku_penulis.php";
require_once __DIR__ . "/../MODEL/m_kategori.php";
require_once __DIR__ . "/../MODEL/m_penerbit.php";
require_once __DIR__ . "/../MODEL/m_penulis.php";

$bukuModel = new Buku();
$bukuPenulisModel = new BukuPenulis();

$aksi = $_GET['aksi'] ?? 'index';

// -----------------------------
// TAMPILAN UTAMA (INDEX ADMIN)
// -----------------------------
if ($aksi === 'index') {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        header("Location: /Aplikasi Peminjaman Buku/CONTROLLER/c_peminjaman.php?aksi=dashboard_siswa");
        exit;
    }

    $allBuku = $bukuModel->getAllBuku();
    require_once __DIR__ . "/../VIEW/ADMIN/daftarBuku.php";
    exit;
}

// -----------------------------
// TAMPIL FORM TAMBAH BUKU (ADMIN)
// -----------------------------
elseif ($aksi === 'tambah_view') {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        header("Location: /Aplikasi Peminjaman Buku/CONTROLLER/c_peminjaman.php?aksi=dashboard_siswa");
        exit;
    }

    $kategoriModel = new Kategori();
    $penerbitModel = new Penerbit();
    $penulisModel = new Penulis();

    $dataKategori = $kategoriModel->getAll();
    $dataPenerbit = $penerbitModel->getAll();
    $dataSemuaPenulis = $penulisModel->getAll();

    require_once __DIR__ . "/../VIEW/ADMIN/tambahBuku.php";
    exit;
}

// -----------------------------
// PROSES TAMBAH BUKU
// -----------------------------
elseif ($aksi === 'tambah') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: /Aplikasi Peminjaman Buku/CONTROLLER/c_buku.php?aksi=tambah_view");
        exit;
    }

    $bukuModel->id_kategori = (int) ($_POST['id_kategori'] ?? 0);
    $bukuModel->id_penerbit = (int) ($_POST['id_penerbit'] ?? 0);
    $bukuModel->judul_buku = trim($_POST['judul_buku'] ?? '');
    $bukuModel->tahun_terbit = (int) ($_POST['tahun_terbit'] ?? 0);
    $bukuModel->harga_buku = (int) ($_POST['harga_buku'] ?? 0);
    $bukuModel->stok = (int) ($_POST['stok'] ?? 0);

    // Validasi Input
    if (
        $bukuModel->id_kategori <= 0 ||
        $bukuModel->id_penerbit <= 0 ||
        $bukuModel->judul_buku === '' ||
        $bukuModel->tahun_terbit <= 0 ||
        $bukuModel->stok < 0
    ) {
        $_SESSION['error'] = "Data buku belum lengkap.";
        header("Location: /Aplikasi Peminjaman Buku/CONTROLLER/c_buku.php?aksi=tambah_view");
        exit;
    }

    // Upload Cover
    $namaCover = '';
    if (isset($_FILES['cover']) && $_FILES['cover']['error'] === UPLOAD_ERR_OK) {
        $namaFile = $_FILES['cover']['name'];
        $tmpFile = $_FILES['cover']['tmp_name'];
        $extension = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array($extension, $allowed)) {
            $_SESSION['error'] = "Format cover tidak diperbolehkan.";
            header("Location: /Aplikasi Peminjaman Buku/CONTROLLER/c_buku.php?aksi=tambah_view");
            exit;
        }

        $namaCover = uniqid('cover_', true) . '.' . $extension;
        $folderCover = __DIR__ . "/../ASSETS/COVER/";

        if (!is_dir($folderCover)) {
            mkdir($folderCover, 0777, true);
        }

        move_uploaded_file($tmpFile, $folderCover . $namaCover);
    }

    $bukuModel->cover = $namaCover;

    if ($bukuModel->insert()) {
        $idBuku = $bukuModel->id_buku;
        $penulis = $_POST['id_penulis'] ?? [];

        if (!is_array($penulis)) {
            $penulis = [$penulis];
        }

        foreach ($penulis as $idPenulis) {
            $idPenulis = (int) $idPenulis;
            if ($idPenulis > 0) {
                $bukuPenulisModel->id_buku = $idBuku;
                $bukuPenulisModel->id_penulis = $idPenulis;
                $bukuPenulisModel->insert();
            }
        }

        $_SESSION['success'] = "Buku berhasil ditambahkan.";
        header("Location: /Aplikasi Peminjaman Buku/CONTROLLER/c_buku.php?aksi=index");
        exit;
    } else {
        $_SESSION['error'] = "Gagal menambahkan buku.";
        header("Location: /Aplikasi Peminjaman Buku/CONTROLLER/c_buku.php?aksi=tambah_view");
        exit;
    }
}

// -----------------------------
// TAMPIL FORM EDIT BUKU
// -----------------------------
elseif ($aksi === 'edit') {
    $idBuku = (int) ($_GET['id_buku'] ?? 0);

    if ($idBuku <= 0) {
        header("Location: /Aplikasi Peminjaman Buku/CONTROLLER/c_buku.php?aksi=index");
        exit;
    }

    $dataBuku = $bukuModel->getBukuById($idBuku);

    if (!$dataBuku) {
        $_SESSION['error'] = "Data buku tidak ditemukan.";
        header("Location: /Aplikasi Peminjaman Buku/CONTROLLER/c_buku.php?aksi=index");
        exit;
    }

    $dataPenulis = $bukuPenulisModel->getPenulisByBuku($idBuku);
    $kategoriModel = new Kategori();
    $penerbitModel = new Penerbit();
    $penulisModel = new Penulis();

    $dataKategori = $kategoriModel->getAll();
    $dataPenerbit = $penerbitModel->getAll();
    $dataSemuaPenulis = $penulisModel->getAll();

    require_once __DIR__ . "/../VIEW/ADMIN/editBuku.php";
    exit;
}

// -----------------------------
// PROSES UPDATE BUKU
// -----------------------------
elseif ($aksi === 'update') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: /Aplikasi Peminjaman Buku/CONTROLLER/c_buku.php?aksi=index");
        exit;
    }

    $idBuku = (int) ($_POST['id_buku'] ?? 0);

    if ($idBuku <= 0) {
        header("Location: /Aplikasi Peminjaman Buku/CONTROLLER/c_buku.php?aksi=index");
        exit;
    }

    $dataLama = $bukuModel->getBukuById($idBuku);

    if (!$dataLama) {
        $_SESSION['error'] = "Data buku tidak ditemukan.";
        header("Location: /Aplikasi Peminjaman Buku/CONTROLLER/c_buku.php?aksi=index");
        exit;
    }

    $bukuModel->id_buku = $idBuku;
    $bukuModel->id_kategori = (int) ($_POST['id_kategori'] ?? 0);
    $bukuModel->id_penerbit = (int) ($_POST['id_penerbit'] ?? 0);
    $bukuModel->judul_buku = trim($_POST['judul_buku'] ?? '');
    $bukuModel->tahun_terbit = (int) ($_POST['tahun_terbit'] ?? 0);
    $bukuModel->harga_buku = (int) ($_POST['harga_buku'] ?? 0);
    $bukuModel->stok = (int) ($_POST['stok'] ?? 0);

    $namaCover = $dataLama['cover'];

    if (isset($_FILES['cover']) && $_FILES['cover']['error'] === UPLOAD_ERR_OK) {
        $namaFile = $_FILES['cover']['name'];
        $tmpFile = $_FILES['cover']['tmp_name'];
        $extension = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array($extension, $allowed)) {
            $_SESSION['error'] = "Format cover tidak diperbolehkan.";
            header("Location: /Aplikasi Peminjaman Buku/CONTROLLER/c_buku.php?aksi=edit&id_buku=" . $idBuku);
            exit;
        }

        $folderCover = __DIR__ . "/../ASSETS/COVER/";

        if (!is_dir($folderCover)) {
            mkdir($folderCover, 0777, true);
        }

        $namaCoverBaru = uniqid('cover_', true) . '.' . $extension;

        if (!empty($dataLama['cover']) && file_exists($folderCover . $dataLama['cover'])) {
            unlink($folderCover . $dataLama['cover']);
        }

        move_uploaded_file($tmpFile, $folderCover . $namaCoverBaru);
        $namaCover = $namaCoverBaru;
    }

    $bukuModel->cover = $namaCover;

    if ($bukuModel->update()) {
        $bukuPenulisModel->deleteByBuku($idBuku);
        $penulis = $_POST['id_penulis'] ?? [];

        if (!is_array($penulis)) {
            $penulis = [$penulis];
        }

        foreach ($penulis as $idPenulis) {
            $idPenulis = (int) $idPenulis;
            if ($idPenulis > 0) {
                $bukuPenulisModel->id_buku = $idBuku;
                $bukuPenulisModel->id_penulis = $idPenulis;
                $bukuPenulisModel->insert();
            }
        }

        $_SESSION['success'] = "Data buku berhasil diperbarui.";
        header("Location: /Aplikasi Peminjaman Buku/CONTROLLER/c_buku.php?aksi=index");
        exit;
    } else {
        $_SESSION['error'] = "Gagal memperbarui data buku.";
        header("Location: /Aplikasi Peminjaman Buku/CONTROLLER/c_buku.php?aksi=edit&id_buku=" . $idBuku);
        exit;
    }
}

// -----------------------------
// PROSES HAPUS BUKU
// -----------------------------
elseif ($aksi === 'hapus') {
    $idBuku = (int) ($_GET['id_buku'] ?? 0);

    if ($idBuku <= 0) {
        header("Location: /Aplikasi Peminjaman Buku/VIEW/ADMIN/daftarBuku.php");
        exit;
    }

    $dataBuku = $bukuModel->getBukuById($idBuku);

    if (!$dataBuku) {
        $_SESSION['error'] = "Data buku tidak ditemukan.";
        header("Location: /Aplikasi Peminjaman Buku/VIEW/ADMIN/daftarBuku.php");
        exit;
    }

    try {
        // Hapus relasi penulis terlebih dahulu
        $bukuPenulisModel->deleteByBuku($idBuku);

        // Coba hapus buku
        if ($bukuModel->delete($idBuku)) {
            if (!empty($dataBuku['cover'])) {
                $fileCover = __DIR__ . "/../ASSETS/COVER/" . $dataBuku['cover'];
                if (file_exists($fileCover)) {
                    unlink($fileCover);
                }
            }
            $_SESSION['success'] = "Buku berhasil dihapus.";
        } else {
            $_SESSION['error'] = "Gagal menghapus data buku.";
        }
    } catch (mysqli_sql_exception $e) {
        // Tangkap Error Foreign Key Constraint (Code 1451)
        if ($e->getCode() === 1451) {
            $_SESSION['error'] = "Buku tidak dapat dihapus karena sedang dipinjam atau memiliki riwayat transaksi peminjaman!";
        } else {
            $_SESSION['error'] = "Gagal menghapus buku: " . $e->getMessage();
        }
    }

    header("Location: /Aplikasi Peminjaman Buku/VIEW/ADMIN/daftarBuku.php");
    exit;
}

// -----------------------------
// DEFAULT REDIRECT
// -----------------------------
else {
    header("Location: /Aplikasi Peminjaman Buku/CONTROLLER/c_buku.php?aksi=index");
    exit;
}