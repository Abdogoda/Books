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
  <li>
   <a class="d-flex align-center fs-14 c-black rad-6 p-10" href="team.php">
    <i class="fa-solid fa-users-gear fa-fw"></i>
    <span>Team</span>
   </a>
  </li>
  <li>
   <a class="d-flex align-center fs-14 c-black rad-6 p-10" href="customers.php">
    <i class="fa-sharp fa-solid fa-users fa-fw"></i>
    <span>Customers</span>
   </a>
  </li>
  <li>
   <a class="d-flex align-center fs-14 c-black rad-6 p-10" href="books.php">
    <i class="fa-solid fa-book fa-fw"></i>
    <span>Books</span>
   </a>
  </li>
  <li>
   <a class="d-flex align-center fs-14 c-black rad-6 p-10" href="orders.php">
    <i class="fa-solid fa-diagram-project fa-fw"></i>
    <span>Orders</span>
   </a>
  </li>
  <li>
   <a class="d-flex align-center fs-14 c-black rad-6 p-10" href="plans.php">
    <i class="fa-regular fa-credit-card fa-fw"></i>
    <span>Plans</span>
   </a>
  </li>
  <li>
   <a class="d-flex align-center fs-14 c-black rad-6 p-10" href="notifications.php">
    <i class="fa-regular fa-bell fa-fw"></i>
    <span>Notifications</span>
   </a>
  </li>
  <li>
   <a class="d-flex align-center fs-14 c-black rad-6 p-10" href="messages.php">
    <i class="fa-regular fa-message fa-fw"></i>
    <span>Messages</span>
   </a>
  </li>
  <li>
   <a class="d-flex align-center fs-14 c-black rad-6 p-10" href="settings.php">
    <i class="fa-solid fa-gear fa-fw"></i>
    <span>Settings</span>
   </a>
  </li>
 </ul>
</div>

<script>
document.querySelectorAll(".sidebar ul li a").forEach(link => {
 if (link.href == window.location.href) {
  link.classList.add("active");
 }
})
</script>