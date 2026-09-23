<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Proteksi Akses Siswa
if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'siswa') {
    $kembali = $_SESSION['last_page_admin'] ?? '/Aplikasi Peminjaman Buku/CONTROLLER/c_peminjaman.php?aksi=dashboard_admin';
    header("Location: " . $kembali);
    exit;
}

// Simpan URL lokasi controller aktif saat ini
$_SESSION['last_page_siswa'] = $_SERVER['REQUEST_URI'];

if (!isset($_SESSION['id_user'])) {

    header(
        "Location: ../login.php"
    );

    exit;
}

require_once
    __DIR__
    . "/../../MODEL/m_buku.php";

$bukuModel =
    new Buku();

$idBuku =
    (int)($_GET['id_buku'] ?? 0);

if ($idBuku <= 0) {

    header(
        "Location: daftarBuku.php"
    );

    exit;
}

$dataBuku =
    $bukuModel->getBukuById($idBuku);

if (!$dataBuku) {

    $_SESSION['error'] =
        "Buku tidak ditemukan.";

    header(
        "Location: daftarBuku.php"
    );

    exit;
}

if ((int)$dataBuku['stok'] <= 0) {

    $_SESSION['error'] =
        "Stok buku sedang habis.";

    header(
        "Location: daftarBuku.php"
    );

    exit;
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Pinjam Buku</title>

    <style>
        * {
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        /* 1. Kunci ukuran Layar Utama agar tidak bisa di-scroll ke mana pun */
        html,
        body {
            height: 100vh;
            width: 100vw;
            margin: 0;
            padding: 0;
            overflow: hidden;
            /* Mencegah scrollbar utama muncul */
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f6;
        }

        body {
            display: flex;
        }

        /* 2. Main Content mengisi sisa area layar tanpa melebihi batas */
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            height: 100vh;
            overflow: hidden;
            /* Mengunci konten utama agar tidak keluar layar */
        }

        /* 3. Container utama dibuat responsif dan hanya konten di dalamnya yang di-scroll jika panjang */
        .container {
            padding: 25px;
            flex: 1;
            overflow-y: auto;
            /* Hanya scroll ke bawah jika isi tabel panjang */
            overflow-x: hidden;
            /* Hilangkan scroll samping kanan-kiri */
        }

        /* 4. Card Container */
        .card {
            background: #ffffff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
            width: 100%;
        }

        /* 5. Mencegah Tabel Memaksa Layar Melebar ke Kanan */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            /* Scroll horizontal hanya aktif di dalam area tabel saja jika terpaksa */
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            min-width: 100%;
            /* UBAH min-width: 800px/900px menjadi 100% agar pas dengan card */
        }

        body {
            margin: 0;
            padding: 30px;
            background: #f4f6f8;
        }

        .container {
            max-width: 650px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
        }

        h2 {
            margin-top: 0;
        }

        .buku {
            display: flex;
            gap: 20px;
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 1px solid #ddd;
        }

        .cover {
            width: 120px;
            height: 160px;
            object-fit: cover;
            border-radius: 6px;
        }

        .info {
            flex: 1;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 7px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        .buttons {
            display: flex;
            gap: 10px;
        }

        .btn {
            border: none;
            padding: 10px 15px;
            border-radius: 6px;
            text-decoration: none;
            cursor: pointer;
        }

        .btn-submit {
            background: #2ecc71;
            color: white;
        }

        .btn-cancel {
            background: #7f8c8d;
            color: white;
        }
    </style>

</head>

<body>

    <div class="container">

        <h2>Form Peminjaman Buku</h2>


        <div class="buku">

            <?php if (!empty($dataBuku['cover'])): ?>

                <img
                    src="../../ASSETS/COVER/<?= htmlspecialchars($dataBuku['cover']) ?>"
                    class="cover"
                    alt="Cover Buku">

            <?php endif; ?>


            <div class="info">

                <h3>
                    <?= htmlspecialchars(
                        $dataBuku['judul_buku']
                    ) ?>
                </h3>

                <p>
                    Tahun:
                    <?= htmlspecialchars(
                        $dataBuku['tahun_terbit']
                    ) ?>
                </p>

                <p>
                    Harga:
                    Rp
                    <?= number_format(
                        (int)$dataBuku['harga_buku'],
                        0,
                        ',',
                        '.'
                    ) ?>
                </p>

                <p>
                    Stok:
                    <strong>
                        <?= htmlspecialchars(
                            $dataBuku['stok']
                        ) ?>
                    </strong>
                </p>

            </div>

        </div>


        <form
            action="/Aplikasi Peminjaman Buku/CONTROLLER/c_peminjaman.php?aksi=tambah"
            method="POST">

            <input
                type="hidden"
                name="id_buku[]"
                value="<?= $dataBuku['id_buku'] ?>">


            <div class="form-group">

                <label for="jumlah">
                    Jumlah Buku
                </label>

                <input
                    type="number"
                    id="jumlah"
                    name="jumlah[]"
                    min="1"
                    max="<?= $dataBuku['stok'] ?>"
                    value="1"
                    required>

            </div>


            <div class="buttons">

                <a
                    href="daftarBuku.php"
                    class="btn btn-cancel">
                    Batal
                </a>


                <button
                    type="submit"
                    class="btn btn-submit">
                    Ajukan Peminjaman
                </button>

            </div>

        </form>

    </div>

</body>

</html>