<?php
session_start();
if(isset($_SESSION['username'])) {
header('location:index.php'); }
require_once("../inc/koneksi.php");
?>
<html>
<head>
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;">
<title>Form Login</title>
<link rel="stylesheet" href="../css/bootstrap.min.css"> 
<link rel="stylesheet" href="../css/style.css"> 
</head>
<body>
<center>

<div class="container">
      <form class="form-signin" action="" method="post">
        <h2 class="form-signin-heading">Sisfo Materai</h2>
        <label for="inputEmail" class="sr-only">Username</label>
        
        <input type="text" name="username" id="inputEmail" class="form-control" placeholder="username" required autofocus>
        
        <label for="inputPassword" class="sr-only">Password</label>
        
        <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password" required><br>
        
        <button class="btn btn-lg btn-primary btn-block" type="submit" name="login">Sign in</button>
      </form>
</div> <!-- /container -->

</center>

<?php
if(isset($_POST['login'])){
$username = $_POST['username'];
$pass = $_POST['password'];
$cekuser = mysqli_query($koneksi, "SELECT * FROM tbl_usr WHERE username = '$username'");
$jumlah = mysqli_num_rows($cekuser);
$hasil = mysqli_fetch_array($cekuser);
if($jumlah == 0) {
	//echo "Username Belum Terdaftar!<br/>";
	//echo "<a href='login.php'>Back to login</a>";
	?>
	<script type="text/javascript">
	alert('login dulu ya');
	</script>
	<?php
} else if($pass != $hasil['pass']) {
		//echo "Password Salah!<br/>";
		//echo "<a href='login.php'>Back to login</a>";
		?>
		<script type="text/javascript">
		alert('username atau password salah');
		</script>
		<?php
	}else if($hasil['username'] == "admin"){
		$_SESSION['username'] = $hasil['username'];
		if($_SESSION['username']){
		     ?>
		    <script language="javascript">
                window.location.href="../laporan/lihattglfem.php";
            </script>
		    <?php
		}else{
    		echo "gagal login admin";
		}
	} else {
		$_SESSION['username'] = $hasil['username'];
		if($_SESSION['username']){
		    ?>
		    <script language="javascript">
                window.location.href="../index.php";
            </script>
		    <?php
		}else{
    		echo "gagal login";
		}
}
}
?>
<script src="js/bootstrap.min.js"></script> 
</body>
</html>