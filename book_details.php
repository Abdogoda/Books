<?php 
include 'includes/connection.php';
$user_id = '';
if(isset($_COOKIE['user_id'])){
  $user_id = $_COOKIE['user_id'];
}
if(!isset($_GET['id'])){
  header("location:books.php");
}
$select_book = $conn->prepare("SELECT b.*, u.name as author_name, c.title FROM books b JOIN category c ON b.category_id = c.id JOIN users u ON b.author = u.id WHERE b.id = ? LIMIT 1");
$select_book->execute([$_GET['id']]);
if(!$select_book->rowCount() > 0){
  header("location:books.php");
}else{
  $book_id = $_GET['id'];
  $book = $select_book->fetch();
  $book_image = $book['image'];
  $book_name = $book['name'];
  $author_name = $book['author_name'];
  $author_id = $book['author'];
  $book_price = $book['price'];
  $book_category_id = $book['category_id'];
  $book_category = $book['title'];
  $book_description = $book['description'];
}
$cart = [];
if(isset($_COOKIE['cart']) && count(json_decode($_COOKIE['cart'])) > 0){
  $cart = json_decode($_COOKIE['cart']);
}
$liked = [];
if(isset($_COOKIE['liked']) && count(json_decode($_COOKIE['liked'])) > 0){
  $liked = json_decode($_COOKIE['liked']);
}
if(isset($_GET['cart'])){
  $book_id_to_add = $_GET['cart'];
  if(count($cart) > 0){
    $book_exits = false;
    foreach ($cart as $book) {
      if($book->id == $book_id_to_add){
        $book->qty = $book->qty + 1;
        $book_exits = true;
      }
    }
    if(!$book_exits){
      $cart[] = ['id' => $book_id_to_add, 'qty' => 1];
    }
  }else{
    $cart[] = ['id' => $book_id_to_add, 'qty' => 1];
  }
  $success_msg[] = "Book Added Successfully";
  setcookie("cart",json_encode($cart), time() + 60*60*24*30, '/');
  header("refresh: 3, url = book_details.php?id=".$book_id);
}
if(isset($_GET['like'])){
  $book_id_to_like = $_GET['like'];
  $book_exits = false;
  if(count($liked) > 0){
    foreach ($liked as $key => $book) {
      if($book == $book_id_to_like){
        $book_exits = true;
        unset($liked[$key]);
        $liked = array_values($liked);
        $info_msg[] = "Book Removed From Favorite";
      }
    }
    if(!$book_exits){
      $liked[] = $book_id_to_like;
      $success_msg[] = "Book Added To Favorite";
    }
  }else{
    $liked[] = $book_id_to_like;
    $success_msg[] = "Book Added";
  }
  setcookie("liked",json_encode($liked), time() + 60*60*24*30, '/');
  header("refresh: 3, url = book_details.php?id=".$book_id);
}
// 
$isLiked = false;
foreach($liked as $like){
  if($like == $book_id){
    $isLiked = true;
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta http-equiv="X-UA-Compatible" content="IE=edge">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Bookly | <?php echo $book_name?></title>
 <link rel="shortcut icon" href="image/icon.svg" type="image/x-icon">
 <link rel="stylesheet" href="css/alert.css">
 <link rel="stylesheet" href="css/all.min.css">
 <link rel="stylesheet" href="css/style.css">
 <link rel="stylesheet" href="css/book.css">
 <script src="js/alert.js"></script>
</head>

<body>
 <header class="header">
  <?php  include 'includes/header1.php'; ?>
  <ul class="notifications"></ul>
  <?php include 'includes/alert.php'; ?>
 </header>
 <section>
  <h1 class="heading"> <span>Book Details (<?= $book_id ?>)</span> </h1>
  <div class="book_box">
   <div class="book_image"><img src="image/books/<?= $book_image?>" alt="<?= $book_name?>"></div>
   <div class="book_info">
    <h2><?= $book_name?></h2>
    <p class="category"><b>category:</b> <?= $book_category?></p>
    <p class="category"><b>author:</b> <a href="author.php?id=<?= $author_id ?>"><?= $author_name?></a></p>
    <p class="price"><b>price: </b><?= $book_price?> EGP</p>
    <span><?= $book_description?></span>
    <div class="btns">
     <a href="book_details.php?id=<?= $book_id ?>&cart=<?= $book_id ?>" class="btn">Add To Cart
      <?= in_array("$book_id", array_column($cart, 'id')) ? "(".$cart[array_search("$book_id", array_column($cart, 'id'))]->qty.")" : "" ?></a>
     <a href="book_details.php?id=<?= $book_id ?>&like=<?= $book_id ?>"
      class="btn"><?= $isLiked  ? "Remove From Favorite" : "Add To Favorite" ?></a>
    </div>
   </div>
  </div>
 </section>
 <?php
 $select_similer_books = $conn->prepare("SELECT * FROM books WHERE category_id = ? AND id != ? ORDER BY id DESC ") ;
 $select_similer_books->execute([$book_category_id, $book_id]);
 if($select_similer_books->rowCount() > 0){?>
 <section class="arrivals" id="arrivals">
  <h1 class="heading"> <span>most relative</span> </h1>
  <div class="books-wrapper">
   <?php while($row = $select_similer_books->fetch(PDO::FETCH_OBJ)){  ?>
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
 </section>
 <?php }?>

</body>

</html>