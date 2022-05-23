<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

if(isset($_POST['add_to_cart'])){

   $novel_nama = $_POST['novel_nama'];
   $novel_harga = $_POST['novel_harga'];
   $novel_gambar = $_POST['novel_gambar'];
   $novel_jumlah = $_POST['novel_jumlah'];

   $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `keranjang` WHERE nama = '$novel_nama' AND user_id = '$user_id'") or die('query failed');

   if(mysqli_num_rows($check_cart_numbers) > 0){
      $message[] = 'Sudah ditambahkan ke keranjang!';
   }else{
      mysqli_query($conn, "INSERT INTO `keranjang`(user_id, nama, harga, jumlah, gambar) VALUES('$user_id', '$novel_nama', '$novel_harga', '$novel_jumlah', '$novel_gambar')") or die('query failed');
      $message[] = 'novel ditambahkan ke keranjang!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>home</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<section class="home">

   <div class="content">
      <h3>HABISKAN HARIMU DENGAN NOVEL.</h3>
      <p>Website ini berisi tentang novel-novel terbaik untuk bersantai.</p>
   </div>

</section>

<section class="products">

   <h1 class="title">novel yang tersedia</h1>

   <div class="box-container">

      <?php  
         $select_novel = mysqli_query($conn, "SELECT * FROM `novel`") or die('query failed');
         if(mysqli_num_rows($select_novel) > 0){
            while($fetch_novel = mysqli_fetch_assoc($select_novel)){
      ?>
     <form action="" method="post" class="box">
      <img class="image" src="uploaded_img/<?php echo $fetch_novel['gambar']; ?>" alt="">
      <div class="name"><?php echo $fetch_novel['nama']; ?></div>
      <div class="price">$<?php echo $fetch_novel['harga']; ?>/-</div>
      <input type="number" min="1" name="novel_jumlah" value="1" class="qty">
      <input type="hidden" name="novel_nama" value="<?php echo $fetch_novel['nama']; ?>">
      <input type="hidden" name="novel_harga" value="<?php echo $fetch_novel['harga']; ?>">
      <input type="hidden" name="novel_gambar" value="<?php echo $fetch_novel['gambar']; ?>">
      <input type="submit" value="masukkan ke keranjang" name="add_to_cart" class="btn">
     </form>
      <?php
         }
      }else{
         echo '<p class="empty">Yahh, belum ada novel yang dijual</p>';
      }
      ?>
   </div>

</section>

<section class="home-contact">

   <div class="content">
      <h3>Apakah kamu memiliki pertanyaan?</h3>
      <p>Jika kalian memiliki kendala, silahkan hubungi kami dengan memencet tombol dibawah!</p>
      <a href="about.php" class="white-btn">kontak kami</a>
   </div>

</section>





<?php include 'footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>