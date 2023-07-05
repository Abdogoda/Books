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
$select_permissions = $conn->prepare("SELECT * FROM job_permisions jp JOIN permissions p ON jp.permission_id = p.id JOIN jobs j ON jp.job_id = j.id WHERE jp.job_id = ?");
$select_permissions->execute([$fetch_admin->job_id]);
$employee_permissions = $select_permissions->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">

<head>
 <title>Customers</title>
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
   <!-- Start Customers Table -->
   <h1 class="p-relative">Customers</h1>
   <div class="p-20 bg-white rad-10 m-20">
    <h2 class="mt-0 mb-20">Customers</h2>
    <div class="responsive-table">
     <table class="fs-15 w-full">
      <thead>
       <tr>
        <td>ID</td>
        <td>Client</td>
        <td>Email</td>
        <td>Phone</td>
        <td>Joined</td>
       </tr>
      </thead>
      <tbody>
       <?php
        $select_customers = $conn->prepare("SELECT * FROM users WHERE group_id = 1 ORDER BY date DESC");
        $select_customers->execute([]);
        if($select_customers->rowCount() > 0){
         while($user = $select_customers->fetch(PDO::FETCH_OBJ)){
          ?>
       <tr>
        <td><a href="profile.php?id=<?= $user->id ?>">#<?= $user->id ?></a></td>
        <td><a href="profile.php?id=<?= $user->id ?>"><?= $user->name ?></a></td>
        <td><?= $user->email ?></td>
        <td><?= $user->phone ?></td>
        <td><?= $user->date ?></td>
       </tr>
       <?php }
        }else{
         echo '<p class="empty">No Customers Found!</p>';
        }
        ?>
      </tbody>
     </table>
    </div>
   </div>
   <!-- End Customers Table -->
  </div>
 </div>
 <!-- START JAVASCRIPT -->
 <script src="js/script.js"></script>
 <!-- END JAVASCRIPT -->
</body>

</html>