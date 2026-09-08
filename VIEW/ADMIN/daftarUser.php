<?php
require_once __DIR__ . "/../../MODEL/m_users.php";

$userModel   = new Users();
$daftarUser  = $userModel->getAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Daftar User - Perpustakaan Online</title>
</head>
<body>

  <h2>Kelola Data User</h2>
  <p>Daftar lengkap semua user yang terdaftar di sistem.</p>

  <a href="tambahUser.php">+ Tambah User</a>
  <br><br>

  <table border="1" cellspacing="0" cellpadding="8">
    <thead>
      <tr>
        <th>No</th>
        <th>NIS</th>
        <th>Nama Lengkap</th>
        <th>Username</th>
        <th>Role</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($daftarUser)): ?>
        <?php $no = 1; foreach ($daftarUser as $user): ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($user['nis']) ?></td>
            <td><?= htmlspecialchars($user['nama_lengkap']) ?></td>
            <td><?= htmlspecialchars($user['username']) ?></td>
            <td><?= htmlspecialchars($user['role']) ?></td>
            <td>
              <a href="../../CONTROLLER/c_user.php?aksi=edit&id_user=<?= $user['id_user'] ?>">Edit</a> |
              <a href="../../CONTROLLER/c_user.php?aksi=hapus&id_user=<?= $user['id_user'] ?>"
                 onclick="return confirm('Yakin ingin menghapus user ini?')">Hapus</a>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="6">Belum ada user terdaftar.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>

  <br>

  <?php if (!empty($daftarUser)): ?>
    <p>Total data user: <strong><?= count($daftarUser) ?></strong></p>
  <?php endif; ?>

</body>
</html>
