<?php
require_once __DIR__ . "/../../MODEL/m_penulis.php";

$penulisModel = new Penulis();
$daftarPenulis = $penulisModel->getAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Penulis</title>
</head>
<body>

    <h2>Kelola Penulis</h2>
    <p>Daftar penulis yang tersedia di perpustakaan.</p>

    <a href="tambahPenulis.php">+ Tambah Penulis</a>
    <br><br>

    <table border="1" cellspacing="0" cellpadding="8">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Penulis</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($daftarPenulis)): ?>
                <?php $no = 1; ?>
                <?php foreach ($daftarPenulis as $penulis): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($penulis['nama_penulis']) ?></td>
                        <td>
                            <a href="../../CONTROLLER/c_penulis.php?aksi=edit&id_penulis=<?= $penulis['id_penulis'] ?>">Edit</a>
                            |
                            <a href="../../CONTROLLER/c_penulis.php?aksi=hapus&id_penulis=<?= $penulis['id_penulis'] ?>" onclick="return confirm('Yakin ingin menghapus penulis ini?')">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">Belum ada data penulis.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <br>
    <a href="daftarBuku.php">← Kembali ke Daftar Buku</a>

</body>
</html>