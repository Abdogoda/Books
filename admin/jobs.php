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



// Add Job Function
if(isset($_POST['add_job'])){
  $add_name = filter_var($_POST['add_name'], FILTER_SANITIZE_STRING);
  // check if job is already existing
  $select = $conn->prepare("SELECT * FROM jobs WHERE job = ? LIMIT 1");
  $select->execute([$add_name]);
  if($select->rowCount() > 0){
   $warning_msg[] = 'Job Title Already Exists!';
  }else{
   // add new job title
   $insert = $conn->prepare("INSERT INTO jobs (job) VALUES (?)");
   $insert->execute([$add_name]);
   // select job id
   $select_id = $conn->prepare("SELECT * FROM jobs WHERE job = ? LIMIT 1");
   $select_id->execute([$add_name]);
   $fetch_id = $select_id->fetch(PDO::FETCH_OBJ);
   // insert job permissions
   if (isset($_POST['permission'])) {
    $selectedCheckboxes = $_POST['permission'];
   }
   foreach ($selectedCheckboxes as $checkboxValue) {
    $insert_per = $conn->prepare("INSERT INTO job_permisions (job_id, permission_id) VALUES (?, ?)");
    $insert_per->execute([$fetch_id->id, $checkboxValue]);
  }
   $success_msg[] = 'You Have Added New Job Successfully';
  }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
 <title>Jobs</title>
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
   <h1 class="p-relative">Jobs</h1>
   <!-- start category section -->
   <div class="p-relative p-20 m-20 mb-20 bg-white rad-10">
    <div class="between-flex f-wrap gap-10">
     <h2 class="mt-0 mb-20">All Jobs</h2>
     <?php if (in_array('10', array_column($employee_permissions, 'permission_id'))) { ?>
     <button class="btn-shape bg-main c-white fs-20 mt-0 mb-20 open-modal" data-modal="addJobModal">Add
      Job <i class="fa fa-plus"></i></button>
     <?php }?>
    </div>
    <div class="mt-10">
     <?php
      $select_all_jobs = $conn->prepare("SELECT * FROM  jobs");
      $select_all_jobs->execute([]);
      if($select_all_jobs->rowCount() > 0){
       while($row = $select_all_jobs->fetch(PDO::FETCH_OBJ)){
        $select_job_perm = $conn->prepare("SELECT * FROM job_permisions WHERE job_id = ?");
        $select_job_perm->execute([$row->id]);
        $no_of_job_permissions = $select_job_perm->rowCount();
        ?>
     <div class="job-box rad-6 between-flex align-center mb-10 p-10 fs-13">
      <div>
       <p class="fs-20"><?= $row->job ?></p>
       <p class="c-grey fs-15"><?= $no_of_job_permissions ?> Permission</p>
      </div>
      <a class="open-modal btn-shape bg-green c-white" href="job.php?id=<?= $row->id ?>">Show
       Job <i class="fa fa-eye"></i></a>
     </div>
     <?php }
      }else{
       echo '<p class="empty">No Jobs Found!</p>';
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
 <div id="addJobModal" class="modal">
  <div class="modal-content">
   <h2>Add New Job</h2>
   <form id="addJobForm" action="" method="POST">
    <div class="input-group">
     <label for="add_name">Job Title</label> <input type="text" name="add_name" id="add_name" required />
    </div>
    <div class="input-group">
     <label for="">Job Permissions</label>
     <div class="d-flex mb-20 gap-10 f-wrap">
      <?php 
      $select_all_permissions = $conn->prepare("SELECT * FROM  permissions ORDER BY id DESC");
      $select_all_permissions->execute([]);
      if($select_all_permissions->rowCount() > 0){
       while($row = $select_all_permissions->fetch(PDO::FETCH_OBJ)){
     ?>
      <div class="permission-box rad-6 fs-13">
       <input type="checkbox" id="permission_<?= $row->id ?>" value="<?= $row->id ?>" name="permission[]">
       <label for="permission_<?= $row->id ?>" class="bg-eee rad-6"><?= $row->permission ?></label>
      </div>
      <?php }} ?>
     </div>
    </div>
    <div class="btns">
     <input type="submit" class="btn" name="add_job" value="Add Job" />
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