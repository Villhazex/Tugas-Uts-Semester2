<?php
include "functions.php";

$produk = $_GET['produk'] ?? '';

// ambil data produk 
$data = mysqli_query($conn,"SELECT * FROM produk WHERE namaproduk='$produk'");
$row = mysqli_fetch_assoc($data);
$stok = $row['stok'] ?? 0;

if(isset($_POST['submit'])){

    $nama = htmlspecialchars($_POST['nama']);
    $alamat = htmlspecialchars($_POST['alamat']);
    $produk = $_POST['produk'];
    $jumlah = (int) $_POST['jumlah'];

    if($jumlah > $stok){

        echo "<script>alert('Stok tidak mencukupi!');</script>";

    } else {

        $stokBaru = $stok - $jumlah;

        mysqli_query($conn,"UPDATE produk 
        SET stok='$stokBaru' 
        WHERE namaproduk='$produk'");

        tambahPesanan($nama,$alamat,$produk,$jumlah);

        echo "<script>
        alert('Pesanan berhasil!');
        window.location='index.php';
        </script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Form Pembelian</title>
<link rel="stylesheet" href="cssnyabeli.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
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
<input type="number" name="jumlah" min="1" max="<?php echo $stok ?>" required>

<p class="stok">Stok tersedia: <b><?php echo $stok ?></b></p>

<button type="submit" name="submit">Pesan Sekarang</button>

</form>

<div class="back">
<a href="index.php">← Kembali ke Produk</a>
</div>

</div>

</body>
</html>