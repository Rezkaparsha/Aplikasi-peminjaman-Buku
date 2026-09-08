<?php
// Pastikan session sudah berjalan dan hanya petugas yang boleh mengakses
// if (session_status() === PHP_SESSION_NONE) {
//   session_start();
// }
// if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
//   header("Location: /Aplikasi Peminjaman Buku/index.php?page=login&msg=Akses ditolak. Silakan login sebagai admin.");
//   exit;
// }

// // include navbar petugas
// include_once __DIR__ . "/../../template/navbar_petugas.php";

// include model untuk mengambil data
include_once __DIR__ . "/../../MODEL/m_buku.php";

$bukuModel = new Buku();
$daftarBuku = $bukuModel->getAllBuku();
?>
<!doctype html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <title>Daftar Buku - Perpustakaan Online</title>
</head>

<body>
  <div>
    <div>
      <div>
        <h2 Kelola Koleksi Buku</h2>
        <p class="small text-muted">Daftar lengkap semua buku digital yang tersedia di perpustakaan.</p>
      </div>
    </div>

    <div>
      <div>
        <table border="2" cellspacing="0">
          <thead class="table-primary">
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
              <th>aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($daftarBuku)): ?>
              <?php $no = 1;
              foreach ($daftarBuku as $buku): ?>
                <tr>
                  <td><?= $no++ ?></td>
                  <td>ini untuk cover</td>
                  <td><?= htmlspecialchars($buku['judul_buku'] ?? '') ?></td>
                  <td><?= htmlspecialchars($buku['penulis'] ?? '') ?></td>
                  <td><?= htmlspecialchars($buku['nama_penerbit'] ?? '') ?></td>
                  <td><?= htmlspecialchars($buku['tahun_terbit'] ?? '') ?></td>
                  <td><?= htmlspecialchars($buku['nama_kategori'] ?? '') ?></td>
                  <td>Rp <?= htmlspecialchars($buku['harga_buku']) ?></td>
                  <td><?= htmlspecialchars($buku['stok'] ?? '') ?></td>
                  <td>
                    <a href="Form_PinjamBuku.php">pinjam buku</a>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="9" class="text-center p-5">
                  <div class="text-muted">Belum ada buku dalam koleksi ini.</div>
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
      <?php if (!empty($daftarBuku)): ?>
        <div>
          Total data buku: <?= count($daftarBuku) ?>
        </div>
      <?php endif; ?>
    </div>
  </div>

</body>

</html>