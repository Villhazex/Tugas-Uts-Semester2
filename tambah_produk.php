<?php
include "functions.php";

if(isset($_POST['submit'])){
    tambahProduk(
        $_POST['nama'],
        $_POST['harga'],
        $_POST['stok']
    );

    echo "Produk berhasil ditambahkan";
}
?>

<h2>Tambah Produk</h2>

<form method="POST">

Nama Produk<br>
<input type="text" name="nama"><br><br>

Harga<br>
<input type="number" name="harga"><br><br>

Stok<br>
<input type="number" name="stok"><br><br>

<button type="submit" name="submit">Tambah</button>

</form>