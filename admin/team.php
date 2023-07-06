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
if (!in_array('3', array_column($employee_permissions, 'permission_id'))) header("location: index.php");



// Add New Member
if (isset($_POST['add_member'])) {
 $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
 $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
 $phone = filter_var($_POST['phone'], FILTER_SANITIZE_STRING);
 $address = filter_var($_POST['address'], FILTER_SANITIZE_STRING);
 $gender = filter_var($_POST['gender'], FILTER_SANITIZE_STRING);
 $job = filter_var($_POST['job'], FILTER_SANITIZE_STRING);
 $ssn = $_POST['ssn'];
 $birth = $_POST['birth'];
 $password = sha1($_POST['password']);
 $password = filter_var($password, FILTER_SANITIZE_STRING);
 $group_id = 0;
 $image= $_FILES['image']['name'];
 $ext = pathinfo($image, PATHINFO_EXTENSION);
 $rename = generate_unique_id().'.'.$ext;
 $image_tmp_name = $_FILES['image']['tmp_name'];
 $image_tmp_folder = '../image/user_images/'.$rename;

 $check_ssn = $conn->prepare("SELECT * FROM employees WHERE ssn = ?");
 $check_ssn->execute([$ssn]);
 if($check_ssn->rowCount() > 0){
  $warning_msg[] = 'SSN Already Taken!';
 }else{
  $check_email = $conn->prepare("SELECT * FROM users WHERE email = ? OR phone = ?");
  $check_email->execute([$email, $phone]);
  if($check_email->rowCount() > 0){
    $warning_msg[] = 'Email Or Phone Already Taken!';
  }else{
    $insert = $conn->prepare("INSERT INTO users (name, email, phone, address, password, image, group_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $insert->execute([$name, $email, $phone, $address, $password, $rename, $group_id]);
    move_uploaded_file($image_tmp_name,$image_tmp_folder);

    $select_last_user = $conn->prepare("SELECT * FROM users WHERE email = ? AND phone = ? AND password = ? LIMIT 1");
    $select_last_user->execute([$email, $phone, $password]);
    if($select_last_user->rowCount() > 0){
     $fetch_last_user = $select_last_user->fetch(PDO::FETCH_OBJ);
     $insert2 = $conn->prepare("INSERT INTO employees (ssn, employee_id, job_id, birth_of_date, gender) VALUES (?, ?, ?, ?, ?)");
     $insert2->execute([$ssn, $fetch_last_user->id, $job, $birth, $gender]);
     $success_msg[] = 'You Have Added New Member Successfully';
    }else{
     $warning_msg[] = 'Some Thing Went Wrong!';
    }
  }
 }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
 <title>Books</title>
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
   <h1 class="p-relative">Team</h1>
   <div class="m-20 mt-0 mb-20 p-20 rad-10 bg-white between-flex gap-10 f-wrap">
    <div class="d-flex gap-10  f-wrap">
     <?php if (in_array('10', array_column($employee_permissions, 'permission_id'))) { ?>
     <a href="jobs.php" class="btn-shape bg-main c-white fs-20">View Jobs</a>
     <?php } ?>
     <?php if (in_array('10', array_column($employee_permissions, 'permission_id'))) { ?>
     <a href="permissions.php" class="btn-shape bg-main c-white fs-20">View Permissions</a>
     <?php } ?>
    </div>
    <?php if (in_array('10', array_column($employee_permissions, 'permission_id'))) { ?>
    <button class="btn-shape bg-main c-white fs-20 open-modal" data-modal="addMemberModal">Add Employee <i
      class="fa fa-plus"></i>
    </button>
    <?php } ?>
   </div>

   <div class="friends-page d-grid m-20 mb-20 gap-20">
    <?php
    $select_team = $conn->prepare("SELECT u.*, e.*, j.job FROM users u JOIN employees e ON u.id = e.employee_id JOIN jobs j ON e.job_id = j.id WHERE u.group_id = ? AND u.id != ?");
    $select_team->execute(["0", $admin_id]);
    if($select_team->rowCount() > 0){
     while($user = $select_team->fetch(PDO::FETCH_OBJ)){
      $user_books = $conn->prepare("SELECT * FROM books WHERE author = ?");
      $user_books->execute([$user->id]);
      $no_of_books = $user_books->rowCount();
      $user_permissions = $conn->prepare("SELECT * FROM job_permisions WHERE job_id = ?");
      $user_permissions->execute([$user->job_id]);
      $no_of_permissions = $user_permissions->rowCount();
      ?>
    <div class="friend bg-white rad-6 p-20 p-relative">
     <div class="contact">
      <a href="tel:+<?= $user->phone ?>"><i class="fa-solid fa-phone"></i></a>
      <a href="mailto:<?= $user->email ?>"><i class="fa-regular fa-envelope"></i></a>
     </div>
     <div class="txt-c">
      <img class="rad-half mt-10 mb-10 w-100 h-100" src="../image/user_images/<?= $user->image ?>" alt="user_image" />
      <h4 class="m-0"><?= $user->name ?></h4>
      <p class="c-grey fs-13 mt-5 mb-0"><?= $user->job ?></p>
     </div>
     <div class="icons fs-14 p-relative">
      <div class="mb-10">
       <i class="fa-solid fa-code-commit fa-fw"></i>
       <span><?= $no_of_permissions ?> Permission</span>
      </div>
      <?php if($user->job_id == "3") {?>
      <div class="mb-10">
       <i class="fa-solid fa-book fa-fw"></i>
       <span><?= $no_of_books ?> Books</span>
      </div>
      <?php } else{?>
      <div class="mb-10">
       <i class="fa-solid fa-smile fa-fw"></i>
       <span>5 Projects</span>
      </div>
      <?php } ?>
     </div>
     <div class="info between-flex fs-13">
      <span class="c-grey">Joined <?= $user->date ?></span>
      <div>
       <a class="bg-main c-white btn-shape" href="profile.php?id=<?= $user->id ?>">Profile</a>
      </div>
     </div>
    </div>
    <?php }
    }else{
     echo '<p class="empty">No Team Members Found!</p>';
    }
    ?>
   </div>
  </div>
 </div>


 <!-- ################### START MODAL ################### -->
 <!-- Add Member Modal -->
 <div id="addMemberModal" class="modal">
  <div class="modal-content">
   <h2>Add New Member</h2>
   <form id="addMemberForm" action="" method="POST" enctype="multipart/form-data">
    <div class="input-group">
     <label for="name">Member Name</label> <input type="text" name="name" id="name" required />
    </div>
    <div class="input-group">
     <label for="phone">Member phone</label>
     <input type="tel" name="phone" id="phone" required maxlength="11">
    </div>
    <div class="input-group">
     <label for="email">Member Email</label>
     <input type="email" name="email" id="email" required />
    </div>
    <div class="input-group">
     <label for="ssn">Member SSN</label>
     <input type="text" name="ssn" id="ssn" required maxlength="14" />
    </div>
    <div class="input-group">
     <label for="address">Member Address</label>
     <input type="text" name="address" id="address" required />
    </div>
    <div class="flex-input-group">
     <div class="input-group">
      <label for="birth">Birth Of Date</label>
      <input type="date" name="birth" id="birth" required />
     </div>
     <div class="input-group">
      <label for="gender">Member Gender</label>
      <select name="gender" id="gender" required>
       <option value="0" selected>Male</option>
       <option value="1">Female</option>
      </select>
     </div>
    </div>
    <div class="flex-input-group">
     <div class="input-group">
      <label for="job">Member Job</label>
      <select name="job" id="job" required>
       <option value="0" disabled selected>Select Member Job</option>
       <?php 
        $all_jobs = $conn->prepare("SELECT * FROM jobs ");
        $all_jobs->execute([]);
        if($all_jobs->rowCount() > 0){
          while($row = $all_jobs->fetch(PDO::FETCH_OBJ)){
            echo "<option value='$row->id'>$row->job</option>";
          }
        }
      ?>
      </select>
     </div>
     <div class="input-group">
      <label for="password">Member Password</label>
      <input type="text" name="password" id="password" required maxlength="6" />
     </div>
    </div>
    <div class="input-group">
     <label for="image">Member Image</label>
     <input type="file" name="image" id="image" required accept=".png, .gif, .jpeg, .jpg" />
    </div>
    <div class="btns">
     <input type="submit" class="btn" name="add_member" value="Add Member" />
     <button type="button" class="btn btn-danger close-modal">Cancel</button>
    </div>
   </form>
  </div>
 </div>
 <!-- ################### END MODAL ################### -->


 <!-- START JAVASCRIPT -->
 <script src="js/script.js"></script>
 <script src="js/modal.js"></script>
 <script src="js/validation.js"></script>
 <!-- END JAVASCRIPT -->
</body>

</html>