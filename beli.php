<?php
include "functions.php";

$produkDipilih = $_GET['produk'] ?? [];
$jumlahDipilih = $_GET['jumlah'] ?? [];

$items = [];

foreach($produkDipilih as $i => $namaProduk){

    $jumlah = (int)$jumlahDipilih[$i];
    if($jumlah <= 0) continue;

    // ambil data produk
    $stmt = $conn->prepare("SELECT * FROM produk WHERE namaproduk=?");
    $stmt->bind_param("s",$namaProduk);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();

    if(!$row) continue;

    // hitung subtotal menggunakan function
    $subtotal = hitungSubtotal($row['harga'],$jumlah);

    $items[] = [
        'nama'     => $namaProduk,
        'harga'    => $row['harga'],
        'jumlah'   => $jumlah,
        'stok'     => $row['stok'],
        'gambar'   => $row['gambar'],
        'subtotal' => $subtotal
    ];
}

// hitung total semua
$totalSemua = hitungTotal($items);


if(isset($_POST['submit'])){

    $nama   = htmlspecialchars(trim($_POST['nama']));
    $alamat = htmlspecialchars(trim($_POST['alamat']));

    foreach($items as $item){

        // cek stok
        if($item['jumlah'] > $item['stok']){
            echo "<script>alert('Stok tidak cukup untuk ".$item['nama']."');</script>";
            exit;
        }

        // update stok
        $stokBaru = $item['stok'] - $item['jumlah'];

        $stmt = $conn->prepare("UPDATE produk SET stok=? WHERE namaproduk=?");
        $stmt->bind_param("is",$stokBaru,$item['nama']);
        $stmt->execute();

        // simpan pesanan
        tambahPesanan($nama,$alamat,$item['nama'],$item['jumlah']);
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
<link rel="stylesheet" href="cssnyabeli.css">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
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