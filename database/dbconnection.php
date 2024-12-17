<?php
	//Koneksi Database
	$server = "localhost";
	$user = "root";
	$pass = "";
	$database = "moondental";

	$koneksi =mysqli_connect($server, $user, $pass, $database)or die(mysqli_error($koneksi));

    if (!$koneksi) {
        echo "Connection failed!";
    }

?>