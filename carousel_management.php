<?php
session_start();
require 'db_connection.php';

// Cek autentikasi admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php?error=Akses ditolak!");
    exit();
}

// Ambil data slide dari database
$query_slides = "SELECT * FROM carousel_slides ORDER BY order_number ASC";
$result_slides = $conn->query($query_slides);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Carousel - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Salin style dari admin_dashboard.php jika perlu layout yang sama -->
    <style> 
        body { background-color: #f8f9fa; }
        .thumbnail { width: 150px; height: auto; }
    </style>
</head>
<body>
<div class="container mt-4">
    <h1 class="mb-4">Manajemen Carousel</h1>
    <a href="admin_dashboard.php" class="btn btn-secondary mb-3"><i class="fa fa-arrow-left"></i> Kembali ke Dashboard</a>

    <!-- Notifikasi -->
    <?php if(isset($_GET['status'])): ?>
        <div class="alert alert-<?php echo $_GET['status'] == 'success' ? 'success' : 'danger'; ?> alert-dismissible fade show">
            <?php echo htmlspecialchars($_GET['message']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between">
            Daftar Slide Carousel
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
                            <th>Judul</th>
                            <th>Keterangan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($slide = $result_slides->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $slide['order_number']; ?></td>
                            <td><img src="assets/images/carousel/<?php echo $slide['image_path']; ?>" alt="<?php echo $slide['title']; ?>" class="thumbnail rounded"></td>
                            <td><?php echo htmlspecialchars($slide['title']); ?></td>
                            <td><?php echo htmlspecialchars($slide['caption']); ?></td>
                            <td>
                                <span class="badge bg-<?php echo ($slide['is_active'] ? 'success' : 'secondary'); ?>">
                                    <?php echo ($slide['is_active'] ? 'Aktif' : 'Non-Aktif'); ?>
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-danger btn-sm" onclick="confirmDelete(<?php echo $slide['id']; ?>)">
                                    <i class="fa fa-trash"></i> Hapus
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

<!-- Modal Tambah Slide -->
<div class="modal fade" id="addSlideModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Slide Baru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="process_carousel.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="add">
        <div class="modal-body">
            <div class="mb-3">
                <label for="title" class="form-label">Judul Slide</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="mb-3">
                <label for="caption" class="form-label">Keterangan (Caption)</label>
                <textarea class="form-control" id="caption" name="caption" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">File Gambar (Rekomendasi: 1200x500px)</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/jpeg, image/png" required>
            </div>
            <div class="mb-3">
                <label for="order_number" class="form-label">Nomor Urut</label>
                <input type="number" class="form-control" id="order_number" name="order_number" value="0" required>
            </div>
             <div class="form-check">
                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" checked>
                <label class="form-check-label" for="is_active">Aktifkan slide ini?</label>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Form untuk Hapus (dibuat tersembunyi) -->
<form id="deleteForm" action="process_carousel.php" method="POST" style="display: none;">
    <input type="hidden" name="action" value="delete">
    <input type="hidden" name="id" id="deleteId">
</form>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function confirmDelete(id) {
    if (confirm("Apakah Anda yakin ingin menghapus slide ini? Gambar juga akan dihapus permanen.")) {
        document.getElementById('deleteId').value = id;
        document.getElementById('deleteForm').submit();
    }
}
</script>
</body>
</html>