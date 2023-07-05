<?php
include 'includes/connection.php';
$user_id = '';
if(isset($_COOKIE['user_id'])){
  $user_id = $_COOKIE['user_id'];
}

$author_id = '';
if(isset($_GET['id'])){
 $author_id = $_GET['id'];
}else{
 header("location: index.php");
}
$author_select = $conn->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
$author_select->execute([$author_id]);
if(!$author_select->rowCount() > 0){
 header('location: login.php');
}
$author = $author_select->fetch(PDO::FETCH_OBJ);
$author_name = $author->name;
$author_email = $author->email;
$author_phone = $author->phone;
$author_address = $author->address;
$author_image = $author->image;

$author_books_select = $conn->prepare("SELECT * FROM books WHERE author = ? ORDER BY id DESC");
$author_books_select->execute([$author_id]);
$no_of_books = $author_books_select->rowCount();

?>
<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta http-equiv="X-UA-Compatible" content="IE=edge">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Author Profile</title>
 <link rel="shortcut icon" href="image/icon.svg" type="image/x-icon">
 <link rel="stylesheet" href="css/alert.css">
 <link rel="stylesheet" href="css/all.min.css">
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
  <h1 class="heading"> <span>author information</span> </h1>
  <div class="profile-container">
   <div class="image"><img src="image/user_images/<?= $author_image ?>" alt="author_image"></div>
   <div class="info">
    <p class="name"><?= $author_name ?></p>
    <p class="p"><b>phone:</b> <?= $author_phone ?></p>
    <p class="p"><b>email:</b> <span style="text-transform: none;"><?= $author_email ?></span></p>
    <p class="p"><b>address:</b> <?= $author_address ?></p>
    <p class="p"><b>no of books:</b> <?= $no_of_books ?> books</p>
   </div>
  </div>
 </section>
 <section class="arrivals" id="arrivals">
  <?php
 if($author_books_select->rowCount() > 0){?>
  <h1 class="heading"> <span>author books</span> </h1>
  <div class="books-wrapper">
   <?php while($row = $author_books_select->fetch(PDO::FETCH_OBJ)){  ?>
   <div class="book-box">
    <div class="image">
     <a href="book_details.php?id=<?= $row->id?>">
      <img src="image/books/<?= $row->image?>" alt="book_image">
     </a>
    </div>
    <div class="content">
     <p class="title"><?= $row->name?></p>
     <div class="price"><?= $row->price?> EGP <span><?= floatval($row->price) * 1.1 ?> EGP</span></div>
    </div>
   </div>
   <?php }?>
  </div>
  <?php }?>
 </section>

</body>

</html>