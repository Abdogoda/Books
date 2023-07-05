<?php
$db_name = "mysql:host=localhost;dbname=book_store";
$user_name = "root";
$user_password = "";
$conn = new PDO($db_name,$user_name,$user_password);

//generate random id
function generate_unique_id() {
  $str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
  $rand = array();
  $length = strlen($str) - 1;
  for ($i = 0; $i < 20; $i++) {
      $n = mt_rand(0, $length);
      $rand[] = $str[$n];
  }
  return implode($rand);
}