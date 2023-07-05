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
// select order details
$select_order = $conn->prepare("SELECT * FROM orders o JOIN order_details od ON o.id = od.o_id WHERE id = ?");
$select_order->execute([$order_id]);
if(!$select_order->rowCount() > 0){
 header("location: orders.php");
}
$order = $select_order->fetch(PDO::FETCH_OBJ);
// select order user
$select_order_user = $conn->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
$select_order_user->execute([$order->u_id]);
$order_user = $select_order_user->fetch(PDO::FETCH_OBJ);
// select order status
$select_order_status = $conn->prepare("SELECT os.*, u.name FROM order_status os JOIN users u ON os.e_id = u.id WHERE o_id = ? ORDER BY s_id DESC");
$select_order_status->execute([$order_id]);
// select order last status
$select_order_last_status = $conn->prepare("SELECT * FROM order_status WHERE o_id = ? ORDER BY s_id DESC LIMIT 1");
$select_order_last_status->execute([$order_id]);
$last_status = $select_order_last_status->fetch(PDO::FETCH_OBJ);

?>

<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Invoice (<?= $order_id ?>)</title>
 <link rel="stylesheet" href="css/invoice.css">
 <link rel="shortcut icon" href="image/icon.svg" type="image/x-icon">
</head>

<body>
 <section>
  <div class="invoice" id="print-section">
   <div class="invoice_info">
    <div class="i_row">
     <div class="i_logo">
      <h1>Bookly Shop</h1>
     </div>
     <div class="title">
      <h1>INVOICE</h1>
     </div>
    </div>
    <div class="i_row">
     <div class="i_to">
      <div class="main_title">
       <p>Invoice To</p>
      </div>
      <div class="p_title">
       <p><?= $order_user->name ?></p>
       <span><?= $order_user->email ?></span>
      </div>
      <div class="p_title">
       <p><?= $order->building ?> <?= $order->street ?></p>
       <p><?= $order->city ?></p>
      </div>
     </div>
     <div class="i_details text_right">
      <div class="main_title">
       <p>Invoice details</p>
      </div>
      <div class="p_title">
       <p>Invoice No:</p>
       <span><?= $order->id ?></span>
      </div>
      <div class="p_title">
       <p>Invoice Date:</p>
       <span><?= $order->date ?></span>
      </div>
     </div>
    </div>
    <div class="i_row">
     <div class="i_payment">
      <div class="main_title">
       <p>Payment Method</p>
      </div>
      <div class="p_title">
       <p><?= $order->payment_method == 0 ? "Cashe On Delievery" : "Credit Card" ?></p>
      </div>
     </div>
     <div class="i_duetotal text_right">
      <div class="main_title">
       <p>Total Due</p>
      </div>
      <div class="p_title">
       <p>Amout In EGP:</p>
       <span><?= $order->total ?> EGP</span>
      </div>
     </div>
    </div>
   </div>
   <div class="invoice_table">
    <div class="i_table">
     <div class="i_table_head">
      <div class="i_row">
       <div class="i_col w_55">
        <p class="p_title">DESCRIPTION</p>
       </div>
       <div class="i_col w_15 text_center">
        <p class="p_title">QTY</p>
       </div>
       <div class="i_col w_15 text_center">
        <p class="p_title">PRICE</p>
       </div>
       <div class="i_col w_15 text_right">
        <p class="p_title">TOTAL</p>
       </div>
      </div>
     </div>
     <div class="i_table_body">
      <?php 
      $select_order_books = $conn->prepare("SELECT * FROM order_books WHERE o_id = ?");
      $select_order_books->execute([$order_id]);
      if($select_order_books->rowCount() > 0){
       while ($order_book = $select_order_books->fetch(PDO::FETCH_OBJ)){
        $book_name = "Unknown";
        $book_category = "Unknown";
        $select_book = $conn->prepare("SELECT b.*, c.title FROM books b JOIN category c ON b.category_id = c.id WHERE b.id = ? LIMIT 1");
        $select_book->execute([$order_book->b_id]);
        if($select_book->rowCount() > 0){
         $fetch_book = $select_book->fetch(PDO::FETCH_OBJ);
         $book_name = $fetch_book->name;
         $book_category = $fetch_book->title;
        }
       ?>
      <div class="i_row">
       <div class="i_col w_55">
        <p> <?= $book_name ?> </p>
        <span> <?= $book_category ?> </span>
       </div>
       <div class="i_col w_15 text_center">
        <p><?= $order_book->quantity ?></p>
       </div>
       <div class="i_col w_15 text_center">
        <p><?= $order_book->o_price ?> EGP</p>
       </div>
       <div class="i_col w_15 text_right">
        <p><?= $order_book->o_total ?> EGP</p>
       </div>
      </div>
      <?php }}?>
     </div>
     <div class="i_table_foot">
      <div class="i_row">
       <div class="i_col w_50">
        <p>Sub Total</p>
        <p>Tax 10%</p>
       </div>
       <div class="i_col w_50 text_right">
        <p><?= $order->sub_total ?> EGP</p>
        <p><?= $order->taxes ?> EGP</p>
       </div>
      </div>
      <div class="i_row grand_total_wrap">
       <div class="i_col w_50">
        <p>GRAND TOTAL:</p>
       </div>
       <div class="i_col w_50 text_right">
        <p><?= $order->total ?> EGP</p>
       </div>
      </div>
     </div>
    </div>

   </div>
   <div class="invoice_terms">
    <div class="main_title">
     <p>terms and Conditions</p>
    </div>
    <p>Please note that all purchases made on our online bookstore are subject to the terms and conditions outlined in
     the invoice.</p>
   </div>
  </div>
 </section>

 <script>
 window.onload = function() {
  var printSection = document.getElementById("print-section");
  var originalContent = document.body.innerHTML;
  document.body.innerHTML = printSection.innerHTML;
  window.print();
  document.body.innerHTML = originalContent;
 };
 </script>
</body>

</html>