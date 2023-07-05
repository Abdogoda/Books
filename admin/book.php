<?php
include '../includes/connection.php';
$admin_id = '';
if(isset($_COOKIE['admin_id'])){
  $admin_id = $_COOKIE['admin_id'];
}else{
  header('location: ../login.php');
}

// Edit Book Function
if(isset($_POST['edit_book'])){
 $o_id = filter_var($_POST['o_id'], FILTER_SANITIZE_STRING);
 $o_select = $conn->prepare("SELECT * FROM books WHERE id = ? ");
 $o_select->execute([$o_id]);
 if($o_select->rowCount() > 0){
   $o_fetch_book = $o_select->fetch(PDO::FETCH_OBJ);
   //update book name
   $o_name = filter_var($_POST['o_name'], FILTER_SANITIZE_STRING);
   if(!empty($o_name) && $o_name != $o_fetch_book->name){
     $update_book_name = $conn->prepare("UPDATE books SET name = ? WHERE id = ?");
     $update_book_name->execute([$o_name, $o_id]);
     $success_msg[] = 'Book Name Updated Successfully!';
   }
   //update book description
   $o_description = filter_var($_POST['o_description'], FILTER_SANITIZE_STRING);
   if(!empty($o_description) && $o_description != $o_fetch_book->description){
      $update_book_description = $conn->prepare("UPDATE books SET description = ? WHERE id = ?");
      $update_book_description->execute([$o_description, $o_id]);
      $success_msg[] = 'Book Description Updated Successfully!';
     }
     //update book price
     $o_price = filter_var($_POST['o_price'], FILTER_SANITIZE_STRING);
     if(!empty($o_price) && $o_price != $o_fetch_book->price){
       $update_book_price = $conn->prepare("UPDATE books SET price = ? WHERE id = ?");
       $update_book_price->execute([$o_price, $o_id]);
       $success_msg[] = 'Book Price Updated Successfully!';
     }
     //update book category
     $o_category = filter_var($_POST['o_category'], FILTER_SANITIZE_STRING);
    if(!empty($o_category) && $o_category != $o_fetch_book->category_id){
      $update_book_category = $conn->prepare("UPDATE books SET category_id = ? WHERE id = ?");
      $update_book_category->execute([$o_category, $o_id]);
      $success_msg[] = 'Book Category Updated Successfully!';
     }
     //update book image
     $o_image= $_FILES['o_image']['name'];
     $o_ext = pathinfo($o_image, PATHINFO_EXTENSION);
     $o_rename = generate_unique_id().'.'.$o_ext;
     $o_image_tmp_name = $_FILES['o_image']['tmp_name'];
     $o_image_tmp_folder = '../image/books/'.$o_rename;
     if(!empty($o_image)){
         if($o_image != $o_fetch_book->image){
           $update_book_image = $conn->prepare("UPDATE books SET image = ? WHERE id = ?");
           $update_book_image->execute([$o_rename, $o_id]);
           move_uploaded_file($o_image_tmp_name, $o_image_tmp_folder);
           unlink('../image/books/'.$o_fetch_book->image);
           $success_msg[] = "Book Image updated successfully!";
         }else{
           $warning_msg[] = 'Image Already Exists With This Name!';
         }
     }
   }else{
   $warning_msg[] = 'Book Not Exists!';
 }
}
// Delete Book Function
if(isset($_POST['delete_book'])){
 $id_to_delete = filter_var($_POST['id_to_delete'], FILTER_SANITIZE_STRING);
 $select = $conn->prepare("SELECT * FROM books WHERE id = ?");
 $select->execute([$id_to_delete]);
 if($select->rowCount() > 0){
   $insert = $conn->prepare("DELETE FROM books WHERE id = ?");
   $insert->execute([$id_to_delete]);
   header("location: books.php");
 }else{
   $warning_msg[] = 'Book Not Exists!';
 }
}
if(isset($_GET['id'])){
  $select_book = $conn->prepare("SELECT books.*,category.title FROM books JOIN category ON books.category_id = category.id WHERE books.id = ?");
  $select_book->execute([$_GET['id']]);
  if($select_book->rowCount() > 0){
   $book = $select_book->fetch(PDO::FETCH_OBJ);
  }else{
   header('location: books.php');
  }
 }else{
  header('location: books.php');
 }

?>
<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8" />
 <meta http-equiv="X-UA-Compatible" content="IE=edge" />
 <meta name="viewport" content="width=device-width, initial-scale=1.0" />
 <title>Book Details</title>
 <link rel="shortcut icon" href="../image/icon.svg" type="image/x-icon">
 <link rel="stylesheet" href="css/all.min.css" />
 <link rel="stylesheet" href="css/framework.css" />
 <link rel="stylesheet" href="css/alert.css">
 <link rel="stylesheet" href="css/master.css" />
 <link rel="stylesheet" href="css/modal.css">
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
   <h1 class="p-relative">Book (<?= $book->id?>)</h1>
   <div class=" d-flex m-20 mb-20 gap-20">
    <!-- start book section -->
    <div class="book-container d-flex align-center">
     <div class="book-image"><img src="../image/books/<?= $book->image?>" alt="<?= $book->name?> image"></div>
     <div class="book_info">
      <h2><?= $book->name?></h2>
      <p><b>Category: </b><?= $book->title ?></p>
      <p><b>Price: </b><?= $book->price?> EGP</p>
      <p class="c-grey"><?= $book->description?></p>
      <div class="info d-flex gap-10 mt-10 pt-10">
       <button data-modal="editBookModal" class="btn-shape bg-main c-white fs-15 rad-6 d-block w-fit open-modal"
        data-id="<?=$book->id?>" data-name="<?=$book->name?>" data-category-id="<?=$book->category_id?>"
        data-category-title="<?=$book->title?>" data-price="<?=$book->price?>" data-image="<?=$book->image?>"
        data-description="<?=$book->description?>">
        Edit <i class="fa fa-edit"></i>
       </button>
       <button data-modal="confirmDeleteBookModal" class="btn-shape bg-red c-white fs-15 rad-6 d-block w-fit open-modal"
        data-id="<?=$book->id?>" data-name="<?=$book->name?>">
        Delete <i class="fa fa-trash"></i>
       </button>
      </div>
     </div>
    </div>
    <!-- end book section -->
   </div>
   <!-- start books section -->
   <?php
      $select_books = $conn->prepare("SELECT books.*,category.title FROM books JOIN category ON books.category_id = category.id WHERE books.category_id = ? ORDER BY date DESC ");
      $select_books->execute([$book->category_id]);
      if($select_books->rowCount() > 0){?>
   <div class="files-page m-20 mt-20 mb-20 gap-20">
    <h2 class="mt-20 mb-10">Similler Books</h2>
    <div class="files-content ">
     <!-- start books grid  -->
     <div class="books-grid d-grid gap-20 mb-20">
      <?php while($book = $select_books->fetch(PDO::FETCH_OBJ)){?>
      <div class="book-box file bg-white p-10 rad-10" data-bookcategory="<?= $book->category_id ?>">
       <a href="book.php?id=<?= $book->id ?>" class="d-block icon txt-c">
        <img class="mt-15 mb-15" src="../image/books/<?= $book->image ?>" alt="book-image" />
       </a>
       <div class="txt-c mb-10 fs-14"><?= $book->name ?></div>
       <p class="c-grey fs-13"><?= $book->title ?></p>
       <div class="info between-flex mt-10 pt-10 fs-13 c-grey">
        <span>20/06/2020</span>
        <span><?= $book->price ?> EGP</span>
       </div>
       <div class="info between-flex mt-10 pt-10 fs-13 c-grey">
        <button data-modal="editBookModal" data-id="<?=$book->id?>" data-name="<?=$book->name?>"
         data-category-id="<?=$book->category_id?>" data-category-title="<?=$book->title?>"
         data-price="<?=$book->price?>" data-image="<?=$book->image?>" data-description="<?=$book->description?>"
         class="btn-shape bg-main c-white fs-13 rad-6 d-block w-fit open-modal">
         Edit <i class="fa fa-edit"></i>
        </button>
        <button data-modal="confirmDeleteBookModal" data-id="<?=$book->id?>" data-name="<?=$book->name?>"
         class="btn-shape bg-red c-white fs-13 rad-6 d-block w-fit open-modal">
         Delete <i class="fa fa-trash"></i>
        </button>
       </div>
      </div>
      <?php } ?>
     </div>
     <!-- end books grid  -->
    </div>
   </div>
   <?php } ?>
   <!-- end books section -->
  </div>
 </div>
 <!-- ################### START MODAL ################### -->
 <!-- Edit Book Modal -->
 <div id="editBookModal" class="modal">
  <div class="modal-content">
   <h2>Edit Book</h2>
   <form id="editBookForm" action="" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="o_id" require id="o_id" value="">
    <div class="input-group">
     <label for="o_name">Book Name</label> <input type="text" name="o_name" id="o_name" required value="" />
    </div>
    <div class="input-group">
     <label for="o_description">Book Desciption</label>
     <textarea name="o_description" id="o_description"></textarea>
    </div>
    <div class="input-group">
     <label for="o_category">Book Category</label>
     <select name="o_category" id="o_category">
      <option value="0" disabled>Select Book Category</option>
      <?php 
        $all_categories = $conn->prepare("SELECT * FROM category ");
        $all_categories->execute([]);
        if($all_categories->rowCount() > 0){
          while($row = $all_categories->fetch(PDO::FETCH_OBJ)){
            echo "<option value='$row->id'>$row->title</option>";
          }
        }
      ?>
     </select>
    </div>
    <div class="input-group">
     <label for="o_price">Book Price</label>
     <input type="text" name="o_price" id="o_price" required />
    </div>
    <img src="" alt="book_image" id="o_img_src">
    <div class="input-group">
     <label for="o_image">Book Image</label>
     <input type="file" name="o_image" id="o_image" accept=".png, .gif, .jpeg, .jpg" />
    </div>
    <div class="btns">
     <button type="submit" class="btn" name="edit_book">Edit Book</button>
     <button type="button" class="btn btn-danger close-modal">Cancel</button>
    </div>
   </form>
  </div>
 </div>
 <!-- Confirm Delete Book Modal -->
 <div id="confirmDeleteBookModal" class="modal">
  <div class="modal-content">
   <h2>Delete <span id="book_name"></span></h2>
   <form id="confirmDeleteBookForm" action="" method="post">
    <input type="hidden" name="id_to_delete" id="id_to_delete">
    <div class="input-group">
     <h3>Are You Sure You Want To Delete This Book?</h3>
    </div>
    <div class="btns">
     <input type="submit" class="btn" name="delete_book" value="Delete" />
     <button type="button" class="btn btn-danger close-modal">Cancel</button>
    </div>
   </form>
  </div>
 </div>
 <!-- END MODAL -->

 <!-- START JAVASCRIPT -->
 <script src="js/script.js"></script>
 <script src="js/modal.js"></script>
 <script src="js/validation.js"></script>
 <!-- END JAVASCRIPT -->

</body>

</html>