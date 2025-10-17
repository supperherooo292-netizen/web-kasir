<?php
session_start();
include '../koneksi.php';

// pastikan hanya admin yang bisa akses
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

// ambil data user berdasarkan id dari URL
if (isset($_GET['id'])) {
    $id_user = $_GET['id'];
    $query = "SELECT * FROM users WHERE id_user = '$id_user'";
    $result = mysqli_query($conn, $query);
    $edit = mysqli_fetch_assoc($result);
}

// proses update data user
if (isset($_POST['btn-edit'])) {
    $id_user = $_POST['id_user'];
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    $no_telp = $_POST['no_telp'];

    // kalau password baru diisi, ubah ke md5, kalau tidak, pakai yang lama
    if (!empty($password)) {
        $password_md5 = md5($password);
    } else {
        $result = mysqli_query($conn, "SELECT pasword FROM users WHERE id_user = '$id_user'");
        $row = mysqli_fetch_assoc($result);
        $password_md5 = $row['pasword'];
    }

    // update ke database
    $sql = "UPDATE users 
            SET nama='$nama', username='$username', pasword='$password_md5', role='$role', no_telp='$no_telp' 
            WHERE id_user='$id_user'";
    $update = mysqli_query($conn, $sql);

    if ($update) {
        echo "<script>alert('Data akun berhasil diperbarui!'); window.location='daftar-akun.php';</script>";
    } else {
        echo "Gagal memperbarui: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Halaman Pertama</title>
  <link rel="stylesheet" href="edit-akun.css?v=<?php echo time(); ?>">
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

      <div class="content">
        <h2>EDIT AKUN</h2>
        <form method="POST" action="">
          <input type="hidden" name="id_user" value="<?php echo $edit['id_user']; ?>">

          <label>Nama:</label><br>
          <input type="text" name="nama" value="<?php echo $edit['nama']; ?>" required><br><br>

          <label>Username:</label><br>
          <input type="text" name="username" value="<?php echo $edit['username']; ?>" required><br><br>

          <label>Password (kosongkan jika tidak ingin diubah):</label><br>
          <input type="password" name="password" placeholder="Masukkan password baru"><br><br>

          <label>Role:</label><br>
          <select name="role" required>
            <option value="admin" <?php if ($edit['role'] == 'admin') echo 'selected'; ?>>Admin</option>
            <option value="kasir" <?php if ($edit['role'] == 'kasir') echo 'selected'; ?>>Kasir</option>
          </select><br><br>

          <label>No. Telepon:</label><br>
          <input type="text" name="no_telp" value="<?php echo $edit['no_telp']; ?>" required><br><br>

          <button type="submit" name="btn-edit">Simpan Perubahan</button>
        </form>
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
  </div>

  <script src="proses.js?v=<?php echo time(); ?>"></script>
</body>
</html>
