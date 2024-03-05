<?php 

session_start();

//conn3ect
$c = mysqli_connect('localhost', 'root' , '' , 'kasir');



//login
if(isset($_POST['login'])){
    //initate variable
    $username = $_POST['username'];
    $password = $_POST['password']; 

    $check = mysqli_query($c, "SELECT * FROM user WHERE username = '$username' and password = '$password'");
    $hitung = mysqli_num_rows($check);

    if($hitung>0){
        //pdatanya ditemukan
        //login berhasil
        $_SESSION['login'] = 'True';
        header('location:index.php');
    }
    else{
        //datanya tidak ditemukan
        //tidak bisa login 
        echo '
        <script>alert ("username atau password salah");
        window.location.href="login.php"
        </script>
        ';
    }
}

if(isset($_POST['tambahbarang'])){
    $namaproduk = $_POST['namaproduk'];
    $deskripsi = $_POST['deskripsi'];
    $harga = $_POST['harga'];
    $hargaj = $_POST['hargaj'];
    $stock = $_POST['stock'];

    $insert = mysqli_query($c, "insert into produk (namaproduk,deskripsi,harga,hargaj,stock) values ('$namaproduk','$deskripsi',
    '$harga','$hargaj','$stock')");
    
    if($insert){
        header('location:stock.php');
    }
    else{
        echo '
        <script>alert ("gagal menambah barang baru");
        window.location.href="stock.php"
        </script>
        ';
    }
};

if(isset($_POST['tambahpelanggan'])){
    $namapelanggan = $_POST['namapelanggan'];
    $notelp = $_POST['notelp'];
    $alamat = $_POST['alamat'];

    $insert = mysqli_query($c, "insert into pelanggan (namapelanggan,notelp,alamat) values ('$namapelanggan','$notelp',
    '$alamat')");

    if($insert){
        header('location:pelanggan.php');
    }
    else{
        echo '
        <script>alert("gagal menambah pelanggan baru");
        window.location.href="pelanggan.php"
        </script>
        ';
    }
}

//status
if(isset($_POST['setuju'])){
    $kondisi = $_POST['kondisi'];
    $idorder = $_POST['idorder'];

    $q = mysqli_query($c,"UPDATE pesanan SET kondisi='SUKSES' WHERE idorder='$idorder'");

    if($q){

        ?>
        <script type="text/javascript">
        alert('Simpan Data Berhasil');
        window.location.href="index.php";
         </script>
        <?php 

    }else{
        echo '
        <script>alert("Gagal");
        window.location.href="?page=masuk"
        </script>
        ';
    }
}

//hapus pelanggan


if(isset($_POST['tambahpesanan'])){
    $idpelanggan = $_POST['idpelanggan'];

    $insert = mysqli_query($c, "insert into pesanan (idpelanggan) values ('$idpelanggan')");

    if($insert){
        header('location:index.php');
    }
    else{
        echo '
        <script>alert("gagal menambah pesanan baru");
        window.location.href="index.php"
        </script>
        ';
    }
}


//produk pesanan dipilih
if(isset($_POST['addproduk'])){
    $idproduk = $_POST['idproduk'];
    $idp = $_POST['idp'];
    $qty = $_POST['qty']; //jumlah

    //hitung sisa stock barang
    $hitung1 = mysqli_query($c,"select * from produk where idproduk='$idproduk'");
    $hitung2 = mysqli_fetch_array($hitung1);
    $stocksekarang = $hitung2['stock']; //stock barang saat ini

    if($stocksekarang>=$qty){

        //pengeurangan stock yang telah keluar
        $selisih = $stocksekarang - $qty ;
                //stock cukup
    $insert = mysqli_query($c, "insert into detailpesanan (idpesanan,idproduk,qty) values ('$idp','$idproduk','$qty')");
    $update = mysqli_query($c, "update produk set stock='$selisih' where idproduk='$idproduk'");
    
    if($insert&&$update){
        header('location:view.php?idp='.$idp);
    } else{
     echo '
    <script>alert("gagal menambah pesanan baru");
    window.location.href="view.php?idp="'.$idp.'
    </script>
    ';
    }

    } else{
        echo '
        <script>alert("stock barang tidak cukup");
        window.location.href="view.php?idp="'.$idp.'
        </script>
        ';
    }
}

?>