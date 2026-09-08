<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Tambah Penulis</title>

</head>

<body>

    <h2>Tambah Penulis</h2>

    <form
        action="../../CONTROLLER/c_penulis.php?aksi=tambah"
        method="POST">

        <label for="nama_penulis">
            Nama Penulis
        </label>

        <br>

        <input
            type="text"
            id="nama_penulis"
            name="nama_penulis"
            placeholder="Masukkan nama penulis"
            required>

        <br><br>

        <button type="submit">
            Simpan
        </button>

        <a href="daftarPenulis.php">
            Batal
        </a>

    </form>

</body>

</html>