<?php
@include 'includes/config.php';
session_start();
$user_id = $_SESSION['user_id'];
if(!isset($user_id)){
  header("location:login.php");
}else if(isset($_GET['book_id'])){
  $book_id = $_GET['book_id'];
  $origin_book = mysqli_query($conn,"SELECT * FROM books WHERE id = '$book_id'") or die("NO ITEM FOUND TO BE ADDED!?");
  if(mysqli_num_rows($origin_book)>0){
    $origin_book_row = mysqli_fetch_assoc($origin_book);
      $book_name = $origin_book_row['name'];
      $book_image = $origin_book_row['image'];
      $book_price = $origin_book_row['price'];
      $book_category = $origin_book_row['category'];
      $book_description = $origin_book_row['description'];
    $cart_book = mysqli_query($conn,"SELECT * FROM cart WHERE name = '$book_name'") or die("ERROR IN CART TABLE!?");
    if(mysqli_num_rows($cart_book)>0){
      $cart_book_row = mysqli_fetch_assoc($cart_book);
      echo $cart_book_row['quantity'];
    $cart_book_id = $cart_book_row['id'];
    $cart_book_quantity = (int)$cart_book_row['quantity'] + 1;
    echo $cart_book_quantity;
    echo "book found";
    $cart_book_price = $cart_book_row['price'];
    $cart_book_total = $cart_book_quantity * $cart_book_price;
    mysqli_query($conn , "UPDATE cart SET quantity = '$cart_book_quantity', total = '$cart_book_total' WHERE id = '$cart_book_id'") or die("ERROR IN INCREMENTING!?");
    $message="Product has been incremented to cart successfully!";
  }else{
      echo "book new to found";
      $book_quantity = 1;
      $book_total =  $book_price;
      mysqli_query($conn,"INSERT INTO cart (user_id,name,image,price,quantity,total,category,description) VALUES ('$user_id','$book_name','$book_image','$book_price','$book_quantity', '$book_total','$book_category','$book_description')") or die("ERROR IN ADDING!?");
    }
    $message="Product has been added to cart successfully!";
    header("location:book_details.php?book_name=$book_name");
  }
}