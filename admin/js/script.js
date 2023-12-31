// toggle sidebar
const sidebarShowBtn = document.querySelector(".sidebarShowBtn");
const sidebarHideBtn = document.querySelector(".sidebarHideBtn");
const sidebar = document.querySelector(".sidebar");
sidebarShowBtn.addEventListener("click", () => {
 sidebar.classList.add("show");
});
sidebarHideBtn.addEventListener("click", () => {
 sidebar.classList.remove("show");
});

//toggle theme
const themeSwitch = document.getElementById("themeSwitch");
function toggleTheme() {
 var theme = localStorage.getItem("theme");
 if (theme == "dark") {
  document.documentElement.setAttribute("data-theme", "dark");
  themeSwitch.setAttribute("checked", "checked");
 } else {
  document.documentElement.removeAttribute("data-theme");
  themeSwitch.removeAttribute("checked");
 }
}
toggleTheme();
themeSwitch.addEventListener("change", function (event) {
 if (event.target.checked) {
  localStorage.setItem("theme", "dark");
 } else {
  localStorage.setItem("theme", "light");
 }
 toggleTheme();
});

// toggle ful screen
var fullscreenButton = document.getElementById("fullScreen");
var isFullscreen = false;
fullscreenButton.addEventListener("click", function () {
 if (isFullscreen) {
  exitFullscreen();
 } else {
  enterFullscreen();
 }
});
function enterFullscreen() {
 var elem = document.documentElement;
 if (elem.requestFullscreen) {
  elem.requestFullscreen();
 } else if (elem.mozRequestFullScreen) {
  elem.mozRequestFullScreen();
 } else if (elem.webkitRequestFullscreen) {
  elem.webkitRequestFullscreen();
 } else if (elem.msRequestFullscreen) {
  elem.msRequestFullscreen();
 }
 isFullscreen = true;
}
function exitFullscreen() {
 if (document.exitFullscreen) {
  document.exitFullscreen();
 } else if (document.mozCancelFullScreen) {
  document.mozCancelFullScreen();
 } else if (document.webkitExitFullscreen) {
  document.webkitExitFullscreen();
 } else if (document.msExitFullscreen) {
  document.msExitFullscreen();
 }
 isFullscreen = false;
}

//filter book categories
const categoryBoxs = document.querySelectorAll(".category-box");
const categoryDetails = document.querySelector(".category-details");
const books = document.querySelectorAll(".book-box");
const categoryDetailsTitle = document.querySelector(
 ".category-details .category-title"
);
const categoryDetailsBooks = document.querySelector(
 ".category-details .category-books"
);
const openEditCategoryModal = document.querySelector(
 ".category-details .open-edit-category-modal"
);
const openDeleteCategoryModal = document.querySelector(
 ".category-details .open-delete-category-modal"
);
if (categoryBoxs && categoryDetails && books) {
 categoryBoxs.forEach((categoryBox) => {
  categoryBox.addEventListener("click", () => {
   // close the opening category
   if (categoryBox.classList.contains("active")) {
    categoryBoxs.forEach((c) => c.classList.remove("active"));
    books.forEach((b) => (b.style.display = "block"));
    categoryDetails.classList.remove("show");
    // open new category
   } else {
    // toggle category details and category box
    categoryDetails.classList.add("show");
    categoryBoxs.forEach((c) => c.classList.remove("active"));
    categoryBox.classList.add("active");
    // set category details data
    categoryDetailsTitle.innerHTML =
     categoryBox.querySelector(".categoryBoxTitle").innerHTML;
    categoryDetailsBooks.innerHTML =
     categoryBox.querySelector(".categoryBoxBooks").innerHTML;
    openEditCategoryModal.dataset.id = categoryBox.dataset.category;
    openEditCategoryModal.dataset.title =
     categoryBox.querySelector(".categoryBoxTitle").innerHTML;
    openDeleteCategoryModal.dataset.id = categoryBox.dataset.category;
    openDeleteCategoryModal.dataset.title =
     categoryBox.querySelector(".categoryBoxTitle").innerHTML;
    // set books in grid
    books.forEach((book) => {
     if (book.dataset.bookcategory == categoryBox.dataset.category) {
      book.style.display = "block";
     } else {
      book.style.display = "none";
     }
    });
   }
  });
 });
 // close the category details
 document
  .querySelector(".closeCategoryDetails")
  .addEventListener("click", () => {
   categoryDetails.classList.remove("show");
   categoryBoxs.forEach((c) => c.classList.remove("active"));
   books.forEach((b) => (b.style.display = "block"));
  });
}

//collapse category section
const showHideCategory = document.querySelector(".show-hide-ctegory");
const categoryContent = document.querySelector(".categories-conn");
if (showHideCategory && categoryContent) {
 showHideCategory.addEventListener("click", function () {
  if (showHideCategory.classList.contains("fa-angle-up")) {
   showHideCategory.classList.remove("fa-angle-up");
  } else {
   showHideCategory.classList.add("fa-angle-up");
  }
  if (window.matchMedia("(min-width: 786px)").matches) {
   categoryContent.classList.toggle("hide");
  } else {
   categoryContent.classList.toggle("show");
  }
 });
}
