<?php 
session_start();
include '../database/dbconnection.php';

// Proses sign up
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);
    
    // Hash password untuk keamanan
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

     // Set default picture
     $default_picture = '../image/default.png';
    
    // Cek apakah username sudah ada
    $sql = "SELECT * FROM customertable WHERE username = '$username'";
    $result = $koneksi->query($sql);
    
    if ($result->num_rows > 0) {
        // Username sudah ada
        echo "Username unavailable. Please use another username";
    } else {
        // Masukkan data user baru ke database
        $sql = "INSERT INTO customertable (username, email, password, picture) VALUES ('$username', '$email', '$hashed_password', '$default_picture')";
        
        if ($koneksi->query($sql) === TRUE) {
            echo "<script>
			            alert('Sign Up sucessed. Log in now');
			            document.location='../view/landingpage.php';
			           </script>";
        } else {
            echo "Terjadi kesalahan: " . $koneksi->error;
        }
    }
}

$koneksi->close();
?>
