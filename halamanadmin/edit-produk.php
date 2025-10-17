<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

// ambil data produk lama berdasarkan id di URL
if (isset($_GET['id'])) {
    $id_produk = $_GET['id'];
    $query = "SELECT * FROM produk WHERE id_produk = '$id_produk'";
    $result = mysqli_query($conn, $query);
    $edit = mysqli_fetch_assoc($result);
}

if (isset($_POST['btn-edit'])) {
    $id_produk = $_POST['id_produk'];
    $nama = $_POST['nama'];
    $kategori = $_POST['kategori'];
    $stok = $_POST['stok'];
    $harga = $_POST['harga'];

    // Jika upload gambar baru
    if (is_uploaded_file($_FILES['gambar']['tmp_name'])) {
        $gambar = file_get_contents($_FILES['gambar']['tmp_name']);
        // Debug (hapus nanti)
        // echo "<script>alert('Gambar baru diupload');</script>";
    } else {
        // Gambar tidak diupload, ambil dari database
        $result = mysqli_query($conn, "SELECT gambar FROM produk WHERE id_produk = '$id_produk'");
        $row = mysqli_fetch_assoc($result);
        $gambar = $row['gambar'];
        // Debug (hapus nanti)
        // echo "<script>alert('Gambar lama dipertahankan');</script>";
    }

    // Hapus query CALL, langsung pakai prepared statement (tanpa stored procedure)
    $sql = "UPDATE produk SET gambar=?, nama=?, id_kategori=?, stok=?, harga=? WHERE id_produk=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssiiii", $gambar, $nama, $kategori, $stok, $harga, $id_produk);
    mysqli_stmt_send_long_data($stmt, 0, $gambar); // kirim data blob
    $edit = mysqli_stmt_execute($stmt);

    if ($edit) {
        echo "<script>alert('Data produk berhasil diperbarui!'); window.location='admin.php';</script>";
    } else {
        echo "Gagal memperbarui: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>halaman pertama</title>
    <link rel="stylesheet" href="edit-produk.css?v=<?php echo time();?>">
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
                <h2>EDIT PRODUK</h2>
                <form method="POST" action="" enctype="multipart/form-data">
                    <input type="hidden" name="id_produk" value="<?php echo $edit['id_produk']; ?>">

                    <label>Nama Produk:</label><br>
                    <input type="text" name="nama" value="<?php echo $edit['nama']; ?>" required><br><br>

                    <label>Kategori:</label><br>
                    <select name="kategori" required>
                        <option value="1" <?php if ($edit['id_kategori'] == 1) echo 'selected'; ?>>Makanan</option>
                        <option value="2" <?php if ($edit['id_kategori'] == 2) echo 'selected'; ?>>Minuman</option>
                    </select><br><br>

                    <label>Stok:</label><br>
                    <input type="number" name="stok" value="<?php echo $edit['stok']; ?>" required><br><br>

                    <label>Harga:</label><br>
                    <input type="number" name="harga" value="<?php echo $edit['harga']; ?>" required><br><br>

                    <label>Gambar Produk:</label><br>
                    <input type="file" name="gambar" accept="image/*"><br><br>

                    <button type="submit" name="btn-edit">Simpan Perubahan</button>
                </form>
            </div>
        </div>

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
