<?php
$localhost = 'localhost';
$username = 'root';
$password = '';
$database = 'kasir';

$conn = mysqli_connect($localhost, $username, $password, $database);

if ($conn->connect_error){
    die("Koneksi gagal :". $conn->connect_error);
} 
?>
