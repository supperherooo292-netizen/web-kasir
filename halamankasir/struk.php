<?php
session_start();
include '../koneksi.php';

// pastikan hanya kasir yang bisa akses
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'kasir') {
    header('Location: ../login.php');
    exit();
}

// ambil data dari tabel keranjang
$sql = "SELECT k.*, p.stok 
        FROM keranjang k
        JOIN produk p ON k.id_produk = p.id_produk";
$result = mysqli_query($conn, $sql);

$total = 0;
$daftar_produk = [];

while ($row = mysqli_fetch_assoc($result)) {
    $subtotal = $row['harga'] * $row['jumlah'];
    $total += $subtotal;
    $daftar_produk[] = $row;
}

if (count($daftar_produk) == 0) {
    echo "<script>alert('Keranjang kosong!'); window.location='keranjang.php';</script>";
    exit();
}

// Loop untuk mengurangi stok di tabel produk
foreach ($daftar_produk as $item) {
    $id_produk = $item['id_produk'];
    $jumlah = $item['jumlah'];
    $stok_sekarang = $item['stok'];

    // Hitung stok baru
    $stok_baru = $stok_sekarang - $jumlah;
    if ($stok_baru < 0) {
        $stok_baru = 0; // cegah stok minus
    }

    // Update stok di tabel produk
    mysqli_query($conn, "UPDATE produk SET stok = '$stok_baru' WHERE id_produk = '$id_produk'");
}

// jika transaksi selesai dan hapus keranjang
if (isset($_GET['selesai']) && $_GET['selesai'] == 'true') {
    mysqli_query($conn, "DELETE FROM keranjang");
    echo "<script>alert('Transaksi selesai!'); window.location='kasir.php';</script>";
    exit();
}

$tanggal = date('d-m-Y H:i');
$kasir = $_SESSION['nama'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Struk Pembelian</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="struk.css?v=<?php echo time(); ?>">
</head>
<body>
  <h2>TOKO MAKANAN ENAK</h2>
  <div class="center">
    Jl. Mawar No. 45, Bandung<br>
    Telp: 0812-3456-7890
  </div>
  <hr>
  <p>Tanggal : <?= $tanggal ?><br>
     Kasir   : <?= $kasir ?></p>
  <hr>

  <table>
    <tr>
      <td><b>Nama Produk</b></td>
      <td class="right"><b>Qty</b></td>
      <td class="right"><b>Harga</b></td>
      <td class="right"><b>Total</b></td>
    </tr>
    <tr><td colspan="4"><hr></td></tr>

    <?php foreach ($daftar_produk as $item): ?>
      <tr>
        <td><?= htmlspecialchars($item['nama_produk']) ?></td>
        <td class="right"><?= $item['jumlah'] ?></td>
        <td class="right"><?= number_format($item['harga'], 0, ',', '.') ?></td>
        <td class="right"><?= number_format($item['harga'] * $item['jumlah'], 0, ',', '.') ?></td>
      </tr>
    <?php endforeach; ?>

    <tr><td colspan="4"><hr></td></tr>
    <tr>
      <td colspan="3" class="right total">TOTAL BAYAR :</td>
      <td class="right total">Rp<?= number_format($total, 0, ',', '.') ?></td>
    </tr>
  </table>

  <hr>

  <form id="formTunai">
    <label><b>Uang Tunai:</b></label>
    <input type="number" id="tunai" placeholder="Masukkan jumlah uang" required>
  </form>

  <p><b>Total:</b> Rp<?= number_format($total, 0, ',', '.') ?></p>
  <p><b>Tunai:</b> <span id="tampil_tunai">Rp0</span></p>
  <p><b>Kembalian:</b> <span id="kembalian">Rp0</span></p>

  <hr>
  <p class="center">
    Terima kasih atas kunjungannya!<br>
    Barang yang sudah dibeli tidak dapat ditukar.
  </p>

  <button class="print" onclick="window.print();"><i class="fa-solid fa-print"></i> Cetak Struk</button>
  <form method="get">
    <input type="hidden" name="selesai" value="true">
    <button type="submit"><i class="fa-solid fa-check"></i> Selesai & Hapus Keranjang</button>
  </form>

  <script>
    const tunaiInput = document.getElementById('tunai');
    const tampilTunai = document.getElementById('tampil_tunai');
    const kembalian = document.getElementById('kembalian');
    const total = <?= $total ?>;

    tunaiInput.addEventListener('input', () => {
      const tunai = parseInt(tunaiInput.value || 0);
      tampilTunai.textContent = "Rp" + tunai.toLocaleString('id-ID');
      const kembali = tunai - total;
      kembalian.textContent = kembali >= 0 
          ? "Rp" + kembali.toLocaleString('id-ID')
          : "Uang kurang Rp" + Math.abs(kembali).toLocaleString('id-ID');
    });
  </script>
</body>
</html>
