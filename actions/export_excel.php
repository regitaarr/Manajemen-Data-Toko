<?php
// actions/export_excel.php
include '../config/db.php';

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=data_barang.xls");

echo "<table border='1'>
<tr>
<th>Kategori</th>
<th>Nama Barang</th>
<th>Harga Beli</th>
<th>Harga Jual (Eceran)</th>
<th>Harga Jual (Diskon)</th>
</tr>";

$filter = isset($_GET['kategori']) ? $_GET['kategori'] : '';
$sql = "SELECT * FROM barang WHERE kategori LIKE '%$filter%' ORDER BY nama_barang ASC";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    echo "<tr>
    <td>{$row['kategori']}</td>
    <td>{$row['nama_barang']}</td>
    <td>{$row['harga_beli']}</td>
    <td>{$row['harga_eceran']}</td>
    <td>{$row['harga_diskon']}</td>
    </tr>";
}

echo "</table>";
?>
