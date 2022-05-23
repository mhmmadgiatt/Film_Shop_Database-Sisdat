<?php

include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
}

if(isset($_POST['update_order'])){

   $order_update_id = $_POST['order_id'];
   $update_payment = $_POST['update_payment'];
   mysqli_query($conn, "UPDATE `orders` SET status_pembayaran = '$update_payment' WHERE id = '$order_update_id'") or die('query failed');
   $message[] = 'Status pembayaran telah diperbarui!';

}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   mysqli_query($conn, "DELETE FROM `orders` WHERE id = '$delete_id'") or die('query failed');
   header('location:admin_orders.php');
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

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="orders">

   <h1 class="title">placed orders</h1>

   <div class="box-container">
      <?php
      $select_orders = mysqli_query($conn, "SELECT * FROM `orders`") or die('query failed');
      if(mysqli_num_rows($select_orders) > 0){
         while($fetch_orders = mysqli_fetch_assoc($select_orders)){
      ?>
      <div class="box">
         <p> user id : <span><?php echo $fetch_orders['user_id']; ?></span> </p>
         <p> tgl_order : <span><?php echo $fetch_orders['tgl_orders']; ?></span> </p>
         <p> nama : <span><?php echo $fetch_orders['nama']; ?></span> </p>
         <p> no_telp : <span><?php echo $fetch_orders['no_telp']; ?></span> </p>
         <p> email : <span><?php echo $fetch_orders['email']; ?></span> </p>
         <p> alamat : <span><?php echo $fetch_orders['alamat']; ?></span> </p>
         <p> total novel : <span><?php echo $fetch_orders['total_novel']; ?></span> </p>
         <p> total harga : <span>$<?php echo $fetch_orders['total_harga']; ?>/-</span> </p>
         <p> metode pembayaran : <span><?php echo $fetch_orders['metode']; ?></span> </p>
         <form action="" method="post">
            <input type="hidden" name="order_id" value="<?php echo $fetch_orders['id']; ?>">
            <select name="update_payment">
               <option value="" selected disabled><?php echo $fetch_orders['status_pembayaran']; ?></option>
               <option value="belum bayar">belum bayar</option>
               <option value="lunas">lunas</option>
            </select>
            <input type="submit" value="update" name="update_order" class="option-btn">
            <a href="admin_orders.php?delete=<?php echo $fetch_orders['id']; ?>" onclick="return confirm('Hapus orderan ini ?');" class="delete-btn">hapus</a>
         </form>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty"> Belum ada orderan!</p>';
      }
      ?>
   </div>

</section>










<!-- custom admin js file link  -->
<script src="js/admin_script.js"></script>

</body>
</html>