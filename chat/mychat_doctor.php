<?php
session_start();
include '../database/dbconnection.php';

// Pastikan user sudah login
if (!isset($_SESSION['username'])) {
    header("location: ../view/landingpage.php");
    exit;
}

// Ambil doctor_id dari session
$doctor_id = $_SESSION['doctor_id'];
$customer_id = $_GET['customer_id']; // ID pelanggan yang ingin dikirimi pesan

// Ambil username customer dari customertable
$customer_query = "SELECT username, picture FROM customertable WHERE id = '$customer_id'";
$customer_result = mysqli_query($koneksi, $customer_query);
$customer_data = mysqli_fetch_assoc($customer_result);

// Ambil username dan picture customer
$customer_username = ucfirst($customer_data['username']);
$customer_picture = $customer_data['picture'];

// Hitung durasi konsultasi untuk customer tertentu
$sql_today_time_check = "SELECT timestamp FROM chat 
                         WHERE doctor_id = '$doctor_id' 
                         AND customer_id = '$customer_id' 
                         AND DATE(timestamp) = CURDATE() 
                         ORDER BY timestamp ASC LIMIT 1";
$today_time_check_result = mysqli_query($koneksi, $sql_today_time_check);

if ($today_time_check_result && mysqli_num_rows($today_time_check_result) > 0) {
    $today_time_data = mysqli_fetch_assoc($today_time_check_result);
    $start_time = strtotime($today_time_data['timestamp']); // Waktu percakapan pertama hari ini
    $current_time = time(); // Waktu sekarang
    $chat_duration = ($current_time - $start_time) / 60; // Durasi dalam menit
} else {
    $chat_duration = 0; // Belum ada percakapan hari ini
}



// Perbarui status jika lebih dari 10 menit
if ($chat_duration > 10) {
    $update_status_query = "UPDATE chat 
                            SET status = 'inactive' 
                            WHERE doctor_id = '$doctor_id' 
                            AND customer_id = '$customer_id' 
                            AND DATE(timestamp) = CURDATE() 
                            AND status = 'active'";
    mysqli_query($koneksi, $update_status_query);
}

// Ambil status sesi untuk customer ini
$query_status = "SELECT status FROM chat 
                 WHERE doctor_id = '$doctor_id' 
                 AND customer_id = '$customer_id' 
                 ORDER BY timestamp DESC LIMIT 1";
$status_result = mysqli_query($koneksi, $query_status);
if ($status_result && mysqli_num_rows($status_result) > 0) {
    $status_row = mysqli_fetch_assoc($status_result);
    $status = $status_row['status'];
} else {
    $status = 'inactive';
}

// Cek apakah dokter masih bisa membalas
$can_send_message = ($status == 'active');

// Ambil semua percakapan antara dokter dan customer ini
$sql = "SELECT chat.message, chat.media_url, chat.media_type, chat.doctor_id, chat.customer_id, chat.sender_type, chat.timestamp, chat.status
        FROM chat 
        WHERE (chat.doctor_id='$doctor_id' AND chat.customer_id='$customer_id') 
        OR (chat.customer_id='$customer_id' AND chat.doctor_id='$doctor_id') 
        ORDER BY chat.timestamp ASC";
$chat_result = mysqli_query($koneksi, $sql);





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
    <link rel="stylesheet" href="css/style.css">
</head>
<style>
/* Tombol End Chat */
/* Tombol End Chat */
.end-chat-form {
    display: flex;
    justify-content: flex-end;  /* Menempatkan tombol di kanan */
    margin-top: 10px;
    width: 100%;  /* Pastikan form mengambil seluruh lebar */
    background: transparent;
    border: 0;
}

.end-chat-btn {
    background-color: red; /* Warna latar belakang merah */
    color: white; /* Teks berwarna putih */
    border: none;
    padding: 6px 10px; /* Ukuran padding yang cukup untuk tombol */
    font-size: 16px; /* Ukuran font yang nyaman dibaca */
    border-radius: 5px; /* Membuat sudut tombol lebih lembut */
    cursor: pointer;
}

.end-chat-btn:hover {
    background-color: #FC6D6D;
}

/* Responsif untuk layar kecil */
@media (max-width: 768px) {
    .end-chat-form {
        justify-content: flex-end;  /* Menempatkan tombol di kanan */
        padding: 10px;
        text-align: center; /* Tombol bisa berada di tengah pada layar kecil */
    }
}



</style>
<body>

<div class="container sticky-top">
    <div class="chat-header sticky-top">
        <a href="customerlist.php" class="back-button"><i class="fa-solid fa-chevron-left"></i></a>
        <div class="profile-image"><img src="../image/<?php echo $customer_picture; ?>" alt="Picture" class="profile-img"></div>
        <h5 style="font-weight: bold;"><?php echo $customer_username; ?></h5>
        <!-- Tombol End Chat -->
        <form method="POST" action="end_chat.php" class="end-chat-form">
            <input type="hidden" name="doctor_id" value="<?php echo $doctor_id; ?>">
            <input type="hidden" name="customer_id" value="<?php echo $customer_id; ?>">
            <button type="submit" name="end_chat" class="end-chat-btn">End Chat</button>
        </form>

    </div>
</div>

<div class="container">
    <div class="chat-area pt-2">
        <div class="message-area">
            <?php if ($customer_id && $customer_username): ?>
            <div class="chat-messages" id="chatMessages">
                <?php if (mysqli_num_rows($chat_result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($chat_result)): ?>
                        <div class="message-container <?php echo ($row['sender_type'] == 'doctor' && $row['doctor_id'] == $doctor_id) ? 'sender-message' : 'receiver-message'; ?>">
                            <div class="chat-message">
                                <?php
                                $message = $row['message'];
                                if (preg_match('/\.(jpeg|jpg|png|gif|mp4)$/', $message)): ?>
                                    <img src="<?php echo htmlspecialchars($message); ?>" alt="Uploaded Image" class="chat-image" style="max-width: 100%; max-height: 300px; width: auto; height: auto; border-radius: 8px;">
                                <?php else: ?>
                                    <p class="message-text"><?php echo htmlspecialchars($message); ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="timestamp"><?php echo date('H:i', strtotime($row['timestamp'])); ?></div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>Start your consultation here.</p>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>

        <!-- Form pengiriman pesan -->
<div class="message-form sticky-bottom" style="bottom: 0;">
    <form method="POST" action="send_reply.php" enctype="multipart/form-data">
        <input type="hidden" name="customer_id" value="<?php echo $customer_id; ?>">
        
        <span type="button" class="gallery" style="display: inline-flex; align-items: center; justify-content: center; margin-right: 10px; background-color: rgba(0, 123, 255, 0); border: 0.5px solid #A3A3A3;">
            <i style="color: #545454;" class="fa-solid fa-image upload-icon" onclick="document.getElementById('file-input').click();" ></i>
        </span>
        <textarea name="message" placeholder="Type a message" <?php echo !$can_send_message ? 'disabled' : ''; ?>></textarea>
        <input style="display: none;" type="file" id="file-input" name="file" accept="image/*,video/mp4">
        
        <button type="submit" <?php echo !$can_send_message ? 'disabled' : ''; ?>>
            <i class="fa-solid fa-paper-plane"></i>
        </button>
    </form>
</div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
