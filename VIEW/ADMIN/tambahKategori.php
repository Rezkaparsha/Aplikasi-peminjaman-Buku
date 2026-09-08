<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kategori</title>
</head>
<body>

<h2>Tambah Kategori</h2>

<form action="../../CONTROLLER/c_kategori.php?aksi=tambah" method="POST">

    <div>
        <label for="nama_kategori">Nama Kategori</label><br>
        <input type="text" id="nama_kategori" name="nama_kategori" required>
    </div>

    <br>

    <div>
        <label for="deskripsi">Deskripsi</label><br>
        <textarea id="deskripsi" name="deskripsi" rows="4"></textarea>
    </div>

    <br>

    <div>
        <label for="status">Status</label><br>
        <select id="status" name="status" required>
            <option value="aktif">Aktif</option>
            <option value="nonaktif">Nonaktif</option>
        </select>
    </div>

    <br>

    <button type="submit">Simpan Kategori</button>
    <a href="daftarKategori.php">Batal</a>

</form>

</body>
</html>