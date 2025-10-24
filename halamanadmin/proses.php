<?php
include "../koneksi.php";

if (isset($_POST['tambah'])) {
    $id_produk = $_POST['id_produk'];
    $nama = $_POST['nama'];
    $id_kategori = $_POST['id_kategori'];
    $stok = $_POST['stok'];
    $harga = $_POST['harga'];
    $gambar_tmp = $_FILES['gambar']['tmp_name'];
    $tipe = $_FILES['gambar']['type'];

    if ($gambar_tmp) {
        // Baca isi file gambar dan ubah menjadi data biner
        $imgData = addslashes(file_get_contents($gambar_tmp));

        // Query untuk simpan ke database
        $sql = "INSERT INTO produk (id_produk, gambar, tipe, nama, id_kategori, stok, harga)
                VALUES ('$id_produk', '$imgData', '$tipe', '$nama', '$id_kategori', '$stok', '$harga')";

        $result = mysqli_query($conn, $sql);

        if ($result) {
            echo "<script>alert('✅ Produk berhasil ditambahkan'); window.location='admin.php';</script>";
        } else {
            echo "❌ Produk gagal ditambahkan " . mysqli_error($conn);
        }
    } else {
        echo "❌ Tidak ada gambar yang diupload!";
    }
}

// proses hapus produk
if (isset($_POST['hapus'])) {
    $id_produk = $_POST['id_produk'];

    $sql = "CALL hapus_produk('$id_produk')";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        echo "<script>alert('✅ Produk berhasil dihapus'); window.location='admin.php';</script>";
    } else {
        echo "❌ Produk gagal dihapus " . mysqli_error($conn);
    }
}

// proses hapus akun
if (isset($_POST['hapus-akun'])) {
    $id_user = $_POST['id_user'];

    $sql = "CALL hapus_akun('$id_user')";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        echo "<script>alert('✅ Akun berhasil dihapus'); window.location='daftar-akun.php';</script>";
    } else {
        echo "❌ Akun gagal dihapus " . mysqli_error($conn);
    }
}

// proses tambah akun
if (isset($_POST['tambah-akun'])) {
    $id_user = $_POST['id_user'];
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $password = md5($_POST['password']); // enkripsi dengan MD5
    $role = $_POST['role'];
    $no_telp = $_POST['no_telp'];

    // cek apakah username sudah ada
    $cek = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");
    if (mysqli_num_rows($cek) > 0) {
        echo "<script>alert('Username sudah digunakan! Silakan pilih yang lain.'); window.location='daftar-akun.php';</script>";
    } else {
        // masukkan ke database
        $sql = "INSERT INTO users (id_user, nama, username, pasword, role, no_telp)
                VALUES ('$id_user', '$nama', '$username', '$password', '$role', '$no_telp')";
        $tambah = mysqli_query($conn, $sql);

        if ($tambah) {
            echo "<script>alert('Akun berhasil ditambahkan!'); window.location='daftar-akun.php';</script>";
        } else {
            echo "Gagal menambah akun: " . mysqli_error($conn);
        }
    }
}

if (isset($_GET['query'])) {
    $query = mysqli_real_escape_string($conn, $_GET['query']);
    
    $sql = "SELECT p.*, k.kategori 
        FROM produk p
        JOIN kategori k ON p.id_kategori = k.id_kategori
        WHERE p.nama LIKE '%$query%' OR k.kategori LIKE '%$query%'";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        echo "<tr>
                <th>ID Produk</th>
                <th>Gambar</th>
                <th>Nama</th>
                <th>Kategori</th>
                <th>Stok</th>
                <th>Harga</th>
                <th>Hapus</th>
                <th>Edit</th>
              </tr>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                    <td>{$row['id_produk']}</td>
                    <td>";
            if (!empty($row['gambar'])) {
                $tipe = !empty($row['tipe']) ? $row['tipe'] : 'image/jpeg';
                echo '<img src="data:' . $tipe . ';base64,' . base64_encode($row['gambar']) . '" alt="Gambar Produk">';
            } else {
                echo 'Tidak ada gambar';
            }
            echo "</td>
                    <td>{$row['nama']}</td>
                    <td>{$row['kategori']}</td>
                    <td>{$row['stok']}</td>
                    <td>Rp{$row['harga']}</td>
                    <td>
                        <form action='proses.php' method='POST' style='display:inline;'>
                            <input type='hidden' name='id_produk' value='{$row['id_produk']}'>
                            <button class='btnhapus' type='submit' name='hapus'>Hapus</button>
                        </form>
                    </td>
                    <td>
                        <button class='btnedit' onclick=\"window.location.href='edit-produk.php?id={$row['id_produk']}'\">Edit</button>
                    </td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='8'>Tidak ada produk ditemukan</td></tr>";
    }
}

?>
