<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>orders</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<div class="heading">
   <h3>Orderan Kamu</h3>
   <p> <a href="home.php">home</a> / orders </p>
</div>

<section class="placed-orders">

   <h1 class="title">Daftar order</h1>

   <div class="box-container">

      <?php
         $order_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE user_id = '$user_id'") or die('query failed');
         if(mysqli_num_rows($order_query) > 0){
            while($fetch_orders = mysqli_fetch_assoc($order_query)){
      ?>
      <div class="box">
         <p> tgl_orders : <span><?php echo $fetch_orders['tgl_orders']; ?></span> </p>
         <p> nama : <span><?php echo $fetch_orders['nama']; ?></span> </p>
         <p> nomor telpon : <span><?php echo $fetch_orders['no_telp']; ?></span> </p>
         <p> email : <span><?php echo $fetch_orders['email']; ?></span> </p>
         <p> Alamat : <span><?php echo $fetch_orders['alamat']; ?></span> </p>
         <p> metode pembayaran : <span><?php echo $fetch_orders['metode']; ?></span> </p>
         <p> orderan kamu : <span><?php echo $fetch_orders['total_film']; ?></span> </p>
         <p> total harga : <span>$<?php echo $fetch_orders['total_harga']; ?>/-</span> </p>
         <p> status pembayaran : <span style="color:<?php if($fetch_orders['status_pembayaran'] == 'belum bayar'){ echo 'red'; }else{ echo 'green'; } ?>;"><?php echo $fetch_orders['status_pembayaran']; ?></span> </p>
         </div>
      <?php
       }
      }else{
         echo '<p class="empty">Kamu belum order!</p>';
      }
      ?>
   </div>

</section>








<?php include 'footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>