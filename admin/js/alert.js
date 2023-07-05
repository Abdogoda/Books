const removeToast = (toast) => {
 toast.classList.add("hide");
 if (toast.timeoutId) clearTimeout(toast.timeoutId);
 setTimeout(() => toast.remove(), 500);
};

const createToast = (type, message) => {
 if (type != "" && message != "") {
  const toasts = [
   { type: "success", icon: "fa-circle-check" },
   { type: "warning", icon: "fa-circle-xmark" },
   { type: "info", icon: "fa-triangle-exclamation" },
   { type: "error", icon: "fa-circle-info" },
  ];
  const matchedToast = toasts.find((toast) => toast.type.includes(type));
  if (matchedToast) {
   const toast = document.createElement("li");
   toast.className = `toast ${type}`;
   toast.innerHTML += `
    <div class="column">
     <i class="fa-solid ${matchedToast.icon}"></i>
     <div class="text"><span>${type}</span> <p>${message}.</p></div>
    </div>
    <i class="fa-solid fa-xmark" onclick="removeToast(this.parentElement)"></i>
  `;
   document.querySelector(".notifications").appendChild(toast);
   toast.timeoutId = setTimeout(() => removeToast(toast), 5000);
  }
 }
};
