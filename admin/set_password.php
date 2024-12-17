<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "../database/dbconnection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validasi apakah data ada di POST
    if (isset($_POST['username'], $_POST['doctorpassword'], $_POST['activation_code'])) {
        $username = $_POST['username'];
        $doctorpassword = $_POST['doctorpassword'];
        $activation_code = $_POST['activation_code'];

        // Lakukan hashing password sebelum disimpan ke database
        $hashed_password = password_hash($doctorpassword, PASSWORD_DEFAULT);

        // Perbarui tabel doctor berdasarkan kode aktivasi
        $sql = "UPDATE doctortable SET username = ?, doctorpassword = ? WHERE activation_code = ?";
        $stmt = $koneksi->prepare($sql);
        $stmt->bind_param("sss", $username, $hashed_password, $activation_code);

        if ($stmt->execute()) {
            echo '<script>
            alert("Password successfully updated!");
            window.location.href = "../view/landingpage.php";
          </script>';
        } else {
            echo "Failed to update password: " . $stmt->error;
        }
    } else {
        echo "Error: Missing required data.";
    }
} else {
    echo "Invalid request method.";
}
?>
