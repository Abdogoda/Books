<div class="sidebar bg-white p-20 p-relative">
 <button class="sidebarHideBtn fs-20 "><i class="fa fa-close "></i></button>
 <h3 class="p-relative txt-c mt-0">Admin</h3>
 <ul>
  <li>
   <a class=" d-flex align-center fs-14 c-black rad-6 p-10" href="index.php">
    <i class="fa-regular fa-chart-bar fa-fw"></i>
    <span>Dashboard</span>
   </a>
  </li>
  <li>
   <a class="d-flex align-center fs-14 c-black rad-6 p-10" href="profile.php?id=<?=$admin_id?>">
    <i class="fa-regular fa-user fa-fw"></i>
    <span>Profile</span>
   </a>
  </li>
  <?php if (in_array('3', array_column($employee_permissions, 'permission_id'))) { ?>
  <li>
   <a class="d-flex align-center fs-14 c-black rad-6 p-10" href="team.php">
    <i class="fa-solid fa-users-gear fa-fw"></i>
    <span>Team</span>
   </a>
  </li>
  <?php }?>
  <?php if (in_array('11', array_column($employee_permissions, 'permission_id'))) { ?>
  <li>
   <a class="d-flex align-center fs-14 c-black rad-6 p-10" href="jobs.php">
    <i class="fa-solid fa-tags fa-fw"></i>
    <span>Jobs</span>
   </a>
  </li>
  <?php }?>
  <?php if (in_array('4', array_column($employee_permissions, 'permission_id'))) { ?>
  <li>
   <a class="d-flex align-center fs-14 c-black rad-6 p-10" href="customers.php">
    <i class="fa-sharp fa-solid fa-users fa-fw"></i>
    <span>Customers</span>
   </a>
  </li>
  <?php }?>
  <?php if (in_array('5', array_column($employee_permissions, 'permission_id'))) { ?>
  <li>
   <a class="d-flex align-center fs-14 c-black rad-6 p-10" href="books.php">
    <i class="fa-solid fa-book fa-fw"></i>
    <span>Books</span>
   </a>
  </li>
  <?php }?>
  <?php if (in_array('2', array_column($employee_permissions, 'permission_id'))) { ?>
  <li>
   <a class="d-flex align-center fs-14 c-black rad-6 p-10" href="orders.php">
    <i class="fa-solid fa-diagram-project fa-fw"></i>
    <span>Orders</span>
   </a>
  </li>
  <?php }?>
  <?php if (in_array('7', array_column($employee_permissions, 'permission_id'))) { ?>
  <li>
   <a class="d-flex align-center fs-14 c-black rad-6 p-10" href="plans.php">
    <i class="fa-regular fa-credit-card fa-fw"></i>
    <span>Plans</span>
   </a>
  </li>
  <?php }?>
  <?php if (in_array('9', array_column($employee_permissions, 'permission_id'))) { ?>
  <li>
   <a class="d-flex align-center fs-14 c-black rad-6 p-10" href="notifications.php">
    <i class="fa-regular fa-bell fa-fw"></i>
    <span>Notifications</span>
   </a>
  </li>
  <?php }?>
  <?php if (in_array('6', array_column($employee_permissions, 'permission_id'))) { ?>
  <li>
   <a class="d-flex align-center fs-14 c-black rad-6 p-10" href="messages.php">
    <i class="fa-regular fa-message fa-fw"></i>
    <span>Messages</span>
   </a>
  </li>
  <?php }?>
  <?php if (in_array('8', array_column($employee_permissions, 'permission_id'))) { ?>
  <li>
   <a class="d-flex align-center fs-14 c-black rad-6 p-10" href="settings.php">
    <i class="fa-solid fa-gear fa-fw"></i>
    <span>Settings</span>
   </a>
  </li>
  <?php }?>
 </ul>
</div>

<script>
document.querySelectorAll(".sidebar ul li a").forEach(link => {
 if (link.href == window.location.href) {
  link.classList.add("active");
 }
})
</script>