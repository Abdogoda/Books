let searchForm = document.querySelector(".search-form");
const header2 = document.querySelector(".header .header-2");
window.onscroll = () => {
 searchForm.classList.remove("active");

 if (header2) {
  if (window.scrollY > 80) {
   header2.classList.add("active");
  } else {
   header2.classList.remove("active");
  }

  window.onload = () => {
   if (window.scrollY > 80) {
    header2.classList.add("active");
   } else {
    header2.classList.remove("active");
   }
  };
 }
};

//filter book categories
const categoryBoxs = document.querySelectorAll(".category-wrapper li");
const books = document.querySelectorAll(".book-box");
if (categoryBoxs && books) {
 categoryBoxs.forEach((categoryBox) => {
  categoryBox.addEventListener("click", () => {
   // close the opening category
   if (categoryBox.classList.contains("active")) {
    categoryBoxs.forEach((c) => c.classList.remove("active"));
    books.forEach((b) => (b.style.display = "block"));
   } else {
    categoryBoxs.forEach((c) => c.classList.remove("active"));
    categoryBox.classList.add("active");
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
}

//collapse category section
const showHideCategory = document.querySelector(".show-hide-ctegory");
const categoryContent = document.querySelector(".category-wrapper ul");
if (showHideCategory && categoryContent) {
 showHideCategory.addEventListener("click", function () {
  console.log("clicked");
  if (showHideCategory.querySelector("i").classList.contains("fa-angle-up")) {
   showHideCategory.querySelector("i").classList.remove("fa-angle-up");
  } else {
   showHideCategory.querySelector("i").classList.add("fa-angle-up");
  }
  categoryContent.classList.toggle("show");
 });
}
