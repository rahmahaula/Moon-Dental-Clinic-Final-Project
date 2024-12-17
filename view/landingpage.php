


<?php

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
include "../database/dbconnection.php";

if($koneksi->connect_error){
  echo "$koneksi->connect_error";
  die("Connection Failed : ". $koneksi->connect_error);
}

    $sql = "SELECT picture, fullname, schedule, schedule1, schedule2, schedule3, schedule4, branch FROM doctor"; // Ganti 'doctors' dengan nama tabel Anda
    $result = $koneksi->query($sql);

$doctor = [];
if ($result->num_rows > 0) {
    // Proses hasil query menjadi array
    while($row = $result->fetch_assoc()) {
        $doctor[] = $row;
    }
} else {
    echo "0 results";
}
$koneksi->close();
?>

<?php


if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
include "../database/dbconnection.php";

if($koneksi->connect_error){
  echo "$koneksi->connect_error";
  die("Connection Failed : ". $koneksi->connect_error);
}

    $sql = "SELECT picture, services, price, highlight, descriptions FROM services"; // Ganti 'doctors' dengan nama tabel Anda
    $result = $koneksi->query($sql);

$services = [];
if ($result->num_rows > 0) {
    // Proses hasil query menjadi array
    while($row = $result->fetch_assoc()) {
        $services[] = $row;
    }
} else {
    echo "0 results";
}
$koneksi->close();
?>

<?php


if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
include "../database/dbconnection.php";

if($koneksi->connect_error){
  echo "$koneksi->connect_error";
  die("Connection Failed : ". $koneksi->connect_error);
}

    $sql = "SELECT picture, promo, descriptions, validdate FROM promo"; // Ganti 'doctors' dengan nama tabel Anda
    $result = $koneksi->query($sql);

$promo = [];
if ($result->num_rows > 0) {
    // Proses hasil query menjadi array
    while($row = $result->fetch_assoc()) {
        $promo[] = $row;
    }
} else {
    echo "0 results";
}
$koneksi->close();
?>

<?php


if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
include "../database/dbconnection.php";

if($koneksi->connect_error){
  echo "$koneksi->connect_error";
  die("Connection Failed : ". $koneksi->connect_error);
}

    $sql = "SELECT picture FROM banner"; // Ganti 'doctors' dengan nama tabel Anda
    $result = $koneksi->query($sql);

$banner = [];
if ($result->num_rows > 0) {
    // Proses hasil query menjadi array
    while($row = $result->fetch_assoc()) {
        $banner[] = $row;
    }
} else {
    echo "0 results";
}
$koneksi->close();
?>

<?php


if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
include "../database/dbconnection.php";

if($koneksi->connect_error){
  echo "$koneksi->connect_error";
  die("Connection Failed : ". $koneksi->connect_error);
}

    $sql = "SELECT testimoni, sourcename FROM testimoni"; // Ganti 'doctors' dengan nama tabel Anda
    $result = $koneksi->query($sql);

$testimoni = [];
if ($result->num_rows > 0) {
    // Proses hasil query menjadi array
    while($row = $result->fetch_assoc()) {
        $testimoni[] = $row;
    }
} else {
    echo "0 results";
}
$koneksi->close();
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
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <link rel="stylesheet" href="../css/style.css">
    
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
                <a class="nav-link act" aria-current="page" href="landingpage.php#menu">Home</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="landingpage.php#services">Services</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="landingpage.php#doctors">Doctors</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="landingpage.php#locations">Locations</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="landingpage.php#contact">Contact Us</a>
              </li>
            </ul>
            <a class="btn btn-reserve fw-medium" href="reservation.html" role="button" data-bs-toggle="modal" data-bs-target="#login-modal">LOG IN</a>
          </div>
        </div>
    </nav>
    <!--END NAVBAR-->

    <!--LOGIN MODAL-->
    <div class="modal fade" id="login-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h1 class="modal-title fs-5 fw-semibold" id="staticBackdropLabel">LOGIN</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <form action="../customer/login.php" method="post" >
                <div class="form-group row">
                <div class="">
                    <label class="mb-3" for="">Log in to make a reservation</label>
                  </div>
                  <div class="">
                    <input id="username" name="username" placeholder="Username" type="text" class="form-control w-100" required="required">
                  </div>
                </div>
                <div class="form-group row">
                  <div class="">
                    <input id="password" name="password" placeholder="Password" type="password" class="form-control w-100" required="required">
                  </div>
                </div>
                <div class="form-group row">
                  <div class="">
                  <a href="#" style= "color: red; text-decoration: none;" data-bs-toggle="modal" data-bs-target="#forgotpassword-customer-modal" data-bs-dismiss="modal">Forgot Password</a>
                  </div>
                </div>
                
                <div class="form-group">
                  <div class="mt-5">
                    <button name="login" type="submit" class="btn btn-reserve w-100">LOGIN</button>
                  </div>
                  <div class="text-center mt-3">
                    Don't have an account? <a href="#" style= "color: #5DB8B1; text-decoration: none;" data-bs-toggle="modal" data-bs-target="#signup-modal" data-bs-dismiss="modal">Sign Up</a>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- END LOGIN MODAL -->

    
<!--SIGNUP FORM MODAL-->
<div class="modal fade" id="signup-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h1 class="modal-title fs-5 fw-semibold" id="staticBackdropLabel">SIGN UP</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <form action="../customer/signup.php" method="post" >
                <div class="form-group row">
                  <div class="">
                    <label class="mb-3" for="">Create new account</label>
                  </div>
                  <div class="">
                    <input id="username" name="username" placeholder="Username" type="text" class="form-control w-100" required="required">
                  </div>
                </div>
                <div class="form-group row">
                  <div class="">
                    <input id="email" name="email" placeholder="Email" type="email" class="form-control w-100" required="required">
                  </div>
                </div>
                <div class="form-group row">
                  <div class="">
                    <input id="password" name="password" placeholder="Password" type="text" class="form-control w-100" required="required">
                  </div>
                </div>
                <div class="form-group">
                  <div class="mt-5">
                    <button name="submit" type="submit" class="btn btn-reserve w-100">SIGN UP</button>
                  </div>
                </div>
                <div class="text-center mt-3">
                    Already have an account? <a href="#" style= "color: #5DB8B1; text-decoration: none;" data-bs-toggle="modal" data-bs-target="#login-modal" data-bs-dismiss="modal">Log In</a>
                  </div>
              </form>

              </div>
            </div>
          </div>
        </div>
      <!--END SIGNUP MODAL-->

      <!--FORGOT PASSWORD FORM MODAL FOR CUSTOMER-->
<div class="modal fade" id="forgotpassword-customer-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h1 class="modal-title fs-5 fw-semibold" id="staticBackdropLabel">FORGOT PASSWORD</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <form action="../customer/send_reset_email.php" method="post" >
                <div class="form-group row">
                  <div class="">
                    <label class="mb-3" for="">Enter your registered email</label>
                  </div>
                  <div class="">
                    <input id="email" name="email" placeholder="Email" type="text" class="form-control w-100" required="required">
                  </div>
                </div>
                <div class="form-group">
                  <div class="mt-5">
                    <button name="submit" type="submit" class="btn btn-reserve w-100">SUBMIT</button>
                  </div>
                </div>
                <div class="text-center mt-3">
                    <a href="#" style= "color: #5DB8B1; text-decoration: none;" data-bs-toggle="modal" data-bs-target="#login-modal" data-bs-dismiss="modal">Back</a>
                  </div>
              </form>

              </div>
            </div>
          </div>
        </div>
        <!--END-->


    <!--CAROUSEL-->
    <div id="carouselExampleInterval" class="carousel slide mb-5" data-bs-ride="carousel">
        <div class="carousel-inner">
          <?php if (!empty($banner)): ?>
            <?php foreach ($banner as $banners): ?>
            <div class="carousel-item active" data-bs-interval="4000">
              <img src="../image/Carousel/<?php echo htmlspecialchars($banners['picture']); ?>" class="d-block w-100" alt="...">
            </div>
          <?php endforeach; ?>
            <?php else: ?>
              <p>Tidak ada data carousel.</p>
            <?php endif; ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Next</span>
        </button>
      </div>
      <!--END CAROUSEL-->

      <!--ABOUT US-->
      <div class="menu"></div>
      <div class="container">
      <div class="mb-4">
        <span class="title fw-semibold" style="color: #88bc67;"><center>What is Moon Dental?</center></span>
      </div>
      <div class=about-us>
        <p class="info fw-normal text-center">Moon Dental has become a trusted clinic for patients with teeth and gum problems with good service. With branches spread across Jakarta, Bogor, Depok and Bekasi, Moon Dental is ready to serve you at any time.</p>
      </div>
      </div>
      <!--END ABOUT US-->

      <!--ABOUT US 2-->
        <div class="container d-flex cards-wrapper mt-5">
            <div class="row gy-4">
              <div class="col-md-3 col-sm-6 col-6">
                <div class="card border-0" >
                  <img src="../image/Aset/Dokter.png" class="card-img-top" alt="...">
                  <div class="card-body">
                    <p class="card-text text-center">Certified Doctors</p>
                  </div>
                </div>
              </div>
              <div class="col-md-3 col-sm-6 col-6">
                <div class="card border-0" >
                  <img src="../image/Aset/easylocation.png" class="card-img-top" alt="...">
                  <div class="card-body">
                    <p class="card-text text-center">Easy Locations</p>
                  </div>
                </div>
              </div>
              <div class="col-md-3 col-sm-6 col-6">
                <div class="card border-0" >
                  <img src="../image/Aset/Services.png" class="card-img-top" alt="...">
                  <div class="card-body">
                    <p class="card-text text-center">Good Services</p>
                  </div>
                </div>
              </div>
              <div class="col-md-3 col-sm-6 col-6">
                <div class="card border-0" >
                  <img src="../image/Aset/promo.png" class="card-img-top" alt="...">
                  <div class="card-body">
                    <p class="card-text text-center">Best Promo</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div>
          </div>
          <!--END ABOUT US 2-->

      <!--SERVICES-->
      <div id="services"></div>
        <div class="container mt-5" data-aos="fade-up" data-aos-duration="1000">
            <div class="mb-4">
                <span class="title fw-semibold" style="color: #88bc67;"><center>Our Services</center></span>
                <div>
                  <p class="info mt-4 fw-normal text-center">We provides several services to maintain your healthy teeth and gum.</p>
                </div>
            </div>
            <div class="row gy-4 mt-5">
              <?php if (!empty($services)): ?>
                <?php foreach ($services as $service): ?>
                <div class="col-md-4 col-sm-6">
                  <div class="card" 
                  data-descriptions="<?php echo htmlspecialchars($service['descriptions']); ?>">
                    
                    <img src="../image/Services/<?php echo htmlspecialchars($service['picture']); ?>" class="card-img-top" alt="...">
                    <div class="card-body">
                      <p class="card-title"><?php echo htmlspecialchars($service['services']); ?></p>
                      <p class="card-text">Start from: <?php echo htmlspecialchars($service['price']); ?></p>
                      <p class="card-text deskripsi text-muted"><?php echo htmlspecialchars($service['highlight']); ?></p>
                      <a href="#" class="btn btn-reserve" 
                      data-bs-toggle="modal" 
                      data-bs-target="#servicesModal"
                      data-service="<?php echo htmlspecialchars($service['services']); ?>">Details <img class="next-icon" src="../image/carbon_next-filled.png" alt=""></a>
                    </div>
                  </div>
                </div>
                <?php endforeach; ?>
                <?php else: ?>
                  <p>Tidak ada data layanan.</p>
                <?php endif; ?>

                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div>
        </div>
        <!--END SERVICES-->

        <!--MODAL SERVICES-->
        <div class="modal fade" id="servicesModal" tabindex="-1" aria-labelledby="servicesModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered ">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="servicesModalLabel"><?php echo htmlspecialchars($service['services']); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <p id="modalDescriptions" class="text-start fw-medium mt-2"><p>
              </div>
            </div>
          </div>
        </div>

        <script>
          document.addEventListener('DOMContentLoaded', function () {
            var servicesModal = document.getElementById('servicesModal');
            servicesModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget; // Tombol yang memicu modal
            var card = button.closest('.card'); // Elemen card
            var descriptions = card.getAttribute('data-descriptions');
            var serviceTitle = button.getAttribute('data-service'); // Ambil nama layanan

            // Update judul modal
            var modalTitle = servicesModal.querySelector('#servicesModalLabel');
            modalTitle.textContent = serviceTitle;

            // Update deskripsi
            var modalDescriptions = servicesModal.querySelector('#modalDescriptions');
            modalDescriptions.innerHTML = '';

              // Format deskripsi menjadi baris
              var lines = descriptions.split('\n');
              lines.forEach(function (line) {
                var paragraph = document.createElement('p');
                paragraph.textContent = line.trim();
                modalDescriptions.appendChild(paragraph);
              });
            });
          });
        </script>
        <!--END MODAL SERVICES-->

        <!--DOCTORS-->
        <div id="doctors"></div>
        <div class="container mt-5" data-aos="fade-up" data-aos-duration="1000">
          <div class="mb-5">
            <span class="title fw-semibold" style="color: #88bc67;"><center>Excellent Doctors</center></span>
            <div>
              <p class="info mt-4 fw-normal text-center">With specialist doctors that spread across our branches to take care of your teeth and gum.</p>
            </div>
          </div>

          <div class="container d-flex cards-wrapper mt-5">
            <div class="row gy-4">
            <?php if (!empty($doctor)): ?>
              <?php foreach ($doctor as $doctors): ?>
                <div class="col-md-4 col-lg-3 col-xl-2 col-sm-6 col-6">
                  <div class="card card-doctor" 
                      data-bs-toggle="modal" 
                      data-bs-target="#doctorModal"
                      data-picture="../image/Dokter/<?php echo htmlspecialchars($doctors['picture']); ?>"
                      data-name="<?php echo htmlspecialchars($doctors['fullname']); ?>"
                      data-schedule="<?php echo htmlspecialchars($doctors['schedule']); ?>"
                      data-schedule1="<?php echo htmlspecialchars($doctors['schedule1']); ?>"
                      data-schedule2="<?php echo htmlspecialchars($doctors['schedule2']); ?>"
                      data-schedule3="<?php echo htmlspecialchars($doctors['schedule3']); ?>"
                      data-schedule4="<?php echo htmlspecialchars($doctors['schedule4']); ?>"
                      data-branch="<?php echo htmlspecialchars($doctors['branch']); ?>">
                    <img id="picture" name="picture" src="../image/Dokter/<?php echo htmlspecialchars($doctors['picture']); ?>" class="card-img-top" alt="...">
                    <div class="card-body">
                      <p class="card-text text-center fw-medium deskripsi" id="name" name="name"><?php echo htmlspecialchars($doctors['fullname']); ?></p>
                      <p class="text-muted text-center deskripsi fw-light" id="schedule" name="schedule"><?php echo htmlspecialchars($doctors['schedule']); ?></p>
                      <p class="text-muted text-center deskripsi fw-light" id="branch" name="branch"><?php echo htmlspecialchars($doctors['branch']); ?></p>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php else: ?>
              <p>Tidak ada data dokter.</p>
            <?php endif; ?>
              </div>
            </div>
          </div>

                    <!--MODAL DOCTORS-->
          <div class="modal fade" id="doctorModal" tabindex="-1" aria-labelledby="doctorModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered ">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="doctorModalLabel">Doctor Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <img id="modalPicture" src="" class="img-fluid" alt="Doctor Picture">
                <p id="modalName" class="text-center fw-medium mt-2"p>
                <p id="modalBranch" class="text-muted text-center fw-light" style=""></p>
                <p id="modalSchedule" class="text-muted text-center fw-light"></p>
                <p id="modalSchedule1" class="text-center fw-light"></p>
                <p id="modalSchedule2" class="text-center fw-light"></p>
                <p id="modalSchedule3" class="text-center fw-light"></p>
                <p id="modalSchedule4" class="text-center fw-light"></p>
              </div>
            </div>
          </div>
        </div>

        <script>
          document.addEventListener('DOMContentLoaded', function () {
            var doctorModal = document.getElementById('doctorModal');
            doctorModal.addEventListener('show.bs.modal', function (event) {
              var card = event.relatedTarget;
              var picture = card.getAttribute('data-picture');
              var name = card.getAttribute('data-name');
              var schedule = card.getAttribute('data-schedule');
              var schedule1 = card.getAttribute('data-schedule1');
              var schedule2 = card.getAttribute('data-schedule2');
              var schedule3 = card.getAttribute('data-schedule3');
              var schedule4 = card.getAttribute('data-schedule4');
              var branch = card.getAttribute('data-branch');
              
              var modalPicture = doctorModal.querySelector('#modalPicture');
              var modalName = doctorModal.querySelector('#modalName');
              var modalSchedule = doctorModal.querySelector('#modalSchedule');
              var modalSchedule1 = doctorModal.querySelector('#modalSchedule1');
              var modalSchedule2 = doctorModal.querySelector('#modalSchedule2');
              var modalSchedule3 = doctorModal.querySelector('#modalSchedule3');
              var modalSchedule4 = doctorModal.querySelector('#modalSchedule4');
              var modalBranch = doctorModal.querySelector('#modalBranch');

              modalPicture.src = picture;
              modalName.textContent = name;
              modalSchedule.textContent = schedule;
              modalSchedule1.textContent = schedule1;
              modalSchedule2.textContent = schedule2;
              modalSchedule3.textContent = schedule3;
              modalSchedule4.textContent = schedule4;
              modalBranch.textContent = branch;
            });
          });
        </script>
        <!--END MODAL DOCTOR-->

          <!--MODAL DOCTORS-->
          <div class="modal fade" id="doctorModal" tabindex="-1" aria-labelledby="doctorModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered ">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="doctorModalLabel">Doctor Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <img id="modalPicture" src="" class="img-fluid" alt="Doctor Picture">
                <p id="modalName" class="text-center fw-medium mt-2"p>
                <p id="modalBranch" class="text-muted text-center fw-light" style=""></p>
                <p id="modalSchedule" class="text-muted text-center fw-light"></p>
                <p id="modalSchedule1" class="text-center fw-light"></p>
                <p id="modalSchedule2" class="text-center fw-light"></p>
                <p id="modalSchedule3" class="text-center fw-light"></p>
                <p id="modalSchedule4" class="text-center fw-light"></p>
              </div>
            </div>
          </div>
        </div>

        <script>
          document.addEventListener('DOMContentLoaded', function () {
            var doctorModal = document.getElementById('doctorModal');
            doctorModal.addEventListener('show.bs.modal', function (event) {
              var card = event.relatedTarget;
              var picture = card.getAttribute('data-picture');
              var name = card.getAttribute('data-name');
              var schedule = card.getAttribute('data-schedule');
              var schedule1 = card.getAttribute('data-schedule1');
              var schedule2 = card.getAttribute('data-schedule2');
              var schedule3 = card.getAttribute('data-schedule3');
              var schedule4 = card.getAttribute('data-schedule4');
              var branch = card.getAttribute('data-branch');
              
              var modalPicture = doctorModal.querySelector('#modalPicture');
              var modalName = doctorModal.querySelector('#modalName');
              var modalSchedule = doctorModal.querySelector('#modalSchedule');
              var modalSchedule1 = doctorModal.querySelector('#modalSchedule1');
              var modalSchedule2 = doctorModal.querySelector('#modalSchedule2');
              var modalSchedule3 = doctorModal.querySelector('#modalSchedule3');
              var modalSchedule4 = doctorModal.querySelector('#modalSchedule4');
              var modalBranch = doctorModal.querySelector('#modalBranch');

              modalPicture.src = picture;
              modalName.textContent = name;
              modalSchedule.textContent = schedule;
              modalSchedule1.textContent = schedule1;
              modalSchedule2.textContent = schedule2;
              modalSchedule3.textContent = schedule3;
              modalSchedule4.textContent = schedule4;
              modalBranch.textContent = branch;
            });
          });
        </script>
        <!--END MODAL DOCTOR-->

          <!--LOCATIONS-->
          <div id="locations"></div>
          <section>
            <div class="mt-5 p-5" style="background-color: #F8F9F2;">
              <div class=" container mb-5">
                <span class="title fw-semibold" style="color:#88bc67"><center>Where You Can Find Us</center></span>
                <div class="container">
                  <p class="info mt-4 fw-normal text-center">No need to be worry, we're spread across where you can reach us easily.</p>
                </div>
              </div>
              <div class="locations mb-5">
                <div class="container">
                  <div class="row mt-5 gy-3"> 
                    <div class="col-md-4 col-lg-3">
                      <div class="card card-location" data-aos="fade-up" data-aos-duration="1000">
                        <img src="../image/Cabang/Jakarta.png" class="card-img-top" alt="...">
                          <div class="card-body">
                            <p class="card-title fs-6 text-center">JAKARTA</p>
                            <p class="card-text deskripsi">Jl. Prapanca Raya, Kby. Baru, South Jakarta</p>
                            <p class="card-text deskripsi text-muted fw-light">Open Everyday 08.30 AM - 09.30 PM</p>
                            <a href="https://maps.app.goo.gl/5YSe9UwLoKc6FHa19" class="btn button-maps btn-sm" target="_blank"><i class="bi bi-geo-alt"></i> Maps</a>
                            <a href="https://wa.me//6285694207180" class="btn btn-whatsapp btn-sm" target="_blank"><i class="bi bi-whatsapp"></i> Whatsapp</a>
                          </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-3">
                      <div class="card card-location" data-aos="fade-up" data-aos-duration="1000" >
                        <img src="../image/Cabang/Bogor.png" class="card-img-top" alt="...">
                          <div class="card-body">
                            <p class="card-title fs-6 text-center">BOGOR</p>
                            <p class="card-text deskripsi">Jl. Yasmin Raya, Bogor Bar, Bogor, West Java</p>
                            <p class="card-text deskripsi text-muted fw-light">Open Everyday 08.30 AM - 09.30 PM</p>
                            <a href="https://maps.app.goo.gl/YicNpYHC2brD2d2S8" class="btn button-maps btn-sm" target="_blank"><i class="bi bi-geo-alt"></i> Maps</a>
                            <a href="https://wa.me//6285694207180" class="btn btn-whatsapp btn-sm" target="_blank"><i class="bi bi-whatsapp"></i> Whatsapp</a>
                          </div>
                      </div>
                    </div>
                    <div class="col-md-4 col-lg-3">
                      <div class="card card-location" data-aos="fade-up" data-aos-duration="1000" >
                        <img src="../image/Cabang/Depok.png" class="card-img-top" alt="...">
                          <div class="card-body">
                            <p class="card-title fs-6 text-center">DEPOK</p>
                            <p class="card-text deskripsi">Jl. Bumi Pancoran Mas, Beji, Depok, West Java</p>
                            <p class="card-text deskripsi text-muted fw-light">Open Everyday 08.30 AM - 09.30 PM</p>
                            <a href="https://maps.app.goo.gl/YicNpYHC2brD2d2S8" class="btn button-maps btn-sm" target="_blank"><i class="bi bi-geo-alt"></i> Maps</a>
                            <a href="https://wa.me//6285694207180" class="btn btn-whatsapp btn-sm" target="_blank"><i class="bi bi-whatsapp"></i> Whatsapp</a>
                          </div>
                      </div>
                    </div>
                    <div class="col-md-4 col-lg-3">
                      <div class="card card-3 card-location" data-aos="fade-up" data-aos-duration="1000">
                        <img src="../image/Cabang/Bekasi.png" class="card-img-top" alt="...">
                          <div class="card-body">
                            <p class="card-title fs-6 text-center">BEKASI</p>
                            <p class="card-text deskripsi">Jl. Galaxy Raya, Jaka Setia, Bekasi, West Java</p>
                            <p class="card-text deskripsi text-muted fw-light">Open Everyday 08.30 AM - 09.30 PM</p>
                            <a href="https://maps.app.goo.gl/H5dJHSqTc1LEvq3B8" class="btn button-maps btn-sm" target="_blank"><i class="bi bi-geo-alt"></i> Maps</a>
                            <a href="https://wa.me//6285694207180" class="btn btn-whatsapp btn-sm" target="_blank"><i class="bi bi-whatsapp"></i> Whatsapp</a>
                           </div>
                         </div>
                      </div>
                    </div>
                </div>                
              </div>
            </div>
          </section>
          <!--END LOCATIONS-->

        <!--CONTENT DESCRIPTIONS-->
        <div class="container mt-5 mb-5">
          <div class="row gy-3 mt-5">
            <div class="col-lg-6 left-content">
              <div class="subtitle fw-semibold" style="color: #88bc67;">WHY MOON DENTAL?</div>
              <p class="mt-3">We provide a variety of services for the well-being of your teeth and gums. With certified and expert doctors, we offer surgical procedures, braces installation, teeth whitening, denture placement, and comprehensive dental care for both adults and kids.</p>
            </div>
            <div class="col-lg-6 right-content">
              <div class="subtitle fw-semibold" style="color: #88bc67;">BUNCH OF BIG PROMO!</div>
              <p class="mt-3">With many promotions, surely you can access our services without worrying about expensive fees. Make sure to choose Moon Dental to become part of a healthy family. Once you subscribe, we won't forget about you! There will be many special promotions just for you.</p>
            </div>
          </div>
        </div>
        <!--END CONTENT DESCRIPTIONS-->

        <!--PROMO-->
          <div class="container mt-5">
            <div class="row gy-3 mt-5">
              <?php if (!empty($promo)): ?>
                <?php foreach ($promo as $promos): ?>
                  <div class="col-lg-6">
                    <div class="card card-promo" data-aos="zoom-in" >
                      <img src="../image/Promo/<?php echo htmlspecialchars($promos['picture']); ?>" class="card-img-top" alt="...">
                      <div class="card-body">
                        <p class="card-text text-center fw-semibold smaller-subtitle"><?php echo htmlspecialchars($promos['promo']); ?></p>
                        <p class="fw-light text-center"><?php echo htmlspecialchars($promos['descriptions']); ?></p>
                        <p class="text-center" style="color: #5DB8B1;">Valid Until : <?php echo htmlspecialchars($promos['validdate']); ?></p>
                      </div>
                    </div>
                  </div>  
              <?php endforeach; ?>
              <?php else: ?>
                <p>Tidak ada data promo.</p>
              <?php endif; ?>
            </div>
          </div>
          <!--END PROMO-->

          <!--TESTIMONI-->
          <div class="mt-5" style="background-color: #F8F9F2;" data-aos="fade-up" data-aos-duration="1000">
            <div class=" container pt-5 pb-3">
              <span class="title fw-semibold" style="color:#88bc67"><center>What They Said</center></span>
            </div>
            <!-- Slideshow container -->
                <?php if (!empty($testimoni)): ?>
                  <?php foreach ($testimoni as $testi): ?>
                    <div class="slideshow-container">

                      <!-- Full-width slides/quotes -->
                      <div class="mySlides">
                        <q class="quote"><?php echo htmlspecialchars($testi['testimoni']); ?></q>
                        <p class="author"><?php echo htmlspecialchars($testi['sourcename']); ?></p>
                      </div>

                      <?php endforeach; ?>
                      <?php else: ?>
                        <p>Tidak ada data testimoni.</p>
                      <?php endif; ?>
                    </div>
          </div>

                  <!-- Next/prev buttons -->
                  <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
                  <a class="next" onclick="plusSlides(1)">&#10095;</a>
                </div>

                <!-- Dots/bullets/indicators -->
                <div class="dot-container">
                  <span class="dot" onclick="currentSlide(1)"></span>
                  <span class="dot" onclick="currentSlide(2)"></span>
                  <span class="dot" onclick="currentSlide(3)"></span>
                </div>
          </div>
          <!--END TESTIMONI-->


          <!--LAST CONTENT-->
          <div class="container">
            <div class="mb-4 mt-5">
              <span class="title fw-semibold" style="color: #88bc67;"><center>You're on The Right Place!</center></span>
            </div>
            <div class=about-us>
              <p class="info fw-normal text-center">Make sure you only come to Moon Dental Clinic! Our customers are part of the healthy family, and we will definitely provide the best service for you.</p>
            </div>
            </div>
            <!--END LAST CONTENT-->

            <!--MAPS-->
            <div class="mt-5">
              <iframe style="border:0; width: 100%; height: 250px;" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.1122225953104!2d106.8071343!3d-6.2489403!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f17091b54551%3A0xcc6b5faaaafe816a!2sJl.%20Prapanca%20Raya%2C%20Kec.%20Kby.%20Baru%2C%20Kota%20Jakarta%20Selatan%2C%20Daerah%20Khusus%20Ibukota%20Jakarta!5e0!3m2!1sid!2sid!4v1709539502814!5m2!1sid!2sid" frameborder="0" allowfullscreen></iframe>
            </div>
            <!--END MAPS-->

            <!--FOOTER-->
            <div id="contact"></div>
            <footer class="text-black pt-2" style="background-color: #F8F9F2;">
              <div class="container mx-auto p-5">
                <div class="row">

                  <div class="col-md-3 col-sm-6 col-lg-3 col-xl-3 mx-auto mt-3">
                    <h5 class="text-uppercase mb-4 font-weight-bold theme-text">MOON DENTAL</h5>
                    <p>Dental and gum health solutions for your smile.</p>
                    <i class="bi bi-facebook pe-2 ps-0"></i>
                    <i class="bi bi-instagram p-2"></i>
                    <i class="bi bi-twitter p-2"></i>
                    <i class="bi bi-whatsapp p-2"></i>
                  </div>

                  <div class="col-md-2 col-sm-6 col-lg-2 col-xl-2 mx-auto mt-3 link-footer">
                    <h5 class="text-uppercase mb-4 font-weight-bold theme-text">Services</h5>
                    <p>
                      <a data-bs-toggle="modal" data-bs-target="#login-modal" class="link" style="text-decoration: none;">Dental Filling</a>
                    </p>
                    <p>
                      <a data-bs-toggle="modal" data-bs-target="#login-modal" class="link" style="text-decoration: none;">Teeth Whitening</a>
                    </p>
                    <p>
                      <a data-bs-toggle="modal" data-bs-target="#login-modal" class="link" style="text-decoration: none;">Orthodontic Braces</a>
                    </p>
                    <p>
                      <a data-bs-toggle="modal" data-bs-target="#login-modal" class="link" style="text-decoration: none;">Dentures</a>
                    </p>
                    <p>
                      <a data-bs-toggle="modal" data-bs-target="#login-modal" class="link" style="text-decoration: none;">Dental Care</a>
                    </p>
                    <p>
                      <a data-bs-toggle="modal" data-bs-target="#login-modal" class="link" style="text-decoration: none;">Tooth Extraction</a>
                    </p>
                  </div>

                  <div class="col-md-3 col-sm-6 col-lg-2 col-xl-2 mx-auto mt-3">
                    <h5 class="text-uppercase mb-4 font-weight-bold theme-text">Navigation</h5>
                    <p>
                      <a href="landingpage.php#menu" class="link" style="text-decoration: none;">Home</a>
                    </p>
                    <p>
                      <a href="landingpage.php#service" class="link" style="text-decoration: none;">Services</a>
                    </p>
                    <p>
                      <a href="landingpage.php#doctor" class="link" style="text-decoration: none;">Doctors</a>
                    </p>
                    <p>
                      <a href="landingpage.php#location" class="link" style="text-decoration: none;">Locations</a>
                    </p>
                    <p>
                      <a href="#loginadmin-modal" class="link" data-bs-toggle="modal" data-bs-target="#loginadmin-modal" style="text-decoration: none;">Login Admin</a>
                    </p>
                    <p>
                      <a href="#logindoctor-modal" class="link" data-bs-toggle="modal" data-bs-target="#logindoctor-modal" style="text-decoration: none;">Login Doctor</a>
                    </p>
                  </div>

                  <div class="col-md-4 col-sm-6 col-lg-3 col-xl-3 mx-auto mt-3">
                    <h5 class="text-uppercase mb-4 font-weight-bold theme-text">REACH US AT</h5>
                    <p>
                      <i class="fas fa-envelope mr-3"></i><span class="ps-3">Contact@moondental.com</span>
                    </p>
                    <p>
                      <i class="fas fa-phone mr-3"></i><span class="ps-3">+62 8889 7776</span>
                    </p>
                  </div>

                  </div>
                  <hr>
                    <div class="row align-items-center mt-5">
                      <div class="mx-auto" style="width: 600px;">
                        <p>Copyright ©2021 All rights reserved by
                          <a href="#" style="text-decoration: none;">
                            <span class="text-black">PT. Moon Dental Indonesia</span>
                          </a>
                        </p>
                
                    </div>
                </div>
              </div>
                
              </div>
            </footer>
            <!--END FOOTER-->

            <!--FLOATING WHATSAPP ICON-->
            <a href="https://wa.me/6285694207180" target="_blank" class="floating-whatsapp">
            <img src="https://img.icons8.com/color/48/whatsapp.png" alt="WhatsApp">
            </a>
            <!-- END FLOATING WHATSAPP ICON-->


          
      <!--SCRIPT-->
        <script>
            var slideIndex = 1;
            showSlides(slideIndex);

            function plusSlides(n) {
              showSlides(slideIndex += n);
            }

            function currentSlide(n) {
              showSlides(slideIndex = n);
            }

            function showSlides(n) {
              var i;
              var slides = document.getElementsByClassName("mySlides");
              var dots = document.getElementsByClassName("dot");
              if (n > slides.length) {slideIndex = 1}
                if (n < 1) {slideIndex = slides.length}
                for (i = 0; i < slides.length; i++) {
                  slides[i].style.display = "none";
                }
                for (i = 0; i < dots.length; i++) {
                  dots[i].className = dots[i].className.replace(" active", "");
                }
              slides[slideIndex-1].style.display = "block";
              dots[slideIndex-1].className += " active";
            }
        </script>  
        
        <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
  <script>
    AOS.init();
  </script>
        
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

        <!--LOGIN ADMIN FORM MODAL-->
        <div class="modal fade" id="loginadmin-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h1 class="modal-title fs-5 fw-semibold" id="staticBackdropLabel">LOGIN</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <form action="../admin/login.php" method="post" >
                <div class="form-group row">
                <div class="">
                    <label class="mb-3" for="">Log in admin</label>
                  </div>
                  <div class="">
                    <input id="username" name="username" placeholder="Username" type="text" class="form-control w-100" required="required">
                  </div>
                </div>
                <div class="form-group row">
                  <div class="">
                    <input id="password" name="password" placeholder="Password" type="password" class="form-control w-100" required="required">
                  </div>
                </div>
                
                <div class="form-group">
                  <div class="mt-5">
                    <button name="login" type="submit" class="btn btn-reserve w-100">LOGIN</button>
                  </div>
                </div>
              </form>


              </div>
            </div>
          </div>
        </div>

        <!--LOGIN DOCTOR MODAL-->
    <div class="modal fade" id="logindoctor-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h1 class="modal-title fs-5 fw-semibold" id="staticBackdropLabel">LOGIN</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <form action="../doctor/login.php" method="post" >
                <div class="form-group row">
                <div class="">
                    <label class="mb-3" for="">Log in doctor</label>
                  </div>
                  <div class="">
                    <input id="username" name="username" placeholder="Username" type="text" class="form-control w-100" required="required">
                  </div>
                </div>
                <div class="form-group row">
                  <div class="">
                    <input id="doctorpassword" name="doctorpassword" placeholder="Password" type="password" class="form-control w-100" required="required">
                  </div>
                </div>
                <div class="form-group row">
                  <div class="">
                  <a href="#" style= "color: red; text-decoration: none;" data-bs-toggle="modal" data-bs-target="#forgotpassword-doctor-modal" data-bs-dismiss="modal">Forgot Password</a>
                  </div>
                </div>
                
                <div class="form-group">
                  <div class="mt-5">
                    <button name="login" type="submit" class="btn btn-reserve w-100">LOGIN</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- END LOGIN MODAL -->

    <!--FORGOT PASSWORD FORM MODAL FOR DOCTOR-->
<div class="modal fade" id="forgotpassword-doctor-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h1 class="modal-title fs-5 fw-semibold" id="staticBackdropLabel">FORGOT PASSWORD</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <form action="../doctor/send_reset_email.php" method="post" >
                <div class="form-group row">
                  <div class="">
                    <label class="mb-3" for="">Enter your registered email</label>
                  </div>
                  <div class="">
                    <input id="email" name="email" placeholder="Email" type="text" class="form-control w-100" required="required">
                  </div>
                </div>
                <div class="form-group">
                  <div class="mt-5">
                    <button name="submit" type="submit" class="btn btn-reserve w-100">SUBMIT</button>
                  </div>
                </div>
                <div class="text-center mt-3">
                    <a href="#" style= "color: #5DB8B1; text-decoration: none;" data-bs-toggle="modal" data-bs-target="#logindoctor-modal" data-bs-dismiss="modal">Back</a>
                  </div>
              </form>

              </div>
            </div>
          </div>
        </div>
        <!--END-->

  </body>
  </html>