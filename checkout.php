<?php
  require 'includes/PHPMailer.php';
  require 'includes/SMTP.php';
  require 'includes/Exception.php';
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\SMTP;
  use PHPMailer\PHPMailer\Exception;
?>
<?php
include 'includes/connection.php';
$user_id = '';
if(isset($_COOKIE['user_id'])){
  $user_id = $_COOKIE['user_id'];
}else{
  header('location: login.php');
}
$user_select = $conn->prepare("SELECT * From users WHERE id = ?");
$user_select->execute([$user_id]);
if($user_select->rowCount() <= 0){
  header('location: login.php');
}
$user_row = $user_select->fetch(PDO::FETCH_OBJ);
$user_name = $user_row->name;
$user_email = $user_row->email;
$user_phone = $user_row->phone;
$cart = [];
if(isset($_COOKIE['cart']) && count(json_decode($_COOKIE['cart'])) > 0){
  $cart = json_decode($_COOKIE['cart']);
}
$liked = [];
if(isset($_COOKIE['liked']) && count(json_decode($_COOKIE['liked'])) > 0){
  $liked = json_decode($_COOKIE['liked']);
}
if(count($cart) > 0){
  $total = 0;
  foreach ($cart as $key => $book) {
    $select_book = $conn->prepare("SELECT b.*, c.title FROM books b JOIN category c ON b.category_id = c.id WHERE b.id = ? LIMIT 1");
    $select_book->execute([$book->id]);
    if($select_book->rowCount() > 0){
      $row = $select_book->fetch(PDO::FETCH_OBJ);
      $total += intval($book->qty) * $row->price; 
    }
  }
}else{
  header('location: cart.php');
}
if(isset($_POST['checkout'])){
  if(count($cart) > 0){
  $sub_total = $_POST['sub_total'];
  $shipping_tax = $_POST['shipping_tax'];
  $total_price = $_POST['total_price'];
  $city = filter_var($_POST['city'], FILTER_SANITIZE_STRING);
  $street = filter_var($_POST['street'], FILTER_SANITIZE_STRING);
  $building = filter_var($_POST['building'], FILTER_SANITIZE_STRING);
  $payment_method = filter_var($_POST['payment_method'], FILTER_SANITIZE_STRING);

  // insert order in deatabase
  $insert_order = $conn->prepare("INSERT INTO orders (u_id, sub_total, taxes, total) VALUES (?, ?, ?, ?)");
  $insert_order->execute([$user_id, $sub_total, $shipping_tax, $total_price]);
  // get order id from order table
  $select_order = $conn->prepare("SELECT * FROM orders WHERE u_id = ? ORDER BY u_id DESC LIMIT 1");
  $select_order->execute([$user_id]);
  if($select_order->rowCount() > 0){
    $fetch_order = $select_order->fetch(PDO::FETCH_OBJ);
    $order_id = $fetch_order->id;
    // insert order books in database
    foreach ($cart as $key => $book) {
      $check_book = $conn->prepare("SELECT * FROM books WHERE id = ? LIMIT 1");
      $check_book->execute([$book->id]);
      if($check_book->rowCount() > 0){
        $fetch_book = $check_book->fetch(PDO::FETCH_OBJ);
        $insert_book = $conn->prepare("INSERT INTO order_books (o_id, b_id, o_price, quantity, o_total) VALUES (?, ?, ?, ?, ?)");
        $insert_book->execute([$order_id, $fetch_book->id, $fetch_book->price, $book->qty, intval($book->qty) * $fetch_book->price]);
      }else{
        $warning_msg[] = "The Book With ID ($book->id) Is Sold Out!";
      }
    }
    // insert order status in database
    if($payment_method == 1){
      $status = "Completed";
    }else{
      $status = "Pending";
    }
    $insert_order_status = $conn->prepare("INSERT INTO order_status (o_id, e_id, status) VALUES (?, ?, ?)");
    $insert_order_status->execute([$order_id, 1, $status]);
    // insert order details in database
    $insert_order_details = $conn->prepare("INSERT INTO order_details (o_id, city, street, building, payment_method) VALUES (?, ?, ?, ?, ?)");
    $insert_order_details->execute([$order_id, $city, $street, $building, $payment_method]);
    if($payment_method == 1){
      $card_number = filter_var($_POST['card_number'], FILTER_SANITIZE_STRING);
      $card_holder = filter_var($_POST['card_holder'], FILTER_SANITIZE_STRING);
      $expiration_date = filter_var($_POST['expiration_date'], FILTER_SANITIZE_STRING);
      $card_cvv = filter_var($_POST['card_cvv'], FILTER_SANITIZE_STRING);
      $insert_order_payment = $conn->prepare("INSERT INTO order_payment (o_id, card_number, card_holder, card_cvv, expiration_date) VALUES (?, ?, ?, ?, ?)");
      $insert_order_payment->execute([$order_id, $card_number, $card_holder, $card_cvv, $expiration_date]);
    }
    // send message
    $success_msg[] ="Your Order Have been Added Successfully, You Will Be Redirect In 5s";
  }else{
    $error_msg[] = "Something went wrong, Please try again later!";
  }

  // sending email
    $bod = "
    <html>
    <body>
    <center>
        <table border='1'>
            <thead>
                <tr>
                    <th scope='col'>Name</th>
                    <th scope='col'>Price</th>
                    <th scope='col'>Quantity</th>
                    <th scope='col'>SubTotal </th>
                </tr>
            </thead>
    ";
    foreach ($cart as $key => $book) {
      $select_book = $conn->prepare("SELECT b.*, c.title FROM books b JOIN category c ON b.category_id = c.id WHERE b.id = ? LIMIT 1");
      $select_book->execute([$book->id]);
      if($select_book->rowCount() > 0){
        $row = $select_book->fetch(PDO::FETCH_OBJ);
        $b_total = $row->price * $book->qty;
        $bod .= "
            <tr>
                <td>$row->name</td>
                <td>$row->price EGP</td>
              <td>$book->qty</td>
              <td>$b_total EGP</td>
          </tr>
      ";
    }
  }
    $bod .= 
    "            </tbody>
                </table>
                <h2> Total: $total EGP</h2>
              </center>
            </body>
          </html>
    
          ";
          // 
    $ToEmail = $user_email;
    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->Host = "smtp.gmail.com";
    $mail->SMTPAuth = "true";
    $mail->SMTPSecure = "ssl";
    $mail->Port = "465";
    $mail->Username = "shopplace0@gmail.com";
    $mail->Password = "lybahnuaynsphzqn";
    $mail->Subject = "Bookly Sale Operation";
    $mail->isHTML(true);
    $mail->setFrom($ToEmail);
    $mail->Body = $bod;
    $mail->addAddress($ToEmail);
    $mail->SMTPOptions=array('ssl'=>array(
      'verify_pear'=>false,
      'verify_pear_name'=>false,
      'allow_self_signed'=>false
    ));
    if($mail->Send()){
      $success_msg[] = "Check Your Email For More Details!";
    }else{
      $warning_msg[] = "Email Not Send Due To Traffic!";
    }
    $mail->smtpClose();
    setcookie('cart', '', time() - 1, '/'); // Reset cart cookie
    
    header("refresh: 5, url = order.php?id=".$order_id);
  }else{
    $warning_msg[] = "Your Cart Is Empty!";
    header("refresh: 5, url = checkout.php");
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta http-equiv="X-UA-Compatible" content="IE=edge">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Bookly | Checkout</title>
 <link rel="shortcut icon" href="image/icon.svg" type="image/x-icon">
 <link rel="stylesheet" href="css/alert.css">
 <link rel="stylesheet" href="css/all.min.css">
 <link rel="stylesheet" href="css/style.css">
 <link rel="stylesheet" href="css/cart.css">
 <script src="js/alert.js"></script>
 <style>
 @media print {

  .header,
  .heading,
  .btn {
   visibility: hidden;
  }
 }
 </style>
</head>

<body>
 <!-- header section  -->
 <header class="header">
  <?php  include 'includes/header1.php'; ?>
  <ul class="notifications"></ul>
  <?php include 'includes/alert.php'; ?>
 </header>

 <!-- checkout section starts  -->
 <section class="cart" id="cart">
  <h1 class="heading"> <span>Checkout Page</span> </h1>
  <form action="" method="POST" class="cart-wrapper cart-layout" id="checkout_form">
   <div class="cart-books billing-details">
    <h3>
     Billing Details <i class="fas fa-truck-fast"></i>
    </h3>
    <div class="input-flex-boxes">
     <div class="input-box">
      <label for="name">Your Name</label> <input type="text" readonly id="name" name="name" value="<?= $user_name ?>"
       required>
     </div>
     <div class="input-box">
      <label for="phone">Your Phone</label> <input type="text" readonly id="phone" name="phone"
       value="<?= $user_phone ?>" required>
     </div>
    </div>
    <div class="input-flex-boxes">
     <div class="input-box ">
      <label for="city">City</label> <input type="text" name="city" id="city" required>
     </div>
     <div class="input-box ">
      <label for="street">Street</label> <input type="text" id="street" name="street" required>
     </div>
     <div class="input-box ">
      <label for="building">Building</label> <input type="text" name="building" id="building" required>
     </div>
    </div>
    <div class=" payment-boxes">
     <label for="name">Payment Method</label>
     <div class="input-flex-boxes">
      <div class="input-box">
       <input type="radio" name="payment_method" id="cash_on_delivery" value="0" required checked> <label
        for="cash_on_delivery">Cash On Delivery</label>
      </div>
      <div class="input-box">
       <input type="radio" name="payment_method" id="pay_with_credit_card" value="1" required>
       <label for="pay_with_credit_card">Pay With Credit Card</label>
      </div>
     </div>
    </div>
    <div class="visa_c" id="visa_c"></div>
    <div class="input-box">
     <input type="hidden" name="sub_total" value="<?= $total?>">
     <input type="hidden" name="shipping_tax" value="<?= $total * 0.1 ?>">
     <input type="hidden" name="total_price" value="<?= $total * 1.1 ?>">
     <button type="submit" name="checkout" class="btn">Order Now</button>
    </div>
   </div>
   <?php
    if($total > 1){ ?>
   <div class="cart-checkout">
    <h3>order summary</h3>
    <p><b>subtotal: </b><?= $total?> EGP</p>
    <p><b>shipping: </b> <?= $total * 0.1 ?> EGP</p>
    <hr>
    <p class="total"><b>total: <span><?= $total * 1.1 ?> EGP</span></b></p>
    <div class="btns">
     <a href="cart.php" class="btn">back to cart</a>
    </div>
   </div>
   <?php } ?>
  </form>
 </section>

 <!--  -->
 <script type="text/javascript" src="js/visa.js"></script>
</body>

</html>