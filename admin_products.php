<?php

include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
};

if(isset($_POST['add_product'])){

   $name = mysqli_real_escape_string($conn, $_POST['nama']);
   $price = $_POST['harga'];
   $image = $_FILES['gambar']['name'];
   $image_size = $_FILES['gambar']['size'];
   $image_tmp_name = $_FILES['gambar']['tmp_name'];
   $image_folder = 'uploaded_img/'.$image;

   $select_product_name = mysqli_query($conn, "SELECT nama FROM `novel` WHERE nama = '$name'") or die('query failed');

   if(mysqli_num_rows($select_product_name) > 0){
      $message[] = 'Nama novel sudah ditambahkan';
   }else{
      $add_product_query = mysqli_query($conn, "INSERT INTO `novel`(nama, harga, gambar) VALUES('$name', '$price', '$image')") or die('query failed');

      if($add_product_query){
         if($image_size > 2000000){
            $message[] = 'Ukuran gambar terlalu besar!';
         }else{
            move_uploaded_file($image_tmp_name, $image_folder);
            $message[] = 'novel berhasil ditambahkan!';
         }
      }else{
         $message[] = 'novel tidak dapat ditambahkan!';
      }
   }
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_image_query = mysqli_query($conn, "SELECT gambar FROM `novel` WHERE id = '$delete_id'") or die('query failed');
   $fetch_delete_image = mysqli_fetch_assoc($delete_image_query);
   unlink('uploaded_img/'.$fetch_delete_image['gambar']);
   mysqli_query($conn, "DELETE FROM `novel` WHERE id = '$delete_id'") or die('query failed');
   header('location:admin_products.php');
}

if(isset($_POST['update_product'])){

   $update_p_id = $_POST['update_p_id'];
   $update_name = $_POST['update_name'];
   $update_price = $_POST['update_price'];

   mysqli_query($conn, "UPDATE `novel` SET nama = '$update_name', harga = '$update_price' WHERE id = '$update_p_id'") or die('query failed');

   $update_image = $_FILES['update_image']['nama'];
   $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
   $update_image_size = $_FILES['update_image']['size'];
   $update_folder = 'uploaded_img/'.$update_image;
   $update_old_image = $_POST['update_old_image'];

   if(!empty($update_image)){
      if($update_image_size > 2000000){
         $message[] = 'Ukuran file gambar terlalu besar!';
      }else{
         mysqli_query($conn, "UPDATE `novel` SET gambar = '$update_image' WHERE id = '$update_p_id'") or die('query failed');
         move_uploaded_file($update_image_tmp_name, $update_folder);
         unlink('uploaded_img/'.$update_old_image);
      }
   }

   header('location:admin_products.php');

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>products</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php include 'admin_header.php'; ?>

<!-- product CRUD section starts  -->

<section class="add-products">

   <h1 class="title">shop novels</h1>

   <form action="" method="post" enctype="multipart/form-data">
      <h3>tambah novel</h3>
      <input type="text" name="nama" class="box" placeholder="Masukkan nama novel" required>
      <input type="number" min="0" name="harga" class="box" placeholder="Masukkan harga novel" required>
      <input type="file" name="gambar" accept="image/jpg, image/jpeg, image/png" class="box" required>
      <input type="submit" value="add novel" name="add_product" class="btn">
   </form>

</section>

<!-- product CRUD section ends -->

<!-- show products  -->

<section class="show-products">

   <div class="box-container">

      <?php
         $select_novel = mysqli_query($conn, "SELECT * FROM `novel`") or die('query failed');
         if(mysqli_num_rows($select_novel) > 0){
            while($fetch_novel = mysqli_fetch_assoc($select_novel)){
      ?>
      <div class="box">
         <img src="uploaded_img/<?php echo $fetch_novel['gambar']; ?>" alt="">
         <div class="name"><?php echo $fetch_novel['nama']; ?></div>
         <div class="price">Rp.<?php echo $fetch_novel['harga']; ?></div>
         <a href="admin_products.php?update=<?php echo $fetch_novel['id']; ?>" class="option-btn">update</a>
         <a href="admin_products.php?delete=<?php echo $fetch_novel['id']; ?>" class="delete-btn" onclick="return confirm('Hapus novel ini ?');">hapus</a>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">Belum ada novel yang ditambahkan!</p>';
      }
      ?>
   </div>

</section>

<section class="edit-product-form">

   <?php
      if(isset($_GET['update'])){
         $update_id = $_GET['update'];
         $update_query = mysqli_query($conn, "SELECT * FROM `novel` WHERE id = '$update_id'") or die('query failed');
         if(mysqli_num_rows($update_query) > 0){
            while($fetch_update = mysqli_fetch_assoc($update_query)){
   ?>
   <form action="" method="post" enctype="multipart/form-data">
      <input type="hidden" name="update_p_id" value="<?php echo $fetch_update['id']; ?>">
      <input type="hidden" name="update_old_image" value="<?php echo $fetch_update['gambar']; ?>">
      <img src="uploaded_img/<?php echo $fetch_update['gambar']; ?>" alt="">
      <input type="text" name="update_name" value="<?php echo $fetch_update['nama']; ?>" class="box" required placeholder="Masukkan nama novel">
      <input type="number" name="update_price" value="<?php echo $fetch_update['harga']; ?>" min="0" class="box" required placeholder="Masukkan harga novel">
      <input type="file" class="box" name="update_image" accept="iamge/jpg, image/jpeg, gambar/png">
      <input type="submit" value="update" name="update_product" class="btn">
      <input type="reset" value="cancel" id="close-update" class="option-btn">
   </form>
   <?php
         }
      }
      }else{
         echo '<script>document.querySelector(".edit-product-form").style.display = "none";</script>';
      }
   ?>

</section>







<!-- custom admin js file link  -->
<script src="js/admin_script.js"></script>

</body>
</html>