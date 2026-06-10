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

const customIntentData = {
  "Transformation": {
    label: "Transformation",
    title: "Release. Renew. Rise.",
    description: "A moonlit bracelet composition for personal growth, courage and new chapters.",
    image: "assets/obsidian-preview.png",
    position: "center",
    alt: "Transformation bracelet preview",
    stones: [
      "Moonstone · New beginnings and intuition",
      "Amethyst · Calm and clarity",
      "Rose Quartz · Self-love and compassion",
      "Obsidian · Protection and grounding"
    ]
  },
  "Self Love": {
    label: "Self Love",
    title: "Soften. Receive. Glow.",
    description: "A warm rose quartz direction for gentleness, feminine energy and heart-led confidence.",
    image: "assets/collection-bracelets.png",
    position: "30% center",
    alt: "Self Love rose quartz bracelet preview",
    stones: [
      "Rose Quartz · Self-love and tenderness",
      "Moonstone · Feminine energy and intuition",
      "Amethyst · Soft calm",
      "Clear Quartz · Heart clarity"
    ]
  },
  "Protection": {
    label: "Protection",
    title: "Ground. Guard. Return.",
    description: "A focused obsidian and clear quartz piece for boundaries, safety and emotional grounding.",
    image: "assets/obsidian-preview.png",
    position: "center",
    alt: "Protection obsidian bracelet preview",
    stones: [
      "Obsidian · Protection and release",
      "Clear Quartz · Amplification",
      "Moonstone · Emotional balance",
      "Amethyst · Calm boundary"
    ]
  },
  "Focus": {
    label: "Focus",
    title: "Clear. Study. Align.",
    description: "A clean amethyst and quartz palette for study, wisdom, clarity and calm concentration.",
    image: "assets/collection-bracelets.png",
    position: "72% center",
    alt: "Focus amethyst bracelet preview",
    stones: [
      "Amethyst · Focus and calm",
      "Clear Quartz · Clarity",
      "Moonstone · Inner guidance",
      "Obsidian · Distraction shield"
    ]
  },
  "New Beginning": {
    label: "New Beginning",
    title: "Begin. Trust. Bloom.",
    description: "A luminous moonstone bracelet for graduation, moving, new work and fresh life chapters.",
    image: "assets/hero-bracelet.png",
    position: "center",
    alt: "New Beginning moonstone bracelet preview",
    stones: [
      "Moonstone · New chapters",
      "Clear Quartz · Fresh clarity",
      "Rose Quartz · Self trust",
      "Amethyst · Peace"
    ]
  }
};

const customIntentButtons = document.querySelectorAll("[data-custom-intent]");
const customImage = document.querySelector("#customImage");
const customLabel = document.querySelector("#customIntentLabel");
const customTitle = document.querySelector("#customIntentTitle");
const customDescription = document.querySelector("#customIntentDescription");
const customStoneList = document.querySelector("#customStoneList");

function renderCustomIntent(intent) {
  const item = customIntentData[intent];
  if (!item || !customImage || !customLabel || !customTitle || !customDescription || !customStoneList) return;

  customImage.style.backgroundImage = `url("${item.image}")`;
  customImage.style.backgroundPosition = item.position;
  customImage.setAttribute("aria-label", item.alt);
  customLabel.textContent = item.label;
  customTitle.textContent = item.title;
  customDescription.textContent = item.description;
  customStoneList.innerHTML = item.stones.map((stone) => `<span>${stone}</span>`).join("");

  customIntentButtons.forEach((button) => {
    const selected = button.dataset.customIntent === intent;
    button.classList.toggle("active", selected);
    button.setAttribute("aria-selected", String(selected));
  });
}

customIntentButtons.forEach((button) => {
  button.addEventListener("click", () => renderCustomIntent(button.dataset.customIntent));
});

renderCustomIntent("Transformation");
