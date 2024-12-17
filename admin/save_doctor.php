<?php
session_start();
include "../database/dbconnection.php";
require '../vendor/autoload.php'; // Autoload untuk PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if(isset($_POST['bsimpan'])){
    if($_GET['hal'] == "edit") {
        // Data akan di edit

        // Persiapan statement SQL untuk update doctor
        $stmt = mysqli_prepare($koneksi, "UPDATE doctor SET
                                            picture = ?,
                                            fullname = ?,
                                            schedule = ?,
                                            schedule1 = ?,
                                            schedule2 = ?,
                                            schedule3 = ?,
                                            schedule4 = ?,
                                            branch = ?,
                                            phonenumber = ?
                                        WHERE id = ?");
        
        // Binding parameter
        mysqli_stmt_bind_param($stmt, 'sssssssssi', $_POST['picture'], $_POST['fullname'], $_POST['schedule'], $_POST['schedule1'], $_POST['schedule2'], $_POST['schedule3'], $_POST['schedule4'], $_POST['branch'], $_POST['phonenumber'], $_GET['id']);
        
        // Eksekusi statement
        mysqli_stmt_execute($stmt);
        
        // Periksa hasil eksekusi
        if(mysqli_stmt_affected_rows($stmt) > 0) {
            // Update email di doctortable
            $email = $_POST['email']; // Ambil email dari form
            $stmtEmail = mysqli_prepare($koneksi, "UPDATE doctortable SET email = ? WHERE id = ?");
            mysqli_stmt_bind_param($stmtEmail, 'si', $email, $_GET['id']);
            mysqli_stmt_execute($stmtEmail);
            mysqli_stmt_close($stmtEmail);
            
            echo "<script>
                    alert('Edit data succeeded.');
                    document.location='doctordata.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Edit data failed.');
                    document.location='doctordata.php';
                  </script>";
        }
        
        // Tutup statement
        mysqli_stmt_close($stmt);

    } else {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Cek apakah ada file gambar yang diunggah
            if (isset($_FILES['picture']) && $_FILES['picture']['error'] == 0) {
                // Pindahkan file yang diupload ke folder yang diinginkan
                $uploadDir = "../image/Dokter/";
                $uploadFile = $uploadDir . basename($_FILES['picture']['name']);
                
                if (move_uploaded_file($_FILES['picture']['tmp_name'], $uploadFile)) {
                    $picture = $_FILES['picture']['name']; // Simpan nama file gambar
                } else {
                    echo "Error uploading file.";
                    exit;
                }
            } else {
                echo "No file uploaded or error in file upload.";
                exit;
            }
            
            // Data dari form untuk tabel doctor
            $fullname = $_POST['fullname'];
            $schedule = $_POST['schedule'];
            $schedule1 = $_POST['schedule1'];
            $schedule2 = $_POST['schedule2'];
            $schedule3 = $_POST['schedule3'];
            $schedule4 = $_POST['schedule4'];
            $vbranch = $_POST['branch'];
            $vphonenumber = $_POST['phonenumber'];
            $vemail = $_POST['email'];
        
            // Simpan data ke tabel doctor (tanpa username dan password)
            $sql = "INSERT INTO doctor (picture, fullname, schedule, schedule1, schedule2, schedule3, schedule4, branch, phonenumber) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $koneksi->prepare($sql);
            $stmt->bind_param("sssssssss", $picture, $fullname, $schedule, $schedule1, $schedule2, $schedule3, $schedule4, $vbranch, $vphonenumber);
        
            if ($stmt->execute()) {
                $doctor_id = $koneksi->insert_id; // Dapatkan id dari tabel doctor
                
                // Masukkan data ke tabel doctortable (tanpa username dan password, hanya email dan activation_code)
                $activation_code = bin2hex(random_bytes(16)); // Buat kode unik untuk aktivasi
                $sql = "INSERT INTO doctortable (id, activation_code, email) VALUES (?, ?, ?)";
                $stmt = $koneksi->prepare($sql);
                $stmt->bind_param("iss", $doctor_id, $activation_code, $vemail);
        
                if ($stmt->execute()) {
                    // Kirim email aktivasi hanya jika dokter baru ditambahkan
                    $mail = new PHPMailer(true);
                    try {
                        // Konfigurasi SMTP
                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com';
                        $mail->SMTPAuth = true;
                        $mail->Username = 'info.moondental@gmail.com'; // Ganti dengan email
                        $mail->Password = 'gzwctjuskrzzyqxu'; // Ganti dengan password email
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                        $mail->Port = 465;
        
                        $mail->setFrom('info.moondental@gmail.com', 'Moon Dental Clinic');
                        $mail->addAddress($vemail, $fullname);
        
                        $activation_link = "http://localhost:81/Moon%20Dental/admin/verify.php?code=$activation_code";
        
                        // Konten email
                        $mail->isHTML(true);
                        $mail->Subject = 'Doctor Account Activation';
                        $mail->Body = "Hello $fullname,<br><br>Click on this link to activate your account:<br>
                                       <a href='$activation_link'>Activate Account</a><br><br>Thank you.";
        
                        $mail->send();
                        echo "Doctor added successfully. Activation email sent to $vemail.";
                    } catch (Exception $e) {
                        echo "Doctor added successfully, but activation email failed: {$mail->ErrorInfo}";
                    }
                } else {
                    echo "Error inserting into doctortable: " . $stmt->error;
                }
            } else {
                echo "Error inserting into doctor table: " . $stmt->error;
            }
        } else {
            echo "Invalid request.";
        }
    }
}

?>
