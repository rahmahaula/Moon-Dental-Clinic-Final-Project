<?php 
    session_start();
    include '../database/dbconnection.php';
 
    // menangkap data yang dikirim dari form login
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $doctorpassword = mysqli_real_escape_string($koneksi, $_POST['doctorpassword']);
 
    // menyeleksi data pada tabel doctortable berdasarkan username
    $data = mysqli_query($koneksi, "SELECT * FROM doctortable WHERE username='$username'");
 
    // memeriksa apakah user ditemukan
    if(mysqli_num_rows($data) > 0){
        $row = mysqli_fetch_assoc($data); // mengambil data dari hasil query
        
        // verifikasi password yang diinput user dengan password hash di database
        if(password_verify($doctorpassword, $row['doctorpassword'])){
            // password benar, login berhasil
            $_SESSION['username'] = $username;
            $_SESSION['user_type'] = 'doctor';
            $_SESSION['doctor_id'] = $row['doctor_id']; // Simpan doctor_id ke session
            $_SESSION['status'] = "login";

            // Tambahkan/Perbarui status login dokter di tabel doctor_sessions
            $doctor_id = $_SESSION['doctor_id'];
            $current_page = basename($_SERVER['PHP_SELF']); // Mendapatkan nama halaman saat ini

            // Tentukan apakah dokter berada di halaman customerlist.php
            if ($current_page == 'customerlist.php') {
                // Jika dokter di customerlist.php, status aktif
                $status = 1;
            } else {
                // Jika dokter di halaman lain, status offline
                $status = 0;
            }

            // Perbarui status login dokter di doctor_sessions
            $query = "INSERT INTO doctor_sessions (doctor_id, is_active, current_page, last_activity) 
                      VALUES ('$doctor_id', '$status', '$current_page', NOW())
                      ON DUPLICATE KEY UPDATE 
                      is_active = '$status', 
                      current_page = '$current_page', 
                      last_activity = NOW()";
            mysqli_query($koneksi, $query);

            header("Location: ../view/index.php");
            exit;
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
