<?php
session_start();
include '../database/dbconnection.php';

$username = $_SESSION['username'];

// Mengambil data user dari database berdasarkan username
$query = "SELECT * FROM customertable WHERE username='$username'";
$result = mysqli_query($koneksi, $query);
$customer = mysqli_fetch_assoc($result);

if (isset($_POST['username']) && isset($_POST['email'])) {
    $new_username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $new_email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $update_fields = "username='$new_username', email='$new_email'";

    // Jika user upload gambar baru
    if ($_FILES['profile_picture']['name']) {
        $target_dir = "../image/";
        $target_file = $target_dir . basename($_FILES['profile_picture']['name']);
        move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_file);
        
        // Tambahkan update gambar ke fields
        $update_fields .= ", picture='$target_file'";
    }

    // Update query dengan fields yang sudah disiapkan
    $query = "UPDATE customertable SET $update_fields WHERE username='$username'";

    if (mysqli_query($koneksi, $query)) {
        $_SESSION['username'] = $new_username; // Update session username
        echo "<script>
                alert('Profile updated successfully!');
                document.location='profile_customer.php';
              </script>";
    } else {
        echo "<script>
                alert('Profile update failed.');
                document.location='profile_customer.php';
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
        <div class="profile-image d-flex justify-content-center"><img id="profilePreview" src="<?php echo $customer['picture']; ?>" alt="Gambar Profil" width="100%">
    </div>
    <div class="card-body">
        <div class="d-flex justify-content-center">
        </div>
        <form method="POST" enctype="multipart/form-data" action="update_profile.php">
            <div class="d-flex flex-column align-items-start justify-content-center">
            <div class="position-relative mb-2 w-100">
            <input type="file" name="profile_picture" id="profilePictureInput" onchange="previewImage(event)" style="display: none;">
            <div onclick="document.getElementById('profilePictureInput').click()" style="border: 1px solid #a9a9a9; border-radius: 0.25rem; padding: 4px; color: black; cursor: pointer; display: flex; align-items: center; width: 100%;">
            <span style="flex-grow: 1; text-align: left;">Change Profile Picture</span>
            <i class="fa-solid fa-image" style="color: #545454;"></i>
             </div>
            </div>
            <label for="" class="mb-1">Username</label>
            <input class="mb-2 w-100" type="text" name="username" value="<?php echo $customer['username']; ?>" class="form-control" style="border-radius: 0.25rem; border: 1px solid #a9a9a9; padding: 4px; color: black;">
            <label for="" class="mb-1">Email</label>
            <input class="mb-2 w-100" type="email" name="email" value="<?php echo $customer['email']; ?>" class="form-control" style="border-radius: 0.25rem; border: 1px solid #a9a9a9; padding: 4px; color: black;">
            </div>
            <input class="btn btn-reserve w-100" type="submit" value="Update Profile">
          </form>
    </div>
</div>

<!--RESERVATION FORM MODAL-->
<div class="modal fade" id="reservationform-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h1 class="modal-title fs-5 fw-semibold" id="staticBackdropLabel">Make a Reservation</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <form action="../view/reserved.php" method="post" >
                <div class="form-group row">
                  <div class="">
                    <input id="fullname" name="fullname" placeholder="Your name" type="text" class="form-control w-100" required="required">
                  </div>
                </div>
                <div class="form-group row">
                  <div class="">
                    <input id="phonenumber" name="phonenumber" placeholder="Your phone number" type="text" class="form-control w-100" required="required">
                  </div>
                </div>
                <div class="form-group row">
                  <div class="">
                    <input id="email" name="email" placeholder="Your Email" type="text" class="form-control w-100" required="required">
                  </div>
                </div>
                <div class="form-group row">
                  <div class="">
                    <input type="date" id="bookdate" name="bookdate" class="form-control w-100" name="Date" required="required">
                  </div>
                </div>
                <div class="form-group row">
                  <div class="">
                    <select id="branch" name="branch" class="form-select w-100" required="required">
                      <option value="Jakarta">Moon Dental Jakarta</option>
                      <option value="Bogor">Moon Dental Bogor</option>
                      <option value="Depok">Moon Dental Depok</option>
                      <option value="Bekasi">Moon Dental Bekasi</option>
                    </select>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="">
                    <select id="dentalservice" name="dentalservice" class="form-select w-100" required="required">
                      <option value="Dental Filling">Dental Filling</option>
                      <option value="Teeth Whitening">Teeth Whitening</option>
                      <option value="Orthodontic Braces">Orthodontic Braces</option>
                      <option value="Dentures">Dentures</option>
                      <option value="Dental Care">Dental Care</option>
                      <option value="Tooth Extraction">Tooth Extraction</option>
                    </select>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="">
                    <textarea id="note" name="note" cols="40" rows="5" class="form-control w-100" placeholder="Your note"></textarea>
                  </div>
                </div> 
                <div class="form-group">
                  <div class="mt-5">
                    <button name="submit" type="submit" class="btn btn-reserve w-100">SEND</button>
                  </div>
                </div>
              </form>

              </div>
            </div>
          </div>
        </div>


<!-- JavaScript untuk preview gambar -->
<script>
function previewImage(event) {
    var reader = new FileReader();
    reader.onload = function(){
        var output = document.getElementById('profilePreview');
        output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
}
</script>

</body>
<html>
