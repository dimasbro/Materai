<?php
date_default_timezone_set('Asia/Jakarta');
$host = "192.168.1.90";
$user = "ymsjkt";
$pass = "ymsjkt";
$koneksi = mysqli_connect($host, $user, $pass);
$konak = mysqli_select_db($koneksi,"db_nofaktur") or die (mysqli_error()); 

@session_start();
include "../inc/koneksi.php";
if(@$_SESSION['username']){
?>

<html>
<head>
<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">
	<title>Laporan</title>
	<link rel="stylesheet" href="css/jquery-ui.css"> 

<link href="css/bootstrap.min.css" rel="stylesheet">
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery-1.11.0.min.js"></script> 
<script src="js/jquery-ui.js"></script>
<script type="text/javascript"> 
$(function() { 
      $("#datepicker").datepicker();
});
$(function() { 
      $("#datepicker2").datepicker();
});
</script> 
</head>
<style>
body {
    //background-color: yellow;
	font-family: arial;
	}
h2 { text-align: center; }
table, tr, th,td{border-collapse: collapse; text-align:center; font-size: 12px; padding: 5px;}
th { background-color: blue; color: white; border: 1px solid white; }
#footer{
  text-align: center;
  padding: 20px;
  background-color: #D3D3D3;
  font-size: 13px;
}
</style>

<body>

<div class="container" align="center">
<h2>Lihat Laporan Materai</h2>
<marquee style="color:blue" behavior="alternate" onmouseover="this.stop()" onmouseout="this.start()"><h3>Selamat datang di laporan Materai</h3></marquee>
<hr>
<form action="" method="post">
	<tr><td>Tanggal Awal </td><td><input type="text" name="tanggal" size="8" id="datepicker"></td></tr>
	<tr><td>Tanggal Akhir </td><td><input type="text" name="tanggal2" size="8" id="datepicker2"></td></tr>
	<tr><td>Nama SA/Request </td>
	<td><select name="request_by">
		<option value="">-- Nama SA/Request --</option>
		<?php
			$snama = mysqli_query($koneksi,"select username from tbl_usr");
			while($data = mysqli_fetch_array($snama)){
				echo '<option value="'.$data['username'].'">'.$data['username'].'</option>';
			}
		?>
	</select></td></tr>
	<tr><td></td><td><input type="submit" value="cari" name="pencarian" class="btn btn-primary btn-sm"></td></tr>
	</tr>
</form>

<?php
	echo '<center><a href="fem-export-excel.php?keyword='.@$_POST['request_by'].'&tanggal='.@$_POST['tanggal'].'&tanggal2='.@$_POST['tanggal2'].' ">
	<button class="btn btn-success btn-sm">Laporan Excel</button></a>    ';
	echo '<a target="blank" href="fem-export-pdf.php?keyword='.@$_POST['request_by'].'&tanggal='.@$_POST['tanggal'].'&tanggal2='.@$_POST['tanggal2'].' ">
	<button class="btn btn-danger btn-sm">Laporan PDF</button></a>';
?>
        <a href="../inc/logout.php"><button class="btn btn-warning btn-sm">Logout</button></a>
</center>
<hr>
</div>
<?php
if(isset($_POST['pencarian'])){
	$tanggals = $_POST['tanggal'];
	$tanggals2 = $_POST['tanggal2'];
	$request_by = $_POST['request_by'];

	function ubahTanggal($tanggals){
	 $pisah = explode('/',$tanggals);
	 @$array = array($pisah[2],$pisah[0],$pisah[1]);
	 $satukan = implode('/',$array);
	 return $satukan;
	}
	function ubahTanggal2($tanggals2){
	 $pisah = explode('/',$tanggals2);
	 @$array = array($pisah[2],$pisah[0],$pisah[1]);
	 $satukan = implode('/',$array);
	 return $satukan;
	}

	$tanggal = ubahTanggal($tanggals);
	$tanggal2 = ubahTanggal2($tanggals2);
	
	if($tanggal !=0 and $tanggal2!=0 and $request_by != null){
		$q = "select * from tbl_faktur_si where tanggal between '$tanggal' and '$tanggal2' and upload='$request_by' order by id asc";
	}else if($tanggal == 0 and $tanggal2==0 and $request_by == null){
		$q = "select * from tbl_faktur_si order by id asc";
	}else if($tanggal == 0 and $tanggal2==0 and $request_by != null){
		$q = "select * from tbl_faktur_si where upload='$request_by' order by id asc";
		//$ex = mysql_query($q);
	}else if($tanggal==0 || $tanggal2==0){
		?>
		<script type="text/javascript">
		alert("jangan pilih hanya kolom 1 tanggal");
		</script>
		<?php
	}else if($request_by == null and $tanggal != 0 and $tanggal2!=0){
		$q = "select * from tbl_faktur_si where tanggal between '$tanggal' and '$tanggal2' order by id asc";
		//$ex = mysql_query($q);
	}

echo"<p align=center><div class='container'>
<section class='col-lg-12'>
<div class='table-responsive'>
<table class='table table-bordered table-striped' align='center'>";
echo"<tr>
<th>No</th>
<th>Tanggal</th>
<th>Nomor_si</th>
<th>Customer</th>
<th>Total</th>
<th>Materai</th>
<th>Upload</th>
<th>Status</th>
</tr>";

$no=1;
$ex = @mysqli_query($koneksi,$q);
$brs = @mysqli_num_rows($ex);

echo "data tampil : <font color='red'>$brs</font>";

if($brs == 0){
	echo "<tr><td colspan='8' align='center'><font color='red'>data tidak ditemukan</font></td></tr>";
}else{
while($r = mysqli_fetch_array($ex)){
if($no%2 != 0){
    $color='#cccccc';
}else{
    $color='white';
}
	echo "<tr bgcolor='".$color."'>";
	echo "<td>".$no."</td>";
	echo "<td>".$r['tanggal']."</td>";
    echo "<td>".$r['nomor_si']."</td>";
    echo "<td>".$r['customer']."</td>";
    echo "<td>".$r['total']."</td>";
    echo "<td>".$r['materai']."</td>";
    echo "<td>".$r['upload']."</td>";
    echo "<td>".$r['status']."</td>";
	echo "</td></tr>";
	$no++;    
	}
}


}
echo "</table></p>";
?>
</div>
</section>
</div>

<div id="footer">
  Copyright &copy; 2017-<?php echo date('Y'); ?>. Created by Dimas Prasetio. All Rights Reserved
</div>

</body>
</html>
<?php
}else{
  header("location:../inc/login.php");
}
?>