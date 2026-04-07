<?php
include "config.php";

function tampilProduk(){
    global $conn;

    $result = mysqli_query($conn,"SELECT * FROM produk");
    return $result;
}


function tambahPesanan($nama,$alamat,$produk,$jumlah){
    global $conn;

    $stmt = $conn->prepare(
        "INSERT INTO pesanan (nama,alamat,produk,jumlah,tanggal) 
         VALUES (?,?,?,?,NOW())"
    );

    $stmt->bind_param("ssss",$nama,$alamat,$produk,$jumlah);
    $stmt->execute();
}


function hitungSubtotal($harga,$jumlah){
    return $harga * $jumlah;
}


function hitungTotal($items){

    $total = 0;

    foreach($items as $item){
        $total += $item['subtotal'];
    }

    return $total;
}

?>