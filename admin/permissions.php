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
if (!in_array('10', array_column($employee_permissions, 'permission_id'))) header("location: index.php");



// Add Permission Function
if(isset($_POST['add_permission'])){
  $add_name = filter_var($_POST['add_name'], FILTER_SANITIZE_STRING);
  $select = $conn->prepare("SELECT * FROM permissions WHERE permission = ? LIMIT 1");
  $select->execute([$add_name]);
  if($select->rowCount() > 0){
    $warning_msg[] = 'Permission Title Already Exists!';
  }else{
    $insert = $conn->prepare("INSERT INTO permissions (permission) VALUES (?)");
    $insert->execute([$add_name]);
    $success_msg[] = 'You Have Added New Permission Successfully';
  }
}
// Edit Permission Function
if(isset($_POST['edit_permission'])){
  $edit_id = $_POST['edit_id'];
  $edit_name = $_POST['edit_name'];
  $o_select = $conn->prepare("SELECT * FROM permissions WHERE id = ? LIMIT 1");
  $o_select->execute([$edit_id]);
  if($o_select->rowCount() > 0){
   $c_select = $conn->prepare("SELECT * FROM permissions WHERE permission = ? LIMIT 1");
   $c_select->execute([$edit_name]);
   if($c_select->rowCount() > 0){
    $warning_msg[] = 'Permission Title Already Exists!';
   }else{
    $update_permission = $conn->prepare("UPDATE permissions SET permission = ? WHERE id = ?");
    $update_permission->execute([$edit_name, $edit_id]);
    $success_msg[] = 'Permission Title Updated Successfully!';
   }
  }else{
   $warning_msg[] = 'Permission Not Exists!';
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
 <title>Permissions</title>
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
   <h1 class="p-relative">Permissions</h1>
   <!-- start category section -->
   <div class="p-relative p-20 m-20 mb-20 bg-white rad-10">
    <div class="between-flex f-wrap gap-10">
     <h2 class="mt-0 mb-20">All Permissions</h2>
     <?php if (in_array('10', array_column($employee_permissions, 'permission_id'))) { ?>
     <button class="btn-shape bg-main c-white fs-20 mt-0 mb-20 open-modal" data-modal="addPermissionModal">Add
      Permission <i class="fa fa-plus"></i></button>
     <?php }?>
    </div>
    <div class="mt-10 d-flex f-wrap gap-10">
     <?php
      $select_all_permissions = $conn->prepare("SELECT * FROM  permissions ORDER BY id DESC");
      $select_all_permissions->execute([]);
      if($select_all_permissions->rowCount() > 0){
       while($row = $select_all_permissions->fetch(PDO::FETCH_OBJ)){?>
     <div class="permission-box d-flex align-center bg-eee p-10 rad-6 fs-13">
      <span><?= $row->permission ?></span>
      <button class="open-modal" data-modal="editPermissionModal" data-title="<?= $row->permission ?>"
       data-id="<?= $row->id ?>"><i class="fa fa-edit c-green"></i></button>
     </div>
     <?php }
      }else{
       echo '<p class="empty">No Permissions Found!</p>';
      }
     ?>
    </div>
   </div>
   <!-- end Permission section -->
  </div>
  <!-- END CONTENT -->
 </div>


 <!-- ################### START MODAL ################### -->
 <!-- Add permission Modal -->
 <?php if (in_array('10', array_column($employee_permissions, 'permission_id'))) { ?>
 <div id="addPermissionModal" class="modal">
  <div class="modal-content">
   <h2>Add New Permission</h2>
   <form id="addpermissionForm" action="" method="POST">
    <div class="input-group">
     <label for="add_name">Permission Title</label> <input type="text" name="add_name" id="add_name" required />
    </div>
    <div class="btns">
     <input type="submit" class="btn" name="add_permission" value="Add Permission" />
     <button type="button" class="btn btn-danger close-modal">Cancel</button>
    </div>
   </form>
  </div>
 </div>
 <?php }?>
 <!-- Edit Book Modal -->
 <?php if (in_array('10', array_column($employee_permissions, 'permission_id'))) { ?>
 <div id="editPermissionModal" class="modal">
  <div class="modal-content">
   <h2>Edit Permission Title</h2>
   <form id="editPermissionForm" action="" method="POST">
    <input type="hidden" name="edit_id" id="edit_id" value="" required>
    <div class="input-group">
     <label for="edit_name">Permission Name</label> <input type="text" name="edit_name" id="edit_name" required
      value="" />
    </div>
    <div class="btns">
     <button type="submit" class="btn" name="edit_permission">Edit Permission</button>
     <button type="button" class="btn btn-danger close-modal">Cancel</button>
    </div>
   </form>
  </div>
 </div>
 <?php }?>
 <!-- ################### END MODAL ###################  -->


 <!-- START JAVASCRIPT -->
 <script src="js/script.js"></script>
 <script src="js/modal.js"></script>
 <script src="js/validation.js"></script>
 <!-- END JAVASCRIPT -->

</body>

</html>