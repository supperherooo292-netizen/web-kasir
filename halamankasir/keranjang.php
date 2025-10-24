<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'kasir') {
  header('Location: ../login.php');
  exit();
}

$sql = "SELECT * FROM keranjang";
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
  <link rel="stylesheet" href="keranjang.css?v=<?php echo time(); ?>">
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

      <div class="produk">
        <div class="produk-head">
          <div style="display: flex; justify-content: space-between; width: 100%; align-items: center;">
              <h2 style="margin-right:10px;">Keranjang<i class="fa-solid fa-cart-shopping"></i></h2>
          </div>
        </div>

        <table class="tabel">
          <tr>
            <th>No</th>
            <th>Gambar</th>
            <th>Nama</th>
            <th>Kategori</th>
            <th>qty</th>
            <th>Harga</th>
            <th>Hapus</th>
          </tr>
          <?php 
          $no = 1;
          while ($row = mysqli_fetch_assoc($result)) : 
          ?>
            <tr>
              <td><?= $no++?></td>
              <td>
                <?php if (!empty($row['gambar'])): ?>
                  <img src="data:<?= !empty($row['tipe']) ? $row['tipe'] : 'image/jpeg' ?>;base64,<?= base64_encode($row['gambar']) ?>" alt="Gambar Produk">
                <?php else: ?>
                  Tidak ada gambar
                <?php endif; ?>
              </td>
              <td><?= $row['nama_produk'] ?></td>
              <td><?= $row['id_kategori'] ?></td>
              
              <!-- Form Edit Jumlah -->
              <td>
              <form action="proses.php" method="POST" class="edit">
                <input type="hidden" name="id_produk" value="<?= $row['id_produk'] ?>">
                <input type="number" name="jumlah" value="<?= $row['jumlah'] ?>" min="1" class="jumlah">
                <button type="submit" name="update_jumlah" class="btnedit">Update</button>
              </form>
              </td>
              <td>Rp<?= $row['harga'] ?></td>
              <td>
              <form action="proses.php?v=<?php echo time(); ?>" method="POST" onsubmit="return confirm('Yakin ingin menghapus pesanan ini?')">
              <input type="hidden" name="id_produk" value="<?= $row['id_produk'] ?>">
              <button class="btnhapus" type="submit" name="hapus_pesanan">Hapus</button>
              </form>
              </td>
            </tr>
          <?php endwhile; ?>
        </table>
        <button class="btnbeli" onclick="konfirmasiBeli()">Buy</button>
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

  <!-- TAMBAH PRODUK -->
  <div id="tambahbackdrop" class="tambahbackdrop" role="dialog" aria-modal="true" aria-hidden="true">
    <div class="tambahproduk" role="document">
      <div class="tambah-header">
        <h3>Tambah Produk</h3>
        <button onclick="closetambah()"><i class="fa-solid fa-xmark"></i></button>
      </div>

      <form action="proses.php" method="POST" enctype="multipart/form-data">
        <input type="text" name="id_produk" placeholder="ID Produk" required>
        <input type="file" name="gambar" placeholder="Gambar Produk" required>
        <input type="text" name="nama" placeholder="Nama Produk" required>

        <select name="id_kategori" required>
          <option value="">-- Pilih Kategori --</option>
          <?php while ($row = mysqli_fetch_assoc($kategori)): ?>
            <option value="<?= $row['id_kategori'] ?>"><?= $row['kategori'] ?></option>
          <?php endwhile; ?>
        </select>

        <input type="number" name="stok" placeholder="Stok Produk" required>
        <input type="number" name="harga" placeholder="Harga Produk" required>
        <button class="btn" type="submit" name="tambah">Tambah Produk</button>
      </form>
    </div>
  </div>

  <script src="proses.js?v=<?php echo time(); ?>"></script>
</body>
</html>
