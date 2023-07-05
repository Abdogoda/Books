<?php
include '../includes/connection.php';
$admin_id = '';
if(isset($_COOKIE['admin_id'])){
  $admin_id = $_COOKIE['admin_id'];
}else{
  header('location: ../login.php');
}
// Save Idea
if(isset($_POST['save_idea'])){
  $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
  $content = filter_var($_POST['content'], FILTER_SANITIZE_STRING);
  $check_idea = $conn->prepare("SELECT * FROM ideas WHERE e_id = ? AND title = ? AND content = ?");
  $check_idea->execute([$admin_id, $title, $content]);
  if($check_idea->rowCount() <= 0){
    $insert_idea = $conn->prepare("INSERT INTO ideas (e_id, title, content) VALUES (?, ?, ?)");
    $insert_idea->execute([$admin_id, $title, $content]);
    $success_msg[] = "Idea Saved Successfully!";
  }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
 <title>Plans</title>
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
   <h1 class="p-relative">Plans</h1>
   <div class="quick-draft p-20 m-20 mb-20 bg-white rad-10">
    <h2 class="mt-0 mb-10">Add A New Plan</h2>
    <form method="POST">
     <input class="d-block mb-20 w-full p-10 b-none bg-eee rad-6" type="text" name="title" placeholder="Title"
      required />
     <textarea class="d-block mb-20 w-full p-10 b-none bg-eee rad-6" name="content" placeholder="Your Thought"
      required></textarea>
     <input class="save d-block fs-14 bg-main c-white b-none w-fit btn-shape" name="save_idea" type="submit"
      value="Add" />
    </form>
   </div>
   <div class="reminders p-20 m-20 mb-20 bg-white rad-10">
    <h2 class="mt-0 mb-10">Your Plans</h2>
    <ul class="m-0">
     <?php
      $select_remiders = $conn->prepare("SELECT * FROM ideas WHERE e_id = ? ORDER BY id DESC LIMIT 5");
      $select_remiders->execute([$admin_id]);
      if($select_remiders->rowCount() > 0){
        while($row = $select_remiders->fetch(PDO::FETCH_OBJ)){?>
     <li class="d-flex align-center mt-15 ">
      <span class="key mr-15 d-block rad-half"></span>
      <div class="pl-15 ">
       <p class="fs-15 txt-upper  fw-bold mt-0 mb-5"><?= $row->title ?></p>
       <p class="c-dark txt-capitalize  fs-15 "><?= $row->content ?></p>
       <span class="fs-13 c-grey"><?= $row->date ?></span>
      </div>
     </li>
     <?php  } }else{ echo "<p class='empty'>There Is No Plans For You Yet</p>"; } ?>
    </ul>
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