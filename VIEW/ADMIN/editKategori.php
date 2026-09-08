<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kategori</title>
</head>

<body>

    <h2>Edit Kategori</h2>

    <?php if (!empty($dataKategori)): ?>

        <form action="/Aplikasi Peminjaman Buku/CONTROLLER/c_kategori.php?aksi=update" method="POST">

            <input
                type="hidden"
                name="id_kategori"
                value="<?= htmlspecialchars($dataKategori['id_kategori']) ?>">

            <div>
                <label for="nama_kategori">Nama Kategori</label><br>
                <input
                    type="text"
                    id="nama_kategori"
                    name="nama_kategori"
                    value="<?= htmlspecialchars($dataKategori['nama_kategori']) ?>"
                    required>
            </div>

            <br>

            <div>
                <label for="deskripsi">Deskripsi</label><br>
                <textarea id="deskripsi" name="deskripsi" rows="4"><?= htmlspecialchars($dataKategori['deskripsi'] ?? '') ?></textarea>
            </div>

            <br>

            <div>
                <label for="status">Status</label><br>
                <select id="status" name="status" required>
                    <option
                        value="aktif"
                        <?= $dataKategori['status'] === 'aktif' ? 'selected' : '' ?>>
                        Aktif
                    </option>

                    <option
                        value="nonaktif"
                        <?= $dataKategori['status'] === 'nonaktif' ? 'selected' : '' ?>>
                        Nonaktif
                    </option>
                </select>
            </div>

            <br>

            <button type="submit">Simpan Perubahan</button>
            <a href="daftarKategori.php">Batal</a>

        </form>

    <?php else: ?>

        <p>Data kategori tidak ditemukan.</p>
        <a href="daftarKategori.php">Kembali</a>

    <?php endif; ?>

</body>

</html>