<?php
    session_start();
    session_destroy(); // Menghancurkan semua session
    header("location: ../view/landingpage.php"); // Redirect ke halaman landingpage atau login
    exit;
?>
