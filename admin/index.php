<?php
include '../includes/connection.php';
$admin_id = '';
if(isset($_COOKIE['admin_id'])){
  $admin_id = $_COOKIE['admin_id'];
}else{
  header('location: ../login.php');
}

// Save Idea
if(isset($_POST['save_idea'])){
  $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
  $content = filter_var($_POST['content'], FILTER_SANITIZE_STRING);
  $check_idea = $conn->prepare("SELECT * FROM ideas WHERE e_id = ? AND title = ? AND content = ?");
  $check_idea->execute([$admin_id, $title, $content]);
  if($check_idea->rowCount() <= 0){
    $insert_idea = $conn->prepare("INSERT INTO ideas (e_id, title, content) VALUES (?, ?, ?)");
    $insert_idea->execute([$admin_id, $title, $content]);
    $success_msg[] = "Idea Saved Successfully!";
  }
}

// ================== select statistics ==================
//SELECT EMPLOYEES
$select_employees = $conn->prepare("SELECT * FROM users u JOIN employees e ON u.id = e.employee_id ORDER BY u.date DESC ");
$select_employees->execute([]);
$no_of_employees = $select_employees->rowCount();
//SELECT VISITORS
$select_visitors = $conn->prepare("SELECT * FROM users WHERE group_id = 1 ORDER BY date DESC ");
$select_visitors->execute([]);
$no_of_visitors = $select_visitors->rowCount();
//SELECT ORDERS
$select_orders = $conn->prepare("SELECT * FROM orders ORDER BY date DESC ");
$select_orders->execute([]);
$no_of_orders = $select_orders->rowCount();
//SELECT SALES
$select_sales = $conn->prepare("SELECT SUM(total) as sum FROM orders o JOIN order_status os ON o.id = os.o_id WHERE os.status = ?");
$select_sales->execute(["Completed"]);
$total_sales = $select_sales->fetch(PDO::FETCH_OBJ)->sum;
//SALES INDICATOR
$select_sales_indicator = $conn->prepare("SELECT DATE_FORMAT(date, '%M') AS month, SUM(total) AS total_sales FROM orders WHERE YEAR(date) = YEAR(CURDATE()) AND date <= CURDATE() GROUP BY MONTH(date)");
$select_sales_indicator->execute([]);
$sales_indicator_month = "";
$sales_indicator_total = "";
while($row = $select_sales_indicator->fetch(PDO::FETCH_OBJ)){ $sales_indicator_month .= "'$row->month',"; $sales_indicator_total .= "$row->total_sales,"; }
//SOLD BOOKS
$select_sold_books = $conn->prepare("SELECT b.name, SUM(ob.quantity) as total_quantity FROM order_books ob JOIN books b ON ob.b_id = b.id GROUP BY ob.b_id ORDER BY total_quantity DESC LIMIT 4 ");
$select_sold_books->execute([]);
$sold_books_name = "";
$sold_books_qty = "";
while($row = $select_sold_books->fetch(PDO::FETCH_OBJ)){ $sold_books_name .= "'$row->name',"; $sold_books_qty .= "$row->total_quantity,"; }
//ORDER STATUS
$select_order_status = $conn->prepare("SELECT status, COUNT(*) AS count FROM order_status os WHERE os.o_id IN (SELECT o_id FROM order_status GROUP BY o_id HAVING MAX(s_id) = os.s_id) GROUP BY status");
$select_order_status->execute([]);
$order_status_status = "";
$order_status_count = "";
while($row = $select_order_status->fetch(PDO::FETCH_OBJ)){ $order_status_status .= "'$row->status',"; $order_status_count .= "$row->count,"; }

?>
<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8" />
 <meta http-equiv="X-UA-Compatible" content="IE=edge" />
 <meta name="viewport" content="width=device-width, initial-scale=1.0" />
 <title>Admin Dashboard</title>
 <link rel="shortcut icon" href="../image/icon.svg" type="image/x-icon">
 <link rel="stylesheet" href="css/all.min.css" />
 <link rel="stylesheet" href="css/framework.css" />
 <link rel="stylesheet" href="css/alert.css">
 <link rel="stylesheet" href="css/master.css" />
 <link rel="stylesheet" href="css/modal.css">
 <link rel="stylesheet" href="css/calendar.css">
 <link rel="preconnect" href="https://fonts.googleapis.com" />
 <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
 <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;500&display=swap" rel="stylesheet" />
 <script src="js/alert.js"></script>
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
   <h1 class="p-relative">Dashboard</h1>
   <!-- Start Welcome Widget -->
   <div class="between-flex bg-white rad-6 m-20 mb-20 p-20">
    <div class="d-flex align-center gap-10">
     <h2 class="m-0">Hi, Welcome</h2>
     <span class="c-grey mt-3"><?= $admin_profile->name ?></span>
    </div>
    <a href="profile.php?id=<?= $admin_id ?>" class="visit d-block fs-14 bg-main c-white w-fit btn-shape">Profile</a>
   </div>
   <!-- End Welcome Widget -->
   <!-- Start Widgets Widget -->
   <div class="widgets d-grid gap-20 rad-10 m-20 mb-20">
    <div class="d-flex gap-10 rad-15 p-20 bg-white green txt-center c-black">
     <div class="icon">
      <i class="fa-solid fa-users-gear"></i>
     </div>
     <div>
      <p class="m-0 fs-20 c-green fw-bold"><?= $no_of_employees ?></p>
      <p class="c-grey m-0 mt-5">Employees</p>
     </div>
    </div>
    <div class="d-flex gap-10 rad-15 p-20 bg-white blue txt-center c-black">
     <div class="icon">
      <i class="fa-solid fa-users"></i>
     </div>
     <div>
      <p class="m-0 fs-20 c-blue fw-bold"><?= $no_of_visitors ?></p>
      <p class="c-grey m-0 mt-5">Visitors</p>
     </div>
    </div>
    <div class="d-flex gap-10 rad-15 p-20 bg-white orange txt-center c-black">
     <div class="icon">
      <i class="fa-solid fa-diagram-project"></i>
     </div>
     <div>
      <p class="m-0 fs-20 c-orange fw-bold"><?= $no_of_orders ?></p>
      <p class="c-grey m-0 mt-5">Orders</p>
     </div>
    </div>
    <div class="d-flex gap-10 rad-15 p-20 bg-white red txt-center c-black">
     <div class="icon">
      <i class="fa-solid fa-dollar-sign"></i>
     </div>
     <div>
      <p class="m-0 fs-20 c-red fw-bold"><?= $total_sales ?> EGP</p>
      <p class="c-grey m-0 mt-5">Total Sales</p>
     </div>
    </div>
   </div>
   <!-- End Widgets Widget -->
   <div class="wrapper d-grid gap-20">
    <!-- Start Top Sold Books Widget -->
    <div class="sold-items p-20 bg-white rad-10 ChartBox1">
     <div class="between-flex align-center mb-20">
      <h2 class="m-0">Top Sold Books</h2>
      <button type="button" onclick="downloadImage('ChartBox1')" class="btn-shape bg-main c-white">Export <i
        class="fas fa-download"></i></button>
     </div>
     <div class="chart">
      <canvas id="chart1"></canvas>
     </div>
    </div>
    <!-- End Top Sold Books Widget -->
    <!-- Start Orders status Widget -->
    <div class="order-status p-20 bg-white rad-10 ChartBox2">
     <div class="between-flex align-center mb-20">
      <h2 class="m-0">Orders Status</h2>
      <button type="button" onclick="downloadImage('ChartBox2')" class="btn-shape bg-main c-white">Export <i
        class="fas fa-download"></i></button>
     </div>
     <div class="chart">
      <canvas id="chart2"></canvas>
     </div>
    </div>
    <!-- End Orders status Widget -->
    <!-- Start Top Search Books Widget -->
    <div class="search-items p-20 bg-white rad-10">
     <h2 class="mt-0 mb-20">Top Search Books</h2>
     <?php 
     $select_search_books = $conn->prepare("SELECT b.name, SUM(ob.quantity) as total_quantity FROM order_books ob JOIN books b ON ob.b_id = b.id GROUP BY ob.b_id ORDER BY total_quantity DESC LIMIT 5 ");
     $select_search_books->execute([]);
     while($row = $select_search_books->fetch(PDO::FETCH_OBJ)){?>
     <div class="items d-flex space-between pt-15 pb-15">
      <span><?= $row->name ?></span>
      <span class="bg-eee fs-13 btn-shape"><?= $row->total_quantity ?></span>
     </div>
     <?php  } ?>
    </div>
    <!-- End Top Search Books Widget -->
    <!-- Start Sales Indicator Widget -->
    <div class="sales-indicator p-20 bg-white rad-10 ChartBox3">
     <div class="between-flex align-center mb-20">
      <h2 class="m-0">Sales Indicator</h2>
      <button type="button" onclick="downloadImage('ChartBox3')" class="btn-shape bg-main c-white">Export <i
        class="fas fa-download"></i></button>
     </div>
     <div class="chart">
      <canvas id="chart3"></canvas>
     </div>
    </div>
    <!-- End Sales Indicator Widget -->
    <!-- Start Latest Uploads Widget -->
    <div class="latest-uploads p-20 bg-white rad-10">
     <h2 class="mt-0 mb-20">Latest Uploads</h2>
     <ul class="m-0">
      <?php 
        $select_latest_upload = $conn->prepare("SELECT b.*, u.name as author_name FROM books b JOIN users u ON b.author = u.id ORDER BY id DESC LIMIT 5");
        $select_latest_upload->execute([]);
        while ($row = $select_latest_upload->fetch(PDO::FETCH_OBJ)){?>
      <li class="between-flex pb-10 mb-10">
       <div class="d-flex align-center">
        <a href="book.php?id=<?= $row->id ?>">
         <img class="mr-10" src="../image/books/<?= $row->image ?>" alt="book<?= $row->id ?>-image" />
        </a>
        <div>
         <span class="d-block"><?= $row->name ?></span>
         <a href="profile.php?id=<?= $row->author ?>" pan class="fs-15 c-grey"><?= $row->author_name ?></a>
        </div>
       </div>
       <div class="bg-eee btn-shape fs-13"><?= $row->price ?> EGP</div>
      </li>
      <?php } ?>
     </ul>
     <img class="launch-icon hide-mobile" src="imgs/project.png" alt="upload-image" />
    </div>
    <!-- End Latest Uploads Widget -->
    <!-- Start Latest Post Widget -->
    <div class="latest-post p-20 bg-white rad-10 p-relative">
     <h2 class="mt-0 mb-25">Latest Message</h2>
     <?php 
      $select_last_message = $conn->prepare("SELECT * FROM messages ORDER BY id DESC LIMIT 1");
      $select_last_message->execute([]);
      if($select_last_message->rowCount() > 0){
      $fetch_last_message = $select_last_message->fetch(PDO::FETCH_OBJ);
     ?>
     <div class="top d-flex align-center">
      <img class="avatar mr-15" src="imgs/avatar.png" alt="" />
      <div class="info w-full between-flex f-wrap">
       <div>
        <span class="d-block mb-5 fw-bold"><?= $fetch_last_message->name ?></span>
        <a href="mailto:<?= $fetch_last_message->email ?>" class="c-grey"><?= $fetch_last_message->email ?></a>
       </div>
       <span class="c-grey"><?= $fetch_last_message->date ?></span>
      </div>
     </div>
     <div class="post-content txt-c-mobile pt-20 pb-20 mt-20 mb-20"><?= $fetch_last_message->content ?></div>
     <div class="post-stats between-flex c-grey">
      <div>
       <i class="fa-regular fa-heart"></i>
       <span>1.8K</span>
      </div>
      <div>
       <i class="fa-regular fa-comments"></i>
       <span>500</span>
      </div>
     </div>
     <?php }else{ echo "<p class='empty'>There Is No Message Found For You!</p>"; }?>
    </div>
    <!-- End Latest Post Widget -->
    <!-- Start Quick Draft Widget -->
    <div class="quick-draft p-20 bg-white rad-10">
     <h2 class="mt-0 mb-10">Quick Draft</h2>
     <p class="mt-0 mb-20 c-grey fs-15">Write A Draft For Your Ideas</p>
     <form method="POST">
      <input class="d-block mb-20 w-full p-10 b-none bg-eee rad-6" type="text" name="title" placeholder="Title"
       required />
      <textarea class="d-block mb-20 w-full p-10 b-none bg-eee rad-6" name="content" placeholder="Your Thought"
       required></textarea>
      <input class="save d-block fs-14 bg-main c-white b-none w-fit btn-shape" name="save_idea" type="submit"
       value="Save" />
     </form>
    </div>
    <!-- End Quick Draft Widget -->
    <!-- Start Reminders Widget -->
    <div class="reminders p-20 bg-white rad-10 p-relative">
     <h2 class="mt-0 mb-25">Reminders</h2>
     <ul class="m-0">
      <?php
      $select_remiders = $conn->prepare("SELECT * FROM ideas WHERE e_id = ? ORDER BY id DESC LIMIT 5");
      $select_remiders->execute([$admin_id]);
      if($select_remiders->rowCount() > 0){
        while($row = $select_remiders->fetch(PDO::FETCH_OBJ)){?>
      <li class="d-flex align-center mt-15 ">
       <span class="key mr-15 d-block rad-half"></span>
       <div class="pl-15 ">
        <p class="fs-15 txt-upper  fw-bold mt-0 mb-5"><?= $row->title ?></p>
        <p class="c-dark txt-capitalize  fs-15 "><?= $row->content ?></p>
        <span class="fs-13 c-grey"><?= $row->date ?></span>
       </div>
      </li>
      <?php  } }else{ echo "<p class='empty'>There Is No Plans For You Yet</p>"; } ?>
     </ul>
    </div>
    <!-- End Reminders Widget -->
    <!-- Start Social Media Stats Widget -->
    <div class="social-media p-20 bg-white rad-10 p-relative">
     <h2 class="mt-0 mb-25">Social Media Stats</h2>
     <div class="box instagram p-15 p-relative mb-10 between-flex">
      <i class="fa-brands fa-instagram fa-2x c-white h-full center-flex"></i>
      <span>90K Followers</span>
      <a class="fs-13 c-white btn-shape" href="#">Follow</a>
     </div>
     <div class="box facebook p-15 p-relative mb-10 between-flex">
      <i class="fa-brands fa-facebook-f fa-2x c-white h-full center-flex"></i>
      <span>2M Like</span>
      <a class="fs-13 c-white btn-shape" href="#">Like</a>
     </div>
     <div class="box youtube p-15 p-relative mb-10 between-flex">
      <i class="fa-brands fa-youtube fa-2x c-white h-full center-flex"></i>
      <span>1M Subs</span>
      <a class="fs-13 c-white btn-shape" href="#">Subscribe</a>
     </div>
     <div class="box tiktok p-15 p-relative mb-10 between-flex">
      <i class="fa-brands fa-tiktok fa-2x c-white h-full center-flex"></i>
      <span>70K Followers</span>
      <a class="fs-13 c-white btn-shape" href="#">Follow</a>
     </div>
    </div>
    <!-- Start End Media Stats Widget -->
    <!-- Start Calendar Widget -->
    <div class="calendar-box p-20 bg-white rad-10 p-relative">
     <h2 class="mt-0 mb-25">Calendar</h2>
     <div class="calendar"></div>
    </div>
    <!-- End Calendar Widget -->
   </div>
   <!-- Start Orders Table -->
   <div class="p-20 bg-white rad-10 m-20 mb-20">
    <h2 class="mt-0 mb-20">Latest Orders</h2>
    <div class="responsive-table">
     <table class="fs-15 w-full">
      <thead>
       <tr>
        <td>ID</td>
        <td>Client</td>
        <td>Status</td>
        <td>Total</td>
        <td>Payment Method</td>
        <td>Date</td>
       </tr>
      </thead>
      <tbody>
       <?php 
         $orders = $conn->prepare("SELECT o.*, u.name, od.payment_method FROM orders o JOIN users u ON o.u_id = u.id JOIN order_details od ON o.id = od.o_id ORDER BY date DESC LIMIT 5");
         $orders->execute([]);
         if($orders->rowCount() > 0){ 
          while($order = $orders->fetch(PDO::FETCH_OBJ)){
            $select_last_status = $conn->prepare("SELECT * FROM order_status WHERE o_id = ? ORDER BY s_id DESC LIMIT 1");
            $select_last_status->execute([$order->id]);
            $last_status = $select_last_status->fetch(PDO::FETCH_OBJ);
        ?>
       <tr>
        <td><a href="order.php?id=<?= $order->id ?>">#<?= $order->id ?></a></td>
        <td><a href="profile.php?id=<?= $order->u_id ?>"><?= $order->name ?></a></td>
        <td><span class="label btn-shape <?= $last_status->status ?> c-white"><?= $last_status->status ?></span></td>
        <td><?= $order->total ?> EGP</td>
        <td><?= $order->payment_method == 0 ? "Cash On Delivery" : "Credit Card"  ?></td>
        <td><?= $order->date ?></td>
       </tr>
       <?php }
        }else{
         echo '<tr><td colspan="6" class="txt-c">No Orders Found!</td></tr>';
        }
        ?>
      </tbody>
     </table>
    </div>
   </div>
   <!-- End Orders Table -->
  </div>
 </div>


 <!-- START JAVASCRIPT -->
 <!-- CHART JS -->
 <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
 <script>
 const ctx1 = document.getElementById('chart1');
 new Chart(ctx1, {
  type: 'bar',
  data: {
   labels: [<?= substr($sold_books_name, 0, -1) ?>],
   datasets: [{
    label: '# of Book',
    data: [<?= substr($sold_books_qty, 0, -1) ?>],
    backgroundColor: '#55c28269',
    borderWidth: 1
   }]
  },
  options: {
   scales: {
    y: {
     beginAtZero: true
    }
   }
  }
 });
 const ctx2 = document.getElementById('chart2');
 new Chart(ctx2, {
  type: 'polarArea',
  data: {
   labels: [<?= substr($order_status_status, 0, -1) ?>],
   datasets: [{
    label: '# of Book',
    data: [<?= substr($order_status_count, 0, -1) ?>],
    backgroundColor: [
     '#55c28269',
     '#f59f0b83',
     '#e24c4c6b'
    ],
    borderWidth: 1
   }]
  },
  options: {
   scales: {
    y: {
     beginAtZero: true
    }
   }
  }
 });
 const ctx3 = document.getElementById('chart3');
 new Chart(ctx3, {
  type: 'line',
  data: {
   labels: [<?= substr($sales_indicator_month, 0, -1) ?>],
   datasets: [{
    label: '# of EGP',
    data: [<?= substr($sales_indicator_total, 0, -1) ?>],
    backgroundColor: '#3499d84b',
    borderWidth: 1
   }]
  },
  options: {
   scales: {
    y: {
     beginAtZero: true
    }
   }
  }
 });
 </script>

 <!-- download image -->
 <script src="js/dom-to-image.js"></script>
 <script>
 function downloadImage(box) {
  connt = document.getElementsByClassName(box)[0];
  domtoimage.toJpeg(connt).then((data) => {
   var link = document.createElement("a");
   link.download = "chart.jpeg";
   link.href = data;
   link.click();
  });
 }
 </script>
 <script src="js/script.js"></script>
 <script src="js/modal.js"></script>
 <script src="js/validation.js"></script>
 <script src="js/jquery.js"></script>
 <script src="js/calendar.js"></script>
 <!-- END JAVASCRIPT -->

</body>

</html>