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
 <title>Orders</title>
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
   <!-- Start Orders Table -->
   <h1 class="p-relative">Orders</h1>
   <div class="p-20 bg-white rad-10 m-20">
    <h2 class="mt-0 mb-20">All Orders</h2>
    <div class="responsive-table">
     <table class="fs-15 w-full">
      <thead>
       <tr>
        <td>ID</td>
        <td>Client</td>
        <td>Status</td>
        <td>Total</td>
        <td>Payment Method</td>
        <td>Date</td>
       </tr>
      </thead>
      <tbody>
       <?php 
         $orders = $conn->prepare("SELECT o.*, u.name, od.payment_method FROM orders o JOIN users u ON o.u_id = u.id JOIN order_details od ON o.id = od.o_id ORDER BY o.id DESC");
         $orders->execute([]);
         if($orders->rowCount() > 0){ 
          while($order = $orders->fetch(PDO::FETCH_OBJ)){
            $select_last_status = $conn->prepare("SELECT * FROM order_status WHERE o_id = ? ORDER BY s_id DESC LIMIT 1");
            $select_last_status->execute([$order->id]);
            $last_status = $select_last_status->fetch(PDO::FETCH_OBJ);
        ?>
       <tr>
        <td><a href="order.php?id=<?= $order->id ?>">#<?= $order->id ?></a></td>
        <td><a href="profile.php?id=<?= $order->u_id ?>"><?= $order->name ?></a></td>
        <td><span class="label btn-shape <?= $last_status->status ?> c-white"><?= $last_status->status ?></span></td>
        <td><?= $order->total ?> EGP</td>
        <td><?= $order->payment_method == 0 ? "Cash On Delivery" : "Credit Card"  ?></td>
        <td><?= $order->date ?></td>
       </tr>
       <?php }
        }else{
         echo '<tr><td colspan="6" class="txt-c">No Orders Found!</td></tr>';
        }
        ?>
      </tbody>
     </table>
    </div>
   </div>
   <!-- End Orders Table -->
  </div>
 </div>
 <!-- START JAVASCRIPT -->
 <script src="js/script.js"></script>
 <!-- END JAVASCRIPT -->
</body>

</html>