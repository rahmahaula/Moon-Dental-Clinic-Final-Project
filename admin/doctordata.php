<?php
session_start();
include "../database/dbconnection.php";
require '../vendor/autoload.php'; // Autoload untuk PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Jika tombol simpan diklik
if(isset($_POST['bsimpan'])) {
    // Pengujian Apakah Data Akan Di Edit Atau Disimpan Baru
    if(isset($_GET['hal'])){
      if($_GET['hal'] == "edit") {
        // Data akan di edit
        // Ambil data gambar lama dari database
        $stmtOldPicture = mysqli_prepare($koneksi, "SELECT picture FROM doctor WHERE id = ?");
        mysqli_stmt_bind_param($stmtOldPicture, 'i', $_GET['id']);
        mysqli_stmt_execute($stmtOldPicture);
        $resultOldPicture = mysqli_stmt_get_result($stmtOldPicture);
        $dataOldPicture = mysqli_fetch_array($resultOldPicture);
        $oldPicture = $dataOldPicture['picture']; // Gambar lama

        // Cek jika ada gambar baru yang diupload
        if (isset($_FILES['picture']) && $_FILES['picture']['error'] == 0) {
          // Pindahkan file yang diupload ke folder yang diinginkan
          $uploadDir = "../image/Dokter/";
          $uploadFile = $uploadDir . basename($_FILES['picture']['name']);
          
          if (move_uploaded_file($_FILES['picture']['tmp_name'], $uploadFile)) {
              $picture = $_FILES['picture']['name']; // Simpan nama file gambar baru
          } else {
              echo "Error uploading file.";
              exit;
          }
      } else {
          // Jika tidak ada gambar baru, gunakan gambar lama
          $picture = $oldPicture;
      }

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
  mysqli_stmt_bind_param($stmt, 'sssssssssi', $picture, $_POST['fullname'], $_POST['schedule'], $_POST['schedule1'], $_POST['schedule2'], $_POST['schedule3'], $_POST['schedule4'], $_POST['branch'], $_POST['phonenumber'], $_GET['id']);

  // Eksekusi statement
  mysqli_stmt_execute($stmt);

  // Periksa hasil eksekusi
  if (mysqli_stmt_affected_rows($stmt) > 0) {
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
    }
    

    } else {
        // Data Akan Disimpan Baru
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){


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
                  
                  echo "<script>
                  alert('Doctor added successfully. Activation email sent to $vemail.');
                  document.location='doctordata.php';
                  </script>" ;
              } catch (Exception $e) {
                  echo "<script>
                    alert('Doctor added successfully, but activation email failed: {$mail->ErrorInfo}');
                    document.location='doctordata.php';
                    </script>" ;
              }
          } else {
            echo "<script>
                    alert('Doctor added successfully, but activation email failed: {$mail->ErrorInfo}');
                    document.location='doctordata.php';
                    </script>" ;
          }
      } 
    }
}
}

// Pengujian jika tombol Edit / Hapus di klik
if(isset($_GET['hal'])) {
    // Pengujian Jika Edit Data
    if($_GET['hal'] == "edit") {

        // Tampilkan Data Yang Akan Diedit
        $stmt = mysqli_prepare($koneksi, "SELECT * FROM doctor WHERE id = ?");
        mysqli_stmt_bind_param($stmt, 'i', $_GET['id']);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        // Fetch data
        $data = mysqli_fetch_array($result);
        
        if($data) {
            // Jika data ditemukan, maka data akan di tampung ke dalam variabel
            $vpicture = $data['picture'];
            $vfullname = $data['fullname'];
            $vschedule = $data['schedule'];
            $vschedule1 = $data['schedule1'];
            $vschedule2 = $data['schedule2'];
            $vschedule3 = $data['schedule3'];
            $vschedule4 = $data['schedule4'];
            $vbranch = $data['branch'];
            $vphonenumber = $data['phonenumber'];

            // Ambil email dari doctortable
            $stmtEmail = mysqli_prepare($koneksi, "SELECT email FROM doctortable WHERE id = ?");
            mysqli_stmt_bind_param($stmtEmail, 'i', $_GET['id']);
            mysqli_stmt_execute($stmtEmail);
            $resultEmail = mysqli_stmt_get_result($stmtEmail);
            $dataEmail = mysqli_fetch_array($resultEmail);
            $vemail = $dataEmail['email']; // Simpan email untuk ditampilkan di form
            mysqli_stmt_close($stmtEmail);
        }

        // Tutup statement
        mysqli_stmt_close($stmt);

    } else if ($_GET['hal'] == "hapus") {
      // Persiapan Hapus Data dari doctortable
      $stmtDeleteDoctor = mysqli_prepare($koneksi, "DELETE FROM doctortable WHERE id = ?");
      mysqli_stmt_bind_param($stmtDeleteDoctor, 'i', $_GET['id']);
      mysqli_stmt_execute($stmtDeleteDoctor);
  
      // Setelah menghapus dari doctortable, hapus juga dari doctor
      $stmtDeleteDoctorData = mysqli_prepare($koneksi, "DELETE FROM doctor WHERE id = ?");
      mysqli_stmt_bind_param($stmtDeleteDoctorData, 'i', $_GET['id']);
      mysqli_stmt_execute($stmtDeleteDoctorData);
      
      // Periksa hasil eksekusi
      if (mysqli_stmt_affected_rows($stmtDeleteDoctor) > 0 && mysqli_stmt_affected_rows($stmtDeleteDoctorData) > 0) {
          echo "<script>
                  alert('Delete data succeeded.');
                  document.location='doctordata.php';
                </script>";
      } else {
          echo "<script>
                  alert('Delete data failed.');
                  document.location='doctordata.php';
                </script>";
      }
      
      // Tutup statement
      mysqli_stmt_close($stmtDeleteDoctor);
      mysqli_stmt_close($stmtDeleteDoctorData);
  }
}  

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Doctor Data</title>
	
	<link rel="icon" href="../image/logo2.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
	<link rel="stylesheet" href="../css/style.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
  
  <link rel="canonical" href="https://getbootstrap.com/docs/5.3/examples/sidebars/">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@docsearch/css@3">

<link href="/docs/5.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- ... (bagian head lainnya) ... -->
    <style>
    /* Sidebar Styles */
    .sidebar {
        position: fixed;
		width: 200px;
        height: 100%;
        overflow-y: auto; /* Tambahkan ini agar sidebar dapat di-scroll jika kontennya lebih panjang dari tinggi layar */
    }

    /* Form Styles */
    .form-container {
        margin-left: 280px; /* Sesuaikan dengan lebar sidebar */
        padding: 20px; /* Sesuaikan sesuai kebutuhan untuk memberikan ruang agar tidak tertutup oleh sidebar */
    }

    /* Table Styles */
    .table-container {
        width: calc(100% - 200px); /* Sesuaikan dengan lebar sidebar */
        padding: 10px; /* Sesuaikan sesuai kebutuhan untuk memberikan ruang agar tidak tertutup oleh sidebar */
		margin-left: auto; /* Menjorokkan ke kanan */
        margin-right: 0; 
    }

	.nav-link.active {
        background-color: #88bc67 !important;/*Dengan menambahkan !important, Anda memastikan bahwa aturan CSS tersebut lebih kuat daripada aturan yang mungkin sudah ada sebelumnya.*/
    }

	.nav-pills .nav-link:hover {
    background-color: #A6DF82; /* Warna latar belakang saat dihover */
    color: #fff; /* Warna teks saat dihover */
}


</style>

</head>
<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  
<div class="d-flex">
<div class="sidebar d-flex flex-column flex-shrink-0 p-3 bg-body-tertiary" style="width: 200px; height: 100vh;">
    <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
      <span class="fs-4 fw-bold">Moon Dental</span>
    </a>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
	<li>
	  <a style="margin-bottom:18px;" class="btn btn-reserve fw-medium w-100" href="customerdata.php" role="button">CUSTOMER</a>
	  <a style="margin-bottom:18px;" class="btn btn-reserve fw-medium w-100" href="doctordata.php" role="button">DOCTOR</a>
	  <a style="margin-bottom:18px;" class="btn btn-reserve fw-medium w-100" href="promodata.php" role="button">PROMO</a>
    <a style="margin-bottom:18px;" class="btn btn-reserve fw-medium w-100" href="servicesdata.php" role="button">SERVICES</a>
    <a style="margin-bottom:18px;" class="btn btn-reserve fw-medium w-100" href="testimonidata.php" role="button">TESTIMONI</a>
    <a style="margin-bottom:18px;" class="btn btn-reserve fw-medium w-100" href="bannerdata.php" role="button">BANNER</a>
	  <a style="background-color: red;" class="btn btn-reserve fw-medium w-100" href="../view/landingpage.php" role="button" onclick="return confirm('Are you sure want to log out?')" >LOGOUT</a>
      </li>
    </ul>
    <hr>
    <!--<<div class="dropdown">
      <a href="#" class="d-flex align-items-center link-body-emphasis text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
        <strong>mdo</strong>
      </a>
      ul class="dropdown-menu text-small shadow">
        <li><a class="dropdown-item" href="#">New project...</a></li>
        <li><a class="dropdown-item" href="#">Settings</a></li>
        <li><a class="dropdown-item" href="#">Profile</a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item" href="#">Sign out</a></li>
      </ul>-->
    </div>
  </div>

  <div class="container">
	<!--Awal Card Form -->
	<div class="container mt-4 mb-5">
	
    <form class="form-container" method="post" action="" enctype="multipart/form-data">
	<div class="mb-5">
	<span class="subtitle fw-semibold"><center>Moon Dental Clinic Doctor Data</center></span>
	</div>
	<div class="form-group row">
    <label class="col-4 col-form-label" for="picture">Picture</label> 
    <div class="col-8">

        <input type="file" class="form-control" id="picture" name="picture" accept="image/*" value="<?=isset($vpicture) ? $vpicture : ''?>" >

    </div>
</div>


        <div class="form-group row">
          <label class="col-4 col-form-label" for="fullname">Name</label> 
          <div class="col-8">
            <input id="fullname" name="fullname" placeholder="Doctor Name" type="text" class="form-control" value="<?=isset($vfullname) ? $vfullname : ''?>" required="required">
          </div>
        </div>
        <div class="form-group row">
          <label for="schedule" class="col-4 col-form-label">Schedule</label> 
          <div class="col-8">
            <input id="schedule" name="schedule" placeholder="Doctor Schedule" type="text" class="form-control" value="<?=isset($vschedule) ? $vschedule : ''?>" required="required">
          </div>
        </div>
        <div class="form-group row">
          <label for="schedule1" class="col-4 col-form-label">Schedule 1</label> 
          <div class="col-8">
            <input id="schedule1" name="schedule1" placeholder="Schedule 1" type="text" class="form-control" value="<?=isset($vschedule1) ? $vschedule1 : ''?>" required="required">
          </div>
        </div>
        <div class="form-group row">
          <label for="schedule2" class="col-4 col-form-label">Schedule 2</label> 
          <div class="col-8">
            <input id="schedule2" name="schedule2" placeholder="Schedule 2" type="text" class="form-control" value="<?=isset($vschedule2) ? $vschedule2 : ''?>" required="required">
          </div>
        </div>
        <div class="form-group row">
          <label for="schedule3" class="col-4 col-form-label">Schedule 3</label> 
          <div class="col-8">
            <input id="schedule3" name="schedule3" placeholder="Schedule 3" type="text" class="form-control" value="<?=isset($vschedule3) ? $vschedule3 : ''?>" required="required">
          </div>
        </div>
        <div class="form-group row">
          <label for="schedule4" class="col-4 col-form-label">Schedule 4</label> 
          <div class="col-8">
            <input id="schedule4" name="schedule4" placeholder="Schedule 4" type="text" class="form-control" value="<?=isset($vschedule4) ? $vschedule4 : ''?>" >
          </div>
        </div>
        <div class="form-group row">
          <label for="branch" class="col-4 col-form-label">Branch</label> 
          <div class="col-8">
            <select id="branch" name="branch" class="form-select" required="required">
            <option value="Jakarta" <?= isset($vbranch) && $vbranch == 'Jakarta' ? 'selected' : '' ?>>Jakarta</option>
			<option value="Bogor" <?= isset($vbranch) && $vbranch == 'Bogor' ? 'selected' : '' ?>>Bogor</option>
			<option value="Depok" <?= isset($vbranch) && $vbranch == 'Depok' ? 'selected' : '' ?>>Depok</option>
			<option value="Bekasi" <?= isset($vbranch) && $vbranch == 'Bekasi' ? 'selected' : '' ?>>Bekasi</option>
            </select>
            <span id="radioHelpBlock" class="form-text text-muted">Click to choose</span>
          </div>
        </div>
		    <div class="form-group row">
          <label for="phonenumber" class="col-4 col-form-label">Phone Number</label> 
          <div class="col-8">
            <input id="phonenumber" name="phonenumber" placeholder="Phone Number" type="text" class="form-control" value="<?=isset($vphonenumber) ? $vphonenumber : ''?>" required="required">
          </div>
        </div>
        <div class="form-group row">
          <label for="schedule" class="col-4 col-form-label">Email</label> 
          <div class="col-8">
            <input id="email" name="email" placeholder="Email" type="text" class="form-control" value="<?=isset($vemail) ? $vemail : ''?>" required="required">
          </div>
        </div>
		<div class="mt-3">
			<button type="submit" class="btn btn-reserve"name="bsimpan">SAVE</button>
		</div>
      </form>
</div>
</div>

</div>

	<div class="table-container">
		<span class="subtitle fw-semibold"><center>Doctor Table</center></span>
		  	<table class="table  table-striped mt-5">
		  		<tr>
				  <th class="col-1">ID</th>
                        <th class="col-2">Picture</th>
                        <th class="col-2">Doctor</th>
                        <th class="col-1">Schedule</th>
                        <th class="col-1">Schedule 1</th>
                        <th class="col-1">Schedule 2</th>
                        <th class="col-1">Schedule 3</th>
                        <th class="col-1">Schedule 4</th>
                        <th class="col-1">Branch</th>
						<th class="col-1">Phone Number</th>
            <th class="col-1">Email</th>
                        <th class="col-2">Action</th>

		  		</tr>
		  		<?php
                        // Ambil data dari database
                        $stmt = mysqli_prepare($koneksi, "SELECT doctor.*, doctortable.email FROM doctor JOIN doctortable ON doctor.id = doctortable.id");
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);
                        $no = 1;
                        while ($data = mysqli_fetch_array($result)) {
                            echo "<tr>
                                    <td>$no</td>
                                    <td><img src='../image/Dokter/{$data['picture']}' width='50'></td>
                                    <td>{$data['fullname']}</td>
                                    <td>{$data['schedule']}</td>
                                    <td>{$data['schedule1']}</td>
                                    <td>{$data['schedule2']}</td>
                                    <td>{$data['schedule3']}</td>
                                    <td>{$data['schedule4']}</td>
                                    <td>{$data['branch']}</td>
                                    <td>{$data['phonenumber']}</td>
                                    <td>{$data['email']}</td>
                                    <td>
                                        <a href='doctordata.php?hal=edit&id={$data['id']}'><i class='fa-solid fa-pen-to-square'></i></a>
                                        &nbsp
                                        &nbsp
                                        <a href='doctordata.php?hal=hapus&id={$data['id']}' onclick='return confirm(\"Are you sure want to delete this data?\");'><i class='fa-solid fa-trash' style='color: red;' ></i></a>
                                    </td>
                                  </tr>";
                            $no++;
                        }
                        mysqli_stmt_close($stmt);
                        ?>
		  	</table>

		  </div>
		</div>
		

<script type="text/javascript" src="js/bootstrap.min.js"></script>
</body>
</html>
