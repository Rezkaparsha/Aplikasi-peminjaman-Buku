<?php

require_once __DIR__ . "/../../MODEL/m_kategori.php";

$kategoriModel = new Kategori();
$daftarKategori = $kategoriModel->getAll();

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Kategori</title>
</head>
<body>

<h2>Kelola Kategori</h2>
<p>Daftar kategori buku yang tersedia di perpustakaan.</p>

<a href="tambahKategori.php">+ Tambah Kategori</a>
<br><br>

<table border="1" cellspacing="0" cellpadding="8">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Kategori</th>
            <th>Deskripsi</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>

    <tbody>
        <?php if (!empty($daftarKategori)): ?>
            <?php $no = 1; ?>
            <?php foreach ($daftarKategori as $kategori): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($kategori['nama_kategori']) ?></td>
                    <td><?= htmlspecialchars($kategori['deskripsi'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($kategori['status']) ?></td>
                    <td>
                        <a href="../../CONTROLLER/c_kategori.php?aksi=edit&id_kategori=<?= $kategori['id_kategori'] ?>">
                            Edit
                        </a>
                        |
                        <a
                            href="../../CONTROLLER/c_kategori.php?aksi=hapus&id_kategori=<?= $kategori['id_kategori'] ?>"
                            onclick="return confirm('Yakin ingin menghapus kategori ini?')"
                        >
                            Hapus
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5">Belum ada data kategori.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<br>

<a href="daftarBuku.php">← Kembali ke Daftar Buku</a>

</body>
</html>