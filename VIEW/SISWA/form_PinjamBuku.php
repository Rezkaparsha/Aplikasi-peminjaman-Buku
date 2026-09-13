<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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