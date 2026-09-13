<?php

session_start();

require_once __DIR__ . "/../MODEL/m_buku.php";
require_once __DIR__ . "/../MODEL/m_buku_penulis.php";
require_once __DIR__ . "/../MODEL/m_kategori.php";
require_once __DIR__ . "/../MODEL/m_penerbit.php";
require_once __DIR__ . "/../MODEL/m_penulis.php";

$bukuModel = new Buku();
$bukuPenulisModel = new BukuPenulis();

$aksi = $_GET['aksi'] ?? '';



// TAMBAH BUKU

if ($aksi === 'tambah') {

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: ../VIEW/ADMIN/tambahBuku.php");
        exit;
    }

    $bukuModel->id_kategori =
        (int) ($_POST['id_kategori'] ?? 0);

    $bukuModel->id_penerbit =
        (int) ($_POST['id_penerbit'] ?? 0);

    $bukuModel->judul_buku =
        trim($_POST['judul_buku'] ?? '');

    $bukuModel->tahun_terbit =
        (int) ($_POST['tahun_terbit'] ?? 0);

    $bukuModel->harga_buku =
        (int) ($_POST['harga_buku'] ?? 0);

    $bukuModel->stok =
        (int) ($_POST['stok'] ?? 0);


    // -----------------------------
    // VALIDASI
    // -----------------------------

    if (
        $bukuModel->id_kategori <= 0 ||
        $bukuModel->id_penerbit <= 0 ||
        $bukuModel->judul_buku === '' ||
        $bukuModel->tahun_terbit <= 0 ||
        $bukuModel->stok < 0
    ) {
        $_SESSION['error'] =
            "Data buku belum lengkap.";

        header("Location: ../VIEW/ADMIN/tambahBuku.php");
        exit;
    }


    // -----------------------------
    // UPLOAD COVER
    // -----------------------------

    $namaCover = '';

    if (
        isset($_FILES['cover']) &&
        $_FILES['cover']['error'] === UPLOAD_ERR_OK
    ) {

        $namaFile =
            $_FILES['cover']['name'];

        $tmpFile =
            $_FILES['cover']['tmp_name'];

        $extension =
            strtolower(
                pathinfo(
                    $namaFile,
                    PATHINFO_EXTENSION
                )
            );

        $allowed = [
            'jpg',
            'jpeg',
            'png',
            'webp'
        ];

        if (!in_array($extension, $allowed)) {

            $_SESSION['error'] =
                "Format cover tidak diperbolehkan.";

            header(
                "Location: ../VIEW/ADMIN/tambahBuku.php"
            );

            exit;
        }

        $namaCover =
            uniqid('cover_', true)
            . '.'
            . $extension;

        $folderCover =
            __DIR__
            . "/../ASSETS/COVER/";

        if (!is_dir($folderCover)) {
            mkdir(
                $folderCover,
                0777,
                true
            );
        }

        move_uploaded_file(
            $tmpFile,
            $folderCover . $namaCover
        );
    }

    $bukuModel->cover = $namaCover;


    // -----------------------------
    // INSERT
    // -----------------------------

    if ($bukuModel->insert()) {

        $idBuku =
            $bukuModel->id_buku;

        $penulis =
            $_POST['id_penulis'] ?? [];

        if (!is_array($penulis)) {
            $penulis = [$penulis];
        }

        foreach ($penulis as $idPenulis) {

            $idPenulis =
                (int) $idPenulis;

            if ($idPenulis > 0) {

                $bukuPenulisModel->id_buku =
                    $idBuku;

                $bukuPenulisModel->id_penulis =
                    $idPenulis;

                $bukuPenulisModel->insert();
            }
        }

        header(
            "Location: ../VIEW/ADMIN/daftarBuku.php"
        );

        exit;
    } else {

        $_SESSION['error'] =
            "Gagal menambahkan buku.";

        header(
            "Location: ../VIEW/ADMIN/tambahBuku.php"
        );

        exit;
    }
}



// EDIT BUKU

elseif ($aksi === 'edit') {

    $idBuku =
        (int) ($_GET['id_buku'] ?? 0);

    if ($idBuku <= 0) {

        header(
            "Location: ../VIEW/ADMIN/daftarBuku.php"
        );

        exit;
    }

    $dataBuku =
        $bukuModel->getBukuById($idBuku);

    if (!$dataBuku) {

        $_SESSION['error'] =
            "Data buku tidak ditemukan.";

        header(
            "Location: ../VIEW/ADMIN/daftarBuku.php"
        );

        exit;
    }

    $dataPenulis =
        $bukuPenulisModel
        ->getPenulisByBuku($idBuku);

    $kategoriModel =
        new Kategori();

    $penerbitModel =
        new Penerbit();

    $penulisModel =
        new Penulis();

    $dataKategori =
        $kategoriModel->getAll();

    $dataPenerbit =
        $penerbitModel->getAll();

    $dataSemuaPenulis =
        $penulisModel->getAll();

    require_once
        __DIR__
        . "/../VIEW/ADMIN/editBuku.php";

    exit;
}



// UPDATE BUKU

elseif ($aksi === 'update') {

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

        header(
            "Location: ../VIEW/ADMIN/daftarBuku.php"
        );

        exit;
    }

    $idBuku =
        (int) ($_POST['id_buku'] ?? 0);

    if ($idBuku <= 0) {

        header(
            "Location: ../VIEW/ADMIN/daftarBuku.php"
        );

        exit;
    }

    $dataLama =
        $bukuModel->getBukuById($idBuku);

    if (!$dataLama) {

        $_SESSION['error'] =
            "Data buku tidak ditemukan.";

        header(
            "Location: ../VIEW/ADMIN/daftarBuku.php"
        );

        exit;
    }


    $bukuModel->id_buku =
        $idBuku;

    $bukuModel->id_kategori =
        (int) ($_POST['id_kategori'] ?? 0);

    $bukuModel->id_penerbit =
        (int) ($_POST['id_penerbit'] ?? 0);

    $bukuModel->judul_buku =
        trim($_POST['judul_buku'] ?? '');

    $bukuModel->tahun_terbit =
        (int) ($_POST['tahun_terbit'] ?? 0);

    $bukuModel->harga_buku =
        (int) ($_POST['harga_buku'] ?? 0);

    $bukuModel->stok =
        (int) ($_POST['stok'] ?? 0);


    // -----------------------------
    // COVER LAMA
    // -----------------------------

    $namaCover =
        $dataLama['cover'];


    // -----------------------------
    // COVER BARU
    // -----------------------------

    if (
        isset($_FILES['cover']) &&
        $_FILES['cover']['error'] === UPLOAD_ERR_OK
    ) {

        $namaFile =
            $_FILES['cover']['name'];

        $tmpFile =
            $_FILES['cover']['tmp_name'];

        $extension =
            strtolower(
                pathinfo(
                    $namaFile,
                    PATHINFO_EXTENSION
                )
            );

        $allowed = [
            'jpg',
            'jpeg',
            'png',
            'webp'
        ];

        if (!in_array($extension, $allowed)) {

            $_SESSION['error'] =
                "Format cover tidak diperbolehkan.";

            header(
                "Location: ../CONTROLLER/c_buku.php?aksi=edit&id_buku="
                    . $idBuku
            );

            exit;
        }

        $folderCover =
            __DIR__
            . "/../ASSETS/COVER/";

        if (!is_dir($folderCover)) {
            mkdir(
                $folderCover,
                0777,
                true
            );
        }

        $namaCoverBaru =
            uniqid('cover_', true)
            . '.'
            . $extension;


        if (
            !empty($dataLama['cover']) &&
            file_exists(
                $folderCover
                    . $dataLama['cover']
            )
        ) {

            unlink(
                $folderCover
                    . $dataLama['cover']
            );
        }


        move_uploaded_file(
            $tmpFile,
            $folderCover
                . $namaCoverBaru
        );

        $namaCover =
            $namaCoverBaru;
    }


    $bukuModel->cover =
        $namaCover;


    // -----------------------------
    // UPDATE
    // -----------------------------

    if ($bukuModel->update()) {

        // Hapus relasi penulis lama
        $bukuPenulisModel
            ->deleteByBuku($idBuku);


        // Ambil penulis baru
        $penulis =
            $_POST['id_penulis'] ?? [];

        if (!is_array($penulis)) {
            $penulis = [$penulis];
        }


        foreach ($penulis as $idPenulis) {

            $idPenulis =
                (int) $idPenulis;

            if ($idPenulis > 0) {

                $bukuPenulisModel->id_buku =
                    $idBuku;

                $bukuPenulisModel->id_penulis =
                    $idPenulis;

                $bukuPenulisModel->insert();
            }
        }


        $_SESSION['success'] =
            "Data buku berhasil diperbarui.";

        header(
            "Location: ../VIEW/ADMIN/daftarBuku.php"
        );

        exit;
    } else {

        $_SESSION['error'] =
            "Gagal memperbarui data buku.";

        header(
            "Location: ../CONTROLLER/c_buku.php?aksi=edit&id_buku="
                . $idBuku
        );

        exit;
    }
}



// HAPUS BUKU

elseif ($aksi === 'hapus') {

    $idBuku =
        (int) ($_GET['id_buku'] ?? 0);

    if ($idBuku <= 0) {

        header(
            "Location: ../VIEW/ADMIN/daftarBuku.php"
        );

        exit;
    }


    $dataBuku =
        $bukuModel->getBukuById($idBuku);

    if (!$dataBuku) {

        header(
            "Location: ../VIEW/ADMIN/daftarBuku.php"
        );

        exit;
    }


    // Hapus relasi penulis terlebih dahulu
    $bukuPenulisModel
        ->deleteByBuku($idBuku);


    if ($bukuModel->delete($idBuku)) {

        if (!empty($dataBuku['cover'])) {

            $fileCover =
                __DIR__
                . "/../ASSETS/COVER/"
                . $dataBuku['cover'];

            if (file_exists($fileCover)) {
                unlink($fileCover);
            }
        }

        $_SESSION['success'] =
            "Buku berhasil dihapus.";
    } else {

        $_SESSION['error'] =
            "Buku tidak dapat dihapus.";
    }


    header(
        "Location: ../VIEW/ADMIN/daftarBuku.php"
    );

    exit;
}



// PINJAM BUKU

elseif ($aksi === 'pinjamBuku') {

    if (!isset($_SESSION['id_user'])) {

        header(
            "Location: ../VIEW/login.php"
        );

        exit;
    }


    $idBuku =
        (int) ($_GET['id_buku'] ?? 0);

    if ($idBuku <= 0) {

        $_SESSION['error'] =
            "ID buku tidak valid.";

        header(
            "Location: ../VIEW/SISWA/daftarBuku.php"
        );

        exit;
    }


    $dataBuku =
        $bukuModel->getBukuById($idBuku);

    if (!$dataBuku) {

        $_SESSION['error'] =
            "Buku tidak ditemukan.";

        header(
            "Location: ../VIEW/SISWA/daftarBuku.php"
        );

        exit;
    }


    if ((int)$dataBuku['stok'] <= 0) {

        $_SESSION['error'] =
            "Stok buku sedang habis.";

        header(
            "Location: ../VIEW/SISWA/daftarBuku.php"
        );

        exit;
    }


    require_once
        __DIR__
        . "/../VIEW/SISWA/form_PinjamBuku.php";

    exit;
}



// DEFAULT

else {

    header(
        "Location: ../VIEW/ADMIN/daftarBuku.php"
    );

    exit;
}
