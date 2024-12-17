<?php
session_start();
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


// Inisialisasi PHPMailer
$mail = new PHPMailer(true); // Passing true enables exceptions


try {
    // Konfigurasi SMTP
    $mail->isSMTP(); // Menggunakan SMTP
    $mail->Host = 'smtp.gmail.com'; // Alamat SMTP server Gmail
    $mail->SMTPAuth = true; // Aktifkan autentikasi SMTP
    $mail->Username = 'info.moondental@gmail.com'; // Ganti dengan alamat email Gmail Anda
    $mail->Password = 'gzwctjuskrzzyqxu'; // Ganti dengan kata sandi aplikasi email Gmail Anda
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Gunakan SSL
    $mail->Port = 465; // Port untuk SSL

    // Opsi tambahan untuk SSL/TLS
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );

    // Aktifkan debugging
    $mail->SMTPDebug = 0; // Tingkatkan tingkat debugging untuk mendapatkan lebih banyak informasi
    

    // Set pengirim dan penerima email
    $mail->setFrom('info.moondental@gmail.com', 'Moon Dental Clinic'); // Pengirim
    $mail->addAddress($_POST['email'], $_POST['fullname']); // Penerima

    // Content email
    $mail->isHTML(true); // Set email format ke HTML
    $mail->Subject = 'Reservation Confirmation'; // Subjek email
    $mail->Body    = '<p>Dear ' . $_POST['fullname'] . ',<br><br>Your reservation at Moon Dental has been confirmed.<br><br>Details:<br>Name: ' . $_POST['fullname'] . '<br>Phone Number: ' . $_POST['phonenumber'] . '<br>Email: ' . $_POST['email'] . '<br>Date: ' . $_POST['bookdate'] . '<br>Branch: ' . $_POST['branch'] . '<br>Dental Service: ' . $_POST['dentalservice'] . '<br><br>Thank you for choosing Moon Dental. Please attend to our branch as you have reserved the date and time.</p>'; // Isi pesan dalam format HTML
    $mail->AltBody = 'Dear ' . $_POST['fullname'] . ', Your reservation at Moon Dental has been confirmed. Details: Name: ' . $_POST['fullname'] . ', Phone Number: ' . $_POST['phonenumber'] . ', Email: ' . $_POST['email'] . ', Date: ' . $_POST['bookdate'] . ', Branch: ' . $_POST['branch'] . ', Dental Service: ' . $_POST['dentalservice'] . '. Thank you for choosing Moon Dental. Please attend to our branch as you have reserved the date and time.'; // Isi pesan dalam format plain text

    // Kirim email
    $mail->send();
    echo '<script>alert("Thanks for trusting Moon Dental! Please check your email.");</script>';
} catch (Exception $e) {
    // Tangani kesalahan jika pengiriman email gagal
    echo '<script>alert("Sorry there is no email sent. Mailer Error: ' . $mail->ErrorInfo . '");</script>';
}
// Simpan data ke database

include "../database/dbconnection.php";

// Ambil customer_id dari session
$customerId = $_SESSION['id'];

if ($koneksi->connect_error) {
    echo "$koneksi->connect_error";
    die("Connection Failed: " . $koneksi->connect_error);
} else {
    $stmt = $koneksi->prepare("INSERT INTO reservation (customer_id, fullname, phonenumber, email, bookdate, branch, dentalservice, note) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssssss", $customerId, $_POST['fullname'], $_POST['phonenumber'], $_POST['email'], $_POST['bookdate'], $_POST['branch'], $_POST['dentalservice'], $_POST['note']);

    $execval = $stmt->execute();

    if ($execval) {
        // Tampilkan halaman ringkasan reservasi
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reservation Summary</title>
    <link rel="icon" href="../image/logo2.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <script defer src="script.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- NAVBAR -->
    <nav class="container-fluid navbar navbar-expand-lg sticky-top mt-3 ">
        <div class="container">
            <a href="index.html">
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
                            <a class="nav-link act" aria-current="page" href="index.php#menu">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php#services">Services</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php#doctors">Doctors</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php#locations">Locations</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php#contact">Contact Us</a>
                        </li>
                        <li class="nav-item">
                        <?php if ($_SESSION['user_type'] == 'doctor'): ?>
                            <a class="nav-link" href="../doctor/profile_doctor.php">Profile</a>
                        <?php else: ?>
                            <a class="nav-link" href="../customer/profile_customer.php">Profile</a>
                        <?php endif; ?>
                        </li>
                        <li class="nav-item">
                        <?php if ($_SESSION['user_type'] == 'doctor'): ?>
                            <a class="nav-link" href="../chat/customerlist.php">My Chat</a>
                        <?php else: ?>
                            <a class="nav-link" href="../chat/doctorlist.php">My Chat</a>
                        <?php endif; ?>
                        <li class="nav-item">
                        <a class="btn btn-reserve fw-medium" href="reservation.html" role="button" data-bs-toggle="modal" data-bs-target="#reservationform-modal">RESERVE NOW</a>
                        </li>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <!-- END NAVBAR -->

    <div class="container mt-5 mb-5">
        <div class="mb-5">
            <span class="subtitle fw-semibold" style="color: #88bc67;"><center>Thank you for the reservation on behalf of:</center></span>
        </div>
        <form class="container">
            <div class="form-group row">
                <label class="col-4 col-form-label" for="text">Name</label>
                <div class="col-8">
                    <input id="fullname" name="fullname" placeholder="<?php echo $_POST['fullname']; ?>" type="text" class="form-control text-break" required="required" disabled>
                </div>
            </div>
            <div class="form-group row">
                <label for="number" class="col-4 col-form-label">Phone Number</label>
                <div class="col-8">
                    <input id="phonenumber" name="phonenumber" placeholder="<?php echo $_POST['phonenumber']; ?>" type="text" class="form-control text-break" required="required" disabled>
                </div>
            </div>
            <div class="form-group row">
                <label for="text" class="col-4 col-form-label">Email</label>
                <div class="col-8">
                    <input id="email" name="email" placeholder="<?php echo $_POST['email']; ?>" type="text" class="form-control text-break" required="required" disabled>
                </div>
            </div>
            <div class="form-group row">
                <label for="text" class="col-4 col-form-label">Date</label>
                <div class="col-8">
                    <input id="bookdate" name="bookdate" placeholder="<?php echo $_POST['bookdate']; ?>" type="text" class="form-control text-break" required="required" disabled>
                </div>
            </div>
            <div class="form-group row">
                <label for="text" class="col-4 col-form-label">Branch</label>
                <div class="col-8">
                    <input id="branch" name="branch" placeholder="<?php echo $_POST['branch']; ?>" type="text" class="form-control text-break" required="required" disabled>
                </div>
            </div>
            <div class="form-group row">
                <label for="dentalservice" class="col-4 col-form-label">Service</label>
                <div class="col-8">
                    <input id="dentalservice" name="dentalservice" placeholder="<?php echo $_POST['dentalservice']; ?>" type="text" class="form-control text-break" required="required" disabled>
                </div>
            </div>
        </form>
        <div class="mt-5 text-center info" style="color: #88bc67;">Please come to Moon Dental branch as you have chosen above, we will send messages to your email if there any information. Thank you for choosing us.</div>
    </div>

    <!-- FOOTER -->
    <footer class="text-black pt-2 mt-5" style="background-color: #fbfbfb;">
        <div class="container mx-auto p-5">
            <div class="row">
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
    <!-- END FOOTER -->

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
<?php
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $koneksi->close();
}
?>