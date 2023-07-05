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
if(isset($_POST['add_to_cart'])){
  $book_id_to_add = $_POST['book_id_to_add'];
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
  setcookie("cart",json_encode($cart), time() + 60*60*24*30, '/');
  header("refresh: 0, url = books.php");
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
      }
    }
    if(!$book_exits){
      $liked[] = $book_id_to_like;
    }
  }else{
    $liked[] = $book_id_to_like;
  }
  setcookie("liked",json_encode($liked), time() + 60*60*24*30, '/');
  header("refresh:0, url = books.php");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta http-equiv="X-UA-Compatible" content="IE=edge">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Bookly | Books</title>
 <link rel="shortcut icon" href="image/icon.svg" type="image/x-icon">
 <link rel="stylesheet" href="css/alert.css">
 <link rel="stylesheet" href="css/all.min.css">
 <link rel="stylesheet" href="css/style.css">
 <script src="js/alert.js"></script>
</head>

<body>
 <!-- header section  -->
 <header class="header">
  <?php  include 'includes/header1.php'; ?>
  <ul class="notifications"></ul>
  <?php include 'includes/alert.php'; ?>
 </header>
 <!-- featured section starts  -->
 <section class="books" id="books">
  <h1 class="heading"> <span>our books</span>
  </h1>
  <div class="books-container">
   <div class="category-wrapper">
    <h2><span>Categories</span> <button class="show-hide-ctegory"><i class="fa-solid fa-angle-down"></i></button></h2>
    <ul>
     <?php
        $select_categories = $conn->prepare("SELECT c.*, COUNT(b.id) AS count FROM category c LEFT JOIN books b ON b.category_id = c.id GROUP BY c.title ORDER BY count DESC");
        $select_categories->execute([]);
        if($select_categories->rowCount() > 0){
          while($row = $select_categories->fetch(PDO::FETCH_OBJ)){?>
     <li data-category="<?= $row->id ?>">
      <div class="image">
       <img src="image/categories/<?= $row->image != "" ? $row->image : "category_8.jpg" ?>"
        alt="category_image<?= $row->id ?>">
      </div>
      <div class="info">
       <p class="title"><?= $row->title ?></p>
       <p class="count"><?= $row->count ?></p>
      </div>
     </li>
     <?php }} ?>
    </ul>
   </div>
   <div class="books-wrapper">
    <?php
          if(isset($_POST['search_box']) or isset($_POST['search_btn'])){
            $search_box = $_POST['search_box'];
            $select_books = $conn->prepare("SELECT books.*,category.title FROM books JOIN category ON books.category_id = category.id WHERE books.name LIKE '%{$search_box}%' ORDER BY date DESC ");
          }else{
        $select_books = $conn->prepare("SELECT * FROM books ORDER BY id DESC");
          }
        $select_books->execute([]);
        if($select_books->rowCount() > 0){
            while($row = $select_books->fetch(PDO::FETCH_OBJ)){
           ?>
    <div class="book-box" data-bookcategory="<?= $row->category_id ?>">
     <div class="icons">
      <a href="cart.php"
       class="fas fa-shopping-cart"><?= in_array("$row->id", array_column($cart, 'id')) ? "<span>".$cart[array_search("$row->id", array_column($cart, 'id'))]->qty."</span>" : "" ?></a>
      <a href="books.php?like=<?= $row->id?>"
       class="fas fa-heart <?php foreach($liked as $like) if($like == $row->id){echo "active_liked";} ?>"></a>
      <a href="book_details.php?id=<?= $row->id?>" class="fas fa-eye"></a>
     </div>
     <div class="image">
      <a href="book_details.php?id=<?= $row->id?>">
       <img src="image/books/<?= $row->image?>" alt="book_image">
      </a>
     </div>
     <form method="POST" action="" class="content">
      <input type="hidden" name="book_id_to_add" value="<?= $row->id ?>">
      <p class="title"><?= $row->name?></p>
      <div class="price"><?= $row->price?> EGP <span><?= floatval($row->price) * 1.1 ?> EGP</span></div>
      <input type="submit" class="btn" name="add_to_cart" value="add to cart">
     </form>
    </div>
    <?php  }}?>
   </div>
  </div>
 </section>

 <script src="js/script.js"></script>
</body>

</html>