<?php
include "functions.php";

$pencarian = "";

if(isset($_GET['search']) && $_GET['search'] != ""){
    $pencarian = $_GET['search'];
    $stmt = $conn->prepare("SELECT * FROM produk WHERE namaproduk LIKE ?");
    $like = "%$pencarian%";
    $stmt->bind_param("s", $like);
    $stmt->execute();
    $data = $stmt->get_result();
}else{
    $data = tampilProduk();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Aneka Kripik Cap Fajar</title>
<link rel="stylesheet" href="cssnyaindex.css">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
</head>

<body>

<!-- HEADER -->
<div class="header">
    <div class="brand">
        <div class="logo-ring">
            <img src="images/logo.png" alt="Logo Cap Fajar">
        </div>
        <div>
            <div class="brand-name">Aneka Kripik Cap Fajar</div>
            <div class="brand-sub">Camilan Pilihan</div>
        </div>
    </div>
    <div class="cart-badge">
        🛒 Keranjang
        <div class="cart-count" id="cartCount">0</div>
    </div>
</div>

<!-- SEARCH -->
<div class="search-area">
    <form method="GET">
        <div class="search-wrap">
            <input
                type="text"
                name="search"
                placeholder="Cari kripik favorit kamu..."
                value="<?php echo htmlspecialchars($pencarian); ?>">
            <button type="submit" class="search-btn">Cari</button>
        </div>
    </form>
</div>

<div class="section-label">Produk Kami</div>

<!-- PRODUCT FORM -->
<form method="GET" action="beli.php" id="beliForm">

<div class="grid">

<?php $i = 0; while($row = mysqli_fetch_assoc($data)){ ?>

<div class="card" data-harga="<?php echo (int)$row['harga']; ?>">

    <div class="img-wrap">
        <div class="sel-dot">✓</div>
        <img
            class="card-img"
            src="images/<?php echo htmlspecialchars($row['gambar']); ?>"
            alt="<?php echo htmlspecialchars($row['namaproduk']); ?>">
    </div>

    <div class="card-body">
        <div class="card-name"><?php echo htmlspecialchars($row['namaproduk']); ?></div>
        <div class="card-price">Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></div>
        <div class="card-stock">Stok: <?php echo (int)$row['stok']; ?> pcs</div>

        <div class="qty-row">
            <div class="qty-ctrl">
                <button type="button" class="qty-btn btn-min">−</button>
                <span class="qty-num">1</span>
                <button type="button" class="qty-btn btn-plus">+</button>
            </div>
        </div>
    </div>

    <!-- Hidden inputs for form submission -->
    <input type="checkbox"
           class="check-produk"
           name="produk[<?php echo $i; ?>]"
           value="<?php echo htmlspecialchars($row['namaproduk']); ?>">
    <input type="number"
           class="qty-hidden"
           name="jumlah[<?php echo $i; ?>]"
           value="1"
           min="1">
</div>

<?php $i++; } ?>

</div><!-- end .grid -->

<!-- BOTTOM BAR -->
<div class="bottom-bar">
    <div>
        <div class="total-label">Total Belanja</div>
        <div class="total-amount" id="totalHarga">Rp 0</div>
    </div>
    <button type="submit" class="beli-btn" id="beliBtn" disabled>Beli Sekarang</button>
</div>

</form>

<script>
const cards = document.querySelectorAll('.card');

function updateTotal(){
    let total = 0;
    let count = 0;

    cards.forEach(card => {
        const check = card.querySelector('.check-produk');
        const qtyHidden = card.querySelector('.qty-hidden');
        const harga = parseInt(card.dataset.harga);

        if(check.checked){
            const qty = parseInt(qtyHidden.value) || 1;
            total += harga * qty;
            count++;
        }
    });

    document.getElementById('totalHarga').textContent =
        'Rp ' + total.toLocaleString('id-ID');
    document.getElementById('cartCount').textContent = count;
    document.getElementById('beliBtn').disabled = count === 0;
}

cards.forEach(card => {
    const checkbox  = card.querySelector('.check-produk');
    const qtyHidden = card.querySelector('.qty-hidden');
    const qtyNum    = card.querySelector('.qty-num');
    const btnMin    = card.querySelector('.btn-min');
    const btnPlus   = card.querySelector('.btn-plus');

    // Click card to toggle select
    card.addEventListener('click', function(e){
        if(e.target.classList.contains('qty-btn')) return;

        checkbox.checked = !checkbox.checked;

        if(checkbox.checked){
            card.classList.add('selected');
        } else {
            card.classList.remove('selected');
        }
        updateTotal();
    });

    // Minus button
    btnMin.addEventListener('click', function(e){
        e.stopPropagation();
        let v = parseInt(qtyHidden.value) || 1;
        if(v > 1){
            v--;
            qtyHidden.value = v;
            qtyNum.textContent = v;
            updateTotal();
        }
    });

    // Plus button
    btnPlus.addEventListener('click', function(e){
        e.stopPropagation();
        let v = parseInt(qtyHidden.value) || 1;
        v++;
        qtyHidden.value = v;
        qtyNum.textContent = v;
        updateTotal();
    });
});
</script>

</body>
</html>