<?php
    session_start();
    include '../database/dbconnection.php';

    // Memastikan user sudah login
    if(!isset($_SESSION['username'])){
        header("location: ../view/landingpage.php");
        exit;
    }

    // Mendapatkan username dari session
    $username = $_SESSION['username'];

    // Mengambil data user dari database berdasarkan username
    $query = "SELECT * FROM customertable WHERE username='$username'";
    $result = mysqli_query($koneksi, $query);
    $customer = mysqli_fetch_assoc($result);
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
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@200;300&family=Poppins:wght@200;300;500&display=swap');

        .body-card {
            color: #333;
            min-height: 100vh;
            padding: 20px;
        }

        .container-table {
          width: 100%;
          max-width: 1000px;
          background-color: #fff;
          padding: 20px;
          border-radius: 8px;
          box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            align-items: center;
            justify-content: center;
      }

        h2 {
            text-align: center;
            color: #88BC67;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .table th, .table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e1e1e1;
            font-size: 0.9em;
            word-wrap: break-word;
        }

        .table th {
            background-color: #88BC67;
            color: #fff;
            font-weight: normal;
        }

        .table td.note {
            white-space: pre-line; /* Preserve line breaks if any */
            max-width: 300px; /* Limit the width of the Note column */
        }

        .table tr:hover {
            background-color: #f1f5f9;
        }

        .no-reservations {
            text-align: center;
            color: #777;
            font-size: 1em;
            margin-top: 20px;
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

        @media screen and (min-width: 769px) and (max-width: 841px) {
          .table, .table thead, .table tbody, .table th, .table td, .table tr { 
                display: block; 
                width: 100%;
            }
            
            .table tr {
                margin-bottom: 15px;
                border-bottom: 2px solid #e1e1e1;
            }
            
            .table th {
                display: none;
            }

            .table td {
                display: flex;
                justify-content: space-between;
                font-size: 0.85em;
                padding: 10px;
                border-bottom: none;
            }

            .table td::before {
                content: attr(data-label);
                font-weight: bold;
                color: black;
                display: inline-block;
                width: 50%;
            }
            
            .table td.note {
                max-width: 100%;
            }

  }

        /* Mobile-friendly table */
        @media (max-width: 768px) {
            .table, .table thead, .table tbody, .table th, .table td, .table tr { 
                display: block; 
                width: 100%;
            }
            
            .table tr {
                margin-bottom: 15px;
                border-bottom: 2px solid #e1e1e1;
            }
            
            .table th {
                display: none;
            }

            .table td {
                display: flex;
                justify-content: space-between;
                font-size: 0.85em;
                padding: 10px;
                border-bottom: none;
            }

            .table td::before {
                content: attr(data-label);
                font-weight: bold;
                color: black;
                display: inline-block;
                width: 50%;
            }
            
            .table td.note {
                max-width: 100%;
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



    <div class="container container-table mt-3">
        <h2>Reservation History</h2>
        <?php

        if (!isset($_SESSION['username'])) {
            header("location: ../view/landingpage.php");
            exit;
        }

        if ($koneksi->connect_error) {
            die("Connection failed: " . $koneksi->connect_error);
        }

        $customer_id = $_SESSION['id'];
        $sql = "SELECT * FROM reservation WHERE customer_id = ?";
        $stmt = $koneksi->prepare($sql);
        $stmt->bind_param("i", $customer_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<table class='table'>";
            echo "<thead><tr><th>Name</th><th>Phone</th><th>Email</th><th>Date</th><th>Branch</th><th>Service</th><th>Note</th></tr></thead><tbody>";
            
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td data-label='Name'>" . $row['fullname'] . "</td>";
                echo "<td data-label='Phone'>" . $row['phonenumber'] . "</td>";
                echo "<td data-label='Email'>" . $row['email'] . "</td>";
                echo "<td data-label='Date'>" . $row['bookdate'] . "</td>";
                echo "<td data-label='Branch'>" . $row['branch'] . "</td>";
                echo "<td data-label='Service'>" . $row['dentalservice'] . "</td>";
                echo "<td data-label='Note' class='note'>" . $row['note'] . "</td>";
                echo "</tr>";
            }
            
            echo "</tbody></table>";
        } else {
            echo "<p class='no-reservations'>No reservations found.</p>";
        }

        $koneksi->close();
        ?>
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

