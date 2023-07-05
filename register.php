<?php
include 'includes/connection.php';
if (isset($_POST['register'])) {
  $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
  $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
  $phone = filter_var($_POST['phone'], FILTER_SANITIZE_STRING);
  $address = filter_var($_POST['address'], FILTER_SANITIZE_STRING);
  $password = sha1($_POST['password']);
  $password = filter_var($password, FILTER_SANITIZE_STRING);
  $cpassword = sha1($_POST['cpassword']);
  $cpassword = filter_var($cpassword, FILTER_SANITIZE_STRING);
  $image= $_FILES['image']['name'];
  $ext = pathinfo($image, PATHINFO_EXTENSION);
  $rename = generate_unique_id().'.'.$ext;
  $image_tmp_name = $_FILES['image']['tmp_name'];
  $image_tmp_folder = 'image/user_images/'.$rename;
  if($password == $cpassword){
    $select = $conn->prepare("SELECT * FROM users WHERE email = ? OR phone = ?");
    $select->execute([$email, $phone]);
  if($select->rowCount() > 0){
    $warning_msg[] = 'Email Or Phone Already Exist!';
  }else{
    $insert = $conn->prepare("INSERT INTO users (name, email, phone, address, password, image) VALUES (?, ?, ?, ?, ?, ?)");
    $insert->execute([$name, $email, $phone, $address, $password, $rename]);
    move_uploaded_file($image_tmp_name,$image_tmp_folder);
    $success_msg[] = 'You Have Created Your Account Successfully';
  }
  }else{
    $warning_msg[] = 'Confirm Password Not Match!';
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
 <title>Bookly | Register</title>
 <meta charset="UTF-8">
 <meta http-equiv="X-UA-Compatible" content="IE=edge">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <link rel="shortcut icon" href="image/icon.svg" type="image/x-icon">
 <link rel="stylesheet" href="css/alert.css">
 <link rel="stylesheet" href="css/all.min.css">
 <link rel="stylesheet" href="css/style.css">
 <script src="js/alert.js"></script>
</head>

<body>
 <!-- Start Includes -->
 <header class="header box-shadow">
  <?php include 'includes/header1.php'; ?>
 </header>
 <ul class="notifications"></ul>
 <?php include 'includes/alert.php'; ?>
 <!-- End Includes -->

 <!-- login form  -->
 <div class="login-form-container register-form container">
  <form action="" method="POST" enctype="multipart/form-data">
   <h3>sign Up</h3>
   <div class="form-boxes">
    <div class="box">
     <label for="name">username</label>
     <input type="name" name="name" class="box-input" placeholder="enter your name" id="name" required>
    </div>
    <div class="box">
     <label for="email">email</label>
     <input type="email" name="email" class="box-input" placeholder="enter your email" id="email" required>
    </div>
    <div class="box">
     <label for="phone">phone</label>
     <input type="tel" name="phone" class="box-input" placeholder="enter your phone" id="phone" required>
    </div>
    <div class="box">
     <label for="address">address</label>
     <input type="text" name="address" class="box-input" placeholder="enter your address" id="address" required>
    </div>
    <div class="box">
     <label for="password">password</label>
     <input type="password" name="password" class="box-input" placeholder="enter your password" id="password" required>
    </div>
    <div class="box">
     <label for="cpassword">confirm password</label>
     <input type="password" name="cpassword" class="box-input" placeholder="confirm your password" id="cpassword"
      required>
    </div>
    <div class="box w-100">
     <label for="image">select image</label>
     <input type="file" name="image" class="box-input" accept="image/png, image/jpg, image/jpeg, image/jifi "
      id="image">
    </div>
   </div>
   <input type="submit" value="sign up" name="register" class="btn">
   <p>already have an account ? <a href="login.php">sign in</a></p>
  </form>
 </div>

 <?php 
if(isset($user_id)){
  ?> <script src="js/profile_box.js"></script><?php 
}
?>
</body>

</html>