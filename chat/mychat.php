<?php
session_start();
include '../database/dbconnection.php';

// Pastikan user sudah login
if (!isset($_SESSION['username'])) {
    header("location: ../view/landingpage.php");
    exit;
}

// Ambil ID customer dari session
$customer_id = $_SESSION['id'];

// Ambil daftar dokter dari database
$query = "SELECT d.id AS doctor_id, dt.doctor_id, dt.username, d.picture 
          FROM doctor d 
          JOIN doctortable dt ON d.id = dt.id";
$doctor_list = mysqli_query($koneksi, $query);

// Periksa apakah query berhasil
if (!$doctor_list) {
    echo "Error: " . mysqli_error($koneksi);
    exit;
}

// Ambil ID dokter dari parameter GET (jika ada)
$doctor_id = isset($_GET['doctor_id']) ? $_GET['doctor_id'] : null;
$doctor_username = "";

// Jika ada doctor_id, ambil data percakapan dan detail dokter
if ($doctor_id) {
    // Ambil fullname dan picture dokter
    $query_doctor = "SELECT d.fullname, d.picture FROM doctor d JOIN doctortable dt ON d.id = dt.id WHERE dt.doctor_id='$doctor_id'";
    $result_doctor = mysqli_query($koneksi, $query_doctor);

    if ($result_doctor) {
        $doctor_data = mysqli_fetch_assoc($result_doctor);
        $doctor_username = $doctor_data ? ucfirst($doctor_data['fullname']) : "Dokter Tidak Ditemukan";
        $doctor_picture = $doctor_data ? $doctor_data['picture'] : "default.png";
    }

    // Ambil pesan percakapan
    $sql = "SELECT chat.message, chat.media_url, chat.media_type, chat.customer_id, chat.doctor_id, chat.sender_type, chat.timestamp
            FROM chat 
            WHERE (chat.customer_id='$customer_id' AND chat.doctor_id='$doctor_id') 
            OR (chat.doctor_id='$doctor_id' AND chat.customer_id='$customer_id') 
            ORDER BY chat.timestamp ASC";
    $chat_result = mysqli_query($koneksi, $sql);
} else {
    $chat_result = [];
}

// Hitung total waktu chat yang sudah digunakan oleh customer dalam satu hari
$query_total_chat_time = "SELECT SUM(TIMESTAMPDIFF(SECOND, timestamp, NOW())) as total_chat_time
                          FROM chat 
                          WHERE customer_id='$customer_id' AND DATE(timestamp) = CURDATE()";
$result_total_time = mysqli_query($koneksi, $query_total_chat_time);
$row = mysqli_fetch_assoc($result_total_time);
$total_chat_time_seconds = $row['total_chat_time'] ?? 0;
$total_chat_time_minutes = $total_chat_time_seconds / 60;

// Tentukan apakah customer masih memiliki waktu untuk chat
$max_chat_time_per_day = 10; // Dalam menit
$remaining_time = $max_chat_time_per_day - $total_chat_time_minutes;

// Jika sudah melewati batas waktu chat per hari
$can_send_message = $remaining_time > 0;
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
<body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>

<!-- Tambahkan JavaScript untuk auto-scroll -->
<script>
// Fungsi untuk scroll otomatis ke bawah
const scrollToBottom = () => {
    const chatMessages = document.getElementById('chatMessages');
    chatMessages.scrollTop = chatMessages.scrollHeight;
};

// Event listener untuk form submit
document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('.message-form form');
    form.addEventListener('submit', (event) => {
        // Menunggu sejenak sebelum scroll ke bawah
        setTimeout(scrollToBottom, 100); // Menunggu 100ms agar pesan baru sudah ditampilkan
    });

    // Cek apakah pengiriman pesan sudah melewati batas waktu 10 menit
    const canSendMessage = <?php echo json_encode($can_send_message); ?>;
    if (!canSendMessage) {
        alert("Your chat time has run out for today. Try again tomorrow.");
    }
});
</script>

<div class="container sticky-top">
    <div class="chat-header sticky-top">
        <a href="doctorlist.php" class="back-button"><i class="fa-solid fa-chevron-left"></i></a>
        <div class="profile-image"><img src="../image/Dokter/<?php echo $doctor_picture; ?>" alt="Picture" class="profile-img"></div>
        <h5 style="font-weight: bold;"><?php echo $doctor_username; ?></h5>
    </div>
</div>

<div class="container">
    <div class="chat-area pt-2">
        <div class="message-area">
            <?php if ($doctor_id && $doctor_username): ?>
            <div class="chat-messages" id="chatMessages"> <!-- Tambahkan ID di sini -->
                <?php if (mysqli_num_rows($chat_result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($chat_result)): ?>
                        <div class="message-container <?php echo ($row['sender_type'] == 'customer' && $row['customer_id'] == $customer_id) ? 'sender-message' : 'receiver-message'; ?>">
                            <div class="chat-message">
                                <?php
                                $message = $row['message'];
                                // Cek jika message adalah path gambar
                                if (preg_match('/\.(jpeg|jpg|png|gif|mp4)$/', $message)): ?>
                                    <img src="<?php echo htmlspecialchars($message); ?>" alt="Uploaded Image" class="chat-image" style="max-width: 100%; max-height: 300px; width: auto; height: auto; border-radius: 8px;">
                                <?php else: ?>
                                    <p class="message-text"><?php echo htmlspecialchars($message); ?></p> <!-- Tampilkan teks pesan -->
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

        <!-- Form pengiriman pesan hanya tampil jika masih dalam waktu 10 menit -->
        <div class="message-form sticky-bottom" style="bottom: 0;">
            <form method="POST" action="send_message.php" enctype="multipart/form-data" <?php echo $can_send_message ? '' : 'disabled'; ?>>
                <input type="hidden" name="doctor_id" value="<?php echo $doctor_id; ?>">
                
                <span type="button" class="gallery" style="display: inline-flex; align-items: center; justify-content: center;  margin-right: 10px; background-color: rgba(0, 123, 255, 0); border: 0.5px solid #A3A3A3;">
                 <i style="color: #545454;" class="fa-solid fa-image upload-icon" onclick="document.getElementById('file-input').click();"></i>
                </span>
                <textarea name="message" placeholder="Type a message" <?php echo $can_send_message ? '' : 'disabled'; ?>></textarea>
                <input style="display: none;" type="file" id="file-input" name="file" accept="image/*,video/mp4" <?php echo $can_send_message ? '' : 'disabled'; ?>>
                <button type="submit" <?php echo $can_send_message ? '' : 'disabled'; ?>><i class="fa-solid fa-paper-plane" style="color: white;"></i></button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
