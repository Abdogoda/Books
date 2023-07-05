<?php
include 'includes/connection.php';
$user_id = '';
if(isset($_COOKIE['user_id'])){
  $user_id = $_COOKIE['user_id'];
}else{
  header('location: login.php');
}

$user_select = $conn->prepare("SELECT * FROM users WHERE id = ?");
$user_select->execute([$user_id]);
if(!$user_select->rowCount() > 0){
  header('location: login.php');
}
$row = $user_select->fetch(PDO::FETCH_OBJ);
$user_name = $row->name;
$user_email = $row->email;
$user_phone = $row->phone;
$user_address = $row->address;
$user_image = $row->image;


if(isset($_POST['update'])){
  $name = $_POST['name'];
  $email = $_POST['email'];
  $phone = $_POST['phone'];
  $address = $_POST['address'];
  $image= $_FILES['image']['name'];
  $image_tmp_name = $_FILES['image']['tmp_name'];
  $image_tmp_folder = 'image/user_images/'.$image;
  $update_stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, phone = ?, address = ?, image = ? WHERE id = ?");
  $update_stmt->execute([$name, $email, $phone, $address, $image, $user_id]);
  move_uploaded_file($image_tmp_name,$image_tmp_folder);
  $success_msg[] = "Profile Updated Successfully!";
  header("location:profile.php");
}


if(isset($_GET['logout'])){
  setcookie('user_id', '', time() - 1, '/');
  header("location:index.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta http-equiv="X-UA-Compatible" content="IE=edge">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Profile</title>
 <link rel="shortcut icon" href="image/icon.svg" type="image/x-icon">
 <link rel="stylesheet" href="css/alert.css">
 <link rel="stylesheet" href="css/all.min.css">
 <link rel="stylesheet" href="css/cart.css">
 <link rel="stylesheet" href="css/style.css">
 <link rel="stylesheet" href="css/profile.css">
 <script src="js/alert.js"></script>
</head>

<body>
 <!-- header section  -->
 <header class="header">
  <?php  include 'includes/header1.php'; ?>
  <ul class="notifications"></ul>
  <?php include 'includes/alert.php'; ?>
 </header>

 <!-- start profile section -->
 <section class="profile">
  <h1 class="heading"> <span>profile information</span> </h1>
  <div class="profile-container">
   <div class="image"><img src="image/user_images/<?= $user_image ?>" alt="user_image"></div>
   <div class="info">
    <p class="name"><?= $user_name ?></p>
    <p class="p"><b>email:</b> <span style="text-transform: none;"><?= $user_email ?></span></p>
    <p class="p"><b>phone:</b> <?= $user_phone ?></p>
    <p class="p"><b>address:</b> <?= $user_address ?></p>
    <div class="btns" style="display:flex;flex-wrap:wrap;gap:15px">
     <a href="profile.php?edit" class="btn">Edit Profile</a>
     <a href="profile.php?logout" class="btn btn-danger" onclick="return confirm('Sure You Want To Logout?')">logout</a>
    </div>
   </div>
  </div>
 </section>
 <section class="cart">
  <h1 class="heading"> <span>your orders</span> </h1>
  <div class="cart-wrapper">
   <div class="responsive-table">
    <table>
     <thead>
      <tr>
       <th>ID</th>
       <th>Status</th>
       <th>Total</th>
       <th>Payment Method</th>
       <th>Date</th>
      </tr>
     </thead>
     <tbody>
      <?php
        $select_orders = $conn->prepare("SELECT o.*, od.payment_method FROM orders o JOIN order_details od ON o.id = od.o_id WHERE o.u_id = ? ORDER BY o.id DESC");
        $select_orders->execute([$user_id]);
        $total = 0;
        if($select_orders->rowCount() > 0){
            while($row = $select_orders->fetch(PDO::FETCH_OBJ)){
             $select_last_status = $conn->prepare("SELECT status FROM order_status WHERE o_id = ? ORDER BY s_id DESC LIMIT 1");
             $select_last_status->execute([$row->id]);
             $last_status = $select_last_status->fetch(PDO::FETCH_OBJ);
           ?>
      <tr>
       <td><a href="order.php?id=<?= $row->id ?>">#<?= $row->id?></a></td>
       <td><span class="status <?= $last_status->status ?>"><?= $last_status->status ?></span></td>
       <td><?= $row->total ?> EGP</td>
       <td><?= $row->payment_method == 0 ? "Cash On Delivery" : "Credit Card" ?></td>
       <td><?= $row->date ?></td>
      </tr>
      <?php  }}else{
            ?>
      <tr>
       <td colspan="5" class="no-books">NO Orders Added Yet! <a href="books.php" class="btn">back to shop</a></td>
      </tr>
      <?php }?>
     </tbody>
    </table>
   </div>
  </div>
 </section>
</body>

</html>