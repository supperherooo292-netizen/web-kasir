<?php

include '../koneksi.php';
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'kasir') {
    header('Location: ../login.php');
    exit();
}

// =============================
// Tambah ke keranjang dengan pembatas stok
// =============================
if (isset($_POST['tambah_keranjang'])) {
    $id_produk = $_POST['id_produk'];

    // Ambil data produk berdasarkan id
    $query = mysqli_query($conn, "SELECT * FROM produk WHERE id_produk='$id_produk'");
    $produk = mysqli_fetch_assoc($query);

    if ($produk) {
        $gambar = $produk['gambar'];
        $tipe = $produk['tipe'];
        $nama = $produk['nama'];
        $id_kategori = $produk['id_kategori'];
        $harga = $produk['harga'];
        $stok = $produk['stok'];
        $jumlah = 1; // default 1 jika baru ditambah

        // Cek apakah produk sudah ada di keranjang
        $cek = mysqli_query($conn, "SELECT * FROM keranjang WHERE id_produk='$id_produk'");
        $dataKeranjang = mysqli_fetch_assoc($cek);

        if ($dataKeranjang) {
            $jumlahSekarang = $dataKeranjang['jumlah'];

            if ($jumlahSekarang >= $stok) {
                echo "<script>alert('Stok produk \"$nama\" tidak mencukupi!'); window.location.href='kasir.php';</script>";
                exit;
            } else {
                // Tambah jumlah selama belum melebihi stok
                mysqli_query($conn, "UPDATE keranjang SET jumlah = jumlah + 1 WHERE id_produk='$id_produk'");
            }
        } else {
            // Tambah baru ke keranjang (hanya kalau stok masih ada)
            if ($stok > 0) {
                $stmt = $conn->prepare("INSERT INTO keranjang (id_produk, gambar, tipe, nama_produk, id_kategori, harga, jumlah) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ibsssii", $id_produk, $gambar, $tipe, $nama, $id_kategori, $harga, $jumlah);
                $stmt->send_long_data(1, $gambar);
                $stmt->execute();
                $stmt->close();
            } else {
                echo "<script>alert('Stok produk \"$nama\" habis!'); window.location.href='kasir.php';</script>";
                exit;
            }
        }
    }
}


$sql = "SELECT p.id_produk, p.gambar, p.nama, k.kategori, p.stok, p.harga, p.tipe 
        FROM produk p 
        JOIN kategori k ON p.id_kategori = k.id_kategori 
        WHERE k.kategori = 'Makanan'";
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
        <button onclick="window.location.href='keranjang.php'">Keranjang</button>
        <button onclick="showprofil()">Profil</button>
        <button onclick="logout()">Log Out</button>
      </div>

      <div class="container-produk">
        <div class="produk-head">
            <h2>List Produk</h2>

            <div class="dropdown">
              <button onclick="toggleDropdown()" class="dropdown-btn">Menu ▼</button>
              <div id="dropdownMenu" class="dropdown-content">
                <a href="kasir.php">Semua Produk</a>
                <a href="makanan.php">Makanan</a>
                <a href="minuman.php">Minuman</a>
              </div>
            </div>

            <div class="search-produk">
              <form action="" method="GET">
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
            <h3>stok : <?php echo number_format($row['stok']) ?></h3>
            <form action="" method="POST">
                  <input type="hidden" name="id_produk" value="<?= $row['id_produk']; ?>">
                  <button class="btnkeranjang" type="submit" name="tambah_keranjang"><i class="fa-solid fa-cart-shopping"></i></button>
            </form>
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
