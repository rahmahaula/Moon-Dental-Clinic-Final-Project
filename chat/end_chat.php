<?php
session_start();
include '../database/dbconnection.php';

// Pastikan user sudah login
if (!isset($_SESSION['username'])) {
    header("location: ../view/landingpage.php");
    exit;
}

// Ambil data dari form
if (isset($_POST['end_chat'])) {
    $doctor_id = $_POST['doctor_id'];
    $customer_id = $_POST['customer_id'];  // Perbaikan pada penggunaan parameter

    // Update status menjadi 'inactive' setelah dokter mengklik End Chat
    $query = "UPDATE chat SET status = 'inactive' WHERE doctor_id = ? AND customer_id = ?";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("ii", $doctor_id, $customer_id);
    $stmt->execute();

    // Periksa apakah status berhasil diperbarui
    if ($stmt->affected_rows > 0) {
        // Redirect ke halaman doctorlist.php untuk memperbarui status tombol
        header("Location: customerlist.php");  // Sesuaikan dengan halaman tujuan Anda
        exit;
    } else {
        echo "Failed to update chat session.";
    }
}
?>
