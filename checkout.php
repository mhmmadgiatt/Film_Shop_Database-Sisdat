<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

if(isset($_POST['order_btn'])){

   $name = mysqli_real_escape_string($conn, $_POST['nama']);
   $number = $_POST['no_telp'];
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $metode = mysqli_real_escape_string($conn, $_POST['metode']);
   $address = mysqli_real_escape_string($conn, $_POST['alamat']);
   $placed_on = date('d-M-Y');

   $cart_total = 0;
   $cart_products[] = '';

   $cart_query = mysqli_query($conn, "SELECT * FROM `keranjang` WHERE user_id = '$user_id'") or die('query failed');
   if(mysqli_num_rows($cart_query) > 0){
      while($cart_item = mysqli_fetch_assoc($cart_query)){
         $cart_products[] = $cart_item['nama'].' ('.$cart_item['jumlah'].') ';
         $sub_total = ($cart_item['harga'] * $cart_item['jumlah']);
         $cart_total += $sub_total;
      }
   }

   $total_products = implode(' ',$cart_products);

   $order_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE nama = '$name' AND no_telp = '$number' AND email = '$email' AND metode = '$metode' AND alamat = '$address' AND total_novel = '$total_products' AND total_harga = '$cart_total'") or die('query failed');

   if($cart_total == 0){
      $message[] = 'keranjang kamu kosong';
   }else{
      if(mysqli_num_rows($order_query) > 0){
         $message[] = 'order sudah pernah dipesan!'; 
      }else{
         mysqli_query($conn, "INSERT INTO `orders`(user_id, nama, no_telp, email, metode, alamat, total_novel, total_harga, tgl_orders) VALUES('$user_id', '$name', '$number', '$email', '$metode', '$address', '$total_products', '$cart_total', '$placed_on')") or die('query failed');
         $message[] = 'order berhasil!';
         mysqli_query($conn, "DELETE FROM `keranjang` WHERE user_id = '$user_id'") or die('query failed');
      }
   }
   
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>checkout</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<div class="heading">
   <h3>checkout</h3>
   <p> <a href="home.php">home</a> / checkout </p>
</div>

<section class="display-order">

   <?php  
      $grand_total = 0;
      $select_cart = mysqli_query($conn, "SELECT * FROM `keranjang` WHERE user_id = '$user_id'") or die('query failed');
      if(mysqli_num_rows($select_cart) > 0){
         while($fetch_cart = mysqli_fetch_assoc($select_cart)){
            $total_price = ($fetch_cart['harga'] * $fetch_cart['jumlah']);
            $grand_total += $total_price;
   ?>
   <p> <?php echo $fetch_cart['nama']; ?> <span>(<?php echo '$'.$fetch_cart['harga'].'/-'.' x '. $fetch_cart['jumlah']; ?>)</span> </p>
   <?php
      }
   }else{
      echo '<p class="empty">keranjang kamu kosong</p>';
   }
   ?>
   <div class="grand-total"> grand total : <span>$<?php echo $grand_total; ?>/-</span> </div>

</section>

<section class="checkout">

   <form action="" method="post">
      <h3>place your order</h3>
      <div class="flex">
         <div class="inputBox">
            <span>Nama kamu :</span>
            <input type="text" name="nama" required placeholder="Masukkan nama kamu">
         </div>
         <div class="inputBox">
            <span>Nomor telpon kamu :</span>
            <input type="number" name="no_telp" required placeholder="Masukkan nomor telpon kamu">
         </div>
         <div class="inputBox">
            <span>Email kamu :</span>
            <input type="email" name="email" required placeholder="Masukkan email kamu">
         </div>
         <div class="inputBox">
            <span>Metode Pembayaran :</span>
            <select name="metode">
               <option value="Dana">Dana</option>
               <option value="Gopay">Gopay</option>
               <option value="ShopeePay">ShopeePay</option>
              <option value="BNI">BNI</option>
              <option value="BCA">BCA</option>
            </select>
         </div>
         <div class="inputBox">
            <span>Alamat kamu:</span>
            <input type="text" name="alamat" required placeholder="Masukkan alamat kamu">
         </div>
      </div>
      <input type="submit" value="order sekarang" class="btn" name="order_btn">
   </form>

</section>









<?php include 'footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>