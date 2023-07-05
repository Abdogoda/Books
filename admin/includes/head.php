<?php
// select admin informtation
$select_admin = $conn->prepare("SELECT u.*, e.*, j.job FROM users u JOIN employees e ON u.id = e.employee_id JOIN jobs j ON e.job_id = j.id  WHERE u.id = ? LIMIT 1");
$select_admin->execute([$admin_id]);
$admin_profile = $select_admin->fetch(PDO::FETCH_OBJ);
// select admin permissions
$select_permissions = $conn->prepare("SELECT * FROM job_permisions jp JOIN permissions p ON jp.permission_id = p.id JOIN jobs j ON jp.job_id = j.id WHERE jp.job_id = ?");
$select_permissions->execute([$admin_profile->job_id]);
$employee_permissions = $select_permissions->fetchAll();
$no_of_permissions = $select_permissions->rowCount();
// select author books
$admin_books = $conn->prepare("SELECT * FROM books WHERE author = ?");
$admin_books->execute([$admin_id]);
$no_of_books = $admin_books->rowCount();
?>
<div class="head bg-white p-15 between-flex">
 <div class="d-flex">
  <button class="sidebarShowBtn  fs-20 d-none block-mobile"><i class="fa fa-bars"></i></button>
  <form action="./books.php" method="post" class="search p-relative">
   <input class="p-10" type="search" name="search_box" placeholder="Search A Book" />
   <button type="submit" class="icon" name="search_btn"><i class="fa fa-search"></i></button>
  </form>
 </div>
 <div class="icons d-flex align-center">
  <a href="notifications.php" class="notification p-relative">
   <i class="fa-regular fa-bell fa-lg"></i>
  </a>
  <a href="profile.php?id=<?=$admin_id?>">
   <img src="../image/user_images/<?= $admin_profile->image ?>" alt="admin_image" class="rad-half" />
  </a>
 </div>
</div>