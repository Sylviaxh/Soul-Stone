const navWrap = document.querySelector(".nav-wrap");
const menuButton = document.querySelector(".menu-button");
const toast = document.querySelector(".toast");

if (menuButton && navWrap) {
  menuButton.addEventListener("click", () => {
    const open = navWrap.classList.toggle("open");
    menuButton.setAttribute("aria-expanded", String(open));
  });
}

function showToast(message) {
  if (!toast) return;
  toast.textContent = message;
  toast.classList.add("show");
  window.setTimeout(() => toast.classList.remove("show"), 2400);
}

document.querySelectorAll("[data-toast]").forEach((item) => {
  item.addEventListener("click", () => showToast(item.dataset.toast));
});

document.querySelectorAll("form[data-form]").forEach((form) => {
  form.addEventListener("submit", (event) => {
    event.preventDefault();
    form.reset();
    showToast(form.dataset.form);
  });
});
