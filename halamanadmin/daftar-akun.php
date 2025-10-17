<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

$sql = "SELECT * FROM users";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Halaman Pertama</title>
  <link rel="stylesheet" href="daftar-akun.css?v=<?php echo time();?>">
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
          <div style="display: flex;">
            <h2 style="margin-right:10px;">Daftar Akun</h2>
          </div>
          <button onclick="showtambah()" class="btntambah">Tambah Akun</button>
        </div>

        <table class="tabel">
          <tr>
            <th>ID Users</th>
            <th>Nama</th>
            <th>Username</th>
            <th>Role</th>
            <th>No Telepone</th>
            <th>Hapus</th>
            <th>Edit</th>
          </tr>

          <?php
          $no = 1;
          while ($row = mysqli_fetch_assoc($result)) :
          ?>
            <tr>
              <td><?= $row['id_user'] ?></td>
              <td><?= $row['nama'] ?></td>
              <td><?= $row['username'] ?></td>
              <td><?= $row['role'] ?></td>
              <td><?= $row['no_telp'] ?></td>
              <td>
                <form action="proses.php" method="POST" style="display:inline;">
                  <input type="hidden" name="id_user" value="<?= $row['id_user'] ?>">
                  <button class="btnhapus" type="submit" name="hapus-akun" onclick="return confirm('Yakin ingin menghapus akun ini?')">Hapus</button>
                </form>
              </td>
              <td>
                <button class="btnedit" type="submit" name="btn-edit" onclick="window.location.href='edit-akun.php?id=<?= $row['id_user']; ?>'">Edit</button>
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

  <!-- TAMBAH AKUN -->
  <div id="tambahbackdrop" class="tambahbackdrop" role="dialog" aria-modal="true" aria-hidden="true">
    <div class="tambahproduk" role="document">
      <div class="tambah-header">
        <h3>Tambah Akun</h3>
        <button onclick="closetambah()"><i class="fa-solid fa-xmark"></i></button>
      </div>

      <form action="proses.php" method="POST" enctype="multipart/form-data">
        <input type="number" name="id_user" placeholder="ID User" required>
        <input type="text" name="nama" placeholder="Nama" required>
        <input type="text" name="username" placeholder="Username" required>
        <input type="text" name="password" placeholder="Password" required>
        <input type="text" name="role" placeholder="Role" required>
        <input type="number" name="no_telp" placeholder="No Telepone" required>
        <button class="btn" type="submit" name="tambah-akun">Tambah Akun</button>
      </form>
    </div>
  </div>

  <script src="proses.js?v=<?php echo time(); ?>"></script>
</body>
</html>
