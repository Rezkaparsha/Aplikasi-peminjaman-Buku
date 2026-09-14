<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Jika bukan admin, tendang kembali ke halaman terakhirnya
if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'admin') {
    // Cek apakah ada histori halaman sebelumnya. Jika ada, kembalikan ke sana. Jika tidak, lempar ke halaman siswa.
    $kembali = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/Aplikasi Peminjaman Buku/VIEW/SISWA/daftarBuku.php';
    header("Location: " . $kembali);
    exit;
}

require_once __DIR__ . "/../../MODEL/m_penerbit.php";
require_once __DIR__ . "/../../MODEL/m_kategori.php";

$penerbitModel = new Penerbit();
$kategoriModel = new Kategori();

$daftarPenerbit = $penerbitModel->getAll();
$daftarKategori = $kategoriModel->getAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Buku - Perpustakaan Online</title>
    <style>
        /* Reset & Base Styles */
        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        body {
            background-color: #f4f7f6;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        /* Container Setup */
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        }

        /* Typography */
        h2 {
            color: #2c3e50;
            margin-top: 0;
            margin-bottom: 25px;
            text-align: center;
            border-bottom: 2px solid #ecf0f1;
            padding-bottom: 10px;
        }

        /* Form Layout */
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #34495e;
            font-size: 14px;
        }

        /* Inputs & Selects */
        .form-control {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ccd1d1;
            border-radius: 5px;
            font-size: 14px;
            color: #2c3e50;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #3498db;
            outline: none;
            box-shadow: 0 0 5px rgba(52, 152, 219, 0.3);
        }

        /* Checkbox Group */
        .checkbox-group {
            background-color: #f8f9fa;
            padding: 12px;
            border: 1px solid #ccd1d1;
            border-radius: 5px;
            max-height: 150px;
            overflow-y: auto;
        }
        .checkbox-item {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
            font-weight: 400;
            cursor: pointer;
        }
        .checkbox-item:last-child {
            margin-bottom: 0;
        }
        .checkbox-item input {
            margin-right: 10px;
            cursor: pointer;
        }

        /* Image Display */
        .current-cover {
            display: block;
            margin-top: 10px;
            border-radius: 6px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            object-fit: cover;
        }

        /* Helper Text */
        .help-text {
            display: block;
            margin-top: 5px;
            font-size: 12px;
            color: #7f8c8d;
        }

        /* Buttons */
        .action-group {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }

        .btn {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 5px;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .btn-submit {
            background-color: #3498db;
            color: white;
        }
        .btn-submit:hover { background-color: #2980b9; }
        
        .btn-cancel {
            background-color: #e74c3c;
            color: white;
        }
        .btn-cancel:hover { background-color: #c0392b; }

        /* Error Message */
        .alert-error {
            background-color: #fdeaea;
            color: #c0392b;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
            margin-bottom: 15px;
        }

    </style>
</head>
<body>

    <div class="container">
        <h2>Edit Data Buku</h2>

        <?php if (!empty($dataBuku)): ?>
            <form action="/Aplikasi Peminjaman Buku/CONTROLLER/c_buku.php?aksi=update" method="POST" enctype="multipart/form-data">
                
                <input type="hidden" name="id_buku" value="<?= htmlspecialchars($dataBuku['id_buku']) ?>">

                <div class="form-group">
                    <label for="judul_buku">Judul Buku</label>
                    <input type="text" id="judul_buku" name="judul_buku" class="form-control" value="<?= htmlspecialchars($dataBuku['judul_buku']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="id_penerbit">Penerbit</label>
                    <select id="id_penerbit" name="id_penerbit" class="form-control" required>
                        <option value="">-- Pilih Penerbit --</option>
                        <?php foreach ($daftarPenerbit as $penerbit): ?>
                            <option value="<?= $penerbit['id_penerbit'] ?>" <?= ($dataBuku['id_penerbit'] == $penerbit['id_penerbit']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($penerbit['nama_penerbit']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="id_kategori">Kategori</label>
                    <select id="id_kategori" name="id_kategori" class="form-control" required>
                        <option value="">-- Pilih Kategori --</option>
                        <?php foreach ($daftarKategori as $kategori): ?>
                            <option value="<?= $kategori['id_kategori'] ?>" <?= ($dataBuku['id_kategori'] == $kategori['id_kategori']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($kategori['nama_kategori']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Penulis</label>
                    <div class="checkbox-group">
                        <?php if (!empty($dataPenulis)): ?>
                            <?php foreach ($dataPenulis as $penulis): ?>
                                <label class="checkbox-item">
                                    <input type="checkbox" name="id_penulis[]" value="<?= $penulis['id_penulis'] ?>" checked>
                                    <?= htmlspecialchars($penulis['nama_penulis']) ?>
                                </label>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <span style="font-size: 13px; color: #7f8c8d;">Buku belum memiliki penulis.</span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="tahun_terbit">Tahun Terbit</label>
                    <input type="number" id="tahun_terbit" name="tahun_terbit" class="form-control" value="<?= htmlspecialchars($dataBuku['tahun_terbit']) ?>" min="1900" max="<?= date('Y') ?>" required>
                </div>

                <div class="form-group">
                    <label for="harga_buku">Harga Buku</label>
                    <input type="number" id="harga_buku" name="harga_buku" class="form-control" value="<?= htmlspecialchars($dataBuku['harga_buku']) ?>" min="0" required>
                </div>

                <div class="form-group">
                    <label for="stok">Stok</label>
                    <input type="number" id="stok" name="stok" class="form-control" value="<?= htmlspecialchars($dataBuku['stok']) ?>" min="0" required>
                </div>

                <div class="form-group">
                    <label>Cover Saat Ini</label>
                    <?php if (!empty($dataBuku['cover'])): ?>
                        <img src="../../ASSETS/COVER/<?= htmlspecialchars($dataBuku['cover']) ?>" alt="Cover Buku" width="120" class="current-cover">
                    <?php else: ?>
                        <span class="help-text">Belum ada cover.</span>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="cover">Ganti Cover</label>
                    <input type="file" id="cover" name="cover" class="form-control" accept=".jpg,.jpeg,.png,.webp" style="padding: 7px 12px;">
                    <span class="help-text">*Kosongkan jika tidak ingin mengganti cover.</span>
                </div>

                <div class="action-group">
                    <a href="../VIEW/ADMIN/daftarBuku.php" class="btn btn-cancel">Batal</a>
                    <button type="submit" class="btn btn-submit">Simpan Perubahan</button>
                </div>

            </form>
        <?php else: ?>
            <div class="alert-error">Data buku tidak ditemukan.</div>
            <div style="text-align: center; margin-top: 20px;">
                <a href="daftarBuku.php" class="btn btn-cancel" style="display: inline-block; padding: 10px 20px;">Kembali ke Daftar Buku</a>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>