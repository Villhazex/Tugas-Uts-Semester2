<?php
include "config.php";

//kenapa functions.php? karena di tutorial wpu gitu

function tampilProduk(){
    global $conn; //variable global
    $result = mysqli_query($conn,"SELECT * FROM produk");
    return $result;
}

function tambahPesanan($nama,$alamat,$produk,$jumlah){
    global $conn;
    mysqli_query($conn,"INSERT INTO pesanan VALUES ('','$nama','$alamat','$produk','$jumlah',NOW())");
}
?>