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


// select job information
$job_id = '';
if(isset($_GET['id'])){
  $job_id = $_GET['id'];
}else{
  header('location: jobs.php');
 }
$select_job = $conn->prepare("SELECT * FROM jobs WHERE id = ?");
$select_job->execute([$job_id]);
if(!$select_job->rowCount() > 0){
 header('location: jobs.php');
}
$job = $select_job->fetch(PDO::FETCH_OBJ);



// Edit Job Function
if(isset($_POST['edit_job'])){
 $edit_name = filter_var($_POST['edit_name'], FILTER_SANITIZE_STRING);
  // check if job is already existing
  $select = $conn->prepare("SELECT * FROM jobs WHERE id = ? LIMIT 1");
  $select->execute([$job->id]);
  if($select->rowCount() > 0){
   // add new job title
   if($edit_name != $job->job){
    $update = $conn->prepare("UPDATE jobs SET job = ? WHERE id = ?");
    $update->execute([$edit_name, $job->id]);
    $success_msg[] = 'Job Title Have Been Updated Successfully';
   }
   // update job permissions
   if (isset($_POST['permission']) && count($_POST['permission']) > 0) {
    $selectedCheckboxes = $_POST['permission'];
    $delete_permissoins = $conn->prepare("DELETE FROM job_permisions WHERE job_id = ?");
    $delete_permissoins->execute([$job->id]);
    foreach ($selectedCheckboxes as $checkboxValue) {
     $insert_per = $conn->prepare("INSERT INTO job_permisions (job_id, permission_id) VALUES (?, ?)");
     $insert_per->execute([$job->id, $checkboxValue]);
    }
    $success_msg[] = 'Job Permissions Have Been Updated Successfully';
   }
  }else{
   $warning_msg[] = 'Job Title Not Exists!';
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
 <title>Job Details</title>
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
   <h1 class="p-relative">Job Details</h1>
   <!-- start category section -->
   <div class="p-relative p-20 m-20 mb-20 bg-white rad-10">
    <div class="between-flex f-wrap gap-10">
     <h2 class="mt-0 mb-20"><?= $job->job ?></h2>
     <?php if (in_array('10', array_column($employee_permissions, 'permission_id'))) { ?>
     <button class="btn-shape bg-main c-white fs-20 mt-0 mb-20 open-modal" data-modal="editJobModal">Edit
      Job <i class="fa fa-edit"></i></button>
     <?php }?>
    </div>
    <p class="c-grey fs-20"><?= $job->job ?> Permissions</p>
    <div class="mt-10 d-flex f-wrap gap-10">
     <?php
      $select_all_permissions = $conn->prepare("SELECT * FROM  permissions p JOIN job_permisions jb ON p.id = jb.permission_id WHERE jb.job_id = ? ORDER BY id DESC");
      $select_all_permissions->execute([$job->id]);
      if($select_all_permissions->rowCount() > 0){
       while($row = $select_all_permissions->fetch(PDO::FETCH_OBJ)){?>
     <div class="permission-box bg-eee p-10 rad-6 fs-13">
      <span><?= $row->permission ?></span>
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
 <!-- Edit permission Modal -->
 <?php if (in_array('10', array_column($employee_permissions, 'permission_id'))) { ?>
 <div id="editJobModal" class="modal">
  <div class="modal-content">
   <h2>Edit Job</h2>
   <form id="editJobForm" action="" method="POST">
    <div class="input-group">
     <label for="edit_name">Job Title</label> <input type="text" name="edit_name" id="edit_name" required
      value="<?= $job->job ?>" />
    </div>
    <div class="input-group">
     <label for="">Job Permissions</label>
     <div class="d-flex mb-20 gap-10 f-wrap">
      <?php 
      $select_all_permissions = $conn->prepare("SELECT * FROM  permissions ORDER BY id DESC");
      $select_all_permissions->execute([]);
      if($select_all_permissions->rowCount() > 0){
       while($row = $select_all_permissions->fetch(PDO::FETCH_OBJ)){
        $c_perm = $conn->prepare("SELECT * FROM job_permisions WHERE job_id = ? AND permission_id = ? LIMIT 1");
        $c_perm->execute([$job->id, $row->id]);
        if($c_perm->rowCount() > 0) {
         $isChecked = true;
        }else{
         $isChecked = false;
        }
     ?>
      <div class="permission-box rad-6 fs-13">
       <input type="checkbox" id="permission_<?= $row->id ?>" value="<?= $row->id ?>" name="permission[]"
        <?= $isChecked ? "checked" : "" ?>>
       <label for="permission_<?= $row->id ?>" class="bg-eee rad-6"><?= $row->permission ?></label>
      </div>
      <?php }} ?>
     </div>
    </div>
    <div class="btns">
     <input type="submit" class="btn" name="edit_job" value="Edit Job" />
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