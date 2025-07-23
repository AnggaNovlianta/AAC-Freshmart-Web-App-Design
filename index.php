<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AAC Freshmart - Distributor Frozen Food Terpercaya</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome untuk Ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Google Fonts (Opsional, untuk tipografi yang lebih baik) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<?php
// Letakkan ini di bagian paling atas file index.php setelah tag <body> jika belum ada
// atau cukup pastikan koneksi DB tersedia sebelum section carousel.
require_once 'db_connection.php'; 

// Ambil data slide yang aktif dari database, urutkan berdasarkan order_number
$slides_query = "SELECT * FROM carousel_slides WHERE is_active = 1 ORDER BY order_number ASC";
$slides_result = $conn->query($slides_query);
$slides = [];
if ($slides_result->num_rows > 0) {
    while($row = $slides_result->fetch_assoc()) {
        $slides[] = $row;
    }
}
?>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">
                <!-- Ganti dengan tag <img> jika Anda punya logo -->
                <i class="fa-solid fa-snowflake"></i>
                AAC Freshmart
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#hero">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#tentang-kami">Tentang Kami</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#produk-unggulan">Produk</a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-lg-3 mt-2 mt-lg-0">
                     <li class="nav-item">
                        <a href="login.php" class="btn btn-primary w-100">
                            <i class="fa-solid fa-right-to-bracket"></i> Login
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header id="hero" class="hero-section d-flex align-items-center">
        <div class="container text-center text-white">
            <h1 class="display-4 fw-bold">Kualitas Frozen Food Terbaik, Langsung ke Tempat Anda</h1>
            <p class="lead my-3">Kami adalah mitra distributor terpercaya untuk hotel, restoran, dan kafe Anda.</p>
            <a href="#produk-unggulan" class="btn btn-primary btn-lg mt-2">Lihat Produk Kami</a>
        </div>
    </header>

    <!-- Tentang Kami Section -->
    <section id="tentang-kami" class="py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h2 class="fw-bold mb-3">Tentang AAC Freshmart</h2>
                    <p>AAC Freshmart adalah perusahaan distribusi yang berfokus pada penyediaan produk makanan beku (frozen food) berkualitas tinggi. Sejak berdiri, kami berkomitmen untuk menjadi penghubung terpercaya antara produsen terbaik dengan para pelaku bisnis kuliner.</p>
                    <p>Dengan jaringan logistik yang efisien dan standar penyimpanan yang ketat, kami memastikan setiap produk tiba di tangan Anda dalam kondisi sempurna.</p>
                </div>
                <div class="col-lg-6 text-center mt-4 mt-lg-0">
                    <!-- Ganti gambar ini dengan gambar yang relevan dengan bisnis Anda -->
                    <img src="https://via.placeholder.com/500x350.png?text=Gudang+AAC+Freshmart" class="img-fluid rounded shadow" alt="Tentang Kami">
                </div>
            </div>
        </div>
    </section>

   <!-- Carousel Section (Produk Unggulan) -->
<section id="produk-unggulan" class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Produk Unggulan Kami</h2>
            <p class="text-muted">Pilihan terbaik untuk kebutuhan bisnis kuliner Anda.</p>
        </div>

        <?php if (!empty($slides)): ?>
        <div id="productCarousel" class="carousel slide shadow-lg" data-bs-ride="carousel">
            <!-- Carousel Indicators -->
            <div class="carousel-indicators">
                <?php foreach ($slides as $index => $slide): ?>
                    <button type="button" data-bs-target="#productCarousel" data-bs-slide-to="<?php echo $index; ?>" class="<?php echo $index == 0 ? 'active' : ''; ?>"></button>
                <?php endforeach; ?>
            </div>

            <!-- Carousel Inner -->
            <div class="carousel-inner rounded">
                <?php foreach ($slides as $index => $slide): ?>
                <div class="carousel-item <?php echo $index == 0 ? 'active' : ''; ?>">
                    <img src="assets/images/carousel/<?php echo htmlspecialchars($slide['image_path']); ?>" class="d-block w-100" alt="<?php echo htmlspecialchars($slide['title']); ?>">
                    <div class="carousel-caption d-none d-md-block">
                        <h5><?php echo htmlspecialchars($slide['title']); ?></h5>
                        <p><?php echo htmlspecialchars($slide['caption']); ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Carousel Controls -->
            <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
        <?php else: ?>
            <div class="text-center">
                <p>Saat ini belum ada produk unggulan yang ditampilkan.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

    <!-- Footer -->
    <footer class="bg-dark text-white pt-5 pb-4">
        <div class="container text-center text-md-start">
            <div class="row">
                <div class="col-md-3 col-lg-4 col-xl-3 mx-auto mb-4">
                    <h6 class="text-uppercase fw-bold">AAC Freshmart</h6>
                    <hr class="mb-4 mt-0 d-inline-block mx-auto" style="width: 60px; background-color: #0d6efd; height: 2px"/>
                    <p>Perusahaan distributor frozen food yang melayani pengiriman ke seluruh area dengan mengutamakan kualitas produk dan ketepatan waktu.</p>
                </div>

                <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mb-4">
                    <h6 class="text-uppercase fw-bold">Tautan Cepat</h6>
                     <hr class="mb-4 mt-0 d-inline-block mx-auto" style="width: 60px; background-color: #0d6efd; height: 2px"/>
                    <p><a href="#hero" class="text-white text-decoration-none">Beranda</a></p>
                    <p><a href="#tentang-kami" class="text-white text-decoration-none">Tentang Kami</a></p>
                    <p><a href="#produk-unggulan" class="text-white text-decoration-none">Produk</a></p>
                    <p><a href="login.php" class="text-white text-decoration-none">Login</a></p>
                </div>

                <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mb-md-0 mb-4">
                    <h6 class="text-uppercase fw-bold">Hubungi Kami</h6>
                     <hr class="mb-4 mt-0 d-inline-block mx-auto" style="width: 60px; background-color: #0d6efd; height: 2px"/>
                    <p><i class="fas fa-home me-3"></i> Jl. Industri Raya No. 1, Jakarta</p>
                    <p><i class="fas fa-envelope me-3"></i> info@aacfreshmart.com</p>
                    <p><i class="fas fa-phone me-3"></i> +62 21 1234 5678</p>
                </div>
            </div>
        </div>
        <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2)">
            © <?php echo date('Y'); ?> AAC Freshmart. All Rights Reserved.
        </div>
    </footer>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>