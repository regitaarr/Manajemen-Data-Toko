<?php
include '../config/db.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validasi input
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $kategori = isset($_POST['kategori']) ? $conn->real_escape_string($_POST['kategori']) : '';
    $nama_barang = isset($_POST['nama_barang']) ? $conn->real_escape_string($_POST['nama_barang']) : '';
    $harga_beli = isset($_POST['harga_beli']) ? (float)$_POST['harga_beli'] : 0;
    $harga_eceran = isset($_POST['harga_eceran']) ? (float)$_POST['harga_eceran'] : 0;
    $harga_diskon = isset($_POST['harga_diskon']) ? (float)$_POST['harga_diskon'] : 0;
    $last_search = isset($_POST['last_search']) ? urlencode($_POST['last_search']) : '';

    if ($id <= 0) {
        die("ID tidak valid.");
    }

    $sql = "UPDATE barang SET 
            kategori = ?, 
            nama_barang = ?, 
            harga_beli = ?, 
            harga_eceran = ?, 
            harga_diskon = ? 
            WHERE id = ?";
    
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        die("Error dalam menyiapkan query: " . $conn->error);
    }

    $stmt->bind_param('ssdddi', $kategori, $nama_barang, $harga_beli, $harga_eceran, $harga_diskon, $id);
    
    if ($stmt->execute()) {
        // Langsung redirect ke cari_barang.php dengan parameter sukses
        header("Location: ../cari_barang.php?edit_success=1&nama_barang=$last_search");
    } else {
        header("Location: ../cari_barang.php?edit_error=1&nama_barang=$last_search");
    }
    
    $stmt->close();
    $conn->close();
    exit;
} else {
    header('Location: ../cari_barang.php');
    exit;
}
?>