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
?>

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