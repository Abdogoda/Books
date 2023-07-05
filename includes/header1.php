<?php

if(isset($_GET['logout'])){
  setcookie('user_id', '', time() - 1, '/');
  header("location:index.php");
}
?>
<div class="header-1">

 <a href="index.php" class="logo"> <i class="fas fa-book"></i> bookly </a>

 <form action="books.php" method="post" class="search-form">
  <input type="search" name="search_box" placeholder="search here..." id="search-box">
  <button type="submit" name="search_btn" class="fas fa-search"></button>
 </form>

 <div class="icons">
  <a href="#" class="fas fa-heart">
   <?php if(isset($_COOKIE['liked']) && count(json_decode($_COOKIE['liked'])) > 0){  ?>
   <span><?= count(json_decode($_COOKIE['liked'])) ?></span>
   <?php } ?>
  </a>
  <a href="cart.php" class="fas fa-shopping-cart ">
   <?php if(isset($_COOKIE['cart']) && count(json_decode($_COOKIE['cart'])) > 0){  ?>
   <span><?= count(json_decode($_COOKIE['cart'])) ?></span>
   <?php } ?>
  </a>
  <a href="<?php if(isset($_COOKIE['user_id'])){echo "#";}else{echo "login.php";}?>" id="login-btn"
   class="fas fa-user <?php if(isset($_COOKIE['user_id'])) echo "active-user"?>"></a>
 </div>
 <?php if(isset($_COOKIE['user_id'])){
    $select = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $select->execute([$user_id]);
    if($select->rowCount() > 0){
      $row = $select->fetch();
    }
    ?>
 <div class="profile-box">
  <button class="profile-box-close">
   <i class="fas fa-times "></i>
  </button>
  <img src="<?= "image/user_images/".$row['image']?>" alt="<?= $row['name']?>">
  <div class="info">
   <div class="info-text">
    <h3><?= $row['name']?></h3>
    <p><?= $row['email']?></p>
   </div>
  </div>
  <div class="action">
   <a href="profile.php" class="btn">Profile</a>
   <a href="index.php?logout" onclick="return confirm('Are You Sure You Want To Logout!?')"
    class="btn btn-danger">logout</a>
  </div>
 </div>
 <?php }?>
</div>
<?php if(isset($_COOKIE['user_id'])){?>
<script>
let login_btn = document.querySelector("#login-btn");
login_btn.onclick = () => {
 if (login_btn.classList.contains("active-user")) {
  document.querySelector(".profile-box").classList.toggle("active");
 }
};
document.querySelector(".profile-box-close").onclick = () => {
 document.querySelector(".profile-box").classList.remove("active");
};
</script>
<?php }?>