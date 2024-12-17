<?php 
    session_start();
     
    // menghapus semua session
    session_destroy();
 
    // mengalihkan halaman dan mengirim pesan logout
    echo file_get_contents("D:/xampp/htdocs/Moon Dental/view/landingpage.php");
?>