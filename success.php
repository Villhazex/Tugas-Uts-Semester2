<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pesanan Berhasil</title>

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
    display:flex;
    align-items:center;
    justify-content:center;
    min-height:100vh;
}

/* CARD */
.success-box{
    background:var(--surface);
    border:1px solid var(--border);
    border-radius:20px;
    padding:40px 32px;
    text-align:center;
    max-width:420px;
    width:100%;
}

/* ICON */
.success-icon{
    width:80px;
    height:80px;
    border-radius:50%;
    background:linear-gradient(135deg,var(--accent),var(--accent2));
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:36px;
    color:#0a0a0f;
    margin:0 auto 20px;
}

/* TITLE */
.success-title{
    font-family:'Playfair Display',serif;
    font-size:24px;
    margin-bottom:10px;
}

/* TEXT */
.success-text{
    font-size:14px;
    color:var(--muted);
    line-height:1.6;
    margin-bottom:28px;
}

/* BUTTON */
.btn{
    display:inline-block;
    padding:12px 20px;
    border-radius:10px;
    text-decoration:none;
    font-size:14px;
    font-weight:500;
    transition:0.2s;
}

/* PRIMARY BUTTON */
.btn-primary{
    background:linear-gradient(135deg,var(--accent),var(--accent2));
    color:#0a0a0f;
}

.btn-primary:hover{
    opacity:0.85;
    transform:translateY(-2px);
}

/* SECOND BUTTON */
.btn-secondary{
    color:var(--muted);
    margin-left:10px;
}

.btn-secondary:hover{
    color:var(--accent);
}

</style>
</head>

<body>

<div class="success-box">

    <div class="success-icon">✓</div>

    <div class="success-title">Pesanan Berhasil!</div>

    <div class="success-text">
        Terima kasih sudah berbelanja di <br>
        <strong>Aneka Kripik Cap Fajar</strong>.<br>
        Pesanan kamu sedang diproses.
    </div>

    <a href="index.php" class="btn btn-primary">
        Kembali ke Toko
    </a>

    <!-- <a href="index.php" class="btn btn-secondary">
        Belanja Lagi
    </a> -->

</div>

</body>
</html>