<?php
include '../includes/connection.php';
$admin_id = '';
if(isset($_COOKIE['admin_id'])){
  $admin_id = $_COOKIE['admin_id'];
}else{
  header('location: ../login.php');
}
$user_id = "";
if(isset($_GET['id'])){
$user_id = $_GET['id'];
}else{
  header("Location: index.php");
}


// admin information and permissions
$select_admin = $conn->prepare("SELECT * FROM users u JOIN employees e ON u.id = e.employee_id WHERE u.id = ?");
$select_admin->execute([$admin_id]);
$fetch_admin = $select_admin->fetch(PDO::FETCH_OBJ);
$select_employee_permissions = $conn->prepare("SELECT * FROM job_permisions WHERE job_id = ?");
$select_employee_permissions->execute([$fetch_admin->job_id]);
$employee_permissions = $select_employee_permissions->fetchAll(PDO::FETCH_OBJ);
if (!in_array('1', array_column($employee_permissions, 'permission_id'))) header("location: index.php");

// select user inforamtion
$select_user = $conn->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
$select_user->execute([$user_id]);
$fetch_user = "";
if($select_user->rowCount() > 0){
  $fetch_user = $select_user->fetch(PDO::FETCH_OBJ);
}else{
  header("Location: index.php");
}
$select_employee = $conn->prepare("SELECT e.*, j.job FROM employees e JOIN jobs j ON e.job_id = j.id WHERE e.employee_id = ?");
$select_employee->execute([$user_id]);
$fetch_employee = "";
if ($select_employee->rowCount() > 0){
  $fetch_employee = $select_employee->fetch(PDO::FETCH_OBJ);
}

// Edit Profile Function
if(isset($_POST['edit_profile'])){
  //update profile name
  $o_name = filter_var($_POST['o_name'], FILTER_SANITIZE_STRING);
  if(!empty($o_name) && $o_name != $fetch_user->name){
    $update_profile_name = $conn->prepare("UPDATE users SET name = ? WHERE id = ?");
    $update_profile_name->execute([$o_name, $fetch_user->id]);
    $success_msg[] = 'Your Name Updated Successfully, Please Refresh To Show Updates!';
  }
  //update profile phone
  $o_phone = filter_var($_POST['o_phone'], FILTER_SANITIZE_STRING);
  if(!empty($o_phone) && $o_phone != $fetch_user->phone){
    $check_phone = $conn->prepare("SELECT * FROM users WHERE phone = ?");
    $check_phone->execute([$o_phone]);
    if($check_phone->rowCount() > 0){
      $warning_msg[] = "Phone Already Exists In Database!";
    }else{
      $update_profile_phone = $conn->prepare("UPDATE users SET phone = ? WHERE id = ?");
      $update_profile_phone->execute([$o_phone, $fetch_user->id]);
      $success_msg[] = 'Your Phone Updated Successfully, Please Refresh To Show Updates!';
    }
  }
  //update profile email
  $o_email = filter_var($_POST['o_email'], FILTER_SANITIZE_EMAIL);
  if(!empty($o_email) && $o_email != $fetch_user->email){
    $check_email = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $check_email->execute([$o_email]);
    if($check_email->rowCount() > 0){
      $warning_msg[] = "Email Already Exists In Database!";
    }else{
      $update_profile_email = $conn->prepare("UPDATE users SET email = ? WHERE id = ?");
      $update_profile_email->execute([$o_email, $fetch_user->id]);
      $success_msg[] = 'Your Email Updated Successfully, Please Refresh To Show Updates!';
    }
  }
  //update profile address
  $o_address = filter_var($_POST['o_address'], FILTER_SANITIZE_STRING);
  if(!empty($o_address) && $o_address != $fetch_user->address){
    $update_profile_address = $conn->prepare("UPDATE users SET address = ? WHERE id = ?");
    $update_profile_address->execute([$o_address, $fetch_user->id]);
    $success_msg[] = 'Your Address Updated Successfully, Please Refresh To Show Updates!';
  }
   //update profile password
  $o_password = filter_var(sha1($_POST['o_password']), FILTER_SANITIZE_STRING);
  $n_password = filter_var(sha1($_POST['n_password']), FILTER_SANITIZE_STRING);
  if(!empty($_POST['o_password']) && !empty($_POST['n_password'])){
    if($o_password != $fetch_user->password){
      $warning_msg[] = "Old Password Not Match!";
    }else if($n_password == $fetch_user->password){
      $warning_msg[] = "Password Not Changed!";
    }else{
      $update_profile_password = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
      $update_profile_password->execute([$o_password, $fetch_user->id]);
      $success_msg[] = 'Your Password Updated Successfully!';
    }
  }
  //update profile image
  $o_image= $_FILES['o_image']['name'];
  $o_ext = pathinfo($o_image, PATHINFO_EXTENSION);
  $o_rename = generate_unique_id().'.'.$o_ext;
  $o_image_tmp_name = $_FILES['o_image']['tmp_name'];
  $o_image_tmp_folder = '../image/user_images/'.$o_rename;
  if(!empty($o_image)){
    if($o_image != $fetch_user->image){
      unlink('../image/user_images/'.$fetch_user->image);
      $update_profile_image = $conn->prepare("UPDATE users SET image = ? WHERE id = ?");
      $update_profile_image->execute([$o_rename, $fetch_user->id]);
      move_uploaded_file($o_image_tmp_name, $o_image_tmp_folder);
      $success_msg[] = "Your Image updated successfully, Please Refresh To Show Updates!";
    }else{
      $warning_msg[] = 'Image Already Exists With This Name!';
    }
  }
}
if(isset($_POST['logout'])){
  setcookie('admin_id', '', time() - 1, '/');
  header('location:../login.php');
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
  <?php if($fetch_user !== ""){ ?>
  <div class="content w-full">
   <h1 class="p-relative"><?= $fetch_user->id == $admin_id ? "My " : "" ?>Profile</h1>
   <div class="profile-page m-20 mb-20">
    <!-- Start Overview -->
    <div class="overview bg-white rad-10 d-flex align-center">
     <div class="avatar-box txt-c p-20">
      <img class="rad-half mb-10" src="../image/user_images/<?= $fetch_user->image ?>" alt="user_image" />
      <h3 class="m-0"><?= $fetch_user->name ?></h3>
      <p class="m-0 mt-5 c-grey"><?= $fetch_user->group_id == 0 ? "Employee" : "Customer" ?></p>
     </div>
     <div class="info-box w-full">
      <!-- Start Information Row -->
      <div class="box p-20 d-flex">
       <h4 class="c-grey fs-15 m-0 w-full">General Information</h4>
       <div class="box-flex d-flex align-center">
        <div class="fs-14">
         <span class="c-grey">Full Name: </span>
         <span><?= $fetch_user->name ?></span>
        </div>
        <?php if($fetch_employee !== ""){ ?>
        <div class="fs-14">
         <span class="c-grey">Gender: </span>
         <span><?= $fetch_employee->gender == 0 ? "Male" : "Female" ?></span>
        </div>
        <?php } ?>
        <div class="fs-14">
         <span class="c-grey">Address: </span>
         <span><?= $fetch_user->address ?></span>
        </div>
       </div>
       <?php if($fetch_user->id == $admin_id){?>
       <div class="fs-14">
        <label>
         <input class="toggle-checkbox" type="checkbox" checked />
         <div class="toggle-switch"></div>
        </label>
       </div>
       <?php } ?>
      </div>
      <!-- End Information Row -->
      <!-- Start Information Row -->
      <div class="box d-flex p-20">
       <h4 class="c-grey w-full fs-15 m-0">Personal Information</h4>
       <div class="box-flex d-flex align-center">
        <div class="fs-14">
         <span class="c-grey">Email: </span>
         <span><?= $fetch_user->email ?></span>
        </div>
        <div class="fs-14">
         <span class="c-grey">Phone: </span>
         <span><?= $fetch_user->phone ?></span>
        </div>
        <?php if($fetch_employee !== ""){ ?>
        <div class="fs-14">
         <span class="c-grey">Date Of Birth:</span>
         <span><?= $fetch_employee->birth_of_date ?></span>
        </div>
        <?php } ?>
       </div>
       <?php if($fetch_user->id == $admin_id){?>
       <div class="fs-14">
        <label>
         <input class="toggle-checkbox" type="checkbox" checked />
         <div class="toggle-switch"></div>
        </label>
       </div>
       <?php } ?>
      </div>
      <!-- End Information Row -->
      <!-- Start Information Row -->
      <?php if($fetch_employee !== ""){
        $user_books = $conn->prepare("SELECT * FROM books WHERE author = ?");
        $user_books->execute([$user_id]);
        $no_of_books = $user_books->rowCount();
        $user_permissions = $conn->prepare("SELECT * FROM job_permisions WHERE job_id = ?");
        $user_permissions->execute([$fetch_employee->job_id]);
        $no_of_user_permissions = $user_permissions->rowCount(); ?>
      <div class="box p-20 d-flex">
       <h4 class="c-grey w-full fs-15 m-0">Job Information</h4>
       <div class="box-flex d-flex align-center">
        <div class="fs-14">
         <span class="c-grey">Title: </span>
         <span><?= $fetch_employee->job ?></span>
        </div>
        <div class="fs-14">
         <span class="c-grey">Permissions: </span>
         <span><?= $no_of_user_permissions ?></span>
        </div>
        <?php if($fetch_employee->job_id == "3"){ ?>
        <div class="fs-14">
         <span class="c-grey">Books: </span>
         <span><?= $no_of_books ?></span>
        </div>
        <?php } ?>
       </div>
       <?php if($fetch_user->id == $admin_id){?>
       <div class="fs-14">
        <label>
         <input class="toggle-checkbox" type="checkbox" checked />
         <div class="toggle-switch"></div>
        </label>
       </div>
       <?php } ?>
      </div>
      <?php } ?>
      <!-- End Information Row -->
      <!-- Start Information Row -->
      <?php if($fetch_employee !== "" && $fetch_user->id == $admin_id){ ?>
      <div class="box p-20 d-flex">
       <h4 class="c-grey w-full fs-15 m-0">Profile Operations</h4>
       <div class="box-flex d-flex align-center">
        <div class="fs-14">
         <button class="btn-shape bg-main c-white open-modal" data-modal="editProfileModal">Edit Profile <i
           class="fa fa-edit"></i></button>
        </div>
        <div class="fs-14">
         <button class="btn-shape bg-red c-white open-modal" data-modal="confirmLogoutModal">Logout <i
           class="fa fa-right-from-bracket"></i></button>
        </div>
       </div>
      </div>
      <?php } ?>
      <!-- End Information Row -->
      <!-- Start Information Row -->
      <?php if($fetch_employee == "" && $fetch_user->group_id == 1){ 
        $select_user_orders = $conn->prepare("SELECT * FROM orders WHERE u_id = ?");
        $select_user_orders->execute([$fetch_user->id]);
        $no_of_orders = $select_user_orders->rowCount();?>
      <div class="box p-20 d-flex">
       <h4 class="c-grey w-full fs-15 m-0">Orders Information</h4>
       <div class="box-flex d-flex align-center">
        <div class="fs-14">
         <span class="c-grey">Orders: </span>
         <span><?= $no_of_orders ?></span>
        </div>
        <div class="fs-14">
         <span class="c-grey">Likes: </span>
         <span><?= $fetch_user->phone ?></span>
        </div>
       </div>
      </div>
      <?php } ?>
      <!-- End Information Row -->
     </div>
    </div>
    <!-- End Overview -->
    <!-- Start Other Data -->
    <!-- ############# EMPLOYEE PERMISSIONS -->
    <?php if($fetch_employee !== ""){ 
      $select_permissions = $conn->prepare("SELECT * FROM job_permisions jp JOIN permissions p ON jp.permission_id = p.id WHERE jp.job_id = ?");
      $select_permissions->execute([$fetch_employee->job_id]); ?>
    <div class="skills-card p-20 bg-white rad-10 mt-20">
     <h2 class="mt-0 mb-10">Member Permissions</h2>
     <div class="d-flex">
      <?php if($select_permissions->rowCount()>0){ while($row = $select_permissions->fetch(PDO::FETCH_OBJ)){?>
      <span><?= $row->permission ?></span>
      <?php }}else{ echo "<p class='empty'>No Permissions Found!</p>"; }?>
     </div>
    </div>
    <?php } ?>
    <!-- ############# AUTHER BOOKS -->
    <?php if($fetch_employee !== "" && $fetch_employee->job_id == 3){
      $user_books = $conn->prepare("SELECT b.*, c.title FROM books b JOIN category c ON b.category_id = c.id WHERE author = ? ORDER BY b.id DESC LIMIT 5");
      $user_books->execute([$user_id]);
      $no_of_books = $user_books->rowCount();?>
    <div class="activities p-20 bg-white rad-10 mt-20">
     <div class="between-flex">
      <h2 class="mt-0 mb-10">Latest Books</h2>
      <?php if($fetch_user->id == $admin_id){?>
      <a href="books.php" class="btn-shape bg-main fs-15 c-white">Add New Book</a>
      <?php } ?>
     </div>
     <p class="mt-0 mb-20 c-grey fs-15">Latest Books Uploaded By <?= $fetch_user->name?></p>
     <?php if($user_books->rowCount() > 0){ while($row = $user_books->fetch(PDO::FETCH_OBJ)){?>
     <div class="activity d-flex align-center txt-c-mobile">
      <img src="../image/books/<?= $row->image ?>" alt="book-image" />
      <div class="info">
       <span class="d-block mb-10"><?= $row->name ?></span>
       <span class="c-grey"><?= $row->title ?></span>
      </div>
      <div class="date">
       <span class="d-block mb-10 c-main"><?= $row->price ?> EGP</span>
       <span class="c-grey"><?= $row->date ?></span>
      </div>
     </div>
     <?php }}else{ echo "<p class='empty'>No Books Uploaded Recently!</p>"; }?>
    </div>
    <?php } ?>
    <!-- ############# USER ORDERS -->
    <?php if($fetch_user->group_id == 1){
      $user_orders = $conn->prepare("SELECT o.*, od.payment_method FROM orders o JOIN order_details od ON o.id = od.o_id WHERE o.u_id = ? ORDER BY o.id DESC");
      $user_orders->execute([$user_id]);?>
    <div class="p-20 bg-white rad-10 mt-20">
     <h2 class="mt-0 mb-20">Latest Orders</h2>
     <div class="responsive-table">
      <table class="fs-15 w-full">
       <thead>
        <tr>
         <td>ID</td>
         <td>Status</td>
         <td>Total</td>
         <td>Payment Method</td>
         <td>Date</td>
        </tr>
       </thead>
       <tbody>
        <?php 
     if($user_orders->rowCount() > 0){ 
      while($order = $user_orders->fetch(PDO::FETCH_OBJ)){
        $select_last_status = $conn->prepare("SELECT * FROM order_status WHERE o_id = ? ORDER BY s_id DESC LIMIT 1");
        $select_last_status->execute([$order->id]);
        $last_status = $select_last_status->fetch(PDO::FETCH_OBJ);
        ?>
        <tr>
         <td><a href="order.php?id=<?= $order->id ?>">#<?= $order->id ?></a></td>
         <td><span class="label btn-shape <?= $last_status->status ?> c-white"><?= $last_status->status ?></span></td>
         <td><?= $order->total ?> EGP</td>
         <td><?= $order->payment_method == 0 ? "Cash On Delivery" : "Credit Card" ?></td>
         <td><?= $order->date ?></td>
        </tr>
        <?php }
        }else{
         echo '<tr><td colspan="5" class="txt-c">No User Orders Found!</td></tr>';
        }
        ?>
       </tbody>
      </table>
     </div>
    </div>
    <?php } ?>
    <!-- End Other Data -->
   </div>
  </div>
  <?php } ?>
  <!-- END CONTENT -->
 </div>


 <!-- ################### START MODAL ################### -->
 <!-- Edit Profile Modal -->
 <div id="editProfileModal" class="modal">
  <div class="modal-content">
   <h2>Edit Profile</h2>
   <form id="editProfileForm" action="" method="POST" enctype="multipart/form-data">
    <div class="input-group">
     <label for="o_name">Your Name</label>
     <input type="text" name="o_name" id="o_name" required value="<?= $fetch_user->name ?>" />
    </div>
    <div class="input-group">
     <label for="o_phone">Your phone</label>
     <input type="tel" name="o_phone" id="o_phone" required maxlength="11" value="<?= $fetch_user->phone ?>">
    </div>
    <div class="input-group">
     <label for="o_email">Your Email</label>
     <input type="email" name="o_email" id="o_email" required value="<?= $fetch_user->email ?>" />
    </div>
    <div class="input-group">
     <label for="o_address">Your Address</label>
     <input type="text" name="o_address" id="o_address" required value="<?= $fetch_user->address ?>" />
    </div>
    <div class="input-group">
     <label for="o_password">Your Old Password</label>
     <input type="text" name="o_password" id="o_password" maxlength="6" />
    </div>
    <div class="input-group">
     <label for="n_password">Your New Password</label>
     <input type="text" name="n_password" id="n_password" maxlength="6" />
    </div>
    <div class="input-group">
     <label for="o_image">Your Image</label>
     <img src="../image/user_images/<?= $fetch_user->image ?>" alt="profile-image" id="o_image_src">
     <input type="file" name="o_image" id="o_image" accept=".png, .gif, .jpeg, .jpg" />
    </div>
    <div class="btns">
     <input type="submit" class="btn" name="edit_profile" value="Edit Profile" />
     <button type="button" class="btn btn-danger close-modal">Cancel</button>
    </div>
   </form>
  </div>
 </div>
 <!-- Confirm Logout Modal -->
 <div id="confirmLogoutModal" class="modal">
  <div class="modal-content">
   <h2>Delete <span id="category_name"></span></h2>
   <form id="confirmLogoutForm" action="" method="post">
    <div class="input-group">
     <h3>Are You Sure You Want To Logout?</h3>
    </div>
    <div class="btns">
     <input type="submit" class="btn" name="logout" value="Logout" />
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