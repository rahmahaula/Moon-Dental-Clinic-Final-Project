<?php 
session_start(); 
include "../database/dbconnection.php";

$search = "";
if(isset($_POST['search'])) {
    $search = $_POST['search'];
}

	//jika tombol simpan diklik
	if(isset($_POST['bsimpan']))
	{
		//Pengujian Apakah Data Akan Di Edit Atau Disimpan Baru
		if($_GET['hal'] == "edit")
		{
			//Data akan di edit
			$simpan = mysqli_query($koneksi, "UPDATE reservation set
													fullname = '$_POST[fullname]',
													phonenumber = '$_POST[phonenumber]',
													email = '$_POST[email]',
													bookdate = '$_POST[bookdate]',
                                                    branch = '$_POST[branch]',
                                                    dentalservice = '$_POST[dentalservice]',
                                                    note = '$_POST[note]'
												WHERE id = '$_GET[id]'
											");
			if($simpan) //Jika edit Sukses
			{
				echo "<script>
			            alert('Edit data successed.');
			            document.location='customerdata.php';
			            </script>";
			}
			else
			{
				echo "<script>
			            alert('Edit data failed.');
			            document.location='customerdata.php';
			            </script>";
			}
		}
		else
		{
			//Data Akan Disimpan Baru
			$simpan = mysqli_query($koneksi, "INSERT INTO reservation (fullname, phonenumber, email, bookdate, branch, dentalservice, note)
											VALUES ('$_POST[fullname]',
													'$_POST[phonenumber]',
													'$_POST[email]',
													'$_POST[bookdate]',
                                                    '$_POST[branch]',
                                                    '$_POST[dentalservice]',
                                                    '$_POST[note]')
												");
			if($simpan) //Jika Simpan Sukses
			{
				echo "<script>
			            alert('Create data successed.');
			            document.location='customerdata.php';
			            </script>";
			}
			else
			{
				echo "<script>
			            alert('Create data failed.');
			            document.location='customerdata.php';
			            </script>";
			}
		}
	}


	//Pengujian jika tombol Edit / Hapus di klik
	if(isset($_GET['hal']))
	{
		//Pengujian Jika Edit Data
		if($_GET['hal'] == "edit")
		{

			//Tampilkan Data Yang Akan Diedit
			$tampil = mysqli_query($koneksi, "SELECT * FROM reservation WHERE id = '$_GET[id]' ");
			$data = mysqli_fetch_array($tampil);
			if($data)
			{
				//Jika data ditemukan, maka data akan di tampung ke dalam variabel
				$vfullname = $data['fullname'];
				$vphonenumber = $data['phonenumber'];
				$vemail = $data['email'];
				$vbookdate = $data['bookdate'];
                $vbranch = $data['branch'];
                $vdentalservice = $data['dentalservice'];
                $vnote = $data['note'];
			}
		}
		else if ($_GET['hal'] == "hapus")
		{
			//Persiapan Hapus Data 
			$hapus = mysqli_query($koneksi, "DELETE FROM reservation WHERE id = '$_GET[id]' ");
			if($hapus){
				 echo "<script>
			            alert('Delete data successed.');
			            document.location='customerdata.php';
			           </script>";
			}
		}
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Customer Data</title>
	
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
	<span class="subtitle fw-semibold"><center>Moon Dental Clinic Customer Data</center></span>
	</div>
        <div class="form-group row">
          <label class="col-4 col-form-label" for="name">Name</label> 
          <div class="col-8">
            <input id="fullname" name="fullname" placeholder="Customer Name" type="text" class="form-control" value="<?=isset($vfullname) ? $vfullname : ''?>" required="required">
          </div>
        </div>
        <div class="form-group row">
          <label for="phonenumber" class="col-4 col-form-label">Phone Number</label> 
          <div class="col-8">
            <input id="phonenumber" name="phonenumber" placeholder="Customer Phone Number" type="text" class="form-control" value="<?=isset($vphonenumber) ? $vphonenumber : ''?>" required="required">
          </div>
        </div>
        <div class="form-group row">
          <label for="email" class="col-4 col-form-label">Email</label> 
          <div class="col-8">
            <input id="email" name="email" placeholder="Customer Email" type="text" class="form-control" value="<?=isset($vemail) ? $vemail : ''?>" required="required">
          </div>
        </div>
        <div class="form-group row">
          <label for="email" class="col-4 col-form-label">Date</label> 
          <div class="col-8">
            <input type="date" id="bookdate" name="bookdate" class="form-control" value="<?=isset($vbookdate) ? $vbookdate : ''?>" required="required">
          </div>
        </div>
        <div class="form-group row">
          <label for="branch" class="col-4 col-form-label">Branch</label> 
          <div class="col-8">
            <select id="branch" name="branch" class="form-select" required="required">
            <option value="Jakarta" <?= isset($vbranch) && $vbranch == 'Jakarta' ? 'selected' : '' ?>>Jakarta</option>
			<option value="Bogor" <?= isset($vbranch) && $vbranch == 'Bogor' ? 'selected' : '' ?>>Bogor</option>
			<option value="Depok" <?= isset($vbranch) && $vbranch == 'Depok' ? 'selected' : '' ?>>Depok</option>
			<option value="Bekasi" <?= isset($vbranch) && $vbranch == 'Bekasi' ? 'selected' : '' ?>>Bekasi</option>
            </select>
            <span id="radioHelpBlock" class="form-text text-muted">Click to choose</span>
          </div>
        </div>
        <div class="form-group row">
          <label for="service" class="col-4 col-form-label">Service</label> 
          <div class="col-8">
            <select id="dentalservice" name="dentalservice" class="form-select" required="required">
            <option value="Dental Filling" <?= isset($vdentalservice) && $vdentalservice == 'Dental Filling' ? 'selected' : '' ?>>Dental Filling</option>
              <option value="Teeth Whitening" <?= isset($vdentalservice) && $vdentalservice == 'Teeth Whitening' ? 'selected' : '' ?>>Teeth Whitening</option>
              <option value="Orthodontic Braces" <?= isset($vdentalservice) && $vdentalservice == 'Orthodontic Braces' ? 'selected' : '' ?>>Orthodontic Braces</option>
              <option value="Dentures" <?= isset($vdentalservice) && $vdentalservice == 'Dentures' ? 'selected' : '' ?>>Dentures</option>
              <option value="Dental Care" <?= isset($vdentalservice) && $vdentalservice == 'Dental Care' ? 'selected' : '' ?>>Dental Care</option>
              <option value="Tooth Extraction" <?= isset($vdentalservice) && $vdentalservice == 'Tooth Extraction' ? 'selected' : '' ?>>Tooth Extraction</option>
            </select>
            <span id="radioHelpBlock" class="form-text text-muted">Click to choose</span>
          </div>
        </div>
        <div class="form-group row">
		<label for="note" class="col-4 col-form-label">Note</label> 
		<div class="col-8">
			<textarea id="note" name="note" cols="40" rows="5" class="form-control" placeholder="Customer Note"><?=isset($vnote) ? $vnote : ''?></textarea>
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
		<span class="subtitle fw-semibold"><center>Reservation Table</center></span>
		<form method="POST" action="" class="mt-4">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Search customer data" value="<?= $search ?>">
                                <button type="submit" class="btn btn-primary">Search</button>
                            </div>
                        </form>
		  	<table class="table  table-striped mt-4">
		  		<tr>
				  <th class="col-1">ID</th>
                        <th class="col-2">Name</th>
                        <th class="col-2">Phone Number</th>
                        <th class="col-1">Email</th>
                        <th class="col-2">Date</th>
                        <th class="col-1">Branch</th>
                        <th class="col-2">Service</th>
                        <th class="col-2">Note</th>
                        <th class="col-2">Action</th>

		  		</tr>
		  		<?php
                            
                            if ($search) {
                                $tampil = mysqli_query($koneksi, "SELECT * FROM reservation WHERE fullname LIKE '%$search%' OR phonenumber LIKE '%$search%' OR email LIKE '%$search%' OR branch LIKE '%$search%' OR dentalservice LIKE '%$search%' order by id desc");
                            } else {
                                $tampil = mysqli_query($koneksi, "SELECT * from reservation order by id desc");
                            }
                            while($data = mysqli_fetch_array($tampil)) :
                            ?>
                            <tr>
                                <td><?=$data['customer_id']?></td>
                                <td><?=$data['fullname']?></td>
                                <td><?=$data['phonenumber']?></td>
                                <td><?=$data['email']?></td>
                                <td><?=$data['bookdate']?></td>
                                <td><?=$data['branch']?></td>
                                <td><?=$data['dentalservice']?></td>
                                <td><?=$data['note']?></td>
                                <td>
                                    <a href="customerdata.php?hal=edit&id=<?=$data['id']?>"><i class="fa-solid fa-pen-to-square"></i></a>
                                    &nbsp
                                    &nbsp
                                    <a href="customerdata.php?hal=hapus&id=<?=$data['id']?>" 
                                        onclick="return confirm('Are you sure want to delete this data?')"><i class="fa-solid fa-trash" style="color: red;" ></i></a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
		  	</table>

		  </div>
		</div>
		

<script type="text/javascript" src="js/bootstrap.min.js"></script>
</body>
</html>
