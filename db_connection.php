<?php
$db_host = 'localhost';
$db_user = 'root'; // Default username XAMPP
$db_pass = '';     // Default password XAMPP
$db_name = 'db_aac_freshmart';

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Koneksi ke database gagal: " . $conn->connect_error);
}
?>