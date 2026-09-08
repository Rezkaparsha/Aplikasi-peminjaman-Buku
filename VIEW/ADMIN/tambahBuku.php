<?php
require_once __DIR__ . "/../../MODEL/m_kategori.php";
require_once __DIR__ . "/../../MODEL/m_penerbit.php";
require_once __DIR__ . "/../../MODEL/m_penulis.php";

$kategoriModel = new Kategori();
$penerbitModel = new Penerbit();
$penulisModel = new Penulis();

$dataKategori = $kategoriModel->getAll();
$dataPenerbit = $penerbitModel->getAll();
$dataPenulis = $penulisModel->getAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Buku</title>
</head>
<body>

    <h2>Tambah Buku</h2>

    <form action="../../CONTROLLER/c_buku.php?aksi=tambah" method="POST" enctype="multipart/form-data">

        <div>
            <label for="judul_buku">Judul Buku</label><br>
            <input type="text" id="judul_buku" name="judul_buku" required>
        </div>
        <br>

        <div>
            <label for="tahun_terbit">Tahun Terbit</label><br>
            <input type="number" id="tahun_terbit" name="tahun_terbit" min="1000" max="9999" required>
        </div>
        <br>

        <div>
            <label for="harga_buku">Harga Buku</label><br>
            <input type="number" id="harga_buku" name="harga_buku" min="0" required>
        </div>
        <br>

        <div>
            <label for="stok">Stok</label><br>
            <input type="number" id="stok" name="stok" min="0" required>
        </div>
        <br>

        <div>
            <label for="id_penerbit">Penerbit</label><br>
            <select id="id_penerbit" name="id_penerbit" required>
                <option value="">-- Pilih Penerbit --</option>
                <?php foreach ($dataPenerbit as $penerbit): ?>
                    <option value="<?= $penerbit['id_penerbit'] ?>">
                        <?= htmlspecialchars($penerbit['nama_penerbit']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <br>

        <div>
            <label for="id_kategori">Kategori</label><br>
            <select id="id_kategori" name="id_kategori" required>
                <option value="">-- Pilih Kategori --</option>
                <?php foreach ($dataKategori as $kategori): ?>
                    <option value="<?= $kategori['id_kategori'] ?>">
                        <?= htmlspecialchars($kategori['nama_kategori']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <br>

        <div>
            <label for="id_penulis">Penulis</label><br>
            <select id="id_penulis" name="id_penulis[]" multiple required size="5">
                <?php foreach ($dataPenulis as $penulis): ?>
                    <option value="<?= $penulis['id_penulis'] ?>">
                        <?= htmlspecialchars($penulis['nama_penulis']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <p><small>Gunakan Ctrl + klik untuk memilih lebih dari satu penulis.</small></p>
        </div>
        <br>

        <div>
            <label for="cover">Cover Buku</label><br>
            <input type="file" id="cover" name="cover" accept=".jpg,.jpeg,.png,.webp">
            <p><small>Maksimal 2 MB.</small></p>
        </div>
        <br>

        <button type="submit">Simpan Buku</button>
        <a href="daftarBuku.php">Batal</a>

    </form>

</body>
</html>