<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cari Barang</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .action-column {
            width: 120px;
        }
        .search-container {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
        }
        .search-input {
            flex: 1;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h2>Cari Barang</h2>
        <a href="index.php" class="btn btn-info mb-3">Tambah Barang</a>
        
        <form method="GET" class="mb-3">
            <div class="search-container">
                <div class="search-input">
                    <label for="nama_barang" class="form-label">Cari Nama Barang</label>
                    <input type="text" name="nama_barang" id="nama_barang" placeholder="Nama barang..." 
                           class="form-control" value="<?php echo isset($_GET['nama_barang']) ? htmlspecialchars($_GET['nama_barang']) : ''; ?>">
                </div>
                
                <div class="search-input">
                    <label for="kategori" class="form-label">Cari Kategori</label>
                    <input type="text" name="kategori" id="kategori" placeholder="Kategori..." 
                           class="form-control" value="<?php echo isset($_GET['kategori']) ? htmlspecialchars($_GET['kategori']) : ''; ?>">
                </div>
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-secondary">Cari</button>
                <a href="actions/export_excel.php?<?php echo http_build_query($_GET); ?>" 
                   class="btn btn-success">Unduh Excel</a>
                <a href="cari_barang.php" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
        
        <?php
        // Notifikasi
        if (isset($_GET['delete_success'])) {
            echo '<div class="alert alert-success">Barang berhasil dihapus!</div>';
        }
        if (isset($_GET['edit_success'])) {
            echo '<div class="alert alert-success">Barang berhasil diperbarui!</div>';
        }
        
        // Koneksi database dan query
        include 'config/db.php';
        
        // Gunakan prepared statement untuk mencegah SQL injection
        $sql = "SELECT * FROM barang WHERE 1=1";
        $params = [];
        $types = '';
        
        if (!empty($_GET['nama_barang'])) {
            $sql .= " AND nama_barang LIKE ?";
            $params[] = '%' . $_GET['nama_barang'] . '%';
            $types .= 's';
        }
        
        if (!empty($_GET['kategori'])) {
            $sql .= " AND kategori LIKE ?";
            $params[] = '%' . $_GET['kategori'] . '%';
            $types .= 's';
        }
        
        $sql .= " ORDER BY nama_barang ASC";
        
        $stmt = $conn->prepare($sql);
        
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        ?>
        
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Kategori</th>
                    <th>Nama Barang</th>
                    <th>Harga Beli</th>
                    <th>Harga Jual (Eceran)</th>
                    <th>Harga Jual (Renceng/Diskon)</th>
                    <th class="action-column">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>".htmlspecialchars($row['kategori'])."</td>
                            <td>".htmlspecialchars($row['nama_barang'])."</td>
                            <td>Rp " . number_format($row['harga_beli'], 0, ',', '.')."</td>
                            <td>Rp " . number_format($row['harga_eceran'], 0, ',', '.')."</td>
                            <td>Rp " . number_format($row['harga_diskon'], 0, ',', '.')."</td>
                            <td>
                                <a href='edit_barang.php?id=".(int)$row['id']."&".http_build_query($_GET)."' 
                                   class='btn btn-warning btn-sm'>Edit</a>
                                <button onclick='confirmDelete(".(int)$row['id'].")' 
                                        class='btn btn-danger btn-sm'>Hapus</button>
                            </td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' class='text-center'>Tidak ada data barang ditemukan</td></tr>";
                }
                $stmt->close();
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        
        // Notifikasi edit sukses
        if (urlParams.has('edit_success')) {
            Swal.fire({
                title: 'Sukses!',
                text: 'Barang berhasil diperbarui.',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                // Hapus parameter notifikasi dari URL
                urlParams.delete('edit_success');
                window.history.replaceState({}, '', `${location.pathname}?${urlParams.toString()}`);
            });
        }
        
        // Notifikasi error
        if (urlParams.has('edit_error')) {
            Swal.fire({
                title: 'Gagal!',
                text: 'Gagal memperbarui barang.',
                icon: 'error',
                confirmButtonText: 'OK'
            }).then(() => {
                urlParams.delete('edit_error');
                window.history.replaceState({}, '', `${location.pathname}?${urlParams.toString()}`);
            });
        }
    });

    // Fungsi konfirmasi hapus
    function confirmDelete(id) {
        const urlParams = new URLSearchParams(window.location.search);
        const searchParams = new URLSearchParams();
        
        if (urlParams.has('nama_barang')) {
            searchParams.append('nama_barang', urlParams.get('nama_barang'));
        }
        if (urlParams.has('kategori')) {
            searchParams.append('kategori', urlParams.get('kategori'));
        }
        
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `actions/hapus_barang.php?id=${id}&${searchParams.toString()}`;
            }
        });
    }
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>