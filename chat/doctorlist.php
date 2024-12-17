<?php
session_start();
include '../database/dbconnection.php';

// Pastikan user sudah login
if (!isset($_SESSION['username'])) {
    header("location: ../view/landingpage.php");
    exit;
}

// Ambil ID customer dari session
$customer_id = $_SESSION['id']; // ID customer yang login


// Ambil daftar dokter dari database
$query = "SELECT d.id AS doctor_id, dt.doctor_id, dt.username, d.picture, d.branch, d.fullname 
          FROM doctor d 
          JOIN doctortable dt ON d.id = dt.id";
$result = mysqli_query($koneksi, $query);

if (!$result) {
    echo "Error: " . mysqli_error($koneksi);
    exit;
}

// Cek apakah dokter sedang aktif dalam chat dengan customer lain
function isChatActive($doctor_id) {
    global $koneksi;
    
    // Cari chat yang aktif dengan dokter tersebut
    $check_chat_query = "SELECT * FROM chat 
                         WHERE doctor_id = $doctor_id 
                         AND status = 'active'";

    $check_chat_result = mysqli_query($koneksi, $check_chat_query);
    
    // Jika ada chat aktif, berarti dokter sedang dalam percakapan dengan customer lain
    return mysqli_num_rows($check_chat_result) > 0;
}

// Fungsi untuk mengecek apakah dokter sedang login dan berada di customerlist.php
function isDoctorOnline($doctor_id) {
  global $koneksi;

  // Cari sesi aktif dari dokter
  $query = "SELECT * FROM doctor_sessions 
            WHERE doctor_id = '$doctor_id' 
            AND is_active = 1 
            AND current_page = 'customerlist.php'";

  $result = mysqli_query($koneksi, $query);

  // Jika ada sesi aktif yang sesuai, dokter sedang online dan berada di customerlist.php
  return mysqli_num_rows($result) > 0;
}


// Inisialisasi variabel pencarian
$search = "";
if (isset($_POST['search'])) {
    $search = mysqli_real_escape_string($koneksi, $_POST['search']);
}

// Ambil daftar dokter dari database
$query = "SELECT d.id AS doctor_id, dt.doctor_id, dt.username, d.picture, d.branch, d.fullname 
          FROM doctor d 
          JOIN doctortable dt ON d.id = dt.id";


// Tambahkan filter jika ada pencarian
if ($search !== "") {
  $query .= " WHERE d.fullname LIKE '%$search%'";
}

$result = mysqli_query($koneksi, $query);


// Periksa apakah query berhasil
if (!$result) {
    echo "Error: " . mysqli_error($koneksi);
    exit;
}


?>


<!DOCTYPE html>
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
      .search{
        border-radius: 50px 0px 0px 50px;
      }

      .btn-search{
        border-radius: 0px 50px 50px 0px;
      }

      .search-input{
        width: 50%;
      }

      @media (max-width: 768px) {
            .search-input {
                width: 100%;
            }
          }
    </style>
</head>

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
                <a class="nav-link"  href="../view/index.php#menu">Home</a>
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
              <li class="nav-item" >
                <a class="nav-link" href="../view/index.php#contact">Contact Us</a>
              </li>
              <li class="nav-item" >
              <?php if ($_SESSION['user_type'] == 'doctor'): ?>
                  <a class="nav-link" href="../doctor/profile_doctor.php">Profile</a>
              <?php else: ?>
                  <a class="nav-link" href="../customer/profile_customer.php">Profile</a>
              <?php endif; ?>
              </li>
              <li class="nav-item">
              <?php if ($_SESSION['user_type'] == 'doctor'): ?>
                  <a class="nav-link act" aria-current="page" href="../chat/customerlist.php">My Chat</a>
              <?php else: ?>
                  <a class="nav-link act" aria-current="page" href="../chat/doctorlist.php">My Chat</a>
              <?php endif; ?>
              </li>

            </ul>
            <a class="btn btn-reserve fw-medium" href="reservation.html" role="button" data-bs-toggle="modal" data-bs-target="#reservationform-modal">RESERVE NOW</a>
          </div>
        </div>
    </nav>
    <!--END NAVBAR-->

    <!-- Doctor List -->
    <div class="container mb-5">
        <div class="mb-4 mt-4">
            <span class="title fw-semibold" style="color: #88bc67;"><center>Make a Consultation</center></span>
            <div>
                <p class="info mt-4 fw-normal text-center">Make a consultation with our doctors. Consultation can only be done for <span style="color: red;" >10 minutes</span> per day</p>
            </div>
        </div>

        <!-- Form Pencarian -->
        <form method="post" action="" class="mb-4 d-flex justify-content-center">
            <div class="input-group search-input" >
                <input type="text" name="search" class="form-control search" placeholder="Search doctor by name" value="<?php echo htmlspecialchars($search); ?>">
                <button class="btn btn-reserve btn-search" type="submit"><i class="fa-solid fa-magnifying-glass" style="color: white;" ></i></button>
            </div>
        </form>

        <div class="doctorlist-card">
            <div class="row gy-4">
                <?php while ($doctor = mysqli_fetch_assoc($result)): ?>
                    <div class="col-12 col-sm-6"> 
                        <div class="card" style="background-color: #e6ebe2;">
                            <div class="row g-0">
                                <div class="col-4 col-sm-4"> 
                                    <img src="../image/Dokter/<?php echo htmlspecialchars($doctor['picture']); ?>" alt="Doctor Picture" class="img-fluid rounded-start">
                                </div>
                                <div class="col-8 col-sm-8"> 
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo htmlspecialchars($doctor['fullname']); ?></h5>
                                        <p class="card-text" style="color:#545454 "><i class="fa-solid fa-stethoscope" style="color: #545454"></i>&nbsp Dentist</p>
                                        <p class="card-text-branch" style="background-color: white; padding: 4px 10px 4px 10px; display: inline-block;  border-radius: 5px; color:#545454"><i class="fa-solid fa-location-dot" style="color: #545454;"></i> <?php echo htmlspecialchars($doctor['branch']); ?></p>
                                        <p class="card-text">
                                        <?php if (isChatActive($doctor['doctor_id'])): ?>
                                            <button class="btn btn-secondary" disabled><i class="fa-solid fa-headset" style="color: white;"></i>&nbsp Unavailable</button>
                                        <?php elseif (!isDoctorOnline($doctor['doctor_id'])): ?>
                                            <button class="btn btn-warning" disabled><i class="fa-solid fa-user-slash" style="color:white;"></i>&nbsp Offline</button>
                                        <?php else: ?>
                                            <a class="btn btn-reserve fw-medium" href="mychat.php?doctor_id=<?php echo htmlspecialchars($doctor['doctor_id']); ?>" role="button"><i class="fa-solid fa-headset" style="color: white;"></i>&nbsp Chat</a>
                                        <?php endif; ?>
                                    </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
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

</body>
</html>
