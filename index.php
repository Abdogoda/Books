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
  header("refresh: 0, url = index.php");
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
  header("refresh:0, url = index.php");
}

// send message
if(isset($_POST['send_message'])){
  $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
  $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
  $content = filter_var($_POST['message'], FILTER_SANITIZE_STRING);
  $send_message = $conn->prepare("INSERT INTO messages (name, email, content) VALUES (?, ?, ?)");
  $send_message->execute([$name, $email, $content]);
  $success_msg[] = "Your message has been sent successfully";
  header("refresh: 3, url=index.php");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta http-equiv="X-UA-Compatible" content="IE=edge">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Bookly</title>
 <link rel="shortcut icon" href="image/icon.svg" type="image/x-icon">
 <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css" />
 <link rel="stylesheet" href="css/alert.css">
 <link rel="stylesheet" href="css/all.min.css">
 <link rel="stylesheet" href="css/style.css">
 <script src="js/alert.js"></script>
</head>

<body>
 <!-- header section  -->
 <header class="header">
  <?php  include 'includes/header1.php'; ?>
  <div class="header-2">
   <nav class="navbar">
    <a href="#home">home</a>
    <a href="books.php">books</a>
    <a href="#featured">featured</a>
    <a href="#arrivals">arrivals</a>
    <a href="#blogs">Categories</a>
    <a href="#reviews">reviews</a>
   </nav>
  </div>
  <ul class="notifications"></ul>
  <?php include 'includes/alert.php'; ?>
 </header>
 <!-- bottom navbar  -->
 <nav class="bottom-navbar">
  <a href="#home" class="fas fa-home"></a>
  <a href="books.php" class="fas fa-book"></a>
  <a href="#arrivals" class="fas fa-tags"></a>
  <a href="#blogs" class="fas fa-blog"></a>
  <a href="#reviews" class="fas fa-comments"></a>
 </nav>

 <!-- home section starts  -->
 <section class="home" id="home">
  <div class="row">
   <div class="content">
    <h3>upto 75% off</h3>
    <p>Discover the magic of words, where imagination takes flight and stories unfold. Step into a captivating realm of
     literature that transcends time and space, and let the journey begin.</p>
    <a href="books.php" class="btn">shop now</a>
   </div>

   <div class="swiper books-slider">
    <div class="swiper-wrapper">
     <?php
        $select_loading_books = $conn->prepare("SELECT * FROM books ORDER BY RAND() LIMIT 10");
        $select_loading_books->execute([]);
        if($select_loading_books->rowCount() > 0){
            while($row = $select_loading_books->fetch()){
           ?>
     <a href="book_details.php?id=<?php echo $row['id']?>" class="swiper-slide"><img
       src="image/books/<?php echo $row['image']?>" alt="<?php echo $row['name']?>"></a>
     <?php }}?>
    </div>
    <img src="image/stand.png" class="stand" alt="stand_image">
   </div>
  </div>
 </section>

 <!-- icons section starts  -->
 <section class="icons-container">
  <div class="icons">
   <i class="fas fa-shipping-fast"></i>
   <div class="content">
    <h3>free shipping</h3>
    <p>order over $100</p>
   </div>
  </div>
  <div class="icons">
   <i class="fas fa-lock"></i>
   <div class="content">
    <h3>secure payment</h3>
    <p>100 secure payment</p>
   </div>
  </div>
  <div class="icons">
   <i class="fas fa-redo-alt"></i>
   <div class="content">
    <h3>easy returns</h3>
    <p>10 days returns</p>
   </div>
  </div>
  <div class="icons">
   <i class="fas fa-headset"></i>
   <div class="content">
    <h3>24/7 support</h3>
    <p>call us anytime</p>
   </div>
  </div>
 </section>

 <!-- featured section starts  -->
 <section class="featured" id="featured">
  <h1 class="heading"> <span>featured books</span> </h1>
  <div class="swiper featured-slider">
   <div class="swiper-wrapper">
    <?php
        $select_featured_books = $conn->prepare("SELECT * FROM books ORDER BY id DESC LIMIT 10");
        $select_featured_books->execute([]);
        if($select_featured_books->rowCount() > 0){
            while($row = $select_featured_books->fetch(PDO::FETCH_OBJ)){
            ?>
    <div class="swiper-slide box">
     <div class="icons">
      <a href="cart.php"
       class="fas fa-shopping-cart"><?= in_array("$row->id", array_column($cart, 'id')) ? "<span>".$cart[array_search("$row->id", array_column($cart, 'id'))]->qty."</span>" : "" ?></a>
      <a href="index.php?like=<?= $row->id?>"
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
      <h3 class="title"><?= $row->name?></h3>
      <div class="price"><?= $row->price?> EGP <span><?= floatval($row->price) * 1.1 ?> EGP</span></div>
      <input type="submit" class="btn" name="add_to_cart" value="add to cart">
     </form>
    </div>
    <?php  }}?>
   </div>
   <div class="swiper-button-next"></div>
   <div class="swiper-button-prev"></div>
  </div>
 </section>

 <!-- newsletter section starts -->
 <section class="newsletter">
  <form action="">
   <h3>subscribe for latest updates</h3>
   <input type="email" name="" placeholder="enter your email" id="" class="box">
   <input type="submit" value="subscribe" class="btn">
  </form>
 </section>

 <!-- arrivals section starts  -->
 <section class="arrivals" id="arrivals">
  <h1 class="heading"> <span>new arrivals</span> </h1>
  <div class="swiper arrivals-slider">
   <div class="swiper-wrapper">
    <?php
        $select_new_arrivals_books = $conn->prepare("SELECT * FROM books ORDER BY id DESC LIMIT 10");
        $select_new_arrivals_books->execute([]);
        if($select_new_arrivals_books->rowCount() > 0){
            while($row = $select_new_arrivals_books->fetch()){
           ?>
    <a href="book_details.php?id=<?php echo $row['id']?>" class="swiper-slide box">
     <div class="image">
      <img src="image/books/<?php echo $row['image']?>" alt="book_image">
     </div>
     <div class="content">
      <h3><?php echo $row['name']?></h3>
      <div class="price"><?php echo $row['price']?>EGP <span><?= floatval($row['price']) * 1.1 ?> EGP</span></div>
      <div class="stars">
       <i class="fas fa-star"></i>
       <i class="fas fa-star"></i>
       <i class="fas fa-star"></i>
       <i class="fas fa-star"></i>
       <i class="fas fa-star-half-alt"></i>
      </div>
     </div>
    </a>
    <?php }}?>
   </div>
  </div>
 </section>

 <!-- deal section starts  -->
 <section class="deal">
  <div class="content">
   <h3>deal of the day</h3>
   <h1>upto 50% off</h1>
   <p>Discover the joy of reading without breaking the bank. Dive into a world of captivating stories and knowledge at
    discounted prices. Feed your mind, ignite your imagination, and let books transport you to new horizons. Embrace the
    thrill of affordable adventures.</p>
   <a href="books.php" class="btn">shop now</a>
  </div>
  <div class="image">
   <img src="image/deal-img.jpg" alt="">
  </div>
 </section>

 <!-- blogs section starts  -->
 <section class="blogs" id="blogs">
  <h1 class="heading"> <span>our Categories</span> </h1>
  <div class="swiper blogs-slider">
   <div class="swiper-wrapper">
    <?php
      $select_categories = $conn->prepare("SELECT * FROM category ORDER BY id DESC ");
      $select_categories->execute([]);
      if($select_categories->rowCount() > 0){
        while($row = $select_categories->fetch(PDO::FETCH_OBJ)){?>
    <div class="swiper-slide box">
     <div class="image">
      <img src="image/categories/<?= $row->image ?>" alt="category_image">
     </div>
     <div class="content">
      <h3><?= $row->title ?></h3>
      <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Optio, odio.</p>
      <a href="books.php" class="btn">Discover More</a>
     </div>
    </div>
    <?php }} ?>
   </div>
  </div>
 </section>

 <!-- reviews section starts  -->
 <section class="reviews" id="reviews">
  <h1 class="heading"> <span>client's reviews</span> </h1>
  <div class="swiper reviews-slider">
   <div class="swiper-wrapper reviews-swiper">
    <div class="swiper-slide box">
     <div class="info">
      <img src="image/testimonials/pic-1.png" alt="user_1">
      <h3>John Snow</h3>
     </div>
     <p>I recently discovered an amazing online bookshop and I couldn't be happier with my experience! The website is
      user-friendly, making it easy to browse through their extensive collection. The ordering process was seamless, and
      the books arrived well-packaged and in perfect condition. Their prices were competitive, and they offer frequent
      discounts and promotions. I highly recommend this online bookshop for any book lover!</p>
     <div class="stars">
      <i class="fas fa-star"></i>
      <i class="fas fa-star"></i>
      <i class="fas fa-star"></i>
      <i class="fas fa-star"></i>
      <i class="fas fa-star-half-alt"></i>
     </div>
    </div>
    <div class="swiper-slide box">
     <div class="info">
      <img src="image/testimonials/pic-2.png" alt="user_2">
      <h3>Emilia Clarck</h3>
     </div>
     <p>I had a fantastic experience shopping at this online bookshop! The website has a sleek and modern design that is
      visually appealing and intuitive to navigate. They have an impressive selection of books across various genres,
      including both popular titles and hidden gems. The checkout process was quick and secure, and my books arrived
      promptly. The customer service team was also very responsive and helpful in addressing my queries. I will
      definitely be returning for my future book purchases!</p>
     <div class="stars">
      <i class="fas fa-star"></i>
      <i class="fas fa-star"></i>
      <i class="fas fa-star"></i>
      <i class="fas fa-star"></i>
      <i class="far fa-star"></i>
     </div>
    </div>
    <div class="swiper-slide box">
     <div class="info">
      <img src="image/testimonials/pic-3.png" alt="user_3">
      <h3>Wiliam mark</h3>
     </div>
     <p>This online bookshop exceeded my expectations. Not only do they have an incredible range of books, but they also
      provide detailed descriptions, reviews, and recommendations for each title, making it easier to find exactly what
      I was looking for. The prices were competitive, and they often offer special deals and bundles. The shipping was
      fast, and the packaging was sturdy, ensuring that my books arrived in pristine condition. I highly recommend this
      online bookshop to fellow book enthusiasts.</p>
     <div class="stars">
      <i class="fas fa-star"></i>
      <i class="fas fa-star"></i>
      <i class="fas fa-star"></i>
      <i class="fas fa-star"></i>
      <i class="fas fa-star"></i>
     </div>
    </div>
    <div class="swiper-slide box">
     <div class="info">
      <img src="image/testimonials/pic-4.png" alt="user_4">
      <h3>Thia queen</h3>
     </div>
     <p>I've been a loyal customer of this online bookshop for years, and they never disappoint. The website is easy to
      navigate, and they have an impressive inventory of books from different authors and genres. What sets them apart
      is their commitment to customer satisfaction. They prioritize timely deliveries, and on the rare occasion when
      there's an issue, their customer support team is quick to resolve it.</p>
     <div class="stars">
      <i class="fas fa-star"></i>
      <i class="fas fa-star"></i>
      <i class="fas fa-star"></i>
      <i class="fas fa-star"></i>
      <i class="fas fa-star-half-alt"></i>
     </div>
    </div>
    <div class="swiper-slide box">
     <div class="info">
      <img src="image/testimonials/pic-5.png" alt="user_6">
      <h3>Ed Sherien</h3>
     </div>
     <p>I stumbled upon this online bookshop while searching for a specific title, and I'm so glad I did! Their website
      is visually appealing and has a user-friendly interface. The search function is robust, allowing me to discover
      new books based on my interests. The prices are competitive, and I appreciate the option to purchase both physical
      copies and e-books. The delivery was prompt, and the packaging was eco-friendly, which was a nice touch. This
      online bookshop is now my favorite destination for all things literary!</p>
     <div class="stars">
      <i class="fas fa-star"></i>
      <i class="fas fa-star"></i>
      <i class="fas fa-star"></i>
      <i class="fas fa-star"></i>
      <i class="fas fa-star-half-alt"></i>
     </div>
    </div>
   </div>
  </div>
 </section>

 <!-- footer section starts  -->
 <section class="footer">
  <div class="box-container">
   <div class="box">
    <h3>our locations</h3>
    <a href="#"> <i class="fas fa-map-marker-alt"></i> india </a>
    <a href="#"> <i class="fas fa-map-marker-alt"></i> USA </a>
    <a href="#"> <i class="fas fa-map-marker-alt"></i> russia </a>
    <a href="#"> <i class="fas fa-map-marker-alt"></i> france </a>
    <a href="#"> <i class="fas fa-map-marker-alt"></i> japan </a>
    <a href="#"> <i class="fas fa-map-marker-alt"></i> africa </a>
   </div>
   <div class="box">
    <h3>quick links</h3>
    <a href="#"> <i class="fas fa-arrow-right"></i> home </a>
    <a href="#"> <i class="fas fa-arrow-right"></i> featured </a>
    <a href="#"> <i class="fas fa-arrow-right"></i> arrivals </a>
    <a href="#"> <i class="fas fa-arrow-right"></i> reviews </a>
    <a href="#"> <i class="fas fa-arrow-right"></i> blogs </a>
   </div>
   <div class="box">
    <h3>contact info</h3>
    <a href="#"> <i class="fas fa-phone"></i> +123-456-7890 </a>
    <a href="#"> <i class="fas fa-phone"></i> +111-222-3333 </a>
    <a href="#"> <i class="fas fa-envelope"></i> abdogoda0a@gmail.com </a>
    <img src="image/worldmap.png" class="map" alt="">
   </div>
   <div class="box">
    <h3>contact form</h3>
    <form action="" method="post">
     <div class="input-box">
      <label for="name">name</label>
      <input type="text" name="name" id="name" required></textarea>
     </div>
     <div class="input-box">
      <label for="email">email</label>
      <input type="email" name="email" id="email" required></textarea>
     </div>
     <div class="input-box">
      <label for="message">your message</label>
      <textarea name="message" id="message" required></textarea>
     </div>
     <input type="submit" name="send_message" value="send" class="btn">
    </form>
   </div>
  </div>
  <div class="share">
   <a href="#" class="fab fa-facebook-f"></a>
   <a href="#" class="fab fa-twitter"></a>
   <a href="#" class="fab fa-instagram"></a>
   <a href="#" class="fab fa-linkedin"></a>
   <a href="#" class="fab fa-pinterest"></a>
  </div>
 </section>

 <!-- loader  -->
 <div class="loader-container">
  <img src="image/loader.gif" alt="loader gif">
 </div>

 <script src="https://unpkg.com/swiper@7/swiper-bundle.min.js"></script>
 <script src="js/script.js"></script>
 <script src="js/loader.js"></script>
 <script src="js/swipers.js"></script>
</body>

</html>