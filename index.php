<!-- index.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Barang</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Tambah Barang</h2>
        <a href="cari_barang.php" class="btn btn-info">Cari Barang</a>
        <form action="actions/tambah_barang.php" method="POST">
            <div class="mb-3">
                <label>Kategori</label>
                <input type="text" name="kategori" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Nama Barang</label>
                <input type="text" name="nama_barang" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Harga Beli</label>
                <input type="number" name="harga_beli" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Harga Jual (Eceran)</label>
                <input type="number" name="harga_eceran" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Harga Jual (Renceng/Diskon)</label>
                <input type="number" name="harga_diskon" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</body>
</html>
