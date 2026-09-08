<?php
require_once __DIR__ . "/../MODEL/m_buku.php";
require_once __DIR__ . "/../MODEL/m_buku_penulis.php";

$bukuModel = new Buku();
$bukuPenulisModel = new BukuPenulis();

$aksi = $_GET['aksi'] ?? '';

// TAMBAH BUKU
if ($aksi === 'tambah') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: ../VIEW/ADMIN/tambahBuku.php");
        exit;
    }

    $bukuModel->id_kategori  = (int) ($_POST['id_kategori'] ?? 0);
    $bukuModel->id_penerbit  = (int) ($_POST['id_penerbit'] ?? 0);
    $bukuModel->judul_buku   = trim($_POST['judul_buku'] ?? '');
    $bukuModel->tahun_terbit = (int) ($_POST['tahun_terbit'] ?? 0);
    $bukuModel->harga_buku   = (int) ($_POST['harga_buku'] ?? 0);
    $bukuModel->stok         = (int) ($_POST['stok'] ?? 0);

    // COVER
    $namaCover = '';
    if (isset($_FILES['cover']) && $_FILES['cover']['error'] === UPLOAD_ERR_OK) {
        $namaFile  = $_FILES['cover']['name'];
        $tmpFile   = $_FILES['cover']['tmp_name'];
        $extension = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));
        $allowed   = ['jpg', 'jpeg', 'png', 'webp'];

        if (in_array($extension, $allowed)) {
            $namaCover   = uniqid('cover_', true) . '.' . $extension;
            $folderCover = __DIR__ . "/../ASSETS/COVER/";

            if (!is_dir($folderCover)) {
                mkdir($folderCover, 0777, true);
            }

            move_uploaded_file($tmpFile, $folderCover . $namaCover);
        }
    }

    $bukuModel->cover = $namaCover;

    // INSERT BUKU
    if ($bukuModel->insert()) {
        $idBuku = $bukuModel->id_buku;
        $penulis = $_POST['id_penulis'] ?? [];

        if (!is_array($penulis)) {
            $penulis = [$penulis];
        }

        foreach ($penulis as $idPenulis) {
            if ((int) $idPenulis > 0) {
                $bukuPenulisModel->id_buku = $idBuku;
                $bukuPenulisModel->id_penulis = (int) $idPenulis;
                $bukuPenulisModel->insert();
            }
        }

        header("Location: ../VIEW/ADMIN/daftarBuku.php");
        exit;
    } else {
        echo "Gagal menambahkan buku.";
        exit;
    }
}

// EDIT BUKU
elseif ($aksi === 'edit') {
    $idBuku = (int) ($_GET['id_buku'] ?? 0);

    if ($idBuku <= 0) {
        header("Location: ../VIEW/ADMIN/daftarBuku.php");
        exit;
    }

    $dataBuku = $bukuModel->getBukuById($idBuku);

    if (!$dataBuku) {
        echo "Data buku tidak ditemukan.";
        exit;
    }

    $dataPenulis = $bukuPenulisModel->getPenulisByBuku($idBuku);

    require_once __DIR__ . "/../VIEW/ADMIN/editBuku.php";
    exit;
}

// UPDATE BUKU
elseif ($aksi === 'update') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: ../VIEW/ADMIN/daftarBuku.php");
        exit;
    }

    $idBuku = (int) ($_POST['id_buku'] ?? 0);

    if ($idBuku <= 0) {
        header("Location: ../VIEW/ADMIN/daftarBuku.php");
        exit;
    }

    $dataLama = $bukuModel->getBukuById($idBuku);

    if (!$dataLama) {
        echo "Data buku tidak ditemukan.";
        exit;
    }

    $bukuModel->id_buku      = $idBuku;
    $bukuModel->id_kategori  = (int) ($_POST['id_kategori'] ?? 0);
    $bukuModel->id_penerbit  = (int) ($_POST['id_penerbit'] ?? 0);
    $bukuModel->judul_buku   = trim($_POST['judul_buku'] ?? '');
    $bukuModel->tahun_terbit = (int) ($_POST['tahun_terbit'] ?? 0);
    $bukuModel->harga_buku   = (int) ($_POST['harga_buku'] ?? 0);
    $bukuModel->stok         = (int) ($_POST['stok'] ?? 0);

    // COVER
    $namaCover = $dataLama['cover'];

    if (isset($_FILES['cover']) && $_FILES['cover']['error'] === UPLOAD_ERR_OK) {
        $namaFile  = $_FILES['cover']['name'];
        $tmpFile   = $_FILES['cover']['tmp_name'];
        $extension = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));
        $allowed   = ['jpg', 'jpeg', 'png', 'webp'];

        if (in_array($extension, $allowed)) {
            $namaCoverBaru = uniqid('cover_', true) . '.' . $extension;
            $folderCover   = __DIR__ . "/../ASSETS/COVER/";

            if (!is_dir($folderCover)) {
                mkdir($folderCover, 0777, true);
            }

            if (!empty($dataLama['cover']) && file_exists($folderCover . $dataLama['cover'])) {
                unlink($folderCover . $dataLama['cover']);
            }

            move_uploaded_file($tmpFile, $folderCover . $namaCoverBaru);
            $namaCover = $namaCoverBaru;
        }
    }

    $bukuModel->cover = $namaCover;

    // UPDATE DATA BUKU
    if ($bukuModel->update()) {
        $bukuPenulisModel->deleteByBuku($idBuku);

        $penulis = $_POST['id_penulis'] ?? [];
        if (!is_array($penulis)) {
            $penulis = [$penulis];
        }

        foreach ($penulis as $idPenulis) {
            if ((int) $idPenulis > 0) {
                $bukuPenulisModel->id_buku = $idBuku;
                $bukuPenulisModel->id_penulis = (int) $idPenulis;
                $bukuPenulisModel->insert();
            }
        }

        header("Location: ../VIEW/ADMIN/daftarBuku.php");
        exit;
    } else {
        echo "Gagal memperbarui data buku.";
        exit;
    }
}

// HAPUS BUKU
elseif ($aksi === 'hapus') {
    $idBuku = (int) ($_GET['id_buku'] ?? 0);

    if ($idBuku <= 0) {
        header("Location: ../VIEW/ADMIN/daftarBuku.php");
        exit;
    }

    $dataBuku = $bukuModel->getBukuById($idBuku);

    if (!$dataBuku) {
        header("Location: ../VIEW/ADMIN/daftarBuku.php");
        exit;
    }

    $bukuPenulisModel->deleteByBuku($idBuku);

    if ($bukuModel->delete($idBuku)) {
        if (!empty($dataBuku['cover'])) {
            $fileCover = __DIR__ . "/../ASSETS/COVER/" . $dataBuku['cover'];
            if (file_exists($fileCover)) {
                unlink($fileCover);
            }
        }
    }

    header("Location: ../VIEW/ADMIN/daftarBuku.php");
    exit;
}

// AKSI TIDAK DITEMUKAN
else {
    header("Location: ../VIEW/ADMIN/daftarBuku.php");
    exit;
}
?>