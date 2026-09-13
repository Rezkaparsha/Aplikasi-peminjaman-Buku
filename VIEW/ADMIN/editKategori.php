<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kategori - Perpustakaan Online</title>
    <style>
        /* Reset & Base Styles */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        body {
            background-color: #f4f7f6;
            color: #333;
            display: flex;
            min-height: 100vh;
        }

        /* Main Content Layout */
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        
        .topbar {
            background-color: #fff;
            padding: 15px 30px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .topbar h2 {
            font-size: 20px;
            color: #2c3e50;
        }

        .container {
            padding: 30px;
            flex: 1;
            overflow-y: auto;
        }

        /* Card Container */
        .card {
            background: #ffffff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
            max-width: 600px;
        }

        .card-header {
            border-bottom: 2px solid #ecf0f1;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .card-header h3 {
            color: #2c3e50;
            font-size: 18px;
        }

        /* Form Layout */
        .form-group {
            margin-bottom: 18px;
        }
        
        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 6px;
            color: #34495e;
            font-size: 13px;
        }

        .form-control {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ccd1d1;
            border-radius: 5px;
            font-size: 14px;
            color: #2c3e50;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #3498db;
            outline: none;
            box-shadow: 0 0 5px rgba(52, 152, 219, 0.3);
        }

        textarea.form-control {
            resize: vertical;
        }

        /* Buttons */
        .action-group {
            display: flex;
            gap: 10px;
            margin-top: 25px;
        }

        .btn {
            padding: 10px 18px;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            transition: background 0.3s ease;
        }

        .btn-submit {
            background-color: #3498db;
            color: white;
            flex: 1;
        }
        .btn-submit:hover { background-color: #2980b9; }

        .btn-cancel {
            background-color: #e74c3c;
            color: white;
            flex: 1;
        }
        .btn-cancel:hover { background-color: #c0392b; }

        /* Error state */
        .alert-error {
            background-color: #fdeaea;
            color: #c0392b;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <!-- Sidebar Admin -->
    <?php include __DIR__ . "/../TEMPLATE/SideBarAdmin.php"; ?>

    <div class="main-content">
        <!-- Topbar -->
        <div class="topbar">
            <h2>Edit Data Kategori</h2>
            <div style="font-size: 14px; color: #7f8c8d;">Panel Admin</div>
        </div>

        <!-- Main Container -->
        <div class="container">
            <div class="card">
                <div class="card-header">
                    <h3>Formulir Perubahan Kategori</h3>
                </div>

                <?php if (!empty($dataKategori)): ?>

                    <form action="/Aplikasi Peminjaman Buku/CONTROLLER/c_kategori.php?aksi=update" method="POST">

                        <input type="hidden" name="id_kategori" value="<?= htmlspecialchars($dataKategori['id_kategori']) ?>">

                        <div class="form-group">
                            <label for="nama_kategori">Nama Kategori</label>
                            <input type="text" id="nama_kategori" name="nama_kategori" class="form-control" value="<?= htmlspecialchars($dataKategori['nama_kategori']) ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="deskripsi">Deskripsi Kategori</label>
                            <textarea id="deskripsi" name="deskripsi" class="form-control" rows="4"><?= htmlspecialchars($dataKategori['deskripsi'] ?? '') ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="status">Status</label>
                            <select id="status" name="status" class="form-control" required>
                                <option value="aktif" <?= ($dataKategori['status'] === 'aktif') ? 'selected' : '' ?>>Aktif</option>
                                <option value="nonaktif" <?= ($dataKategori['status'] === 'nonaktif') ? 'selected' : '' ?>>Nonaktif</option>
                            </select>
                        </div>

                        <div class="action-group">
                            <button type="submit" class="btn btn-submit">Simpan Perubahan</button>
                            <a href="../VIEW/ADMIN/daftarKategori.php" class="btn btn-cancel">Batal</a>
                        </div>

                    </form>

                <?php else: ?>

                    <div class="alert-error">Data kategori tidak ditemukan.</div>
                    <div style="text-align: center;">
                        <a href="/Aplikasi Peminjaman Buku/VIEW/ADMIN/daftarKategori.php" class="btn btn-cancel" style="display: inline-block;">Kembali ke Daftar Kategori</a>
                    </div>

                <?php endif; ?>
            </div>
        </div>
    </div>

</body>
</html>