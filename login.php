<?php
include 'includes/connection.php';
$user_id = '';
if(isset($_COOKIE['user_id'])){
  $user_id = $_COOKIE['user_id'];
}
if (isset($_POST['login'])) {
  $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
  $password = sha1($_POST['password']);
  $password = filter_var($password, FILTER_SANITIZE_STRING);
  $select_user = $conn->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
  $select_user->execute([$email, $password]);
  if($select_user->rowCount() > 0){
    $row = $select_user->fetch(PDO::FETCH_OBJ);
    $success_msg[] = "Login Successfully!";
    if($row->group_id == 1){
      setcookie('user_id', $row->id, time() + 60*60*24*30, '/');
      header('location: index.php');
    }else if($row->group_id == 0){
      setcookie('admin_id', $row->id, time() + 60*60*24*30, '/');
      header('location: admin/index.php');
    }
  }else{
    $warning_msg[] = 'Wrong Email Or Password!';
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
 <title>Bookly | Login</title>
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
 <div class="login-form-container">
  <form action="" method="POST">
   <h3>sign in</h3>
   <div class="box">
    <label for="email">email</label>
    <input type="email" name="email" class="box-input" placeholder="enter your email" id="email">
   </div>
   <div class="box">
    <label for="password">password</label>
    <input type="password" name="password" class="box-input" placeholder="enter your password" id="password">
   </div>
   <div class="checkbox">
    <input type="checkbox" name="" id="remember-me">
    <label for="remember-me"> remember me</label>
   </div>
   <input type="submit" value="sign in" name="login" class="btn">
   <p>forget password ? <a href="#">click here</a></p>
   <p>don't have an account ? <a href="register.php">create one</a></p>
  </form>
 </div>


</body>

</html>