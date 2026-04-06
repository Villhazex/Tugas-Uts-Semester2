<?php
include "functions.php";

$produkDipilih = $_GET['produk'] ?? [];
$jumlahDipilih = $_GET['jumlah'] ?? [];

$items = [];
$totalSemua = 0;

foreach($produkDipilih as $i => $namaProduk){

    $jumlah = (int)$jumlahDipilih[$i];
    if($jumlah <= 0) continue;

    // Prepared statement — aman dari SQL Injection
    $stmt = $conn->prepare("SELECT * FROM produk WHERE namaproduk = ?");
    $stmt->bind_param("s", $namaProduk);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    if(!$row) continue;

    $subtotal = $row['harga'] * $jumlah;
    $totalSemua += $subtotal;

    $items[] = [
        'nama'     => $namaProduk,
        'harga'    => $row['harga'],
        'jumlah'   => $jumlah,
        'stok'     => $row['stok'],
        'gambar'   => $row['gambar'],
        'subtotal' => $subtotal
    ];
}

if(isset($_POST['submit'])){

    $nama   = htmlspecialchars(trim($_POST['nama']));
    $alamat = htmlspecialchars(trim($_POST['alamat']));

    foreach($items as $item){

        if($item['jumlah'] > $item['stok']){
            echo "<script>alert('Stok tidak cukup untuk ".addslashes($item['nama'])."');</script>";
            exit;
        }

        $stokBaru  = $item['stok'] - $item['jumlah'];
        $namaItem  = $item['nama'];

        $stmtUpd = $conn->prepare("UPDATE produk SET stok = ? WHERE namaproduk = ?");
        $stmtUpd->bind_param("is", $stokBaru, $namaItem);
        $stmtUpd->execute();

        tambahPesanan($nama, $alamat, $item['nama'], $item['jumlah']);
    }

    header("Location: success.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Checkout — Cap Fajar</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<style>

:root{
    --bg:#0a0a0f;
    --surface:#12121a;
    --surface2:#1a1a26;
    --accent:#f5a623;
    --accent2:#e8783a;
    --text:#f0ede8;
    --muted:#8a8898;
    --border:#2a2a3a;
}

*{margin:0;padding:0;box-sizing:border-box;}

body{
    font-family:'DM Sans',sans-serif;
    background:var(--bg);
    color:var(--text);
    min-height:100vh;
}

/* HEADER */
.header{
    background:var(--surface);
    border-bottom:1px solid var(--border);
    padding:18px 32px;
    display:flex;
    align-items:center;
    gap:14px;
}

.logo-ring{
    width:46px;height:46px;
    border-radius:50%;
    background:linear-gradient(135deg,var(--accent),var(--accent2));
    display:flex;align-items:center;justify-content:center;
    overflow:hidden;
}

.logo-ring img{width:100%;height:100%;object-fit:cover;}

.brand-name{
    font-family:'Playfair Display',serif;
    font-size:18px;
}

.brand-sub{
    font-size:11px;
    color:var(--muted);
    letter-spacing:2px;
    text-transform:uppercase;
    margin-top:2px;
}

/* BREADCRUMB */
.breadcrumb{
    padding:18px 32px;
    font-size:13px;
    color:var(--muted);
    display:flex;
    align-items:center;
    gap:8px;
}

.breadcrumb a{
    color:var(--muted);
    text-decoration:none;
    transition:color 0.2s;
}

.breadcrumb a:hover{color:var(--accent);}

.breadcrumb span{color:var(--text);}

/* WRAPPER */
.wrapper{
    max-width:1100px;
    margin:0 auto;
    padding:0 32px 60px;
    display:flex;
    gap:28px;
    align-items:flex-start;
}

/* ========== ORDER LIST ========== */
.list{
    flex:1.1;
}

.section-title{
    font-family:'Playfair Display',serif;
    font-size:22px;
    margin-bottom:20px;
}

/* EMPTY STATE */
.empty{
    background:var(--surface);
    border:1px solid var(--border);
    border-radius:16px;
    padding:48px;
    text-align:center;
    color:var(--muted);
}

.empty-icon{font-size:40px;margin-bottom:12px;}
.empty p{font-size:14px;line-height:1.6;}

/* ITEM CARD */
.item{
    background:var(--surface);
    border:1px solid var(--border);
    border-radius:16px;
    padding:16px;
    margin-bottom:14px;
    display:flex;
    gap:16px;
    align-items:center;
    transition:border-color 0.2s;
}

.item:hover{
    border-color:rgba(245,166,35,0.3);
}

.item-img{
    width:80px;height:80px;
    border-radius:12px;
    object-fit:cover;
    flex-shrink:0;
    background:var(--surface2);
}

.item-info{flex:1;}

.item-name{
    font-weight:500;
    font-size:15px;
    margin-bottom:6px;
}

.item-price{
    font-family:'Playfair Display',serif;
    color:var(--accent);
    font-size:15px;
}

.item-meta{
    font-size:12px;
    color:var(--muted);
    margin-top:4px;
}

.item-subtotal{
    font-family:'Playfair Display',serif;
    font-size:16px;
    color:var(--text);
    text-align:right;
    flex-shrink:0;
}

.item-subtotal small{
    display:block;
    font-family:'DM Sans',sans-serif;
    font-size:11px;
    color:var(--muted);
    margin-bottom:4px;
    letter-spacing:0.5px;
    text-transform:uppercase;
}

/* DIVIDER */
.divider{
    height:1px;
    background:var(--border);
    margin:6px 0 16px;
}

/* TOTAL ROW */
.total-row{
    display:flex;
    justify-content:space-between;
    align-items:center;
    background:var(--surface);
    border:1px solid var(--border);
    border-radius:16px;
    padding:18px 20px;
}

.total-label{
    font-size:13px;
    color:var(--muted);
    letter-spacing:0.5px;
    text-transform:uppercase;
}

.total-amount{
    font-family:'Playfair Display',serif;
    font-size:26px;
    color:var(--accent);
}

/* ========== FORM ========== */
.form-box{
    width:380px;
    flex-shrink:0;
    background:var(--surface);
    border:1px solid var(--border);
    border-radius:20px;
    padding:28px;
    position:sticky;
    top:24px;
}

.form-title{
    font-family:'Playfair Display',serif;
    font-size:20px;
    margin-bottom:24px;
}

.field{
    margin-bottom:18px;
}

.field label{
    display:block;
    font-size:11px;
    letter-spacing:1.5px;
    text-transform:uppercase;
    color:var(--muted);
    margin-bottom:8px;
}

.field input,
.field textarea{
    width:100%;
    padding:12px 16px;
    background:var(--surface2);
    border:1px solid var(--border);
    border-radius:10px;
    color:var(--text);
    font-family:'DM Sans',sans-serif;
    font-size:14px;
    outline:none;
    transition:border-color 0.2s, box-shadow 0.2s;
}

.field input:focus,
.field textarea:focus{
    border-color:rgba(245,166,35,0.6);
    box-shadow:0 0 0 3px rgba(245,166,35,0.08);
}

.field input::placeholder,
.field textarea::placeholder{
    color:var(--muted);
}

.field textarea{
    height:96px;
    resize:none;
}

/* SUBMIT BUTTON */
.submit-btn{
    width:100%;
    padding:15px;
    background:linear-gradient(135deg,var(--accent),var(--accent2));
    border:none;
    border-radius:12px;
    color:#0a0a0f;
    font-family:'DM Sans',sans-serif;
    font-size:15px;
    font-weight:500;
    cursor:pointer;
    letter-spacing:0.3px;
    transition:opacity 0.2s, transform 0.15s;
    margin-top:8px;
}

.submit-btn:hover{
    opacity:0.88;
    transform:translateY(-2px);
}

/* BACK LINK */
.back-link{
    display:flex;
    align-items:center;
    justify-content:center;
    gap:6px;
    margin-top:14px;
    color:var(--muted);
    font-size:13px;
    text-decoration:none;
    transition:color 0.2s;
}

.back-link:hover{color:var(--accent);}

/* RESPONSIVE */
@media(max-width:768px){
    .wrapper{
        flex-direction:column;
        padding:0 20px 60px;
    }

    .form-box{
        width:100%;
        position:static;
    }

    .header{padding:16px 20px;}
    .breadcrumb{padding:14px 20px;}
}

</style>
</head>

<body>

<!-- HEADER -->
<div class="header">
    <div class="logo-ring">
        <img src="images/logo.png" alt="Logo">
    </div>
    <div>
        <div class="brand-name">Aneka Kripik Cap Fajar</div>
        <div class="brand-sub">Camilan Pilihan</div>
    </div>
</div>

<!-- BREADCRUMB -->
<div class="breadcrumb">
    <a href="index.php">Produk</a>
    <span>›</span>
    <span>Checkout</span>
</div>

<div class="wrapper">

    <!-- ORDER SUMMARY -->
    <div class="list">
        <h2 class="section-title">Ringkasan Pesanan</h2>

        <?php if(empty($items)): ?>
        <div class="empty">
            <div class="empty-icon">🛒</div>
            <p>Tidak ada produk yang dipilih.<br>
            <a href="index.php" style="color:var(--accent);text-decoration:none;">Kembali ke toko →</a></p>
        </div>
        <?php endif; ?>

        <?php foreach($items as $item): ?>
        <div class="item">
            <img
                class="item-img"
                src="images/<?php echo htmlspecialchars($item['gambar']); ?>"
                alt="<?php echo htmlspecialchars($item['nama']); ?>">

            <div class="item-info">
                <div class="item-name"><?php echo htmlspecialchars($item['nama']); ?></div>
                <div class="item-price">Rp <?php echo number_format($item['harga'], 0, ',', '.'); ?></div>
                <div class="item-meta">Qty: <?php echo (int)$item['jumlah']; ?> pcs</div>
            </div>

            <div class="item-subtotal">
                <small>Subtotal</small>
                Rp <?php echo number_format($item['subtotal'], 0, ',', '.'); ?>
            </div>
        </div>
        <?php endforeach; ?>

        <?php if(!empty($items)): ?>
        <div class="divider"></div>
        <div class="total-row">
            <div>
                <div class="total-label">Total Pembayaran</div>
                <div style="font-size:12px;color:var(--muted);margin-top:2px;"><?php echo count($items); ?> item</div>
            </div>
            <div class="total-amount">Rp <?php echo number_format($totalSemua, 0, ',', '.'); ?></div>
        </div>
        <?php endif; ?>
    </div>

    <!-- CHECKOUT FORM -->
    <div class="form-box">
        <div class="form-title">Data Pembeli</div>

        <form method="POST">

            <div class="field">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" placeholder="Masukkan nama kamu" required>
            </div>

            <div class="field">
                <label>Alamat Pengiriman</label>
                <textarea name="alamat" placeholder="Tulis alamat lengkap..." required></textarea>
            </div>

            <button type="submit" name="submit" class="submit-btn">
                Konfirmasi Pesanan
            </button>

        </form>

        <a href="javascript:history.back()" class="back-link">
            ← Kembali ke toko
        </a>
    </div>

</div>

</body>
</html>