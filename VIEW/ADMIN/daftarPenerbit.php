<?php
require_once __DIR__ . "/../../MODEL/m_penerbit.php";

$penerbitModel = new Penerbit();
$daftarPenerbit = $penerbitModel->getAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Penerbit</title>
</head>
<body>

    <h2>Kelola Penerbit</h2>
    <p>Daftar penerbit yang tersedia di perpustakaan.</p>

    <a href="tambahPenerbit.php">+ Tambah Penerbit</a>
    <br><br>

    <table border="1" cellspacing="0" cellpadding="8">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Penerbit</th>
                <th>Alamat</th>
                <th>Kota</th>
                <th>Telepon</th>
                <th>Email</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($daftarPenerbit)): ?>
                <?php $no = 1; ?>
                <?php foreach ($daftarPenerbit as $penerbit): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($penerbit['nama_penerbit']) ?></td>
                        <td><?= htmlspecialchars($penerbit['alamat'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($penerbit['kota'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($penerbit['telepon'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($penerbit['email'] ?? '-') ?></td>
                        <td>
                            <a href="../../CONTROLLER/c_penerbit.php?aksi=edit&id_penerbit=<?= $penerbit['id_penerbit'] ?>">Edit</a>
                            |
                            <a href="../../CONTROLLER/c_penerbit.php?aksi=hapus&id_penerbit=<?= $penerbit['id_penerbit'] ?>" onclick="return confirm('Yakin ingin menghapus penerbit ini?')">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" style="text-align: center;">Belum ada data penerbit.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <br>
    <a href="daftarBuku.php">← Kembali ke Daftar Buku</a>

</body>
</html>