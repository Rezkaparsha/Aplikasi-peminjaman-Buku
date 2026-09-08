<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Daftar User</title>
  <link rel="stylesheet" href="/PERPUSTAKAAN_kel6/assets/daftar_user.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
  <div class="container mt-4">
    <h4 class="mb-3">Daftar User</h4>
    <a href="/Aplikasi Peminjmaan Buku/index.php?page=tambah_pengguna" class="btn btn-primary mb-3">
      <i class="fas fa-user-plus"></i> Tambah Pengguna
    </a>

    <table class="table table-bordered table-striped align-middle">
      <thead class="table-primary text-center">
        <tr>
          <th>No</th>
          <th>ID USER</th>
          <th>NIS</th>
          <th>Username</th>
          <th>Nama Lengkap</th>
          <th>Role</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($users)) : ?>
          <?php $no = 1;
          foreach ($users as $data) : ?>
            <tr>
              <td class="text-center"><?= $no++ ?></td>
              <td><?= htmlspecialchars($data->username) ?></td>
              <td><?= htmlspecialchars($data->email) ?></td>
              <td class="text-center"><?= htmlspecialchars($data->role) ?></td>
              <td class="text-center">
                <a href="/PERPUSTAKAAN_kel6/index.php?page=pengguna_process&aksi=edit&id_user=<?= $data->id_user ?>"
                  class="btn btn-warning btn-sm">
                  <i class="fas fa-edit"></i> Edit
                </a>

                <a href="/PERPUSTAKAAN_kel6/index.php?page=pengguna_process&aksi=hapus&id_user=<?= $data->id_user ?>"
                  class="btn btn-danger btn-sm"
                  onclick="return confirm('Yakin ingin menghapus user ini?')">
                  <i class="fas fa-trash-alt"></i> Hapus
                </a>
              </td>
            </tr>
          <?php endforeach ?>
        <?php else : ?>
          <tr>
            <td colspan="5" class="text-center text-muted p-4">Belum ada data user.</td>
          </tr>
        <?php endif ?>
      </tbody>
    </table>
  </div>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/js/all.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>