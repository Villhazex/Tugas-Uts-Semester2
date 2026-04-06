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

<!-- FONT -->
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
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg,#0f172a,#1e293b);
    color:white;
}

/* HEADER */
.header{
    background: rgba(255,255,255,0.05);
    backdrop-filter: blur(10px);
    padding:20px;
    box-shadow:0 4px 20px rgba(0,0,0,0.3);
}

/* LOGO */
.logo-area{
    display:flex;
    align-items:center;
    justify-content:center;
    gap:15px;
}

.logo-area img{
    width:60px;
    height:60px;
    border-radius:50%;
    object-fit:contain;
    background:white;
}

.logo-area h2{
    color:#fff;
    font-weight:600;
}

/* SEARCH */
.search-box{
    display:flex;
    justify-content:center;
    margin:30px 20px;
}

.search-box form{
    display:flex;
    width:100%;
    max-width:500px;
    background:#1e293b;
    border-radius:50px;
    overflow:hidden;
    box-shadow:0 5px 20px rgba(0,0,0,0.4);
}

.search-box input{
    flex:1;
    padding:15px;
    border:none;
    outline:none;
    background:transparent;
    color:white;
    font-size:15px;
}

.search-box input::placeholder{
    color:#94a3b8;
}

.search-box button{
    padding:15px 25px;
    border:none;
    background:#38bdf8;
    color:black;
    font-weight:bold;
    cursor:pointer;
    transition:0.3s;
}

.search-box button:hover{
    background:#0ea5e9;
}

/* CONTAINER */
.container{
    display:flex;
    flex-wrap:wrap;
    gap:25px;
    justify-content:center;
    padding:20px;
}

/* CARD */
.card{
    width: calc(20% - 25px);
    min-width:230px;
    background:#1e293b;
    border-radius:15px;
    overflow:hidden;
    box-shadow:0 10px 30px rgba(0,0,0,0.5);
    transition:0.3s;
}

.card:hover{
    transform: translateY(-8px) scale(1.03);
}

/* IMAGE */
.card img{
    width:100%;
    height:200px;
    object-fit:cover;
    transition:0.4s;
}

.card:hover img{
    transform:scale(1.1);
}

/* BODY */
.card-body{
    padding:18px;
}

/* TEXT */
.card-body h3{
    font-size:17px;
    margin-bottom:8px;
}

.harga{
    color:#38bdf8;
    font-weight:bold;
    font-size:18px;
}

.stok{
    font-size:13px;
    color:#94a3b8;
    margin-top:5px;
}

/* BUTTON */
.beli-btn{
    display:block;
    text-align:center;
    background:#22c55e;
    color:white;
    padding:10px;
    border-radius:8px;
    margin-top:12px;
    text-decoration:none;
    font-weight:600;
    transition:0.3s;
}

.beli-btn:hover{
    background:#16a34a;
    transform:scale(1.05);
}

/* RESPONSIVE */
@media(max-width:1024px){
    .card{
        width: calc(33.33% - 25px);
    }
}

@media(max-width:768px){
    .card{
        width: calc(50% - 25px);
    }
}

@media(max-width:480px){
    .card{
        width:100%;
    }

    .search-box form{
        flex-direction:column;
        border-radius:15px;
    }

    .search-box button{
        border-radius:0 0 15px 15px;
    }
}

</style>
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