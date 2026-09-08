<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Penerbit</title>
</head>
<body>

    <h2>Tambah Penerbit</h2>

    <form action="../../CONTROLLER/c_penerbit.php?aksi=tambah" method="POST">
        <div>
            <label for="nama_penerbit">Nama Penerbit</label><br>
            <input type="text" id="nama_penerbit" name="nama_penerbit" required>
        </div>
        <br>
        <div>
            <label for="alamat">Alamat</label><br>
            <textarea id="alamat" name="alamat" rows="3"></textarea>
        </div>
        <br>
        <div>
            <label for="kota">Kota</label><br>
            <input type="text" id="kota" name="kota">
        </div>
        <br>
        <div>
            <label for="telepon">Telepon</label><br>
            <input type="text" id="telepon" name="telepon">
        </div>
        <br>
        <div>
            <label for="email">Email</label><br>
            <input type="email" id="email" name="email">
        </div>
        <br>

        <button type="submit">Simpan Penerbit</button>
        <a href="daftarPenerbit.php">Batal</a>

    </form>

</body>
</html>