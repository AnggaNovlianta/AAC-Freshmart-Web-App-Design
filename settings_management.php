<?php
session_start();
require 'db_connection.php';

// Cek autentikasi admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php?error=Akses ditolak!");
    exit();
}

// Ambil semua pengaturan dari database dan masukkan ke dalam array
$settings_query = "SELECT setting_key, setting_value FROM site_settings";
$settings_result = $conn->query($settings_query);
$settings = [];
while ($row = $settings_result->fetch_assoc()) {
    $settings[$row['setting_key']] = $row['setting_value'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Website - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style> body { background-color: #f8f9fa; } </style>
</head>
<body>
<div class="container my-4">
    <h1 class="mb-4">Pengaturan Website</h1>
    <a href="admin_dashboard.php" class="btn btn-secondary mb-3"><i class="fa fa-arrow-left"></i> Kembali ke Dashboard</a>

    <!-- Notifikasi -->
    <?php if(isset($_GET['status'])): ?>
        <div class="alert alert-<?php echo $_GET['status'] == 'success' ? 'success' : 'danger'; ?> alert-dismissible fade show">
            <?php echo htmlspecialchars($_GET['message']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <div class="card shadow-sm">
        <div class="card-header">
            <h3>Pengaturan Halaman Depan (Hero Section)</h3>
        </div>
        <div class="card-body">
            <form action="process_settings.php" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="hero_title" class="form-label">Judul Hero</label>
                    <input type="text" class="form-control" name="hero_title" id="hero_title" value="<?php echo htmlspecialchars($settings['hero_title'] ?? ''); ?>">
                </div>
                <div class="mb-3">
                    <label for="hero_subtitle" class="form-label">Subjudul Hero</label>
                    <textarea class="form-control" name="hero_subtitle" id="hero_subtitle" rows="3"><?php echo htmlspecialchars($settings['hero_subtitle'] ?? ''); ?></textarea>
                </div>
                <div class="mb-4">
                    <label for="hero_image" class="form-label">Ganti Gambar Hero (Rekomendasi: 1920x1080px)</label>
                    <input type="file" class="form-control" name="hero_image" id="hero_image" accept="image/jpeg, image/png, image/webp">
                    <small class="form-text text-muted">Biarkan kosong jika tidak ingin mengganti gambar.</small>
                </div>
                
                <div class="mb-3">
                    <h5>Gambar Hero Saat Ini:</h5>
                    <img src="assets/images/site/<?php echo htmlspecialchars($settings['hero_image']); ?>" alt="Hero Image" class="img-fluid rounded" style="max-height: 200px;">
                </div>

                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan Perubahan</button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>