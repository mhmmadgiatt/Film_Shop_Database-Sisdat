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
   <title>about</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<div class="heading">
   <h3>Anggota Kami</h3>
   <p> <a href="home.php">home</a> / about </p>
</div>

<section class="reviews">

   <h1 class="title">Anggota</h1>

   <div class="box-container">

      <div class="box">
         <img src="images/pic-1.jpeg" alt="">
         <p>NPM   : 140810210029</p>
         <h3>MUAJIB</h3>
      </div>

      <div class="box">
         <img src="images/pic-2.jpeg" alt="">
         <p>NPM   : 140810210013</p>
         <h3>GIYAT</h3>
      </div>

      <div class="box">
         <img src="images/pic-3.jpeg" alt="">
         <p>NPM   : 140810210003</p>
         <h3>ISAN</h3>
      </div>
</section>


<?php include 'footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>