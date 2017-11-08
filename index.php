<?php
@session_start();
include "inc/koneksi.php";
if(@$_SESSION['username']){
?>

<?php error_reporting(0) // tambahkan untuk menghilangkan notice... hehe ?>
<!doctype html>
<html>
    <head>
        <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;">
        <title>SIM</title>
        <link rel="stylesheet" href="css/bootstrap.min.css"/>
        <link rel="stylesheet" href="css/jquery-ui.css">
        <style>
            /*fix margin pagination*/
            .pagination{
                margin-top: 0px;
            }
            #footer{
              text-align: center;
              padding: 20px;
              background-color: #D3D3D3;
              font-size: 13px;
            }
            table,tr,th,td{
                text-align: center;
                font-size: 12px;
            }
            h1,h2,h3{
                color: #800000;
            }
            h4{
                color: white;
                background-color: #1E90FF;
                padding: 5px;
            }
        </style>
        <script language="javascript">
            function hanyaAngka(e, decimal) {
            var key;
            var keychar;
             if (window.event) {
                 key = window.event.keyCode;
             } else
             if (e) {
                 key = e.which;
             } else return true;
           
            keychar = String.fromCharCode(key);
            if ((key==null) || (key==0) || (key==8) ||  (key==9) || (key==13) || (key==27) ) {
                return true;
            } else
            if ((("0123456789").indexOf(keychar) > -1)) {
                return true;
            } else
            if (decimal && (keychar == ".")) {
                return true;
            } else return false;
            }

            </script>
    </head>
    <body>
        <?php 
//        includekan fungsi paginasi
        include 'inc/pagination1.php';
//        koneksi ke database
        include 'inc/koneksi.php';
        
        
//        mengatur variabel reload dan sql
        if(isset($_REQUEST['keyword']) && $_REQUEST['keyword']<>""){
//        jika ada kata kunci pencarian (artinya form pencarian disubmit dan tidak kosong)
//        pakai ini
            $keyword=$_REQUEST['keyword'];
            $reload = "index.php?pagination=true&keyword=$keyword";
            $sql =  "SELECT * FROM tbl_faktur_si WHERE upload='$_SESSION[username]' and tanggal LIKE '%$keyword%' or nomor_si LIKE '%$keyword%' or customer LIKE '%$keyword%' order by id desc";
            $result = mysqli_query($koneksi,$sql);
        }else{
//            jika tidak ada pencarian pakai ini
            $reload = "index.php?pagination=true";
            $sql =  "SELECT * FROM tbl_faktur_si where upload='$_SESSION[username]' order by id desc";
            $result = mysqli_query($koneksi, $sql);
        }
        
        //pagination config start
        $rpp = 10; // jumlah record per halaman
        $page = intval($_GET["page"]);
        if($page<=0) $page = 1;  
        $tcount = mysqli_num_rows($result);
        $tpages = ($tcount) ? ceil($tcount/$rpp) : 1; // total pages, last page number
        $count = 0;
        $i = ($page-1)*$rpp;
        $no_urut = ($page-1)*$rpp;
        //pagination config end
        ?>
        <div class="container">
        <center><h1>Sistem Informasi Materai</h1>
        <h4>Welcome, <?php echo $_SESSION['username'] ?></h4>
        </center><hr>
        <h3>Form isi Materai</h3>

        <form method="post" action="inc/simpan.php">
        <div class="col-sm-2">
            <input type="text" name="tanggal" class="form-control" placeholder="Tanggal SI" id="datepicker" required />
        </div>
        <div class="col-sm-2">
            <input type="text" name="nomor_si" class="form-control" placeholder="Masukkan Nomor SI" required />
        </div>
        <div class="col-sm-2">
            <input list="cust" name="customer" class="form-control" placeholder="Masukkan Customer" required />
        </div>
        <div class="col-sm-2">
            <input type="text" name="total" class="form-control" id="total" onkeypress="return hanyaAngka(event, false)" placeholder="Masukkan Total" required />
        </div>
        <div class="col-sm-2">
            <select name="materai" class="form-control" id="hasil" required />
                <option value="">-PILIH MATERAI-</option>
                <option value="TANPA MATERAI">TANPA MATERAI</option>
                <option value="MATERAI 3.000">MATERAI 3.000</option>
                <option value="MATERAI 6.000">MATERAI 6.000</option>
            </select>
        </div>
        <div class="col-sm-2">
            <input type="submit" name="save" class="btn btn-success" value="Save">
        </div>
        </form>

        <br><br>
        <hr>
        <h3>List Permintaan Materai</h3>
            <div class="row">

                <div class="col-lg-8">
                    <!--muncul jika ada pencarian (tombol reset pencarian)-->
                    <?php
                    if($_REQUEST['keyword']<>""){
                    ?>
                        <a class="btn btn-default btn-outline btn-sm" href="index.php"> Reset Pencarian</a>
                    <?php
                    }
                    ?>
                </div>
                <div class="col-lg-4 text-right">
                    <form method="post" action="index.php">
                        <div class="form-group input-group">
                            <input type="text" name="keyword" class="form-control" placeholder="Search..." value="<?php echo $_REQUEST['keyword']; ?>">
                            <span class="input-group-btn">
                                <button class="btn btn-primary" type="submit">Cari
                                </button>
                            </span>
                        </div>
                    </form>
                </div>
            </div>
            <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>TANGGAL_SI</th>
                        <th>NOMOR_SI</th>
                        <th>CUSTOMER</th>
                        <th>TOTAL</th>
                        <th>MATERAI</th>
                        <th>UPLOAD</th>
                        <th>STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while(($count<$rpp) && ($i<$tcount)) {
                        mysqli_data_seek($result,$i);
                        $data = mysqli_fetch_array($result);
                    ?>
                    <tr>
                        <td><?php echo ++$no_urut;?></td>
                        <td><?php echo $data['tanggal']; ?></td>
                        <td><?php echo $data['nomor_si']; ?></td>
                        <td><?php echo $data['customer']; ?></td>
                        <td><?php 
                        $angka = $data['total'];
                        $angka_format = number_format($angka,0,",",".");
                        echo $angka_format;
                         ?></td>
                        <td><?php echo $data['materai']; ?></td>
                        <td><?php echo $data['upload']; ?></td>
                        <td><?php echo $data['status']; ?></td>
                    </tr>
                    <?php
                        $i++; 
                        $count++;
                    }
                    ?>
                </tbody>
            </table>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <?php echo paginate_one($reload, $page, $tpages); ?>
                </div>
                <div class="col-md-4 text-right">
                    <a href="inc/logout.php"><button class="btn btn-danger btn-sm">Logout</button></a>
                </div><br>
            </div>
        </div>

        <div id="footer">
          Copyright &copy; 2017-<?php echo date('Y'); ?>. Created by Dimas Prasetio. All Rights Reserved
        </div>


<datalist id="cust">
<?php
$custnm = mysqli_query($koneksi,"select distinct customer from tbl_faktur_si order by customer asc");
while($datanam = mysqli_fetch_array($custnm)){
echo '<option value="'.$datanam['customer'].'">';
}
?>
</datalist>
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.11.0/jquery-ui.js"></script>
<script src="js/bootstrap.min.js"></script>
<script type="text/javascript">
$(function() { 
      $("#datepicker").datepicker();
});

$(document).ready(function(){
    $("#total").keypress(function(){
            var nilai = $("#total").val();
            var nil = parseInt(nilai+0);
            if(nil >=0 && nil <= 249999){
                var i="TANPA MATERAI";
            }else if(nil >= 250000 && nil <= 999999){
                var i="MATERAI 3.000";
            }else if(nil >= 1000000){
                var i="MATERAI 6.000";
            }
        $("#hasil").val(i);
    });
});
</script>

    </body>
</html>
<?php
}else{
  header("location:inc/login.php");
}
?>

<!--harviacode.com-->