<?php 
    session_start();
    include '../database/dbconnection.php';
 
    // menangkap data yang dikirim dari form login
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);
 
    // menyeleksi data pada tabel customertable berdasarkan username
    $data = mysqli_query($koneksi, "SELECT * FROM customertable WHERE username='$username'");
 
    // memeriksa apakah user ditemukan
    if(mysqli_num_rows($data) > 0){
        $row = mysqli_fetch_assoc($data); // mengambil data dari hasil query
        
        // verifikasi password yang diinput user dengan password hash di database
        if(password_verify($password, $row['password'])){
            // password benar, login berhasil
            $_SESSION['username'] = $username;
            $_SESSION['user_type'] = 'customer';
            $_SESSION['id'] = $row['id']; // Simpan ID customer ke session
            $_SESSION['status'] = "login";
            header("location:../view/index.php");
        } else {
            // password salah
            echo "<script>
			            alert('Wrong username or password');
			            document.location='../view/landingpage.php';
			           </script>";
        }
    } else {
        // username tidak ditemukan
        echo "<script>
			        alert('Username not found');
			        document.location='../view/landingpage.php';
			   </script>";
    }
?>
