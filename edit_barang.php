<?php
// Try to include the database connection file
$db_file = 'config/db.php';
if (!file_exists($db_file)) {
    die("Database configuration file not found: " . $db_file);
}

include $db_file;

if (!isset($conn)) {
    die("Database connection not established");
}

if (!isset($_GET['id'])) {
    header("Location: ../cari_barang.php");
    exit;
}

$id = $_GET['id'];

// Fetch item data
$sql = "SELECT * FROM barang WHERE id = $id";
$result = $conn->query($sql);
if (!$result) {
    die("Query failed: " . $conn->error);
}

$row = $result->fetch_assoc();

if (!$row) {
    header("Location: ../cari_barang.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kategori = $conn->real_escape_string($_POST['kategori']);
    $nama_barang = $conn->real_escape_string($_POST['nama_barang']);
    $harga_beli = (float)$_POST['harga_beli'];
    $harga_eceran = (float)$_POST['harga_eceran'];
    $harga_diskon = (float)$_POST['harga_diskon'];
    
    $update_sql = "UPDATE barang SET 
                  kategori = '$kategori',
                  nama_barang = '$nama_barang',
                  harga_beli = $harga_beli,
                  harga_eceran = $harga_eceran,
                  harga_diskon = $harga_diskon
                  WHERE id = $id";
    
    if ($conn->query($update_sql) === TRUE) {
        header("Location: ../cari_barang.php?edit_success=1");
        exit;
    } else {
        $error = "Error: " . $conn->error;
    }
}
?>

<!-- edit_barang.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Barang</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Edit Barang</h2>
        <a href="/cari_barang.php?nama_barang=<?php echo isset($_GET['nama_barang']) ? urlencode($_GET['nama_barang']) : ''; ?>" class="btn btn-secondary mb-3">Kembali</a>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <!-- FORM EDIT BARANG -->
        <form method="POST" action="actions/update_barang.php">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            
            <div class="mb-3">
                <label for="kategori" class="form-label">Kategori</label>
                <input type="text" class="form-control" id="kategori" name="kategori" 
                       value="<?php echo htmlspecialchars($row['kategori']); ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="nama_barang" class="form-label">Nama Barang</label>
                <input type="text" class="form-control" id="nama_barang" name="nama_barang" 
                       value="<?php echo htmlspecialchars($row['nama_barang']); ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="harga_beli" class="form-label">Harga Beli</label>
                <input type="number" class="form-control" id="harga_beli" name="harga_beli" 
                       value="<?php echo $row['harga_beli']; ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="harga_eceran" class="form-label">Harga Jual (Eceran)</label>
                <input type="number" class="form-control" id="harga_eceran" name="harga_eceran" 
                       value="<?php echo $row['harga_eceran']; ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="harga_diskon" class="form-label">Harga Jual (Renceng/Diskon)</label>
                <input type="number" class="form-control" id="harga_diskon" name="harga_diskon" 
                       value="<?php echo $row['harga_diskon']; ?>" required>
            </div>
            
            <!-- Menyimpan parameter pencarian terakhir -->
            <input type="hidden" name="last_search" 
                   value="<?php echo isset($_GET['nama_barang']) ? htmlspecialchars($_GET['nama_barang']) : ''; ?>">
            
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </form>
        <!-- END FORM EDIT BARANG -->
    </div>
</body>
</html>