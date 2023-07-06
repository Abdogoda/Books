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
if (!in_array('5', array_column($employee_permissions, 'permission_id'))) header("location: index.php");



// Add Book Function
if(isset($_POST['add_book'])){
  $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
  $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
  $price = filter_var($_POST['price'], FILTER_SANITIZE_STRING);
  $category = filter_var($_POST['category'], FILTER_SANITIZE_STRING);
  $image= $_FILES['image']['name'];
  $ext = pathinfo($image, PATHINFO_EXTENSION);
  $rename = generate_unique_id().'.'.$ext;
  $image_tmp_name = $_FILES['image']['tmp_name'];
  $image_tmp_folder = '../image/books/'.$rename;
  $select = $conn->prepare("SELECT * FROM books WHERE name = ? AND category_id = ? AND author = ?");
  $select->execute([$name, $category, $admin_id]);
  if($select->rowCount() > 0){
    $warning_msg[] = 'Book Already Exists!';
  }else{
    $insert = $conn->prepare("INSERT INTO books (name, image, price, category_id, description, author) VALUES (?, ?, ?, ?, ?, ?)");
    $insert->execute([$name, $rename, $price, $category, $description, $admin_id]);
    move_uploaded_file($image_tmp_name,$image_tmp_folder);
    $success_msg[] = 'You Have Added Book Successfully';
  }
}
// Edit Book Function
if(isset($_POST['edit_book'])){
  $o_id = $_POST['o_id'];
  $o_select = $conn->prepare("SELECT * FROM books WHERE id = ?");
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
       $update_book_category->execute([$o_category, $o_id,]);
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
// Add Category Function
if(isset($_POST['add_category'])){
  $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
  $select = $conn->prepare("SELECT * FROM category WHERE title = ?");
  $select->execute([$title]);
  if($select->rowCount() > 0){
    $warning_msg[] = 'Category Already Exists!';
  }else{
    $insert = $conn->prepare("INSERT INTO category (title) VALUES (?)");
    $insert->execute([$title]);
    $success_msg[] = 'New Category Have Been Added Successfully!';
  }
}
// Delete Book Function
if(isset($_POST['delete_book'])){
  $id_to_delete = filter_var($_POST['id_to_delete'], FILTER_SANITIZE_STRING);
  $select = $conn->prepare("SELECT * FROM books WHERE id = ? AND author = ?");
  $select->execute([$id_to_delete, $admin_id]);
  if($select->rowCount() > 0){
    $insert = $conn->prepare("DELETE FROM books WHERE id = ? AND author = ?");
    $insert->execute([$id_to_delete, $admin_id]);
    $success_msg[] = 'Book Have Been Deleted Successfully!';
  }else{
    $warning_msg[] = 'Book Not Exists!';
  }
}
// Edit Category Function
if(isset($_POST['edit_category'])){
  $category_title = filter_var($_POST['category_title'], FILTER_SANITIZE_STRING);
  $category_id_to_edit = filter_var($_POST['category_id_to_edit'], FILTER_SANITIZE_STRING);
  $select = $conn->prepare("SELECT * FROM category WHERE id = ?");
  $select->execute([$category_id_to_edit]);
  if($select->rowCount() > 0){
    $select2 = $conn->prepare("SELECT * FROM category WHERE title = ?");
    $select2->execute([$category_title]);
    if($select2->rowCount() > 0){
      $warning_msg[] = 'Category Title Already Taken!';
    }else{
      $insert = $conn->prepare("UPDATE category SET title = ?  WHERE id = ?");
      $insert->execute([$category_title, $category_id_to_edit]);
      $success_msg[] = 'Category Title Have Been Update Successfully!';
    }
  }else{
    $warning_msg[] = 'Category Not Exists!';
  }
}
// Delete Category Function
if(isset($_POST['delete_category'])){
  $category_id_to_delete = filter_var($_POST['category_id_to_delete'], FILTER_SANITIZE_STRING);
  $select = $conn->prepare("SELECT * FROM category WHERE id = ?");
  $select->execute([$category_id_to_delete]);
  if($select->rowCount() > 0){
    $delete_books = $conn->prepare("DELETE FROM books WHERE category_id = ?");
    $delete_books->execute([$category_id_to_delete]);
    $delete = $conn->prepare("DELETE FROM category WHERE id = ?");
    $delete->execute([$category_id_to_delete]);
    $success_msg[] = 'Category Have Been Deleted Successfully!';
  }else{
    $warning_msg[] = 'Category Not Exists!';
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
   <div class="between-flex f-wrap">
    <h1 class="p-relative">Books </h1>
    <?php if (in_array('13', array_column($employee_permissions, 'permission_id'))) { ?>
    <button class="btn-shape bg-main c-white fs-20 mr-20 ml-20 open-modal" data-modal="addBookModal">Add Book <i
      class="fa fa-plus"></i></button>
    <?php }?>
   </div>
   <div class="files-page d-flex m-20 mb-20 gap-20">
    <!-- start category section -->
    <div class="files-stats p-20 bg-white rad-10">
     <h3 class="mt-0 mb-15 between-flex">Book Categories <i class="show-hide-ctegory fa-solid fa-angle-down"></i></h3>
     <div class="categories-conn">
      <?php
      $select_categories = $conn->prepare("SELECT c.*, COUNT(b.id) AS count FROM category c LEFT JOIN books b ON b.category_id = c.id GROUP BY c.title ORDER BY count DESC;");
      $select_categories->execute([]);
      if($select_categories->rowCount() > 0){
       while($category = $select_categories->fetch(PDO::FETCH_OBJ)){?>
      <div class="category-box d-flex align-center border-eee p-10 rad-6 mb-15 fs-13"
       data-category="<?= $category->id ?>">
       <i class="fa-solid fa-book fa-lg center-flex icon"></i>
       <div class="info">
        <span class=" categoryBoxTitle"><?= $category->title ?></span>
        <span class="c-grey d-block mt-5 categoryBoxBooks"><?= $category->count ?></span>
       </div>
       <div class="size c-grey"><?= $category->count * 0.1 ?> MB</div>
      </div>
      <?php }
      }else{
       echo '<p class="empty">No Categories Found!</p>';
      }
     ?>
     </div>
     <?php if (in_array('16', array_column($employee_permissions, 'permission_id'))) { ?>
     <button class="upload bg-main c-white fs-13 rad-6 d-block w-fit open-modal" data-modal="addCategoryModal">
      <i class="fa-solid fa-plus mr-10"></i>
      Add Category
     </button>
     <?php }?>
    </div>
    <!-- end category section -->
    <!-- start books section -->
    <div class="files-content ">
     <!-- start category details -->
     <div class="category-details bg-white p-15 rad-10 mb-15">
      <div class="between-flex">
       <h3 class="m-0">Category Details</h3>
       <button class="closeCategoryDetails fs-20"><i class="fa fa-close"></i></button>
      </div>
      <p class="c-grey"><b>Title: </b><span class="c-main category-title">Sports</span></p>
      <p class="c-grey"><b>Books: </b><span class="c-main"><span class="category-books">50</span> Book</span></p>
      <div class="d-flex gap-10">
       <?php if (in_array('17', array_column($employee_permissions, 'permission_id'))) { ?>
       <button data-modal="editCategoryModal" data-id="" data-title=""
        class="btn-shape bg-main c-white fs-13 rad-6 d-block w-fit open-modal open-edit-category-modal">
        Edit Title <i class="fa fa-edit"></i>
       </button>
       <?php }?>
       <?php if (in_array('18', array_column($employee_permissions, 'permission_id'))) { ?>
       <button data-modal="confirmDeleteCategoryModal" data-id="" data-title=""
        class="btn-shape bg-red c-white fs-13 rad-6 d-block w-fit open-modal open-delete-category-modal">
        Delete Category <i class="fa fa-trash"></i>
       </button>
       <?php }?>
      </div>
     </div>
     <!-- end category details -->
     <!-- start books grid  -->
     <div class="books-grid d-grid gap-20">
      <?php
      if(isset($_POST['search_box']) or isset($_POST['search_btn'])){
        $search_box = $_POST['search_box'];
        $select_books = $conn->prepare("SELECT books.*,category.title FROM books JOIN category ON books.category_id = category.id WHERE books.name LIKE '%{$search_box}%' ORDER BY date DESC ");
      }else{
        $select_books = $conn->prepare("SELECT books.*,category.title FROM books JOIN category ON books.category_id = category.id ORDER BY date DESC ");
      }
      $select_books->execute();
      if($select_books->rowCount() > 0){
       while($book = $select_books->fetch(PDO::FETCH_OBJ)){?>
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
        <?php if (in_array('14', array_column($employee_permissions, 'permission_id'))) { ?>
        <button data-modal="editBookModal" data-id="<?=$book->id?>" data-name="<?=$book->name?>"
         data-category-id="<?=$book->category_id?>" data-category-title="<?=$book->title?>"
         data-price="<?=$book->price?>" data-image="<?=$book->image?>" data-description="<?=$book->description?>"
         class="btn-shape bg-main c-white fs-13 rad-6 d-block w-fit open-modal">
         Edit <i class="fa fa-edit"></i>
        </button>
        <?php }?>
        <?php if (in_array('15', array_column($employee_permissions, 'permission_id'))) { ?>
        <button data-modal="confirmDeleteBookModal" data-id="<?=$book->id?>" data-name="<?=$book->name?>"
         class="btn-shape bg-red c-white fs-13 rad-6 d-block w-fit open-modal">
         Delete <i class="fa fa-trash"></i>
        </button>
        <?php }?>
       </div>
      </div>
      <?php }
      }else{?>
      <p class="empty">No Books Found!</p>
      <?php }
     ?>
     </div>
     <!-- end books grid  -->
    </div>
    <!-- end books section -->
   </div>
  </div>
  <!-- END CONTENT -->
 </div>


 <!-- ################### START MODAL ################### -->
 <!-- Add Book Modal -->
 <?php if (in_array('13', array_column($employee_permissions, 'permission_id'))) { ?>
 <div id="addBookModal" class="modal">
  <div class="modal-content">
   <h2>Add New Book</h2>
   <form id="addBookForm" action="" method="POST" enctype="multipart/form-data">
    <div class="input-group">
     <label for="name">Book Name</label> <input type="text" name="name" id="name" required />
    </div>
    <div class="input-group">
     <label for="description">Book Desciption</label>
     <textarea name="description" id="description"></textarea>
    </div>
    <div class="input-group">
     <label for="category">Book Category</label>
     <select name="category" id="category">
      <option value="0" disabled selected>Select Book Category</option>
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
     <label for="price">Book Price</label>
     <input type="text" name="price" id="price" required />
    </div>
    <div class="input-group">
     <label for="image">Book Image</label>
     <input type="file" name="image" id="image" required accept=".png, .gif, .jpeg, .jpg" />
    </div>
    <div class="btns">
     <input type="submit" class="btn" name="add_book" value="Add Book" />
     <button type="button" class="btn btn-danger close-modal">Cancel</button>
    </div>
   </form>
  </div>
 </div>
 <?php }?>
 <!-- Edit Book Modal -->
 <?php if (in_array('14', array_column($employee_permissions, 'permission_id'))) { ?>
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
 <?php }?>
 <!-- Add Category Modal -->
 <?php if (in_array('16', array_column($employee_permissions, 'permission_id'))) { ?>
 <div id="addCategoryModal" class="modal">
  <div class="modal-content">
   <h2>Add New Category</h2>
   <form id="addCategoryForm" action="" method="post">
    <div class="input-group">
     <label for="title">Category Title</label> <input type="text" name="title" id="title" required />
    </div>
    <div class="btns">
     <input type="submit" class="btn" name="add_category" value="Add Category" />
     <button type="button" class="btn btn-danger close-modal">Cancel</button>
    </div>
   </form>
  </div>
 </div>
 <?php }?>
 <!-- Confirm Delete Book Modal -->
 <?php if (in_array('15', array_column($employee_permissions, 'permission_id'))) { ?>
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
 <?php }?>
 <!-- Edit Category Modal -->
 <?php if (in_array('17', array_column($employee_permissions, 'permission_id'))) { ?>
 <div id="editCategoryModal" class="modal">
  <div class="modal-content">
   <h2>Edit Category Title</h2>
   <form id="editCategoryForm" action="" method="post">
    <input type="hidden" name="category_id_to_edit" id="category_id_to_edit">
    <div class="input-group">
     <label for="category_title">Category Title</label> <input type="text" name="category_title" id="category_title"
      required />
    </div>
    <div class="btns">
     <input type="submit" class="btn" name="edit_category" value="Edit Category" />
     <button type="button" class="btn btn-danger close-modal">Cancel</button>
    </div>
   </form>
  </div>
 </div>
 <?php }?>
 <!-- Confirm Delete Category Modal -->
 <?php if (in_array('18', array_column($employee_permissions, 'permission_id'))) { ?>
 <div id="confirmDeleteCategoryModal" class="modal">
  <div class="modal-content">
   <h2>Delete <span id="category_name"></span></h2>
   <form id="confirmDeleteCategoryForm" action="" method="post">
    <input type="hidden" name="category_id_to_delete" id="category_id_to_delete">
    <div class="input-group">
     <h3>Are You Sure You Want To Delete This Category And Its Related Books?</h3>
    </div>
    <div class="btns">
     <input type="submit" class="btn" name="delete_category" value="Delete" />
     <button type="button" class="btn btn-danger close-modal">Cancel</button>
    </div>
   </form>
  </div>
 </div>
 <?php } ?>
 <!-- ################### END MODAL ###################  -->


 <!-- START JAVASCRIPT -->
 <script src="js/script.js"></script>
 <script src="js/modal.js"></script>
 <script src="js/validation.js"></script>
 <!-- END JAVASCRIPT -->

</body>

</html>