<?php
session_start();
require '../vendor/autoload.php';
include '../database/dbconnection.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);

    // Cek apakah email ada di database
    $query = "SELECT * FROM doctortable WHERE email = '$email'";
    $result = $koneksi->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $token = bin2hex(random_bytes(32)); // Generate token
        $expiry = time() + (60 * 30); // Token berlaku selama 30 menit

        // Simpan token ke database
        $koneksi->query("INSERT INTO reset_password (email, token, expiry) VALUES ('$email', '$token', '$expiry')");

        // Konfigurasi PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'info.moondental@gmail.com';
            $mail->Password = 'gzwctjuskrzzyqxu';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            $mail->setFrom('info.moondental@gmail.com', 'Moon Dental Clinic');
            $mail->addAddress($email);

            $reset_link = "http://localhost:81/Moon%20Dental/doctor/form_reset_password.php?token=" . urlencode($token);

            $mail->isHTML(true);
            $mail->Subject = 'Reset Your Password';
            $mail->Body = "<p>Hi, <br><br>Please click the link below to reset your password:<br><a href='$reset_link'>$reset_link</a><br><br>This link will expire in 30 minutes.</p>";
            $mail->AltBody = "Hi, Please use the following link to reset your password: $reset_link";

            $mail->send();
            echo "<script>
            alert('An email with reset instructions has been sent to your email address.');
            window.location.href='../view/landingpage.php'; // Redirect setelah alert
          </script>";
} catch (Exception $e) {
    echo "<script>
            alert('Failed to send email. Mailer Error: {$mail->ErrorInfo}');
          </script>";
}
} else {
    echo "<script>
            alert('Email not found.');
          </script>";
}
}
?>
