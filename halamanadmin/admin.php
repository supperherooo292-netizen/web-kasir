<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
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
  <link rel="stylesheet" href="admin.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
  <div>
    <nav class="navbar">
      <h1>Toko Makanan</h1>
    </nav>

    <div class="container">
      <div class="sidebar">
        <button onclick="window.location.href='admin.php'">Dashboard</button>
        <button onclick="window.location.href='daftar-akun.php'">Daftar Akun</button>
        <button onclick="showprofil()">Profil</button>
        <button onclick="logout()">Log Out</button>
      </div>

      <div class="produk">
        <div class="produk-head">
          <div style="display: flex; justify-content: space-between; width: 100%; align-items: center;">
            <div style="display: flex;">
              <h2 style="margin-right:10px;">List Produk</h2>

              <div class="dropdown">
                <button onclick="toggleDropdown()" class="dropdown-btn">Menu ▼</button>
                <div id="dropdownMenu" class="dropdown-content">
                  <a href="admin.php">Semua Produk</a>
                  <a href="makanan.php">Makanan</a>
                  <a href="minuman.php">Minuman</a>
                </div>
              </div>
            </div>

            <div class="search-produk">
              <form action="search.php" method="GET">
                <input type="text" name="query" placeholder="Cari produk..." required>
                <button type="submit"><i class="fa fa-search"></i></button>
              </form>
            </div>

            <button onclick="showtambah()" class="btntambah">Tambah Produk</button>
          </div>
        </div>

        <table class="tabel">
          <tr>
            <th>ID Produk</th>
            <th>Gambar</th>
            <th>Nama</th>
            <th>Kategori</th>
            <th>Stok</th>
            <th>Harga</th>
            <th>Hapus</th>
            <th>Edit</th>
          </tr>

          <?php while ($row = mysqli_fetch_assoc($result)) : ?>
            <tr>
              <td><?= $row['id_produk'] ?></td>
              <td>
                <?php if (!empty($row['gambar'])): ?>
                  <img src="data:<?= !empty($row['tipe']) ? $row['tipe'] : 'image/jpeg' ?>;base64,<?= base64_encode($row['gambar']) ?>" alt="Gambar Produk">
                <?php else: ?>
                  Tidak ada gambar
                <?php endif; ?>
              </td>
              <td><?= $row['nama'] ?></td>
              <td><?= $row['kategori'] ?></td>
              <td><?= $row['stok'] ?></td>
              <td>Rp<?= $row['harga'] ?></td>
              <td>
                <form action="proses.php" method="POST" style="display:inline;">
                  <input type="hidden" name="id_produk" value="<?= $row['id_produk'] ?>">
                  <button class="btnhapus" type="submit" name="hapus" onclick="return confirm('Yakin ingin menghapus produk ini?')">Hapus</button>
                </form>
              </td>
              <td>
                <button class="btnedit" type="submit" name="btn-edit" onclick="window.location.href='edit-produk.php?id=<?= $row['id_produk']; ?>'">Edit</button>
              </td>
            </tr>
          <?php endwhile; ?>
        </table>
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
