<?php
session_start();
require 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Gunakan prepared statement untuk mencegah SQL Injection
    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verifikasi password yang di-hash
        if (password_verify($password, $user['password'])) {
            // Login berhasil, simpan data ke session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Arahkan berdasarkan role
            if ($user['role'] == 'admin') {
                header("Location: admin_dashboard.php");
            } else {
                header("Location: user_dashboard.php");
            }
            exit();
        }
    }

    // Jika login gagal
    header("Location: login.php?error=Username atau password salah");
    exit();

} else {
    // Jika diakses langsung tanpa POST
    header("Location: login.php");
    exit();
}
?>```

#### **`admin_dashboard.php`** (Dashboard Admin)
Halaman ini hanya bisa diakses oleh admin.

```php
<?php
session_start();

// Cek apakah pengguna sudah login dan memiliki role 'admin'
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Dashboard Admin - AAC Freshmart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Selamat Datang di Dashboard Admin, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
        <p>Ini adalah halaman khusus untuk admin.</p>
        <p>Di sini Anda bisa mengelola produk, pesanan, dan pengguna.</p>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
</body>
</html>