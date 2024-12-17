<?php
include "../database/dbconnection.php";

if (isset($_GET['code'])) {
    $activation_code = $_GET['code'];

    // Cek kode aktivasi
    $sql = "SELECT id FROM doctortable WHERE activation_code = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("s", $activation_code);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Tampilkan form untuk username dan password
        echo '<form method="POST" action="set_password.php">
                <input type="hidden" name="activation_code" value="' . htmlspecialchars($activation_code) . '">
                <label for="username">Username:</label>
                <input type="text" name="username" required>
                <br>
                <label for="doctorpassword">Password:</label>
                <input type="password" name="doctorpassword" required>
                <br>
                <input type="submit" value="Set Password">
              </form>';
    } else {
        echo "Activation code is invalid.";
    }
} else {
    echo "Activation code is unavailable.";
}
?>