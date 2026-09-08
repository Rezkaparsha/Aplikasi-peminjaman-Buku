<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah User - Perpustakaan Online</title>
</head>
<body>

  <h2>Tambah User Baru</h2>

  <form action="/Aplikasi Peminjaman Buku/CONTROLLER/c_user.php?aksi=tambah" method="POST">
    <div>
      <label>NIS</label>
      <input type="text" name="nis" required>
    </div>

    <div>
      <label>Nama Lengkap</label>
      <input type="text" name="nama_lengkap" required>
    </div>

    <div>
      <label>Username</label>
      <input type="text" name="username" required>
    </div>

    <div>
      <label>Password</label>
      <input type="password" name="password" required>
    </div>

    <div>
      <label>Role</label>
      <select name="role" required>
        <option value="">-- Pilih Role --</option>
        <option value="admin">Admin</option>
        <option value="siswa">Siswa</option>
      </select>
    </div>

    <br>
    <button type="submit">Simpan</button>
    <a href="daftarUser.php">Batal</a>
  </form>

</body>
</html>
