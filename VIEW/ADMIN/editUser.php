<?php
require_once __DIR__ . "/../../MODEL/m_users.php";

// Pastikan data user dikirim dari controller
if (!isset($dataUser)) {
    die("Data user tidak ditemukan.");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit User - Perpustakaan Online</title>
</head>
<body>

  <h2>Edit Data User</h2>

<form action="/Aplikasi Peminjaman Buku/CONTROLLER/c_user.php?aksi=update" method="POST">
    <input type="hidden" name="id_user" value="<?= htmlspecialchars($dataUser['id_user']) ?>">

    <div>
      <label>NIS</label>
      <input type="text" name="nis" value="<?= htmlspecialchars($dataUser['nis']) ?>">
    </div>

    <div>
      <label>Nama Lengkap</label>
      <input type="text" name="nama_lengkap" value="<?= htmlspecialchars($dataUser['nama_lengkap']) ?>" required>
    </div>

    <div>
      <label>Username</label>
      <input type="text" name="username" value="<?= htmlspecialchars($dataUser['username']) ?>" required>
    </div>

    <div>
      <label>Role</label>
      <select name="role" required>
        <option value="admin" <?= ($dataUser['role'] === 'admin') ? 'selected' : '' ?>>Admin</option>
        <option value="siswa" <?= ($dataUser['role'] === 'siswa') ? 'selected' : '' ?>>Siswa</option>
      </select>
    </div>

    <br>
    <button type="submit">Simpan Perubahan</button>
    <a href="daftarUser.php">Batal</a>
  </form>

  <br><br>

  <!-- Form khusus untuk update password -->
  <h3>Ganti Password</h3>
  <form action="../../CONTROLLER/c_user.php?aksi=updatePassword" method="POST">
    <input type="hidden" name="id_user" value="<?= htmlspecialchars($dataUser['id_user']) ?>">

    <div>
      <label>Password Baru</label>
      <input type="password" name="password" required>
    </div>

    <br>
    <button type="submit">Update Password</button>
  </form>

</body>
</html>
