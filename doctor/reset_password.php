<?php
session_start();
include '../database/dbconnection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['token']) && isset($_POST['doctorpassword'])) {
    $token = $_POST['token'];
    $new_password = mysqli_real_escape_string($koneksi, $_POST['doctorpassword']);
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Cek token di database
    $query = "SELECT * FROM reset_password WHERE token = '$token'";
    $result = $koneksi->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $email = $row['email'];
        $expiry = $row['expiry'];

        // Cek apakah token valid
        if (time() < $expiry) {
            // Update password
            $update_query = "UPDATE doctortable SET doctorpassword = '$hashed_password' WHERE email = '$email'";
            if ($koneksi->query($update_query)) {
                echo '<script>
            alert("Password has been reset successfully.!");
            window.location.href = "../view/landingpage.php";
          </script>';
                // Hapus token setelah digunakan
                $koneksi->query("DELETE FROM reset_password WHERE token = '$token'");
            } else {
                echo "Failed to reset password.";
            }
        } else {
            echo "The reset link has expired.";
        }
    } else {
        echo "Invalid token.";
    }
}
?>
