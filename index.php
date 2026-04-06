<?php
include "functions.php";

// SEARCH
$pencarian = "";

if(isset($_GET['search']) && $_GET['search'] != ""){
    $pencarian = $_GET['search'];
    // Prepared statement — aman dari SQL Injection
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

/* RESET */
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
    padding:20px 32px;
    display:flex;
    align-items:center;
    justify-content:space-between;
    position:sticky;
    top:0;
    z-index:100;
}

.brand{
    display:flex;
    align-items:center;
    gap:14px;
}

.logo-ring{
    width:50px;
    height:50px;
    border-radius:50%;
    background:linear-gradient(135deg,var(--accent),var(--accent2));
    display:flex;
    align-items:center;
    justify-content:center;
    overflow:hidden;
}

.logo-ring img{
    width:100%;
    height:100%;
    object-fit:cover;
}

.brand-name{
    font-family:'Playfair Display',serif;
    font-size:19px;
    letter-spacing:0.5px;
    color:var(--text);
}

.brand-sub{
    font-size:11px;
    color:var(--muted);
    letter-spacing:2px;
    text-transform:uppercase;
    margin-top:2px;
}

.cart-badge{
    background:var(--surface2);
    border:1px solid var(--border);
    border-radius:12px;
    padding:8px 18px;
    font-size:13px;
    display:flex;
    align-items:center;
    gap:8px;
    color:var(--muted);
}

.cart-count{
    background:var(--accent);
    color:#0a0a0f;
    border-radius:50%;
    width:22px;
    height:22px;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:11px;
    font-weight:500;
}

/* SEARCH */
.search-area{
    padding:28px 32px 0;
}

.search-wrap{
    background:var(--surface2);
    border:1px solid var(--border);
    border-radius:40px;
    display:flex;
    align-items:center;
    padding:4px 4px 4px 22px;
    max-width:520px;
    transition:border-color 0.2s;
}

.search-wrap:focus-within{
    border-color:rgba(245,166,35,0.5);
}

.search-wrap input{
    flex:1;
    background:none;
    border:none;
    outline:none;
    color:var(--text);
    font-family:'DM Sans',sans-serif;
    font-size:14px;
    padding:8px 0;
}

.search-wrap input::placeholder{
    color:var(--muted);
}

.search-btn{
    background:linear-gradient(135deg,var(--accent),var(--accent2));
    border:none;
    color:#0a0a0f;
    font-family:'DM Sans',sans-serif;
    font-size:13px;
    font-weight:500;
    padding:10px 22px;
    border-radius:36px;
    cursor:pointer;
    letter-spacing:0.3px;
    transition:opacity 0.2s;
}

.search-btn:hover{
    opacity:0.85;
}

/* SECTION LABEL */
.section-label{
    padding:24px 32px 0;
    font-size:11px;
    letter-spacing:3px;
    text-transform:uppercase;
    color:var(--muted);
}

/* GRID */
.grid{
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(210px,1fr));
    gap:18px;
    padding:16px 32px 140px;
}

/* CARD */
.card{
    background:var(--surface);
    border:1px solid var(--border);
    border-radius:16px;
    overflow:hidden;
    cursor:pointer;
    transition:all 0.25s;
    position:relative;
}

.card:hover{
    border-color:rgba(245,166,35,0.35);
    transform:translateY(-4px);
}

.card.selected{
    border-color:var(--accent);
    background:var(--surface2);
}

/* Hidden checkbox (state only) */
.card input[type=checkbox]{display:none;}

/* CARD IMAGE */
.card-img{
    width:100%;
    height:155px;
    object-fit:cover;
    display:block;
    transition:transform 0.35s;
}

.card:hover .card-img{
    transform:scale(1.05);
}

.img-wrap{
    overflow:hidden;
    position:relative;
}

.img-wrap::after{
    content:'';
    position:absolute;
    bottom:0;left:0;right:0;
    height:50px;
    background:linear-gradient(transparent,var(--surface));
    pointer-events:none;
    transition:background 0.25s;
}

.card.selected .img-wrap::after{
    background:linear-gradient(transparent,var(--surface2));
}

/* Selected badge */
.sel-dot{
    position:absolute;
    top:10px;right:10px;
    width:24px;height:24px;
    border-radius:50%;
    background:var(--accent);
    color:#0a0a0f;
    font-size:12px;
    font-weight:700;
    display:none;
    align-items:center;
    justify-content:center;
    z-index:2;
}

.card.selected .sel-dot{
    display:flex;
}

/* CARD BODY */
.card-body{
    padding:14px 16px 16px;
}

.card-name{
    font-size:14px;
    font-weight:500;
    margin-bottom:6px;
    line-height:1.35;
}

.card-price{
    font-family:'Playfair Display',serif;
    font-size:18px;
    color:var(--accent);
}

.card-stock{
    font-size:11px;
    color:var(--muted);
    margin-top:4px;
    letter-spacing:0.3px;
}

/* QTY CONTROL */
.qty-row{
    display:flex;
    align-items:center;
    justify-content:flex-end;
    margin-top:12px;
}

.qty-ctrl{
    display:flex;
    align-items:center;
    gap:8px;
    opacity:0.25;
    pointer-events:none;
    transition:opacity 0.2s;
}

.card.selected .qty-ctrl{
    opacity:1;
    pointer-events:all;
}

.qty-btn{
    width:28px;height:28px;
    border-radius:50%;
    border:1px solid var(--border);
    background:none;
    color:var(--text);
    font-size:16px;
    cursor:pointer;
    display:flex;align-items:center;justify-content:center;
    transition:all 0.15s;
    line-height:1;
}

.qty-btn:hover{
    background:var(--accent);
    color:#0a0a0f;
    border-color:var(--accent);
}

.qty-num{
    font-size:14px;
    font-weight:500;
    min-width:22px;
    text-align:center;
}

/* Hidden number input for form submission */
.qty-hidden{
    display:none;
}

/* BOTTOM BAR */
.bottom-bar{
    position:fixed;
    bottom:0;left:0;right:0;
    background:var(--surface);
    border-top:1px solid var(--border);
    padding:16px 32px;
    display:flex;
    align-items:center;
    justify-content:space-between;
    z-index:100;
}

.total-label{
    font-size:12px;
    color:var(--muted);
    letter-spacing:0.5px;
    text-transform:uppercase;
}

.total-amount{
    font-family:'Playfair Display',serif;
    font-size:22px;
    color:var(--accent);
    margin-top:2px;
}

.beli-btn{
    background:linear-gradient(135deg,var(--accent),var(--accent2));
    color:#0a0a0f;
    border:none;
    font-family:'DM Sans',sans-serif;
    font-size:14px;
    font-weight:500;
    padding:14px 32px;
    border-radius:30px;
    cursor:pointer;
    letter-spacing:0.3px;
    transition:opacity 0.2s, transform 0.15s;
}

.beli-btn:hover{
    opacity:0.88;
    transform:scale(1.03);
}

.beli-btn:disabled{
    opacity:0.25;
    cursor:default;
    transform:none;
}

/* RESPONSIVE */
@media(max-width:768px){
    .header{padding:16px 20px;}
    .brand-name{font-size:16px;}
    .search-area{padding:20px 20px 0;}
    .section-label{padding:18px 20px 0;}
    .grid{grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:14px;padding:12px 20px 130px;}
    .bottom-bar{padding:14px 20px;}
    .total-amount{font-size:18px;}
}

</style>
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