<?php
include "config.php";

function tampilProduk(){
    global $conn;
    $result = mysqli_query($conn,"SELECT * FROM produk");
    return $result;
}

function tambahProduk($nama,$harga,$stok){
    global $conn;
    mysqli_query($conn,"INSERT INTO produk VALUES ('','$nama','$harga','$stok')");
}

function tambahPesanan($nama,$alamat,$produk,$jumlah){
    global $conn;
    mysqli_query($conn,"INSERT INTO pesanan VALUES ('','$nama','$alamat','$produk','$jumlah',NOW())");
}
?>