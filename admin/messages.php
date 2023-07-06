<?php
include '../includes/connection.php';
$admin_id = '';
if(isset($_COOKIE['admin_id'])){
  $admin_id = $_COOKIE['admin_id'];
}else{
  header('location: ../login.php');
}
// admin information and permissions
$select_admin = $conn->prepare("SELECT * FROM users u JOIN employees e ON u.id = e.employee_id WHERE u.id = ?");
$select_admin->execute([$admin_id]);
$fetch_admin = $select_admin->fetch(PDO::FETCH_OBJ);
$select_employee_permissions = $conn->prepare("SELECT * FROM job_permisions WHERE job_id = ?");
$select_employee_permissions->execute([$fetch_admin->job_id]);
$employee_permissions = $select_employee_permissions->fetchAll(PDO::FETCH_OBJ);
if (!in_array('6', array_column($employee_permissions, 'permission_id'))) header("location: index.php");

?>
<!DOCTYPE html>
<html lang="en">

<head>
 <title>Messages</title>
 <?php include 'includes/pageHead.php'; ?>
</head>

<body>
 <div class="page d-flex">

  <!-- START SIDEBAR SECTION -->
  <?php include 'includes/sidebar.php';?>
  <!-- END SIDEBAR SECTION -->

  <!-- Start Includes -->
  <?php include 'includes/head.php'; ?>
  <ul class="notifications"></ul>
  <?php include 'includes/alert.php'; ?>
  <!-- End Includes -->

  <!-- START CONTENT -->
  <div class="content w-full">
   <h1 class="p-relative">Messages</h1>
   <div class="latest-post p-20 m-20 bg-white rad-10">
    <h2 class="m-0 mb-20">Your Messages</h2>
    <?php 
      $select_messages = $conn->prepare("SELECT * FROM messages ORDER BY id DESC");
      $select_messages->execute([]);
      if($select_messages->rowCount() > 0){
      while($message = $select_messages->fetch(PDO::FETCH_OBJ)){
     ?>
    <div class="top d-flex align-center">
     <img class="avatar mr-15" src="imgs/avatar.png" alt="avatar-image" />
     <div class="info w-full between-flex f-wrap">
      <div>
       <span class="d-block mb-5 fw-bold"><?= $message->name ?></span>
       <a href="mailto:<?= $message->email ?>" class="c-grey"><?= $message->email ?></a>
      </div>
      <span class="c-grey"><?= $message->date ?></span>
     </div>
    </div>
    <div class="post-content txt-c-mobile pt-20 mt-20 mb-20" style="border-top: none;"><?= $message->content ?>
    </div>
    <?php }}else{ echo "<p class='empty'>There Is No Message Found For You!</p>"; }?>
   </div>
  </div>
 </div>
 <!-- START JAVASCRIPT -->
 <script src="js/script.js"></script>
 <script src="js/modal.js"></script>
 <script src="js/validation.js"></script>
 <!-- END JAVASCRIPT -->
</body>

</html>