<?php
include '../includes/connection.php';
$admin_id = '';
if(isset($_COOKIE['admin_id'])){
  $admin_id = $_COOKIE['admin_id'];
}else{
  header('location: ../login.php');
}?>
<!DOCTYPE html>
<html lang="en">

<head>
 <title>Settings</title>
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
   <h1 class="p-relative">Settings</h1>
   <div class="settings-page m-20 d-grid gap-20">
    <!-- Start Settings Box -->
    <div class="p-20 bg-white rad-10">
     <h2 class="mt-0 mb-10">Site Control</h2>
     <p class="mt-0 mb-20 c-grey fs-15">
      Control The Website If There Is Maintenance
     </p>
     <div class="mb-15 between-flex">
      <div>
       <span>Website Control</span>
       <p class="c-grey mt-5 mb-0 fs-13">
        Open/Close Website And Type The Reason
       </p>
      </div>
      <div>
       <label>
        <input class="toggle-checkbox" type="checkbox" checked />
        <div class="toggle-switch"></div>
       </label>
      </div>
     </div>
     <textarea class="close-message p-10 rad-6 d-block w-full" placeholder="Close Message Content"></textarea>
    </div>
    <!-- End Settings Box -->
    <!-- Start Settings Box -->
    <div class="p-20 bg-white rad-10">
     <h2 class="mt-0 mb-10">General Info</h2>
     <p class="mt-0 mb-20 c-grey fs-15">
      General Information About Your Account
     </p>
     <div class="mb-15">
      <label class="fs-14 c-grey d-block mb-10" for="first">First Name</label>
      <input class="b-none border-ccc p-10 rad-6 d-block w-full" type="text" id="first" placeholder="First Name" />
     </div>
     <div class="mb-15">
      <label class="fs-14 c-grey d-block mb-5" for="last">Last Name</label>
      <input class="b-none border-ccc p-10 rad-6 d-block w-full" id="last" type="text" placeholder="Last Name" />
     </div>
     <div>
      <label class="fs-14 c-grey d-block mb-5" for="email">Email</label>
      <input class="email b-none border-ccc p-10 rad-6 w-full mr-10" id="email" type="email" value="o@nn.sa" disabled />
      <a class="c-main" href="#">Change</a>
     </div>
    </div>
    <!-- End Settings Box -->
    <!-- Start Settings Box -->
    <div class="p-20 bg-white rad-10">
     <h2 class="mt-0 mb-10">Security Info</h2>
     <p class="mt-0 mb-20 c-grey fs-15">
      Security Information About Your Account
     </p>
     <div class="sec-box mb-15 between-flex">
      <div>
       <span>Password</span>
       <p class="c-grey mt-5 mb-0 fs-13">Last Change On 25/10/2021</p>
      </div>
      <a class="button bg-main c-white btn-shape" href="#">Change</a>
     </div>
     <div class="sec-box mb-15 between-flex">
      <div>
       <span>Two-Factor Authentication</span>
       <p class="c-grey mt-5 mb-0 fs-13">Enable/Disable The Feature</p>
      </div>
      <label>
       <input class="toggle-checkbox" type="checkbox" checked />
       <div class="toggle-switch"></div>
      </label>
     </div>
     <div class="sec-box between-flex">
      <div>
       <span>Devices</span>
       <p class="c-grey mt-5 mb-0 fs-13">Check The Login Devices List</p>
      </div>
      <a class="bg-eee c-black btn-shape" href="#">Devices</a>
     </div>
    </div>
    <!-- End Settings Box -->
    <!-- Start Settings Box -->
    <div class="social-boxes p-20 bg-white rad-10">
     <h2 class="mt-0 mb-10">Social Info</h2>
     <p class="mt-0 mb-20 c-grey fs-15">Social Media Information</p>
     <div class="d-flex align-center mb-15">
      <i class="fa-brands fa-twitter center-flex c-grey"></i>
      <input class="w-full" type="text" placeholder="Twitter Username" />
     </div>
     <div class="d-flex align-center mb-15">
      <i class="fa-brands fa-facebook-f center-flex c-grey"></i>
      <input class="w-full" type="text" placeholder="Facebook Username" />
     </div>
     <div class="d-flex align-center mb-15">
      <i class="fa-brands fa-linkedin center-flex c-grey"></i>
      <input class="w-full" type="text" placeholder="Linkedin Username" />
     </div>
     <div class="d-flex align-center">
      <i class="fa-brands fa-youtube center-flex c-grey"></i>
      <input class="w-full" type="text" placeholder="Youtube Username" />
     </div>
    </div>
    <!-- End Settings Box -->
    <!-- Start Settings Box -->
    <div class="widgets-control p-20 bg-white rad-10">
     <h2 class="mt-0 mb-10">Widgets Control</h2>
     <p class="mt-0 mb-20 c-grey fs-15">Show/Hide Widgets</p>
     <div class="control d-flex align-center mb-15">
      <input type="checkbox" id="one" checked />
      <label for="one">Quick Draft</label>
     </div>
     <div class="control d-flex align-center mb-15">
      <input type="checkbox" id="two" checked />
      <label for="two">Yearly Targets</label>
     </div>
     <div class="control d-flex align-center mb-15">
      <input type="checkbox" id="three" checked />
      <label for="three">Tickets Statistics</label>
     </div>
     <div class="control d-flex align-center mb-15">
      <input type="checkbox" id="four" checked />
      <label for="four">Latest News</label>
     </div>
     <div class="control d-flex align-center mb-15">
      <input type="checkbox" id="five" />
      <label for="five">Latest Tasks</label>
     </div>
     <div class="control d-flex align-center mb-15">
      <input type="checkbox" id="six" checked />
      <label for="six">Top Search Items</label>
     </div>
    </div>
    <!-- End Settings Box -->
    <!-- Start Settings Box -->
    <div class="backup-control p-20 bg-white rad-10">
     <h2 class="mt-0 mb-10">Backup Manager</h2>
     <p class="mt-0 mb-20 c-grey fs-15">Control Backup Time And Location</p>
     <div class="date d-flex align-center mb-15">
      <input type="radio" name="time" id="daily" checked />
      <label for="daily">Daily</label>
     </div>
     <div class="date d-flex align-center mb-15">
      <input type="radio" name="time" id="weekly" />
      <label for="weekly">Weekly</label>
     </div>
     <div class="date d-flex align-center mb-15">
      <input type="radio" name="time" id="monthly" />
      <label for="monthly">Monthly</label>
     </div>
     <div class="servers d-flex align-center txt-c">
      <input type="radio" name="servers" id="server-one" />
      <div class="server mb-15 rad-10 w-full">
       <label class="d-block m-15" for="server-one">
        <i class="fa-solid fa-server d-block mb-10"></i>
        Megaman
       </label>
      </div>
      <input type="radio" name="servers" id="server-two" checked />
      <div class="server mb-15 rad-10 w-full">
       <label class="d-block m-15" for="server-two">
        <i class="fa-solid fa-server d-block mb-10"></i>
        Zero
       </label>
      </div>
      <input type="radio" name="servers" id="server-three" />
      <div class="server mb-15 rad-10 w-full">
       <label class="d-block m-15" for="server-three">
        <i class="fa-solid fa-server d-block mb-10"></i>
        Sigma
       </label>
      </div>
     </div>
    </div>
    <!-- End Settings Box -->
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