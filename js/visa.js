// TOGGLE VISA CONTAIINER
const visaContainer = `<div class="credit-card-con" id="credit-card-con">
						<div class="card-container">
							<div class="front">
								<div class="image">
									<img src="image/chip.png" alt="chip image" /> <img
										src="image/visa.png" alt="visa image" />
								</div>
								<div class="card-number-box">################</div>
								<div class="flexbox">
									<div class="box">
										<span>card holder</span>
										<div class="card-holder-name">full name</div>
									</div>
									<div class="box">
										<span>expires</span>
										<div class="expiration">
											<span class="expiration_date">mm yy</span>
										</div>
									</div>
								</div>
							</div>

							<div class="back">
								<div class="stripe"></div>
								<div class="box">
									<span>cvv</span>
									<div class="cvv-box"></div>
									<img src="image/visa.png" alt="" />
								</div>
							</div>
						</div>
						<div class="form">
							<div class="input-flex-boxes">
								<div class="input-box">
									<label for='card_number'>card number</label> <input type="text" id="card_number" name="card_number" minlength="16" length="16" maxlength="16"
										class="card-number-input" required />
								</div>
								<div class="input-box">
									<label for='card_holder'>card holder</label> <input type="text" name="card_holder" id="card_holder" minlength="3"
										class="card-holder-input"  required/>
								</div>
							</div>
							<div class="input-flex-boxes">
								<div class="input-box">
									<label for="expiration_date">Expiration MM</label> 
									<input type="date" name="expiration_date" id="expiration_date" required/>
								</div>
        <div class="input-box">
								<label for="cvv">CVV</label> <input type="text" minlength="4" length="4" maxlength="4"
        class="cvv-input"  name="card_cvv" id="card_cvv" required/>
        </div>
        </div>
						</div>
					</div>`;
const visa_c = document.getElementById("visa_c");
const paymentMethod = document.getElementsByName("payment_method");
paymentMethod.forEach((method) => {
 method.addEventListener("change", function () {
  if (this.value == "1") {
   visa_c.innerHTML = visaContainer;

   // VISA STYLING
   document.querySelector(".card-number-input").oninput = () => {
    document.querySelector(".card-number-box").innerText =
     document.querySelector(".card-number-input").value;
   };
   document.querySelector(".card-holder-input").oninput = () => {
    document.querySelector(".card-holder-name").innerText =
     document.querySelector(".card-holder-input").value;
   };
   document.getElementById("expiration_date").oninput = () => {
    document.querySelector(".expiration_date").innerText =
     document.getElementById("expiration_date").value;
   };
   document.querySelector(".cvv-input").onmouseenter = () => {
    document.querySelector(".front").style.transform =
     "perspective(1000px) rotateY(-180deg)";
    document.querySelector(".back").style.transform =
     "perspective(1000px) rotateY(0deg)";
   };
   document.querySelector(".cvv-input").onmouseleave = () => {
    document.querySelector(".front").style.transform =
     "perspective(1000px) rotateY(0deg)";
    document.querySelector(".back").style.transform =
     "perspective(1000px) rotateY(180deg)";
   };

   document.querySelector(".cvv-input").oninput = () => {
    document.querySelector(".cvv-box").innerText =
     document.querySelector(".cvv-input").value;
   };
   const card_number = document.querySelector(".card-number-input");
   const card_holder = document.querySelector(".card-holder-input");
   const expiration_date = document.getElementById("expiration_date");
   const card_cvv = document.getElementById("card_cvv");
  } else {
   visa_c.innerHTML = "";
  }
 });
});

// ##################### VALIDATION ############################

function isMoreThanThree(input) {
 return input.value.length >= 3;
}
function isMoreThanSix(input) {
 return input.value.length >= 6;
}
function isSelectOption(option) {
 return option.value != 0;
}
function isValidNumber(input) {
 return !isNaN(parseFloat(input));
}
function isValidImage(image) {
 if (image.files[0].length > 0) {
  const fileType = image.files[0].type;
  const fileSize = image.files[0].size;
  const maxSize = 5 * 1024 * 1024; // 5 MB
  if (!fileType.startsWith("image/")) {
   createToast("warning", "Invalid Image Type!");
   image.classList.add("error");
   return false;
  }
  if (fileSize > maxSize) {
   createToast("warning", "Image Size Must Be Less Than 5MB!");
   image.classList.add("error");
   return false;
  }
 }
 return true;
}
function isValidEmail(input) {
 const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
 return emailRegex.test(input.value);
}
function isValidDate(input) {
 var today = new Date();
 var userInputDate = new Date(input);
 return userInputDate > today;
}

function isValidPhone(number) {
 if (number.value.length !== 11) {
  return false;
 }
 if (!number.value.startsWith("01")) {
  return false;
 }
 if (!/^\d+$/.test(number.value)) {
  return false;
 }
 return true;
}

//===========================
//=== Checkout Validation
//===========================
const checkout_form = document.getElementById("checkout_form");
if (checkout_form) {
 const city = document.getElementById("city");
 const street = document.getElementById("street");
 const payment_method = document.getElementById("pay_with_credit_card");

 checkout_form.addEventListener("submit", function (event) {
  var isValiedCheckout = false;
  document.querySelectorAll(".error").forEach((element) => {
   element.classList.remove("error");
  });
  if (!isMoreThanThree(city)) {
   createToast("warning", "City Name Must Be More Than 3 Characters!");
   city.classList.add("error");
  } else if (!isMoreThanThree(street)) {
   createToast("warning", "Street Name Must Be More Than 3 Characters!");
   street.classList.add("error");
  } else if (payment_method.checked) {
   if (!isValidNumber(card_number.value)) {
    createToast(
     "warning",
     "Card Number Must Be A Number With Length Of 16 Characters!"
    );
    card_number.classList.add("error");
   } else if (!isMoreThanThree(card_holder)) {
    createToast("warning", "Card Holder Name Must Be More Than 3 Characters!");
    card_holder.classList.add("error");
   } else if (!isValidNumber(card_cvv.value)) {
    createToast(
     "warning",
     "Card CVV Must Be A Number With Length Of 4 Characters!"
    );
    card_cvv.classList.add("error");
   } else if (!isValidDate(expiration_date.value)) {
    createToast("warning", "Invalid Expiration Date!");
    expiration_date.classList.add("error");
   } else {
    isValiedCheckout = true;
   }
  } else {
   isValiedCheckout = true;
  }
  if (!isValiedCheckout) {
   event.preventDefault();
  }
 });
}
