<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'kasir') {
    header('Location: ../login.php');
    exit();
}

$sql = "CALL tampilkan_semua_produk()";
$result = mysqli_query($conn, $sql);
mysqli_next_result($conn);
$kategori = mysqli_query($conn, "SELECT * FROM kategori;");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Halaman Pertama</title>
  <link rel="stylesheet" href="kasir.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
  <div>
    <nav class="navbar">
      <h1>Toko Makanan</h1>
    </nav>

    <div class="container">
      <div class="sidebar">
        <button onclick="window.location.href='kasir.php'">Dashboard</button>
        <button >Keranjang</button>
        <button onclick="showprofil()">Profil</button>
        <button onclick="logout()">Log Out</button>
      </div>

      <div class="container-produk">
        <div class="produk-head">
            <h2>List Produk</h2>

            <div class="dropdown">
              <button onclick="toggleDropdown()" class="dropdown-btn">Menu ▼</button>
              <div id="dropdownMenu" class="dropdown-content">
                <a href="admin.php">Semua Produk</a>
                <a href="makanan.php">Makanan</a>
                <a href="minuman.php">Minuman</a>
              </div>
            </div>

            <div class="search-produk">
              <form action="search.php" method="GET">
                <input type="text" name="query" placeholder="Cari produk..." required>
                <button type="submit"><i class="fa fa-search"></i></button>
              </form>
            </div>    
        </div>

        <div class="produk">
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
      </div>
    </div>
  </div>

  <!-- PROFIL ADMIN -->
  <div id="profilbackdrop" class="profilbackdrop" role="dialog" aria-modal="true" aria-hidden="true">
    <div class="profil" role="document">
      <h3>Profil Admin</h3>
      <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Login Illustration">
      <p>Nama : <?= $_SESSION['nama'] ?></p>
      <p>Username : <?= $_SESSION['username'] ?></p>
      <p>Role : <?= $_SESSION['role'] ?></p>
      <p>No. Telp : <?= $_SESSION['no_telp'] ?></p>
      <button class="btn" onclick="closeprofil()">Tutup</button>
    </div>
  </div>

  <script src="proses.js?v=<?php echo time(); ?>"></script>
</body>
</html>
