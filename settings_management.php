<?php
session_start();
require 'db_connection.php';

// Cek autentikasi admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php?error=Akses ditolak!");
    exit();
}

// === AMBIL DATA PENGATURAN HERO ===
$settings_query = "SELECT setting_key, setting_value FROM site_settings";
$settings_result = $conn->query($settings_query);
$settings = [];
while ($row = $settings_result->fetch_assoc()) {
    $settings[$row['setting_key']] = $row['setting_value'];
}

// === AMBIL DATA CAROUSEL SLIDES ===
$query_slides = "SELECT * FROM carousel_slides ORDER BY order_number ASC";
$result_slides = $conn->query($query_slides);

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Website - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style> 
        body { background-color: #f8f9fa; }
        .thumbnail { width: 150px; height: auto; object-fit: cover; }
    </style>
</head>
<body>
<div class="container my-4">
    <h1 class="mb-4">Pengaturan Tampilan Website</h1>
    <a href="admin_dashboard.php" class="btn btn-secondary mb-3"><i class="fa fa-arrow-left"></i> Kembali ke Dashboard</a>

    <!-- Notifikasi -->
    <?php if(isset($_GET['message'])): ?>
        <div class="alert alert-<?php echo $_GET['status'] == 'success' ? 'success' : 'danger'; ?> alert-dismissible fade show">
            <?php echo htmlspecialchars($_GET['message']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <!-- PENGATURAN HERO SECTION -->
    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <h4><i class="fa fa-image me-2"></i> Pengaturan Hero Section</h4>
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
                    <label for="hero_image" class="form-label">Ganti Gambar Hero</label>
                    <input type="file" class="form-control" name="hero_image" id="hero_image" accept="image/jpeg, image/png, image/webp">
                    <div class="mt-2">
                        <small>Gambar Saat Ini:</small><br>
                        <img src="assets/images/site/<?php echo htmlspecialchars($settings['hero_image']); ?>" alt="Hero Image" class="img-thumbnail" style="max-height: 100px;">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan Pengaturan Hero</button>
            </form>
        </div>
    </div>

    <!-- PENGATURAN CAROUSEL -->
    <div class="card shadow-sm" id="carousel-section">
        <div class="card-header d-flex justify-content-between align-items-center">
             <h4><i class="fa-regular fa-images me-2"></i> Pengaturan Carousel</h4>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addSlideModal">
                <i class="fa fa-plus"></i> Tambah Slide Baru
            </button>
        </div>
        <div class="card-body">
             <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Urutan</th>
                            <th>Gambar</th>
                            <th>Judul & Keterangan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php mysqli_data_seek($result_slides, 0); // Reset pointer jika sudah dipakai ?>
                        <?php while($slide = $result_slides->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $slide['order_number']; ?></td>
                            <td><img src="assets/images/carousel/<?php echo $slide['image_path']; ?>" alt="<?php echo $slide['title']; ?>" class="thumbnail rounded"></td>
                            <td>
                                <strong><?php echo htmlspecialchars($slide['title']); ?></strong><br>
                                <small><?php echo htmlspecialchars($slide['caption']); ?></small>
                            </td>
                            <td>
                                <span class="badge bg-<?php echo ($slide['is_active'] ? 'success' : 'secondary'); ?>">
                                    <?php echo ($slide['is_active'] ? 'Aktif' : 'Non-Aktif'); ?>
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-danger btn-sm" onclick="confirmDelete(<?php echo $slide['id']; ?>)">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Slide (Sama seperti sebelumnya) -->
<div class="modal fade" id="addSlideModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Tambah Slide Baru</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <form action="process_carousel.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="add">
        <div class="modal-body">
            <div class="mb-3"><label for="title" class="form-label">Judul Slide</label><input type="text" class="form-control" name="title" required></div>
            <div class="mb-3"><label for="caption" class="form-label">Keterangan</label><textarea class="form-control" name="caption" rows="2"></textarea></div>
            <div class="mb-3"><label for="image" class="form-label">File Gambar</label><input type="file" class="form-control" name="image" accept="image/jpeg, image/png" required></div>
            <div class="mb-3"><label for="order_number" class="form-label">Nomor Urut</label><input type="number" class="form-control" name="order_number" value="0" required></div>
            <div class="form-check"><input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" checked><label class="form-check-label" for="is_active">Aktifkan slide</label></div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary">Simpan</button></div>
      </form>
    </div>
  </div>
</div>

<!-- Form Hapus Tersembunyi -->
<form id="deleteForm" action="process_carousel.php" method="POST" style="display: none;"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" id="deleteId"></form>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function confirmDelete(id) {
    if (confirm("Yakin ingin menghapus slide ini?")) {
        document.getElementById('deleteId').value = id;
        document.getElementById('deleteForm').submit();
    }
}
</script>
</body>
</html>