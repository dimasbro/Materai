<?php
@session_start();
include "../inc/koneksi.php";
@$_SESSION['username'];

if(isset($_POST['save'])){
    $tanggals = $_POST['tanggal'];
    function ubahTanggal($tanggals){
     $pisah = explode('/',$tanggals);
     @$array = array($pisah[2],$pisah[0],$pisah[1]);
     $satukan = implode('/',$array);
     return $satukan;
    }
    $tanggal = ubahTanggal($tanggals);

    $nomor_si = $_POST['nomor_si'];
    $customer = $_POST['customer'];
    $total = $_POST['total'];
    $materai = $_POST['materai'];
    $upload = strtoupper($_SESSION['username']);
    $status = "OK";
    
    $ada = mysqli_query($koneksi, "select nomor_si from tbl_faktur_si");
    $banding = mysqli_fetch_array($ada);

    if($tanggal=="" || $nomor_si=="" || $customer=="" || $total=="" || $materai == "" || $upload=="" || $status==""){
        ?>
        <script type="text/javascript">
        alert("Harus diisi semua...");
        </script>
        <?php
    }else if($nomor_si == $banding['nomor_si']){
        ?>
        <script type="text/javascript">
        alert("Nomor SI udah ada ya...");
        window.location.href="../index.php";
        </script>
        <?php
    }else{
        /*echo $tanggal."<br>";
        echo $nomor_si."<br>";
        echo $customer."<br>";
        echo $total."<br>";
        echo $materai."<br>";
        echo $status."<br>";*/
        mysqli_query($koneksi, "insert into tbl_faktur_si(id,tanggal,nomor_si,customer,total,materai,upload,status)
            values('null','$tanggal','$nomor_si','$customer',$total,'$materai','$upload','$status') ") or die(mysqli_error());
    }
}
?>
<script language="javascript">
    window.location.href="../index.php";
</script>