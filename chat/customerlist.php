<?php
session_start();
include '../database/dbconnection.php';

date_default_timezone_set('Asia/Jakarta'); // Sesuaikan dengan zona waktu yang sesuai

// Pastikan user sudah login
if (!isset($_SESSION['username'])) {
    header("location: ../view/landingpage.php");
    exit;
}

// Ambil ID dokter dari session
if (isset($_SESSION['doctor_id'])) {
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
}

// Ambil daftar pelanggan yang telah mengirim pesan
$query = "
    SELECT c.username, c.picture, c.id AS customer_id, MAX(ch.timestamp) AS last_message_time,
           MAX(CASE WHEN ch.is_read = 0 THEN 1 ELSE 0 END) AS has_unread
    FROM chat ch
    JOIN customertable c ON ch.customer_id = c.id
    WHERE ch.doctor_id = '$doctor_id'
    GROUP BY c.id
    ORDER BY last_message_time DESC 
";

//ORDER BY last_message_time DESC untuk mengurutkan yang paling terakhir mengirim pesan, maka ada di urutan paling atas list

$result = mysqli_query($koneksi, $query);

// Cek apakah query berhasil
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
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .chat-cust {
            background-color: #5DB8B1;
        }

        .chat-cust:hover {
            background-color: #82c8c2;
        }

        .profile-image {
            width: 50px; /* Base size for image */
            height: 50px;
            flex-shrink: 0; /* Prevents shrinking on smaller screens */
        }

        .profile-image img {
            width: 100%;
            height: 100%;
            border-radius: 50%; /* Makes the image circular */
            object-fit: cover; /* Keeps the image centered and scaled */
        }

        .cust-profile {
            display: flex;
        }

        .badge {
            width: 10px; 
            height: 10px; 
            border-radius: 50%; /* Make it circular */
            background-color: yellow; /* Set the color of the badge */
            display: inline-block; /* Ensures the badge is visible */
        }

        @media (max-width: 768px) {
            .profile-image {
                width: 25px; /* Base size for image */
                height: 25px;
                flex-shrink: 0; /* Prevents shrinking on smaller screens */
            }

            .profile-image img {
                width: 100%;
                height: 100%;
                border-radius: 50%; /* Makes the image circular */
                object-fit: cover; /* Keeps the image centered and scaled */
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
                    
                </div>
            </div>
        </div>
    </nav>
    <!--END NAVBAR-->

    <div class="container mt-5">
        <div class="customerlist">
            <h2 class="mb-4">Chat with Customers</h2>
            <div class="list-group">
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($customer = mysqli_fetch_assoc($result)): ?>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="profile-image"><img src="../image/<?php echo $customer['picture']; ?>" alt="Picture" class="profile-img"></div>
                                <p class="customer-username mb-1 ms-2"><?php echo htmlspecialchars(ucfirst($customer['username'])); ?></p>
                                <?php if ($customer['has_unread']): ?>
                                    <span class="badge"></span> <!-- Badge for unread messages -->
                                <?php endif; ?>
                            </div>
                            <div class="d-flex align-items-center">
                                <small class="text-body-secondary me-3 fw-light relative-timestamp" data-timestamp="<?php echo strtotime($customer['last_message_time']); ?>"></small>
                                <a href="mychat_doctor.php?customer_id=<?php echo $customer['customer_id']; ?>" class="btn btn-reserve btn-sm chat-cust">Chat</a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="alert alert-warning" role="alert">
                        No messages from customer yet.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        function timeAgo(timestamp) {
            const now = new Date();
            const secondsPast = Math.floor(now.getTime() / 1000 - timestamp);

            if (secondsPast < 60) return `${secondsPast} seconds ago`;
            if (secondsPast < 3600) return `${Math.floor(secondsPast / 60)} minutes ago`;
            if (secondsPast < 86400) return `${Math.floor(secondsPast / 3600)} hours ago`;
            if (secondsPast < 2592000) return `${Math.floor(secondsPast / 86400)} days ago`;
            return `${Math.floor(secondsPast / 2592000)} months ago`;
        }

        // Update timestamps
        const timestamps = document.querySelectorAll('.relative-timestamp');
        timestamps.forEach((element) => {
            const timestamp = parseInt(element.getAttribute('data-timestamp'));
            element.innerText = timeAgo(timestamp);
        });
    </script>
</body>
</html>
