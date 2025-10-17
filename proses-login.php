<?php
    session_start();
    include "koneksi.php";

    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username= '$username' AND pasword= MD5('$password')";
    $result = mysqli_query($conn,$sql);

    if(mysqli_num_rows($result) > 0){
        $row = mysqli_fetch_assoc($result);

        $_SESSION['username'] = $row['username'];
        $_SESSION['role'] = $row['role'];
        $_SESSION['nama'] = $row['nama'];
        $_SESSION['no_telp'] = $row['no_telp'];

        if($_SESSION['role'] == "admin"){
            header("Location: halamanadmin/admin.php");
        exit;
        } elseif($_SESSION['role'] == "kasir"){
            header("Location: halamankasir/kasir.php");
        exit;
        } else {
            header("Location: login-gagal.php");
        exit;
        }
    } else {
        header("Location: login-gagal.php");
        exit;
}

?>