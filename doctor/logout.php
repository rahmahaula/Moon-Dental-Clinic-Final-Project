<?php
session_start();
include '../database/dbconnection.php'; // Pastikan file koneksi database di-include

// Update status dokter menjadi tidak aktif
if (isset($_SESSION['doctor_id'])) {
    $doctor_id = $_SESSION['doctor_id'];
    $query = "UPDATE doctor_sessions SET is_active = 0 WHERE doctor_id = '$doctor_id'";
    mysqli_query($koneksi, $query);
}

// Menghancurkan semua session
session_destroy();

// Redirect ke halaman landingpage atau login
header("Location: ../view/landingpage.php");
exit;
?>
