<?php
ob_start();
date_default_timezone_set('Asia/Jakarta');
$host = "192.168.1.90";
$user = "ymsjkt";
$pass = "ymsjkt";
$koneksi = mysqli_connect($host, $user, $pass);
$konak = mysqli_select_db($koneksi,"db_nofaktur") or die (mysql_error()); 
?>

<style>
table,tr,th,td{
    font-size: 8px;
    padding: 3px;
    border-collapse: collapse;
}
</style>

<h2>Laporan Materai</h2>
<table border="1px">
		<tr>
			<th>no</th>
			<th>tanggal</th>
			<th>nomor_si</th>
			<th>customer</th>
			<th>total</th>
			<th>materai</th>
			<th>upload</th>
			<th>status</th>
		</tr>
		<?php
		$request_by = @$_GET['keyword'];
		$tanggals = @$_GET['tanggal'];
		$tanggals2 = @$_GET['tanggal2'];

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

		$no=1;
		$sql = @mysqli_query($koneksi,$q);
		$cek=mysqli_num_rows($sql);
		if($cek <1){
			?>
			<tr>
				<td colspan="8" align="center" style="padding:10px;">Data tidak ditemukan</td>
			</tr>
			<?php
		}else{
			while($data = mysqli_fetch_array($sql)){
			?>
			<tr>
				<td><?php echo $no ?></td>
				<td><?php echo $data['tanggal']; ?></td>
				<td><?php echo $data['nomor_si']; ?></td>
				<td><?php echo $data['customer']; ?></td>
				<td><?php echo $data['total']; ?></td>
				<td><?php echo $data['materai']; ?></td>
				<td><?php echo $data['upload']; ?></td>
				<td><?php echo $data['status']; ?></td>
			</tr>
			<?php
			$no++;
			}
		}
		?>
	</table>

<?php
$html = ob_get_contents();
ob_end_clean();
        
require_once('html2pdf/html2pdf.class.php');
$pdf = new HTML2PDF('P','A4','fr', false);
$pdf->WriteHTML($html); 
$pdf->Output('masterai-'.date('dmy').'.pdf', 'P');
?>