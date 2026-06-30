<?php
$host = "localhost";
$username = "dwineidv_wineshop";
$password = "Matkhau123@";
$db_name = "dwineidv_wineshop";

$conn = mysqli_connect($host, $username, $password, $db_name);

if (!$conn) {
    die("Kết nối thất bại: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");
?>