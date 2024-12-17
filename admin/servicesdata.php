<?php
session_start();
include "../database/dbconnection.php";

// Jika tombol simpan diklik
if(isset($_POST['bsimpan'])) {
    // Pengujian Apakah Data Akan Di Edit Atau Disimpan Baru
    if($_GET['hal'] == "edit") {
        // Data akan di edit

        // Persiapan statement SQL
        $stmt = mysqli_prepare($koneksi, "UPDATE services SET
                                            picture = ?,
                                            services = ?,
                                            price = ?,
                                            highlight = ?,
                                            descriptions = ?
                                            WHERE id = ?");
        
        // Binding parameter
        mysqli_stmt_bind_param($stmt, 'sssssi', $_POST['picture'], $_POST['services'], $_POST['price'], $_POST['highlight'], $_POST['descriptions'], $_GET['id']);
        
        // Eksekusi statement
        mysqli_stmt_execute($stmt);
        
        // Periksa hasil eksekusi
        if(mysqli_stmt_affected_rows($stmt) > 0) {
            echo "<script>
                    alert('Edit data succeeded.');
                    document.location='servicesdata.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Edit data failed.');
                    document.location='servicesdata.php';
                  </script>";
        }
        
        // Tutup statement
        mysqli_stmt_close($stmt);

    } else {
        // Data Akan Disimpan Baru

        // Persiapan statement SQL
        $stmt = mysqli_prepare($koneksi, "INSERT INTO services (picture, services, price, highlight, descriptions)
                                            VALUES (?, ?, ?, ?, ?)");
        
        // Binding parameter
        mysqli_stmt_bind_param($stmt, 'sssss', $_POST['picture'], $_POST['services'], $_POST['price'], $_POST['highlight'], $_POST['descriptions']);
        
        // Eksekusi statement
        mysqli_stmt_execute($stmt);
        
        // Periksa hasil eksekusi
        if(mysqli_stmt_affected_rows($stmt) > 0) {
            echo "<script>
                    alert('Create data succeeded.');
                    document.location='servicesdata.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Create data failed.');
                    document.location='servicesdata.php';
                  </script>";
        }
        
        // Tutup statement
        mysqli_stmt_close($stmt);
    }
}

if(isset($_POST['bsimpan'])) {
    // Upload file gambar
    $picture = $_FILES['picture']['name'];
    $temp = $_FILES['picture']['tmp_name'];
    $folder = "D:\xampp\htdocs\Moon Dental\image\Dokter"; // Lokasi folder untuk menyimpan gambar

    // Pindahkan file yang diunggah ke folder uploads
    move_uploaded_file($temp, $folder.$picture);

    // Lanjutkan dengan proses insert/update ke database sesuai kebutuhan
    // ... (bagian ini tergantung pada logika Anda untuk menyimpan ke database)
}

// Pengujian jika tombol Edit / Hapus di klik
if(isset($_GET['hal'])) {
    // Pengujian Jika Edit Data
    if($_GET['hal'] == "edit") {

        // Tampilkan Data Yang Akan Diedit
        $stmt = mysqli_prepare($koneksi, "SELECT * FROM services WHERE id = ?");
        mysqli_stmt_bind_param($stmt, 'i', $_GET['id']);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        // Fetch data
        $data = mysqli_fetch_array($result);
        
        if($data) {
            // Jika data ditemukan, maka data akan di tampung ke dalam variabel
            $vpicture = $data['picture'];
            $vservices = $data['services'];
            $vprice = $data['price'];
            $vhighlight = $data['highlight'];
            $vdescriptions = $data['descriptions'];
        }

        // Tutup statement
        mysqli_stmt_close($stmt);

    } else if ($_GET['hal'] == "hapus") {
        // Persiapan Hapus Data 
        $stmt = mysqli_prepare($koneksi, "DELETE FROM services WHERE id = ?");
        mysqli_stmt_bind_param($stmt, 'i', $_GET['id']);
        mysqli_stmt_execute($stmt);
        
        // Periksa hasil eksekusi
        if(mysqli_stmt_affected_rows($stmt) > 0) {
            echo "<script>
                    alert('Delete data succeeded.');
                    document.location='servicesdata.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Delete data failed.');
                    document.location='servicesdata.php';
                  </script>";
        }
        
        // Tutup statement
        mysqli_stmt_close($stmt);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Services Data</title>
	
	<link rel="icon" href="../image/logo2.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
	<link rel="stylesheet" href="../css/style.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
  
  <link rel="canonical" href="https://getbootstrap.com/docs/5.3/examples/sidebars/">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@docsearch/css@3">

<link href="/docs/5.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- ... (bagian head lainnya) ... -->
    <style>
    /* Sidebar Styles */
    .sidebar {
        position: fixed;
		width: 200px;
        height: 100%;
        overflow-y: auto; /* Tambahkan ini agar sidebar dapat di-scroll jika kontennya lebih panjang dari tinggi layar */
    }

    /* Form Styles */
    .form-container {
        margin-left: 280px; /* Sesuaikan dengan lebar sidebar */
        padding: 20px; /* Sesuaikan sesuai kebutuhan untuk memberikan ruang agar tidak tertutup oleh sidebar */
    }

    /* Table Styles */
    .table-container {
        width: calc(100% - 200px); /* Sesuaikan dengan lebar sidebar */
        padding: 10px; /* Sesuaikan sesuai kebutuhan untuk memberikan ruang agar tidak tertutup oleh sidebar */
		margin-left: auto; /* Menjorokkan ke kanan */
        margin-right: 0; 
    }

	.nav-link.active {
        background-color: #88bc67 !important;/*Dengan menambahkan !important, Anda memastikan bahwa aturan CSS tersebut lebih kuat daripada aturan yang mungkin sudah ada sebelumnya.*/
    }

	.nav-pills .nav-link:hover {
    background-color: #A6DF82; /* Warna latar belakang saat dihover */
    color: #fff; /* Warna teks saat dihover */
}


</style>

</head>
<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  
<div class="d-flex">
<div class="sidebar d-flex flex-column flex-shrink-0 p-3 bg-body-tertiary" style="width: 200px; height: 100vh;">
    <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
      <span class="fs-4 fw-bold">Moon Dental</span>
    </a>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
	<li>
	  <a style="margin-bottom:18px;" class="btn btn-reserve fw-medium w-100" href="customerdata.php" role="button">CUSTOMER</a>
	  <a style="margin-bottom:18px;" class="btn btn-reserve fw-medium w-100" href="doctordata.php" role="button">DOCTOR</a>
	  <a style="margin-bottom:18px;" class="btn btn-reserve fw-medium w-100" href="promodata.php" role="button">PROMO</a>
    <a style="margin-bottom:18px;" class="btn btn-reserve fw-medium w-100" href="servicesdata.php" role="button">SERVICES</a>
    <a style="margin-bottom:18px;" class="btn btn-reserve fw-medium w-100" href="testimonidata.php" role="button">TESTIMONI</a>
    <a style="margin-bottom:18px;" class="btn btn-reserve fw-medium w-100" href="bannerdata.php" role="button">BANNER</a>
	  <a style="background-color: red;" class="btn btn-reserve fw-medium w-100" href="../view/landingpage.php" role="button" onclick="return confirm('Are you sure want to log out?')" >LOGOUT</a>
      </li>
    </ul>
    <hr>
    <!--<<div class="dropdown">
      <a href="#" class="d-flex align-items-center link-body-emphasis text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
        <strong>mdo</strong>
      </a>
      ul class="dropdown-menu text-small shadow">
        <li><a class="dropdown-item" href="#">New project...</a></li>
        <li><a class="dropdown-item" href="#">Settings</a></li>
        <li><a class="dropdown-item" href="#">Profile</a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item" href="#">Sign out</a></li>
      </ul>-->
    </div>
  </div>

  <div class="container">
	<!--Awal Card Form -->
	<div class="container mt-4 mb-5">
	
    <form class="form-container" method="post" action="">
	<div class="mb-5">
	<span class="subtitle fw-semibold"><center>Moon Dental Clinic Services Data</center></span>
	</div>
	<div class="form-group row">
    <label class="col-4 col-form-label" for="picture">Picture</label> 
    <div class="col-8">

        <input type="file" class="form-control" id="picture" name="picture" accept="image/*" value="<?=isset($vpicture) ? $vpicture : ''?>" >

    </div>
</div>
        <div class="form-group row">
          <label class="col-4 col-form-label" for="services">Service</label> 
          <div class="col-8">
            <input id="services" name="services" placeholder="Service Name" type="text" class="form-control" value="<?=isset($vservices) ? $vservices : ''?>" required="required">
          </div>
        </div>
        <div class="form-group row">
          <label for="price" class="col-4 col-form-label">Price</label> 
          <div class="col-8">
            <input id="price" name="price" placeholder="Price" type="text" class="form-control" value="<?=isset($vprice) ? $vprice : ''?>" required="required">
          </div>
        </div>
        <div class="form-group row">
          <label for="highlight" class="col-4 col-form-label">Highlight</label> 
          <div class="col-8">
            <input id="highlight" name="highlight" placeholder="Highlight" type="text" class="form-control" value="<?=isset($vhighlight) ? $vhighlight : ''?>" required="required">
          </div>
        </div>
        <div class="form-group row">
          <label for="descriptions" class="col-4 col-form-label">Description</label> 
          <div class="col-8">
            <textarea id="textarea" name="descriptions" placeholder="Description" type="text" class="form-control" value="<?=isset($vdescriptions) ? $vdescriptions : ''?>" required="required" onclick="replacePeriodsWithLineBreaks()"></textarea>
          </div>
        </div>

		<div class="mt-3">
			<button type="submit" class="btn btn-reserve"name="bsimpan">SAVE</button>
		</div>
      </form>
</div>
</div>

</div>

	<div class="table-container">
		<span class="subtitle fw-semibold"><center>Services Table</center></span>
		  	<table class="table  table-striped mt-5">
		  		<tr>
				  <th class="col-1">ID</th>
                        <th class="col-2">Picture</th>
                        <th class="col-2">Service</th>
                        <th class="col-1">Price</th>
                        <th class="col-1">Highlight</th>
                        <th class="col-1">Description</th>
                        <th class="col-2">Action</th>

		  		</tr>
		  		<?php
				$no = 1;
				$tampil = mysqli_query($koneksi, "SELECT * from services order by id desc");
				while($data = mysqli_fetch_array($tampil)) :
				?>

		  		<tr>
		  			<td><?=$data['id']?></td>
		  			<td><?=$data['picture']?></td>
		  			<td><?=$data['services']?></td>
		  			<td><?=$data['price']?></td>
            <td><?=$data['highlight']?></td>
            <td style="width: 100%;" ><?=$data['descriptions']?></td>
		  			<td>
		  			<a href="servicesdata.php?hal=edit&id=<?=$data['id']?>"><i class="fa-solid fa-pen-to-square"></i></a>
            &nbsp
            &nbsp
		  			<a href="servicesdata.php?hal=hapus&id=<?=$data['id']?>" onclick="return confirm('Are you sure want to delete this data?')"><i class="fa-solid fa-trash" style="color: red;" ></i></a>
				</td>
		  		</tr>
		  
		  	<?php endwhile; //penutup perulangan while ?>
		  	</table>

		  </div>
		</div>
		

<script type="text/javascript" src="js/bootstrap.min.js"></script>
</body>
</html>
