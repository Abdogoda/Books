var modalsBtn = document.querySelectorAll(".open-modal");
modalsBtn.forEach((modalBtn) => {
 modalBtn.addEventListener("click", () => {
  let modal = document.getElementById(`${modalBtn.dataset.modal}`);
  let closeModal = modal.querySelector(".close-modal");
  modal.style.display = "block";
  closeModal.onclick = function () {
   modal.style.display = "none";
  };
  window.onclick = function (event) {
   if (event.target == modal) {
    modal.style.display = "none";
   }
  };
  //Edit Book Modal
  if (
   modal.id == "editBookModal" &&
   modalBtn.dataset.modal == "editBookModal"
  ) {
   modal.querySelector("#o_id").value = modalBtn.getAttribute("data-id");
   modal.querySelector("#o_name").value = modalBtn.getAttribute("data-name");
   modal.querySelector("#o_price").value = modalBtn.getAttribute("data-price");
   modal.querySelector("#o_img_src").src =
    "../image/books/" + modalBtn.getAttribute("data-image");
   modal.querySelector("#o_description").textContent =
    modalBtn.getAttribute("data-description");
   modal.querySelector(
    "#o_category"
   ).innerHTML += `<option value="${modalBtn.getAttribute(
    "data-category-id"
   )}" selected>${modalBtn.getAttribute("data-category-title")}</option>`;
  }
  //Confirm Delete Book Modal
  if (
   modal.id == "confirmDeleteBookModal" &&
   modalBtn.dataset.modal == "confirmDeleteBookModal"
  ) {
   modal.querySelector("#id_to_delete").value =
    modalBtn.getAttribute("data-id");
   modal.querySelector("#book_name").innerHTML =
    modalBtn.getAttribute("data-name");
  }
  //Update Category Modal
  if (
   modal.id == "editCategoryModal" &&
   modalBtn.dataset.modal == "editCategoryModal"
  ) {
   modal.querySelector("#category_id_to_edit").value =
    modalBtn.getAttribute("data-id");
   modal.querySelector("#category_title").value =
    modalBtn.getAttribute("data-title");
  }
  //Confirm Delete Category Modal
  if (
   modal.id == "confirmDeleteCategoryModal" &&
   modalBtn.dataset.modal == "confirmDeleteCategoryModal"
  ) {
   modal.querySelector("#category_id_to_delete").value =
    modalBtn.getAttribute("data-id");
   modal.querySelector("#category_name").innerHTML =
    modalBtn.getAttribute("data-title");
  }
  //Edit Permission Modal
  if (
   modal.id == "editPermissionModal" &&
   modalBtn.dataset.modal == "editPermissionModal"
  ) {
   modal.querySelector("#edit_name").value =
    modalBtn.getAttribute("data-title");
   modal.querySelector("#edit_id").value = modalBtn.getAttribute("data-id");
  }
 });
});
