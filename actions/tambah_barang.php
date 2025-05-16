<?php
// actions/tambah_barang.php
include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kategori = $_POST['kategori'];
    $nama_barang = $_POST['nama_barang'];
    $harga_beli = $_POST['harga_beli'];
    $harga_eceran = $_POST['harga_eceran'];
    $harga_diskon = $_POST['harga_diskon'];

    $sql = "INSERT INTO barang (kategori, nama_barang, harga_beli, harga_eceran, harga_diskon) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssddd', $kategori, $nama_barang, $harga_beli, $harga_eceran, $harga_diskon);
    if ($stmt->execute()) {
        header('Location: ../index.php');
    } else {
        echo "Gagal menambahkan data.";
    }
    $stmt->close();
    $conn->close();
}
?>