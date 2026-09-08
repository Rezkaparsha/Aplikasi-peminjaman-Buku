<?php
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
    <title>Edit Buku</title>
</head>
<body>

    <h2>Edit Buku</h2>

    <?php if (!empty($dataBuku)): ?>
        <form action="/Aplikasi Peminjaman Buku/CONTROLLER/c_buku.php?aksi=update" method="POST" enctype="multipart/form-data">
            
            <input type="hidden" name="id_buku" value="<?= htmlspecialchars($dataBuku['id_buku']) ?>">

            <div>
                <label for="judul_buku">Judul Buku</label><br>
                <input type="text" id="judul_buku" name="judul_buku" value="<?= htmlspecialchars($dataBuku['judul_buku']) ?>" required>
            </div>
            <br>

            <div>
                <label for="id_penerbit">Penerbit</label><br>
                <select id="id_penerbit" name="id_penerbit" required>
                    <option value="">-- Pilih Penerbit --</option>
                    <?php foreach ($daftarPenerbit as $penerbit): ?>
                        <option value="<?= $penerbit['id_penerbit'] ?>" <?= ($dataBuku['id_penerbit'] == $penerbit['id_penerbit']) ? 'selected' : '' ?>>
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
                    <?php foreach ($daftarKategori as $kategori): ?>
                        <option value="<?= $kategori['id_kategori'] ?>" <?= ($dataBuku['id_kategori'] == $kategori['id_kategori']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($kategori['nama_kategori']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <br>

            <div>
                <label>Penulis</label><br>
                <?php if (!empty($dataPenulis)): ?>
                    <?php foreach ($dataPenulis as $penulis): ?>
                        <label>
                            <input type="checkbox" name="id_penulis[]" value="<?= $penulis['id_penulis'] ?>" checked>
                            <?= htmlspecialchars($penulis['nama_penulis']) ?>
                        </label><br>
                    <?php endforeach; ?>
                <?php else: ?>
                    <span>Buku belum memiliki penulis.</span>
                <?php endif; ?>
            </div>
            <br>

            <div>
                <label for="tahun_terbit">Tahun Terbit</label><br>
                <input type="number" id="tahun_terbit" name="tahun_terbit" value="<?= htmlspecialchars($dataBuku['tahun_terbit']) ?>" min="1900" max="<?= date('Y') ?>" required>
            </div>
            <br>

            <div>
                <label for="harga_buku">Harga Buku</label><br>
                <input type="number" id="harga_buku" name="harga_buku" value="<?= htmlspecialchars($dataBuku['harga_buku']) ?>" min="0" required>
            </div>
            <br>

            <div>
                <label for="stok">Stok</label><br>
                <input type="number" id="stok" name="stok" value="<?= htmlspecialchars($dataBuku['stok']) ?>" min="0" required>
            </div>
            <br>

            <div>
                <label>Cover Saat Ini</label><br>
                <?php if (!empty($dataBuku['cover'])): ?>
                    <img src="../../ASSETS/COVER/<?= htmlspecialchars($dataBuku['cover']) ?>" alt="Cover Buku" width="120">
                <?php else: ?>
                    <span>Belum ada cover.</span>
                <?php endif; ?>
            </div>
            <br>

            <div>
                <label for="cover">Ganti Cover</label><br>
                <input type="file" id="cover" name="cover" accept=".jpg,.jpeg,.png,.webp"><br>
                <small>Kosongkan jika tidak ingin mengganti cover.</small>
            </div>
            <br>

            <button type="submit">Simpan Perubahan</button>
            <a href="daftarBuku.php">Batal</a>

        </form>
    <?php else: ?>
        <p>Data buku tidak ditemukan.</p>
        <a href="daftarBuku.php">Kembali ke Daftar Buku</a>
    <?php endif; ?>

</body>
</html>