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

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<style>

/* RESET */
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

/* BODY */
body{
    font-family:'Poppins', sans-serif;
    background: linear-gradient(135deg,#0f172a,#1e293b);
    display:flex;
    align-items:center;
    justify-content:center;
    min-height:100vh;
    color:white;
}

/* CONTAINER */
.container{
    background:#1e293b;
    padding:30px;
    width:100%;
    max-width:400px;
    border-radius:15px;
    box-shadow:0 10px 30px rgba(0,0,0,0.5);
    animation: fadeIn 0.5s ease;
}

/* ANIMATION */
@keyframes fadeIn{
    from{opacity:0; transform:translateY(20px);}
    to{opacity:1; transform:translateY(0);}
}

/* TITLE */
h2{
    text-align:center;
    margin-bottom:20px;
}

/* LABEL */
label{
    font-size:14px;
    font-weight:500;
}

/* INPUT */
input, textarea{
    width:100%;
    padding:12px;
    margin-top:6px;
    margin-bottom:15px;
    border-radius:8px;
    border:none;
    outline:none;
    background:#334155;
    color:white;
    transition:0.3s;
}

/* FOCUS */
input:focus, textarea:focus{
    box-shadow:0 0 0 2px #38bdf8;
}

/* TEXTAREA */
textarea{
    resize:none;
    height:80px;
}

/* INFO STOK */
.stok{
    font-size:14px;
    color:#94a3b8;
    margin-bottom:15px;
}

/* BUTTON */
button{
    width:100%;
    padding:13px;
    border:none;
    background:#22c55e;
    color:white;
    font-size:15px;
    border-radius:8px;
    cursor:pointer;
    font-weight:600;
    transition:0.3s;
}

button:hover{
    background:#16a34a;
    transform:scale(1.03);
}

/* BACK */
.back{
    text-align:center;
    margin-top:15px;
}

.back a{
    color:#94a3b8;
    text-decoration:none;
    font-size:14px;
}

.back a:hover{
    color:white;
}

/* RESPONSIVE */
@media(max-width:500px){
    .container{
        margin:20px;
        padding:25px;
    }
}

</style>

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