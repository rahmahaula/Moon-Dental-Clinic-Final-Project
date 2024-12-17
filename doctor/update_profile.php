<?php
session_start();
include '../database/dbconnection.php';

// Periksa apakah dokter sudah login
if (isset($_SESSION['doctor_id'])) {
    $doctor_id = $_SESSION['doctor_id'];
    $current_page = basename($_SERVER['PHP_SELF']); // Mendapatkan nama halaman saat ini

    // Tentukan apakah dokter berada di halaman customerlist.php
    if ($current_page == '../doctor/customerlist.php') {
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
}

// Cek koneksi ke database
if ($koneksi->connect_error) {
    echo "$koneksi->connect_error";
    die("Connection Failed : " . $koneksi->connect_error);
}

// Memastikan user sudah login
if (!isset($_SESSION['username'])) {
    header("location: ../view/landingpage.php");
    exit;
}

// Mendapatkan username dari session
$username = $_SESSION['username'];

// Mengambil data user dari database berdasarkan username
$query = "SELECT d.branch, d.fullname, d.picture, dt.username, dt.email FROM doctor AS d
          JOIN doctortable AS dt ON d.id = dt.id
          WHERE dt.username='$username'";
$result = mysqli_query($koneksi, $query);
$doctor = mysqli_fetch_assoc($result);

// Cek jika form di-submit
if (isset($_POST['email'])) {
    $new_email = mysqli_real_escape_string($koneksi, $_POST['email']);
    
    // Inisialisasi query update
    $update_fields = "email='$new_email'"; // Field yang selalu diperbarui

    // Jika password baru diisi, hash dan tambahkan ke fields
    if (!empty($_POST['new_password'])) {
        $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
        $update_fields .= ", doctorpassword='$new_password'"; // Tambahkan password jika diisi
    }

    // Buat query update
    $query = "UPDATE doctortable SET $update_fields WHERE username='$username'";

    if (mysqli_query($koneksi, $query)) {
        echo "<script>
                alert('Profile updated successfully!');
                document.location='profile_doctor.php';
              </script>";
    } else {
        echo "<script>
                alert('Profile update failed.');
                document.location='profile_doctor.php';
              </script>";
    }
}
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Moon Dental Clinic</title>
    <link rel="icon" href="../image/logo2.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <script defer src="script.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .container-card{
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .card{
            border: 0;
        }
        .card-body{
            text-align: center;
        }

        .profile-image img {
        width: 80%;
        height: 80%;
        justify-content: center;
        border-radius: 50%; /* Makes the image circular */
        object-fit: cover; /* Keeps the image centered and scaled */
        }

        .btn-logout{
        background-color: rgba(0, 123, 255, 0); border: 2px solid red; color: red;
        }    

        .btn-logout:hover{
            background-color: #FFDDDD; 
            border: 2px solid red; 
            color: red;
        }

        /* Breadcrumb Styling */
.breadcrumb {
    background-color: #f3f4f6;
    padding: 8px 16px;
    margin-bottom: 20px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    font-size: 0.9rem;
}

.breadcrumb-item + .breadcrumb-item::before {
    content: ">";
    color: #6c757d;
    padding: 0 10px;
}

.breadcrumb a {
    color: #6c757d;
    text-decoration: none;
    transition: color 0.3s ease;
}

.breadcrumb a:hover {
    color: #5DB8B1;
    text-decoration: underline;
}

.breadcrumb .breadcrumb-item.active {
    color: #6c757d;
    font-weight: bold;
}
    </style>
<body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>

<!--NAVBAR-->
<nav class="container-fluid navbar navbar-expand-lg sticky-top mt-3 ">
    <div class="container">
        <a href="index.php">
            <img class="logo" src="../image/logo.png" alt="">
            <a class="navbar-brand theme-text" href="#menu"></a>
        </a>
      
      <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="offcanvas offcanvas-end w-75" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
        <div class="offcanvas-header">
          <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Moon Dental</h5>
          <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
          <div class="offcanvas-body">
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link" href="../view/index.php#menu">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../view/index.php#services">Services</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../view/index.php#doctors">Doctors</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../view/index.php#locations">Locations</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../view/index.php#contact">Contact Us</a>
          </li>
          <li class="nav-item">
          <?php if ($_SESSION['user_type'] == 'doctor'): ?>
              <a class="nav-link act" aria-current="page" href="../doctor/profile_doctor.php">Profile</a>
          <?php else: ?>
              <a class="nav-link act" aria-current="page" href="../customer/profile_customer.php">Profile</a>
          <?php endif; ?>
          </li>
          <li class="nav-item">
          <?php if ($_SESSION['user_type'] == 'doctor'): ?>
              <a class="nav-link" href="../chat/customerlist.php">My Chat</a>
          <?php else: ?>
              <a class="nav-link" href="../chat/doctorlist.php">My Chat</a>
          <?php endif; ?>
          </li>

        </ul>
        
      </div>
    </div>
</nav>
<!--END NAVBAR-->

    <!-- Breadcrumbs -->
    <div class="container mt-3">
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="profile_doctor.php">Profile</a></li>
        <?php
            // Menentukan halaman saat ini berdasarkan nama file
            $current_page = basename($_SERVER['PHP_SELF']);
            if ($current_page == 'update_profile.php') {
                echo '<li class="breadcrumb-item act" aria-current="page">Change Email</li>';
            } elseif ($current_page == 'update_password.php') {
                echo '<li class="breadcrumb-item act" aria-current="page">Change Password</li>';
            }
        ?>
    </ol>
</nav>
</div>

<div class="container container-card mb-5">


    <div class="card mt-5" style="width: 18rem;">
        <div class="profile-image d-flex justify-content-center"><img src="../image/Dokter/<?php echo htmlspecialchars($doctor['picture']); ?>" alt="Gambar Profil" width="100%" class="profile-img"></div>
            <div class="card-body">
                <h5 class="card-title mt-2"><?php echo htmlspecialchars($doctor['fullname']); ?></h5>
                <div class="d-flex justify-content-center">
                <span><i class="fa-solid fa-location-dot"></i>&nbsp<?php echo htmlspecialchars($doctor['branch']); ?></span>
            </div>
                <p class="card-text text-muted mb-0 mt-2"><?php echo htmlspecialchars($doctor['username']); ?></p>
                <p class="card-text text-muted mb-4"><?php echo htmlspecialchars($doctor['email']); ?></p>
                <form method="POST" action="update_profile.php">
            <div class="d-flex justify-content-center">
                <input class="mb-2 w-100" type="email" name="email" value="<?php echo htmlspecialchars($doctor['email']); ?>" required class="form-control btn-primary" style="border-radius: 0.25rem; border: 1px solid #a9a9a9; padding: 4px; color: black;">
            </div>
            <input class="btn btn-reserve w-100" type="submit" value="Change Email">
                </form>
          </div>
          </div>

</div>

</body>
</html>
