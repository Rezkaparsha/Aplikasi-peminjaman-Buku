<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Penerbit</title>
</head>
<body>

    <h2>Edit Penerbit</h2>

    <?php if (!empty($dataPenerbit)): ?>
        <form action="/Aplikasi Peminjaman Buku/CONTROLLER/c_penerbit.php?aksi=update" method="POST">
            
            <input type="hidden" name="id_penerbit" value="<?= htmlspecialchars($dataPenerbit['id_penerbit']) ?>">
            
            <div>
                <label for="nama_penerbit">Nama Penerbit</label><br>
                <input type="text" id="nama_penerbit" name="nama_penerbit" value="<?= htmlspecialchars($dataPenerbit['nama_penerbit']) ?>" required>
            </div>
            <br>

            <div>
                <label for="alamat">Alamat</label><br>
                <textarea id="alamat" name="alamat" rows="3"><?= htmlspecialchars($dataPenerbit['alamat'] ?? '') ?></textarea>
            </div>
            <br>

            <div>
                <label for="kota">Kota</label><br>
                <input type="text" id="kota" name="kota" value="<?= htmlspecialchars($dataPenerbit['kota'] ?? '') ?>">
            </div>
            <br>

            <div>
                <label for="telepon">Telepon</label><br>
                <input type="text" id="telepon" name="telepon" value="<?= htmlspecialchars($dataPenerbit['telepon'] ?? '') ?>">
            </div>
            <br>

            <div>
                <label for="email">Email</label><br>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($dataPenerbit['email'] ?? '') ?>">
            </div>
            <br>

            <button type="submit">Simpan Perubahan</button>
            <a href="daftarPenerbit.php">Batal</a>

        </form>
    <?php else: ?>
        <p>Data penerbit tidak ditemukan.</p>
        <a href="daftarPenerbit.php">Kembali</a>
    <?php endif; ?>

</body>
</html>