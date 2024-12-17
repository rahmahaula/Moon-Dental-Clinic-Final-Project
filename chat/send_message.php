<?php
session_start();
include '../database/dbconnection.php';

// Pastikan user sudah login
if (!isset($_SESSION['username'])) {
    header("location: ../view/landingpage.php");
    exit;
}

// Mendapatkan ID customer atau dokter dari session
if (isset($_SESSION['id'])) {
    $customer_id = $_SESSION['id'];
    $sender_type = 'customer';
} elseif (isset($_SESSION['doctor_id'])) {
    $doctor_id = $_SESSION['doctor_id'];
    $sender_type = 'doctor';
} else {
    echo "User tidak ditemukan.";
    exit;
}

// Mendapatkan ID tujuan (untuk customer, kirim ke dokter dan sebaliknya)
if ($sender_type == 'customer') {
    $doctor_id = $_POST['doctor_id'];
} else {
    $customer_id = $_POST['id'];
}

// Ambil pesan dari form
$message = isset($_POST['message']) ? mysqli_real_escape_string($koneksi, $_POST['message']) : "";

// Cek apakah ada file yang diunggah
$file_uploaded = isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK;
if ($file_uploaded) {
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'video/mp4'];
    $file_type = $_FILES['file']['type'];
    $file_size = $_FILES['file']['size'];
    $max_size = 10 * 1024 * 1024; // Batas 10MB

    if (in_array($file_type, $allowed_types) && $file_size <= $max_size) {
        $upload_dir = realpath('../uploads/') . '/';
        $file_name = time() . '_' . basename($_FILES['file']['name']);
        $file_path = $upload_dir . $file_name;
        
        // Pindahkan file ke direktori tujuan
        if (move_uploaded_file($_FILES['file']['tmp_name'], $file_path)) {
            $message = '../uploads/' . $file_name; // Simpan path relatif file di kolom message
        } else {
            echo "Gagal mengunggah file.";
            exit;
        }
    } else {
        echo "<script>
                alert('Jenis atau ukuran file tidak didukung. Hanya file gambar (JPEG, PNG, GIF) dan video (MP4) maksimal 10MB yang diperbolehkan.');
                window.location.href='mychat.php?doctor_id=" . htmlspecialchars($doctor_id) . "';
              </script>";
        exit;
    }
}

// Cek apakah ada pesan atau file yang akan dikirim
if (empty($message) && !$file_uploaded) {
    echo "<script>
            alert('Harap isi pesan atau unggah file.');
            window.location.href='mychat.php?doctor_id=" . htmlspecialchars($doctor_id) . "';
          </script>";
    exit;
}

// Kirim pesan ke database
$query = "INSERT INTO chat (customer_id, doctor_id, message, sender_type) VALUES ('$customer_id', '$doctor_id', '$message', '$sender_type')";
if (mysqli_query($koneksi, $query)) {
    header("location: mychat.php?doctor_id=" . htmlspecialchars($doctor_id));
} else {
    echo "Error: " . mysqli_error($koneksi);
}
exit;
?>
