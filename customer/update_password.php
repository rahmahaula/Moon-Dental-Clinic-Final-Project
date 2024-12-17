<?php
session_start();
include '../database/dbconnection.php';

$username = $_SESSION['username'];

// Mengambil data user dari database berdasarkan username
$query = "SELECT * FROM customertable WHERE username='$username'";
$result = mysqli_query($koneksi, $query);
$customer = mysqli_fetch_assoc($result);

if (isset($_POST['old_password']) && isset($_POST['new_password']) && isset($_POST['confirm_password'])) {
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Cek apakah password lama benar
    if (password_verify($old_password, $customer['password'])) {
        // Cek apakah password baru sama dengan konfirmasi
        if ($new_password === $confirm_password) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            // Update password di database
            $update_query = "UPDATE customertable SET password='$hashed_password' WHERE username='$username'";

            if (mysqli_query($koneksi, $update_query)) {
                echo "<script>
                        alert('Password successfully updated!');
                        document.location='profile_customer.php';
                      </script>";
            } else {
                echo "<script>
                        alert('Failed to update password.');
                        document.location='update_password.php';
                      </script>";
            }
        } else {
            echo "<script>
                    alert('New password and confirm password do not match.');
                    document.location='update_password.php';
                  </script>";
        }
    } else {
        echo "<script>
                alert('Old password is incorrect.');
                document.location='update_password.php';
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
        <a class="btn btn-reserve fw-medium" href="reservation.html" role="button" data-bs-toggle="modal" data-bs-target="#reservationform-modal">RESERVE NOW</a>
      </div>
    </div>
</nav>
<!--END NAVBAR-->

    <!-- Breadcrumbs -->
    <div class="container mt-3">
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="profile_customer.php">Profile</a></li>
        <?php
            // Menentukan halaman saat ini berdasarkan nama file
            $current_page = basename($_SERVER['PHP_SELF']);
            if ($current_page == 'update_profile.php') {
                echo '<li class="breadcrumb-item act" aria-current="page">Manage Account</li>';
            } elseif ($current_page == 'update_password.php') {
                echo '<li class="breadcrumb-item act" aria-current="page">Change Password</li>';
            } elseif ($current_page == 'reservation_history.php') {
                echo '<li class="breadcrumb-item act" aria-current="page">Reservation History</li>';
            }
        ?>
    </ol>
</nav>
</div>


<div class="container container-card mb-5">


    <div class="card mt-5" style="width: 18rem;">
    <div class="profile-image d-flex justify-content-center"><img src="../image/<?php echo $customer['picture']; ?>" alt="Gambar Profil" width="100%" class="profile-img"></div>
    <div class="card-body">
    <h5 class="card-title mt-2"><?php echo $customer['username']; ?></h5>
        <p class="card-text text-muted mb-4"><?php echo $customer['email']; ?></p>
        <form method="POST" action="update_password.php">
        <div class="d-flex flex-column align-items-start">
        <label for="old_password" class="mb-1">Old Password</label>
        <input class="mb-2 w-100" type="password" name="old_password" required class="form-control" style="border-radius: 0.25rem; border: 1px solid #a9a9a9; padding: 4px; color: black;">
        
        <label for="new_password" class="mb-1">New Password</label>
        <input class="mb-2 w-100" type="password" name="new_password" required class="form-control" style="border-radius: 0.25rem; border: 1px solid #a9a9a9; padding: 4px; color: black;">
        
        <label for="confirm_password" class="mb-1">Confirm New Password</label>
        <input class="mb-2 w-100" type="password" name="confirm_password" required class="form-control" style="border-radius: 0.25rem; border: 1px solid #a9a9a9; padding: 4px; color: black;">
        </div>

        <input class="btn btn-reserve w-100 mt-2" type="submit" value="Change Password">
        </form>

          </div>
          </div>
          <body>
            <html>

