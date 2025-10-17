<?php
$conn = new mysqli('localhost','root','','kasir');

if (!$conn){
    die("Koneksi gagal :". $mysqli_connect_error());
} 
?>
