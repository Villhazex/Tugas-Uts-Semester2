<?php
include "functions.php";

$produk = $_GET['produk'];

if(isset($_POST['submit'])){
    tambahPesanan(
        $_POST['nama'],
        $_POST['alamat'],
        $_POST['produk'],
        $_POST['jumlah']
    );

    echo "<script>alert('Pesanan berhasil!');</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Form Pembelian</title>
<link rel="stylesheet" href="cssnyabeli.css">


</head>

<body>

<div class="container">

<h2>Form Pembelian</h2>

<form method="POST">

<label>Nama Pembeli</label>
<input type="text" name="nama" required>

<label>Alamat</label>
<textarea name="alamat" required></textarea>

<label>Produk</label>
<input type="text" name="produk" value="<?php echo $produk ?>" readonly>

<label>Jumlah</label>
<input type="number" name="jumlah" min="1" required>

<button type="submit" name="submit">Pesan Sekarang</button>

</form>

<div class="back">
<a href="index.php">← Kembali ke Produk</a>
</div>

</div>

</body>
</html>