<?php
require_once __DIR__ . "/../../MODEL/m_buku.php";

$bukuModel = new Buku();
$daftarBuku = $bukuModel->getAllBuku();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Buku - Perpustakaan Online</title>
</head>''
<body>

    <h2>Kelola Koleksi Buku</h2>
    <p>Daftar lengkap semua buku yang tersedia di perpustakaan.</p>

    <a href="tambahBuku.php">+ Tambah Buku</a>
    <br><br>

    <table border="1" cellspacing="0" cellpadding="8">
        <thead>
            <tr>
                <th>No</th>
                <th>Cover</th>
                <th>Judul Buku</th>
                <th>Penulis</th>
                <th>Penerbit</th>
                <th>Tahun Terbit</th>
                <th>Kategori</th>
                <th>Harga Buku</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($daftarBuku)): ?>
                <?php $no = 1; ?>
                <?php foreach ($daftarBuku as $buku): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td>
                            <?php if (!empty($buku['cover'])): ?>
                                <img src="../../ASSETS/COVER/<?= htmlspecialchars($buku['cover']) ?>" alt="Cover Buku" width="80">
                            <?php else: ?>
                                <span>Tidak ada cover</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($buku['judul_buku']) ?></td>
                        <td><?= htmlspecialchars($buku['penulis'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($buku['nama_penerbit'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($buku['tahun_terbit'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($buku['nama_kategori'] ?? '-') ?></td>
                        <td>Rp <?= number_format((int) $buku['harga_buku'], 0, ',', '.') ?></td>
                        <td><?= htmlspecialchars($buku['stok']) ?></td>
                        <td><button><a href="../../CONTROLLER/c_buku.php?aksi=edit&id_buku=<?= $buku['id_buku'] ?>">Edit</a></button>
                            <button><a href="../../CONTROLLER/c_buku.php?aksi=hapus&id_buku=<?= $buku['id_buku'] ?>" onclick="return confirm('Yakin ingin menghapus buku ini?')">Hapus</a></button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="10">Belum ada buku dalam koleksi.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <br>

    <?php if (!empty($daftarBuku)): ?>
        <p>Total data buku: <strong><?= count($daftarBuku) ?></strong></p>
    <?php endif; ?>

</body>
</html>