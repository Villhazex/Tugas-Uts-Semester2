<?php
include "functions.php";

/* ========================
   SEARCH
======================== */
$keyword = "";
if(isset($_GET['search'])){
    $keyword = $_GET['search'];
    $data = mysqli_query($conn, "SELECT * FROM produk 
        WHERE namaproduk LIKE '%$keyword%'");
} else {
    $data = tampilProduk();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Toko Kerupuk</title>

<style>
body{
    font-family: Arial, Helvetica, sans-serif;
    background:#f4f6f9;
    margin:0;
}

/* HEADER */
.header{
    text-align:center;
    padding:20px;
    background:white;
    box-shadow:0 2px 10px rgba(0,0,0,0.1);
}

/* SEARCH */
.search-box{
    text-align:center;
    margin:20px;
}

.search-box input{
    padding:10px;
    width:250px;
    border-radius:5px;
    border:1px solid #ccc;
}

.search-box button{
    padding:10px 15px;
    background:#4CAF50;
    color:white;
    border:none;
    border-radius:5px;
    cursor:pointer;
}

/* CONTAINER */
.container{
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center;
    padding: 20px;
}

/* CARD (5 per baris) */
.card{
    width: calc(20% - 20px); /* 5 card */
    min-width: 220px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    overflow: hidden;
    transition: 0.3s;
}

.card:hover{
    transform:translateY(-5px);
}

.card img{
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.card-body{
    padding:15px;
}

.harga{
    color:#e53935;
    font-weight:bold;
}

.stok{
    color:gray;
    font-size:14px;
}

.beli-btn{
    display:block;
    text-align:center;
    background:#4CAF50;
    color:white;
    padding:8px;
    border-radius:5px;
    margin-top:10px;
    text-decoration:none;
}

.beli-btn:hover{
    background:#2e7d32;
}
</style>

</head>

<body>

<div class="header">
    <h2>Aneka Macam Kripik Cap Fajar</h2>
</div>

<!-- SEARCH -->
<div class="search-box">
    <form method="GET">
        <input type="text" name="search" placeholder="Cari produk..." value="<?php echo $keyword; ?>">
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