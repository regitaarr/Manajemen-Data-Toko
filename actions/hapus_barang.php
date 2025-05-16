<?php
include '../config/db.php';

if (!isset($_GET['id'])) {
    header("Location: ../cari_barang.php");
    exit;
}

$id = $_GET['id'];

// Delete item
$sql = "DELETE FROM barang WHERE id = $id";

if ($conn->query($sql)) {
    header("Location: ../cari_barang.php?delete_success=1");
} else {
    header("Location: ../cari_barang.php?delete_error=1");
}

$conn->close();
?>