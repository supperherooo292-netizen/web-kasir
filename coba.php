<?php
include 'koneksi.php';

// ambil data produk
$query = "SELECT * FROM produk";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Daftar Produk</title>
<style>
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f5f5f5;
        margin: 0;
        padding: 40px;
    }

    h2 {
        text-align: center;
        margin-bottom: 40px;
        color: #333;
    }

    .container {
        display: grid;
        grid-template-columns: repeat(4, 1fr); /* 4 kolom per baris */
        gap: 25px;
        justify-items: center;
    }

    .card {
        background: #fff;
        border-radius: 15px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        text-align: center;
        transition: transform 0.2s, box-shadow 0.2s;
        width: 100%;
        max-width: 250px;
    }

    .card:hover {
        transform: translateY(-6px);
        box-shadow: 0 6px 18px rgba(0,0,0,0.15);
    }

    .card img {
        width: 100%;
        height: 170px;
        object-fit: cover;
    }

    .card h3 {
        font-size: 1.1em;
        color: #333;
        margin: 10px 0 5px 0;
    }

    .card p {
        font-weight: bold;
        color: #e91e63;
        margin-bottom: 15px;
    }
</style>
</head>
<body>

<h2>Daftar Produk</h2>

<div class="container">
    <?php while($row = mysqli_fetch_assoc($result)): ?>
        <div class="card">
            <?php 
            // jika kolom gambar bertipe LONGBLOB
            $imgData = base64_encode($row['gambar']);
            echo "<img src='data:image/jpeg;base64,{$imgData}' alt='Gambar Produk'>";
            ?>
            <h3><?php echo htmlspecialchars($row['nama']); ?></h3>
            <p>Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></p>
        </div>
    <?php endwhile; ?>
</div>

</body>
</html>
