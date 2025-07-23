<?php
session_start();
require 'db_connection.php';

// Hanya admin yang boleh mengakses file ini
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Akses tidak sah!");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $nama_lengkap = $_POST['nama_lengkap'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Validasi dasar
    if (empty($nama_lengkap) || empty($username) || empty($password) || empty($role)) {
        header("Location: admin_dashboard.php?status=error&message=Semua field wajib diisi.");
        exit();
    }

    // Cek apakah username sudah ada
    $stmt_check = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt_check->bind_param("s", $username);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        header("Location: admin_dashboard.php?status=error&message=Username sudah digunakan.");
        exit();
    }
    $stmt_check->close();

    // Enkripsi password dengan aman
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Siapkan query INSERT dengan prepared statement untuk keamanan
    $stmt = $conn->prepare("INSERT INTO users (nama_lengkap, username, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nama_lengkap, $username, $hashed_password, $role);

    // Eksekusi dan redirect
    if ($stmt->execute()) {
        header("Location: admin_dashboard.php?status=success");
    } else {
        header("Location: admin_dashboard.php?status=error&message=Terjadi kesalahan saat menyimpan data.");
    }

    $stmt->close();
    $conn->close();

} else {
    // Redirect jika halaman diakses secara langsung tanpa POST
    header("Location: admin_dashboard.php");
}
?>