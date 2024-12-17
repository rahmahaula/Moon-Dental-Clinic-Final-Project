<?php
include "../database/dbconnection.php";

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Cek apakah token ada dan belum kedaluwarsa
    $sql = "SELECT email, expiry FROM reset_password WHERE token = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $expiry = $row['expiry'];

        if (time() < $expiry) {
            // Tampilkan form untuk mengganti password
            echo '<form method="POST" action="reset_password.php">
                    <input type="hidden" name="token" value="' . htmlspecialchars($token) . '">
                    <label for="doctorpassword">New Password:</label>
                    <input type="password" name="doctorpassword" required>
                    <br>
                    <label for="confirm_password">Confirm Password:</label>
                    <input type="password" name="confirm_password" required>
                    <br>
                    <input type="submit" value="Reset Password">
                  </form>';
        } else {
            echo "The reset link has expired. Please request a new password reset.";
        }
    } else {
        echo "Invalid reset token.";
    }
} else {
    echo "No reset token provided.";
}
?>
