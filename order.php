<?php
include 'includes/connection.php';
$user_id = '';
if(isset($_COOKIE['user_id'])){
  $user_id = $_COOKIE['user_id'];
}else{
 header("location: login.php");
}
$order_id = "";
if(isset($_GET['id'])){
 $order_id = $_GET['id'];
}else{
 header("location: orders.php");
}
$select_order = $conn->prepare("SELECT * FROM orders o JOIN order_details od ON o.id = od.o_id WHERE id = ?");
$select_order->execute([$order_id]);
if(!$select_order->rowCount() > 0){
 header("location: orders.php");
}
$order = $select_order->fetch(PDO::FETCH_OBJ);
$select_order_status = $conn->prepare("SELECT os.*, u.name FROM order_status os JOIN users u ON os.e_id = u.id WHERE o_id = ? ORDER BY s_id DESC");
$select_order_status->execute([$order_id]);
$select_order_last_status = $conn->prepare("SELECT * FROM order_status WHERE o_id = ? ORDER BY s_id DESC LIMIT 1");
$select_order_last_status->execute([$order_id]);
$last_status = $select_order_last_status->fetch(PDO::FETCH_OBJ);
$select_order_books = $conn->prepare("SELECT * FROM order_books WHERE o_id = ?");
$select_order_books->execute([$order_id]);

$cart = [];
if(isset($_COOKIE['cart']) && count(json_decode($_COOKIE['cart'])) > 0){
  $cart = json_decode($_COOKIE['cart']);
}
$liked = [];
if(isset($_COOKIE['liked']) && count(json_decode($_COOKIE['liked'])) > 0){
  $liked = json_decode($_COOKIE['liked']);
}
// Cancell Pending Order
if(isset($_POST['cancel_order'])){
 if($last_status->status == "Pending"){
   $cancel_order = $conn->prepare("INSERT INTO order_status (o_id, e_id, status) VALUES (?, ?, ?)");
   $cancel_order->execute([$order_id, 1, " Cancelled"]);
   $success_msg[] = "Order Cancelled Successfully!";
   header("refresh: 2; url=order.php?id=".$order_id);
 }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta http-equiv="X-UA-Compatible" content="IE=edge">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Order Detatils</title>
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
  <h1 class="heading"> <span>Order (<?= $order_id ?>)</span> </h1>
  <div class="order-details-tabs m-20 bg-white">
   <div class="tab">
    <button class="tablinks active" onclick="openTab(event, 'order_details')">Order Details</button>
    <button class="tablinks" onclick="openTab(event, 'order_products')">Order
     Products</button>
    <button class="tablinks" onclick="openTab(event, 'order_status')">Order
     Status</button>
   </div>
   <div id="order_details" class="tabcontent" style="display: block;">
    <h2>Order Details</h2>
    <p> <b>Sub Total</b> : <span><?= $order->sub_total ?> EGP</span> </p>
    <p> <b>Shipping Tax</b> : <span><?= $order->taxes ?> EGP</span> </p>
    <p> <b>Total</b> : <span><?= $order->total ?> EGP</span> </p>
    <p> <b>Status</b> : <span class="status <?= $last_status->status ?>"><?= $last_status->status ?></span> </p>
    <p> <b>Date</b> : <?= $order->date ?> </p>
    <br>
    <h2>Billing Details</h2>
    <p> <b>City</b>: <?= $order->city ?> </p>
    <p> <b>Street</b>: <?= $order->street ?> </p>
    <p> <b>Building</b>: <?= $order->building ?> </p>
    <p> <b>Payment Method</b>: <?= $order->payment_method == 0 ? "Cash On Delivery" : "Credit Card" ?> </p>
    <form method="POST" class="btns">
     <?php if($last_status->status == "Pending"){ ?>
     <button type="submit" name="cancel_order" class="btn bg-red">Cancel Order <i class="fa fa-trash"></i></button>
     <?php } ?>
     <?php if($last_status->status == "Completed"){ ?>
     <a href="invoice.php?id=<?= $order->id ?>" class="btn">Print Invoice <i class="fa fa-print"></i></a>
     <?php } ?>
    </form>
   </div>

   <div id="order_products" class="tabcontent">
    <h2>Order Products</h2>
    <?php
        while($order_book = $select_order_books->fetch(PDO::FETCH_OBJ)){
          $select_order_book = $conn->prepare("SELECT b.*,c.title FROM books b JOIN category c ON b.category_id = c.id WHERE b.id = ?");
          $select_order_book->execute([$order_book->b_id]);
          $order_book_exists = $select_order_book->rowCount();
          $fetch_order_book = $select_order_book->fetch(PDO::FETCH_OBJ);
				?>
    <div class="order_product">
     <a href="<?= $order_book_exists > 0 ? 'book.php?id='.$fetch_order_book->id : '#' ?>" class="info">
      <img alt="product-img"
       src="<?= $order_book_exists > 0 ? "image/books/".$fetch_order_book->image : "image/books/no_book.jpg" ?>">
      <div>
       <p class="fs-17 c-black"><?= $order_book_exists > 0 ? $fetch_order_book->name : "Unknown" ?></p>
       <p class="fs-17 c-grey"><?= $order_book_exists > 0 ? $fetch_order_book->title : "Unknown" ?></p>
      </div>
     </a>
     <div class="price">
      <div><?= $order_book->quantity ?> * <span><?= $order_book->o_price ?> EGP</span> </div>
      <p>Total: <span><?= $order_book->o_total ?> EGP</span></p>
     </div>
    </div>
    <?php }?>
   </div>

   <div id="order_status" class="tabcontent">
    <h2>Order Status</h2>
    <?php
     while($status = $select_order_status->fetch(PDO::FETCH_OBJ)){
				?>
    <div class="order_status">
     <p>
      The Status Of Order Changed To <span class="status <?= $status->status ?>"><?= $status->status ?></span><br>
      By <a href="profile.php?id=<?= $status->e_id ?>" class="c-main"><?= $status->name ?></a>
     </p>
     <p class="c-grey"><?= $status->status_date ?></p>
    </div>
    <?php } ?>
   </div>
  </div>
 </section>

 <!-- javasript -->
 <script type="text/javascript">
 function openTab(evt, tabName) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
   tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
   tablinks[i].className = tablinks[i].className.replace(
    " active", "");
  }
  document.getElementById(tabName).style.display = "block";
  evt.currentTarget.className += " active";
 }
 </script>
</body>

</html>