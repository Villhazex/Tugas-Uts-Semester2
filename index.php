<?php
include "functions.php";

//search metode get
$pencarian = "";
if(isset($_GET['search'])){
    $pencarian = $_GET['search'];
    $data = mysqli_query($conn, "SELECT * FROM produk 
        WHERE namaproduk LIKE '%$pencarian%'");
} else {
    $data = tampilProduk();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Toko Kerupuk</title>
<link rel="stylesheet" href="cssnyaindex.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>

<body>

<div class="header">
    <div class="logo-area">
        <img src="images/logo.png" alt="Logo">
        <h2>Aneka Macam Kripik Cap Fajar</h2>
    </div>
</div>

<!-- SEARCH -->
<div class="search-box">
    <form method="GET">
        <input type="text" name="search" placeholder="Cari produk..." value="<?php echo $pencarian; ?>">
        <button type="submit">Cari</button>
    </form>
</div>

<!-- PRODUK -->
<div class="container">

<?php while($row = mysqli_fetch_assoc($data)){ ?>

<div class="card">

    <img src="images/<?php echo $row['gambar']; ?>" alt="produk">

    <div class="card-body">

        <h3><?php echo $row['namaproduk']; ?></h3>

        <p class="harga">
            Rp <?php echo number_format($row['harga']); ?>
        </p>

        <p class="stok">
            Stok : <?php echo $row['stok']; ?>
        </p>

        <a class="beli-btn"
        href="beli.php?produk=<?php echo $row['namaproduk']; ?>">
        Beli
        </a>

    </div>

</div>

<?php } ?>

</div>

</body>
</html>