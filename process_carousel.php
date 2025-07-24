<?php
session_start();
require 'db_connection.php';

// Keamanan: Hanya admin yang boleh melakukan aksi
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Akses ditolak.");
}

$action = $_POST['action'] ?? '';

// --- FUNGSI TAMBAH SLIDE ---
if ($action == 'add') {
    $title = $_POST['title'];
    $caption = $_POST['caption'];
    $order_number = $_POST['order_number'];
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    // --- Penanganan Upload Gambar ---
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $upload_dir = 'assets/images/carousel/';
        $allowed_types = ['image/jpeg', 'image/png'];
        $file_type = $_FILES['image']['type'];

        if (in_array($file_type, $allowed_types)) {
            // Buat nama file unik untuk menghindari tumpang tindih
            $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $new_filename = uniqid('slide_', true) . '.' . $file_extension;
            $upload_path = $upload_dir . $new_filename;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                // Simpan ke database
                $stmt = $conn->prepare("INSERT INTO carousel_slides (title, caption, image_path, order_number, is_active) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sssii", $title, $caption, $new_filename, $order_number, $is_active);              
                 if ($stmt->execute()) {
                    header("Location: settings_management.php?status=success&message=Slide berhasil ditambahkan.#carousel-section");
                } else {
                    header("Location: settings_management.php?status=error&message=Gagal menyimpan ke database.#carousel-section");
                }
                $stmt->close();
            } else {
                header("Location: settings_management.php?status=error&message=Gagal memindahkan file.");
            }
            } else {
                header("Location: settings_management.php?status=error&message=Tipe file tidak valid. Hanya JPG/PNG.");
            }
            } else {
                header("Location: settings_management.php??status=error&message=Tidak ada gambar yang diupload atau terjadi error.");
            }
}

// --- FUNGSI HAPUS SLIDE ---
if ($action == 'delete') {
    $id = $_POST['id'];
    
    // 1. Ambil nama file gambar dari DB sebelum dihapus
    $stmt = $conn->prepare("SELECT image_path FROM carousel_slides WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if($row = $result->fetch_assoc()) {
        $image_to_delete = 'assets/images/carousel/' . $row['image_path'];
    }
    $stmt->close();

    // 2. Hapus record dari database
    $stmt_delete = $conn->prepare("DELETE FROM carousel_slides WHERE id = ?");
    $stmt_delete->bind_param("i", $id);
    if ($stmt_delete->execute()) {
        if (isset($image_to_delete) && file_exists($image_to_delete)) {
            unlink($image_to_delete);
        }
        header("Location: settings_management.php?status=success&message=Slide berhasil dihapus.#carousel-section");
    } else {
        header("Location: settings_management.php?status=error&message=Gagal menghapus slide.#carousel-section");
    }
    $stmt_delete->close();
}
$conn->close();