<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Penulis</title>
</head>
<body>

    <h2>Edit Penulis</h2>

    <?php if (!empty($dataPenulis)): ?>
        <form action="/Aplikasi Peminjaman Buku/CONTROLLER/c_penulis.php?aksi=update" method="POST">
            
            <input type="hidden" name="id_penulis" value="<?= htmlspecialchars($dataPenulis['id_penulis']) ?>">

            <div>
                <label for="nama_penulis">Nama Penulis</label><br>
                <input type="text" id="nama_penulis" name="nama_penulis" value="<?= htmlspecialchars($dataPenulis['nama_penulis']) ?>" required>
            </div>
            <br>

            <button type="submit">Simpan Perubahan</button>
            <a href="daftarPenulis.php">Batal</a>

        </form>
    <?php else: ?>
        <p>Data penulis tidak ditemukan.</p>
        <a href="daftarPenulis.php">Kembali</a>
    <?php endif; ?>

</body>
</html>