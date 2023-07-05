<?php
include 'includes/connection.php';
$user_id = '';
if(isset($_COOKIE['user_id'])){
  $user_id = $_COOKIE['user_id'];
}

$cart = [];
if(isset($_COOKIE['cart']) && count(json_decode($_COOKIE['cart'])) > 0){
  $cart = json_decode($_COOKIE['cart']);
}
$liked = [];
if(isset($_COOKIE['liked']) && count(json_decode($_COOKIE['liked'])) > 0){
  $liked = json_decode($_COOKIE['liked']);
}
if(isset($_POST['inc'])){
  $book_id = $_POST['book_id'];
  foreach ($cart as $book) {
    if($book->id == $book_id){
      $book->qty = $book->qty + 1;
    }
  }
  setcookie("cart",json_encode($cart), time() + 60*60*24*30, '/');
  header("location: cart.php");
}
if(isset($_POST['dec'])){
  $book_id = $_POST['book_id'];
  foreach ($cart as $key => $book) {
    if($book->id == $book_id){
      if($book->qty <= 1){
        unset($cart[$key]);
      }else{
        $book->qty = $book->qty - 1;
      }
    }
  }
  setcookie("cart",json_encode(array_values($cart)), time() + 60*60*24*30, '/');
  header("location: cart.php");
}
if(isset($_POST['del'])){
  $book_id = $_POST['book_id'];
  foreach ($cart as $key => $book) {
    if($book->id == $book_id){
      unset($cart[$key]);
    }
  }
  setcookie("cart",json_encode(array_values($cart)), time() + 60*60*24*30, '/');
  header("location: cart.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta http-equiv="X-UA-Compatible" content="IE=edge">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Bookly | Cart</title>
 <link rel="shortcut icon" href="image/icon.svg" type="image/x-icon">
 <link rel="stylesheet" href="css/alert.css">
 <link rel="stylesheet" href="css/all.min.css">
 <link rel="stylesheet" href="css/style.css">
 <link rel="stylesheet" href="css/cart.css">
 <script src="js/alert.js"></script>
</head>

<body>
 <!-- header section  -->
 <header class="header">
  <?php  include 'includes/header1.php'; ?>
  <ul class="notifications"></ul>
  <?php include 'includes/alert.php'; ?>
 </header>

 <!-- cart section starts  -->
 <section class="cart" id="cart">
  <h1 class="heading"> <span>your cart</span> </h1>
  <div class="cart-wrapper">
   <ul class="cart-books">
    <?php
        if(count($cart) > 0){
          $total = 0;
          foreach ($cart as $key => $book) {
            $select_book = $conn->prepare("SELECT b.*, c.title FROM books b JOIN category c ON b.category_id = c.id WHERE b.id = ? LIMIT 1");
            $select_book->execute([$book->id]);
            if($select_book->rowCount() > 0){
              $row = $select_book->fetch(PDO::FETCH_OBJ);
              $total += intval($book->qty) * $row->price; ?>
    <li>
     <a href="book_details.php?id=<?= $row->id?>" class="info">
      <img src="image/books/<?= $row->image?>" alt="book-image<?= $row->id ?>">
      <div>
       <p class="title"><?= $row->name?></p>
       <p class="price"><?= $row->price?> EGP</p>
      </div>
     </a>
     <div class="quantity">
      <form method="post" action="">
       <input type="hidden" name="book_id" value="<?= $row->id ?>">
       <button type="submit" name="dec" class="btn btn-dec"><i class="fas fa-chevron-down"></i></button>
       <span><?= $book->qty?></span>
       <button type="submit" name="inc" class="btn btn-inc"><i class="fas fa-chevron-up"></i></button>
       <button type="submit" name="del" onclick="return confirm('Are You Sure You Want To Delete This Book!?')"
        class="btn btn-danger">
        <i class="fas fa-trash"></i></button>
      </form>
      <p class="price"><?= intval($book->qty) * $row->price?> EGP</p>
     </div>
    </li>
    <?php }}}else{ ?>
    <li>
     <p class="no-books">No Book Added Yet
      <span class="btns"><a href="books.php" class="btn">back to shop</a>
       <a href="profile.php" class="btn">view orders</a>
      </span>
     </p>
    </li>
    <?php } ?>
   </ul>
   <?php
    if(count(($cart)) >= 1 && $total > 1){
      ?>
   <div class="cart-checkout">
    <h3>order summary</h3>
    <p><b>subtotal: </b><?= $total?> EGP</p>
    <p><b>shipping: </b> <?= $total * 0.1 ?> EGP</p>
    <hr>
    <p class="total"><b>total: <span><?= $total * 1.1 ?> EGP</span></b></p>
    <div class="btns">
     <a href="checkout.php" class="btn">checkout and order</a>
     <a href="books.php" class="btn">back to shop</a>
     <a href="profile.php" class="btn">view orders</a>
    </div>
   </div>
   <?php
    }
    ?>
  </div>
 </section>

</body>

</html>