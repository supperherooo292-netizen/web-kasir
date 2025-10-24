<?php
include '../koneksi.php';
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'kasir') {
    header('Location: ../login.php');
    exit();
}

// ===============================
// HAPUS PESANAN DARI KERANJANG
// ===============================
if (isset($_POST['hapus_pesanan'])) {
    $id_produk = $_POST['id_produk'];

    $sql = "DELETE FROM keranjang WHERE id_produk = '$id_produk'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        echo "<script>alert('✅ Pesanan berhasil dihapus dari keranjang'); window.location='keranjang.php';</script>";
    } else {
        echo "<script>alert('❌ Gagal menghapus pesanan: " . mysqli_error($conn) . "'); window.location='keranjang.php';</script>";
    }
}
// ===============================
// UPDATE JUMLAH PRODUK DENGAN CEK STOK
// ===============================
elseif (isset($_POST['update_jumlah'])) {
    $id_produk = $_POST['id_produk'];
    $jumlah = intval($_POST['jumlah']);

    if ($jumlah < 1) {
        $jumlah = 1;
    }

    // Ambil stok dari tabel produk
    $stok_query = mysqli_query($conn, "SELECT stok FROM produk WHERE id_produk = '$id_produk'");
    $stok_data = mysqli_fetch_assoc($stok_query);

    if (!$stok_data) {
        echo "<script>
                alert('❌ Produk tidak ditemukan!');
                window.location.href='keranjang.php';
              </script>";
        exit();
    }

    $stok_tersedia = intval($stok_data['stok']);

    // Validasi stok
    if ($jumlah > $stok_tersedia) {
        echo "<script>
                alert('⚠️ Jumlah melebihi stok tersedia ($stok_tersedia)!');
                window.location.href='keranjang.php';
              </script>";
        exit();
    }

    // Update jumlah jika valid
    $update = mysqli_query($conn, "UPDATE keranjang SET jumlah = '$jumlah' WHERE id_produk = '$id_produk'");

    if ($update) {
        echo "<script>
                alert('✅ Jumlah berhasil diperbarui!');
                window.location.href='keranjang.php';
              </script>";
    } else {
        echo "<script>
                alert('❌ Gagal memperbarui jumlah!');
                window.location.href='keranjang.php';
              </script>";
    }
}

// Jika tidak ada aksi apapun
else {
    header("Location: keranjang.php");
    exit();
}
?>
