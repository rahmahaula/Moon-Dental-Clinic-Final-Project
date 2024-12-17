<?php
session_start();
include '../database/dbconnection.php';

date_default_timezone_set('Asia/Jakarta'); // Sesuaikan dengan timezone lokal

// Pastikan user sudah login
if (!isset($_SESSION['username'])) {
    header("location: ../view/landingpage.php");
    exit;
}

// Mendapatkan ID dokter dari session
$doctor_id = $_SESSION['doctor_id']; // Pastikan ID dokter disimpan dalam session saat login
$customer_id = $_POST['customer_id']; // Pastikan ID customer diambil dari form
$message = mysqli_real_escape_string($koneksi, $_POST['message']);

// Mendapatkan ID customer atau dokter dari session
if (isset($_SESSION['doctor_id'])) {
    $doctor_id = $_SESSION['doctor_id'];
    $sender_type = 'doctor';
} elseif (isset($_SESSION['customer_id'])) {
    $customer_id = $_SESSION['customer_id'];
    $sender_type = 'customer';
} else {
    echo "User tidak ditemukan.";
    exit;
}

// Mendapatkan ID tujuan (untuk customer, kirim ke dokter dan sebaliknya)
if ($sender_type == 'doctor') {
    $customer_id = $_POST['customer_id'];
} else {
    $doctor_id = $_POST['doctor_id'];
}

// Ambil pesan dari form
$message = isset($_POST['message']) ? mysqli_real_escape_string($koneksi, $_POST['message']) : "";

// Ambil waktu pertama kali customer mengirim pesan ke dokter hari ini
$sql_time_check = "
    SELECT timestamp 
    FROM chat 
    WHERE doctor_id = '$doctor_id' 
      AND customer_id = '$customer_id' 
      AND sender_type = 'customer' 
      AND DATE(timestamp) = CURDATE() 
    ORDER BY timestamp ASC 
    LIMIT 1";

$time_check_result = mysqli_query($koneksi, $sql_time_check);
$time_check_data = mysqli_fetch_assoc($time_check_result);

if ($time_check_data) {
    $start_time = strtotime($time_check_data['timestamp']); // Waktu pertama customer mengirim pesan hari ini
    $current_time = time(); // Waktu sekarang
    $chat_duration = ($current_time - $start_time) / 60; // Durasi dalam menit

    // Cek apakah durasi lebih dari 10 menit
    if ($chat_duration > 10) {
        echo "<script>
                alert('Consultation time with this customer has ended for today.');
                window.location.href='customerlist.php';
              </script>";
        exit;
    }
} else {
    // Jika belum ada pesan dari customer hari ini, dokter tidak dapat memulai chat
    echo "<script>
            alert('Customer has not initiated chat today.');
            window.location.href='customerlist.php';
          </script>";
    exit;
}

// Jika durasi masih di bawah 10 menit atau customer baru saja memulai chat, lanjutkan logika pengiriman pesan
$query = "INSERT INTO chat (customer_id, doctor_id, message, sender_type) 
          VALUES ('$customer_id', '$doctor_id', '$message', 'doctor')";

if (mysqli_query($koneksi, $query)) {
    // Redirect ke halaman chat dengan customer ini
    header("location: mychat_doctor.php?customer_id=" . htmlspecialchars($customer_id));
} else {
    echo "Error: " . mysqli_error($koneksi);
}
exit;



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

// Kirim pesan ke database jika tidak ada batasan waktu
$query = "INSERT INTO chat (customer_id, doctor_id, message, sender_type) VALUES ('$customer_id', '$doctor_id', '$message', 'doctor')";
if (mysqli_query($koneksi, $query)) {
    // Redirect kembali ke halaman chat atau berikan feedback
    header("location: mychat_doctor.php?customer_id=" . htmlspecialchars($customer_id));
} else {
    echo "Error: " . mysqli_error($koneksi);
}
exit;
?>
