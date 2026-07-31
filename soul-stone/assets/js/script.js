const SOUL_STONE_ASSET_BASE = window.SOUL_STONE_THEME_URI ? `${window.SOUL_STONE_THEME_URI}/` : "";
const navWrap = document.querySelector(".nav-wrap");
const menuButton = document.querySelector(".menu-button");
const toast = document.querySelector(".toast");
const CART_KEY = "soulStoneCart";

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

function getCart() {
  try {
    return JSON.parse(localStorage.getItem(CART_KEY) || "[]");
  } catch {
    return [];
  }
}

function saveCart(cart) {
  try {
    localStorage.setItem(CART_KEY, JSON.stringify(cart));
  } catch {
    showToast("Cart could not be saved in this browser.");
  }
}

function updateCartCount() {
  const wooCount = Number(window.SOUL_STONE_WC_CART_COUNT || 0);
  const count = wooCount + getCart().reduce((total, item) => total + item.quantity, 0);
  document.querySelectorAll(".cart-count").forEach((node) => {
    node.textContent = String(count);
  });
}

function normalizeProductName(name) {
  return String(name).replace(/[’‘]/g, "'").trim();
}

function productByName(name) {
  return Object.values(productCatalog).find((product) => normalizeProductName(product.name) === normalizeProductName(name));
}

function addToCart(product, price, details = {}) {
  const cart = getCart();
  const existing = cart.find((item) => item.product === product);
  const catalogItem = productByName(product);
  const image = details.image || catalogItem?.image || `${SOUL_STONE_ASSET_BASE}assets/collection-bracelets.png`;
  const theme = details.theme || catalogItem?.theme || "Soul Stone";
  const stone = details.stone || catalogItem?.stone || "Crystal";

  if (existing) {
    existing.quantity += 1;
    existing.image = existing.image || image;
    existing.theme = existing.theme || theme;
    existing.stone = existing.stone || stone;
  } else {
    cart.push({
      product,
      price: Number(price),
      quantity: 1,
      image,
      theme,
      stone
    });
  }
  saveCart(cart);
  updateCartCount();
  showToast(`${product} added to cart.`);
}

function removeCartItem(product) {
  const cart = getCart().filter((item) => item.product !== product);
  saveCart(cart);
  updateCartCount();
  renderCartPage();
  showToast("Removed from cart.");
}

async function saveCustomDesignToAccount(design) {
  const config = window.SOUL_STONE_DESIGN_SAVE;
  if (!config || !config.isLoggedIn) {
    showToast("Please login to add and save your custom bracelet.");
    if (config?.loginUrl) {
      window.setTimeout(() => {
        window.location.href = config.loginUrl;
      }, 900);
    }
    return false;
  }

  const formData = new FormData();
  formData.append("action", "soul_stone_save_custom_design");
  formData.append("nonce", config.nonce);
  formData.append("product_name", design.product);
  formData.append("total", String(design.price));
  formData.append("length", design.length || "");
  formData.append("materials", design.materials || "");
  formData.append("stone_count", String((design.designItems || []).filter((item) => item.type === "stone").length));
  formData.append("accessory_count", String((design.designItems || []).filter((item) => item.type !== "stone").length));
  formData.append("items", JSON.stringify(design.designItems || []));

  try {
    const response = await fetch(config.ajaxUrl, {
      method: "POST",
      credentials: "same-origin",
      body: formData
    });
    const result = await response.json();
    if (result?.success && typeof result?.data?.cartCount !== "undefined") {
      saveCart(getCart().filter((item) => item.theme !== "Custom Design"));
      window.SOUL_STONE_WC_CART_COUNT = Number(result.data.cartCount || 0);
      updateCartCount();
    }
    showToast(result?.data?.message || (result?.success ? "Custom bracelet added to cart and saved." : "Custom bracelet could not be added."));
    return result;
  } catch {
    showToast("Custom bracelet could not be added. Please try again.");
    return false;
  }
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

document.addEventListener("click", (event) => {
  const button = event.target.closest(".add-cart");
  if (!button) return;
  addToCart(button.dataset.product, button.dataset.price, {
    image: button.dataset.image,
    theme: button.dataset.theme,
    stone: button.dataset.stone
  });
});

document.addEventListener("click", (event) => {
  const card = event.target.closest(".shop-product-card");
  if (!card || event.target.closest(".add-cart") || event.target.closest("a")) return;
  const link = card.querySelector(".shop-product-link");
  if (link) window.location.href = link.href;
});

document.addEventListener("click", (event) => {
  const removeButton = event.target.closest("[data-remove-cart]");
  if (!removeButton) return;
  removeCartItem(removeButton.dataset.removeCart);
});

const productCatalog = {
  "transformation-moonstone-bracelet": {
    name: "Transformation Moonstone Bracelet",
    price: 49,
    theme: "Transformation",
    stone: "Moonstone",
    image: `${SOUL_STONE_ASSET_BASE}assets/collection-bracelets.png`,
    short: "Moonstone, amethyst and clear quartz with a celestial charm.",
    long: "A soft luminous bracelet designed for personal growth and inner renewal. The placeholder composition pairs moonstone for new chapters, amethyst for calm reflection, and clear quartz for clarity of intention.",
    category: "collection-gallery"
  },
  "self-love-rose-quartz-bracelet": {
    name: "Self Love Rose Quartz Bracelet",
    price: 45,
    theme: "Self Love",
    stone: "Rose Quartz",
    image: `${SOUL_STONE_ASSET_BASE}assets/collection-bracelets.png`,
    short: "Soft rose quartz and moonstone tones for tenderness.",
    long: "A gentle everyday bracelet for heart-led confidence and softness. Rose quartz is the main placeholder stone, supported by pale accents for a warm, delicate Soul Stone feeling.",
    category: "collection-gallery"
  },
  "protection-obsidian-bracelet": {
    name: "Protection Obsidian Bracelet",
    price: 48,
    theme: "Protection",
    stone: "Obsidian",
    image: `${SOUL_STONE_ASSET_BASE}assets/obsidian-preview.png`,
    short: "Obsidian and clear quartz for grounding and boundaries.",
    long: "A darker protection-led piece with obsidian as the central stone. This default product description can later be replaced with exact bead sizes, charm materials, and care details.",
    category: "collection-gallery"
  },
  "focus-amethyst-bracelet": {
    name: "Focus Amethyst Bracelet",
    price: 46,
    theme: "Focus",
    stone: "Amethyst",
    image: `${SOUL_STONE_ASSET_BASE}assets/collection-bracelets.png`,
    short: "Amethyst and clear quartz for study and clarity.",
    long: "A calm purple-toned bracelet for focus, studying, and mental quiet. The current image and text are placeholders, ready for your real product photography.",
    category: "collection-gallery"
  },
  "new-beginning-moonstone-bracelet": {
    name: "New Beginning Moonstone Bracelet",
    price: 49,
    theme: "New Beginning",
    stone: "Moonstone",
    image: `${SOUL_STONE_ASSET_BASE}assets/hero-bracelet.png`,
    short: "A luminous piece for graduation, moving and fresh starts.",
    long: "A bright moonstone-led bracelet for new work, moving home, graduation, or any fresh chapter. The detail page is structured so you can add measurements and shipping notes later.",
    category: "collection-gallery"
  },
  "clear-quartz-charm-bracelet": {
    name: "Clear Quartz Charm Bracelet",
    price: 42,
    theme: "Focus",
    stone: "Clear Quartz",
    image: `${SOUL_STONE_ASSET_BASE}assets/stone-clear-quartz.png`,
    short: "A minimal bracelet for clarity and intention stacking.",
    long: "A clean clear quartz design for simple daily wear. It works as a placeholder for minimalist pieces and can later be paired with charm options or metal finishes.",
    category: "collection-gallery"
  },
  "aquamarine-calm-bracelet": {
    name: "Aquamarine Calm Bracelet",
    price: 52,
    theme: "New Beginning",
    stone: "Aquamarine",
    image: `${SOUL_STONE_ASSET_BASE}assets/stone-aquamarine.png`,
    short: "Pale aquamarine for quiet courage and communication.",
    long: "A blue-toned bracelet direction for calm communication and gentle courage. The placeholder layout lets customers understand the mood before final photography is added.",
    category: "new-arrivals"
  },
  "pink-catseye-glow-bracelet": {
    name: "Pink Cat's Eye Glow Bracelet",
    price: 44,
    theme: "Self Love",
    stone: "Cat's Eye",
    image: `${SOUL_STONE_ASSET_BASE}assets/stone-pink-catseye.png`,
    short: "Pink cat's eye beads with a silky confident glow.",
    long: "A luminous pink cat's eye bracelet for softness with a little shine. This product detail can later include bead finish, elastic size, and packaging options.",
    category: "new-arrivals"
  },
  "silver-obsidian-insight-bracelet": {
    name: "Silver Obsidian Insight Bracelet",
    price: 54,
    theme: "Protection",
    stone: "Silver Obsidian",
    image: `${SOUL_STONE_ASSET_BASE}assets/stone-silver-obsidian.png`,
    short: "Silver obsidian for reflection and inner strength.",
    long: "A reflective silver obsidian piece for grounding, insight, and steadiness. The default content keeps the catalog usable while leaving space for your final story and materials.",
    category: "new-arrivals"
  },
  "new-chapter-gift-set": {
    name: "New Chapter Gift Set",
    price: 68,
    theme: "Gift",
    stone: "Moonstone",
    image: `${SOUL_STONE_ASSET_BASE}assets/hero-bracelet.png`,
    short: "A moonstone bracelet with a default intention card.",
    long: "A gift-ready placeholder set for birthdays, graduations, farewell gifts, or fresh starts. The future version can include packaging photos, card copy, and personalization fields.",
    category: "gift-ideas"
  },
  "custom-intention-gift-card": {
    name: "Custom Intention Gift Card",
    price: 75,
    theme: "Gift",
    stone: "Clear Quartz",
    image: `${SOUL_STONE_ASSET_BASE}assets/about-studio.png`,
    short: "A custom design option for a personal intention-led gift.",
    long: "A flexible custom design placeholder for customers who want a more personal Soul Stone piece. Later this can connect directly to your custom design form.",
    category: "gift-ideas"
  },
  "friendship-intention-pair": {
    name: "Friendship Intention Pair",
    price: 88,
    theme: "Gift",
    stone: "Rose Quartz",
    image: `${SOUL_STONE_ASSET_BASE}assets/collection-bracelets.png`,
    short: "Two coordinated bracelets for friendship or shared milestones.",
    long: "A paired bracelet set for friends, sisters, partners, or shared intentions. The placeholder price and story are ready to be replaced once the final set is confirmed.",
    category: "gift-ideas"
  }
};

function renderProductDetail() {
  const detail = document.querySelector("#productDetail");
  if (!detail) return;
  if (detail.dataset.serverProduct === "1") return;

  const params = new URLSearchParams(window.location.search);
  const product = productCatalog[params.get("id") || ""];
  const title = document.querySelector("#detailTitle");
  const image = document.querySelector("#detailImage");
  const theme = document.querySelector("#detailTheme");
  const price = document.querySelector("#detailPrice");
  const description = document.querySelector("#detailDescription");
  const longDescription = document.querySelector("#detailLong");
  const specTheme = document.querySelector("#detailSpecTheme");
  const specStone = document.querySelector("#detailSpecStone");
  const specSizes = document.querySelector("#detailSpecSizes");
  const specPrice = document.querySelector("#detailSpecPrice");
  const addButton = document.querySelector("#detailAddCart");

  if (!product) {
    title.textContent = "Product not found";
    description.textContent = "This product link is not available yet. Please return to All Products.";
    image.style.display = "none";
    if (addButton) addButton.style.display = "none";
    return;
  }

  document.title = `${product.name} | Soul Stone`;
  image.src = product.image;
  image.alt = product.name;
  theme.textContent = product.theme;
  title.textContent = product.name;
  price.textContent = `$${product.price} AUD`;
  description.textContent = product.short;
  longDescription.textContent = product.long;
  specTheme.textContent = product.theme;
  specStone.textContent = product.stone;
  if (specSizes) specSizes.textContent = "6mm / 8mm / 10mm available";
  specPrice.textContent = `$${product.price} AUD`;
  addButton.dataset.product = product.name;
  addButton.dataset.price = String(product.price);
}

function setupCatalogFilters() {
  const grid = document.querySelector("#productGrid");
  const cards = Array.from(document.querySelectorAll(".shop-product-card"));
  const pagination = document.querySelector("#catalogPagination");
  const empty = document.querySelector("#catalogEmpty");
  const themeFilter = document.querySelector("#themeFilter");
  const stoneFilter = document.querySelector("#stoneFilter");
  const priceFilter = document.querySelector("#priceFilter");
  const clearFilters = document.querySelector("#clearFilters");
  if (!grid || !cards.length || !pagination || !empty || !themeFilter || !stoneFilter || !priceFilter) return;

  const perPage = 10;
  let currentPage = 1;

  function currentCategory() {
    const category = window.location.hash.replace("#", "");
    return ["collection-gallery", "new-arrivals", "gift-ideas"].includes(category) ? category : "all";
  }

  function matchesPrice(card) {
    if (priceFilter.value === "all") return true;
    const [min, max] = priceFilter.value.split("-").map(Number);
    const price = Number(card.dataset.price);
    return price >= min && price <= max;
  }

  function visibleCards() {
    const category = currentCategory();
    return cards.filter((card) => {
      const themeMatch = themeFilter.value === "all" || card.dataset.theme === themeFilter.value;
      const stoneMatch = stoneFilter.value === "all" || card.dataset.stone === stoneFilter.value;
      const categoryMatch = category === "all" || card.dataset.category === category;
      return themeMatch && stoneMatch && categoryMatch && matchesPrice(card);
    });
  }

  function renderCatalog() {
    const matchedCards = visibleCards();
    const pageCount = Math.max(1, Math.ceil(matchedCards.length / perPage));
    currentPage = Math.min(currentPage, pageCount);
    const start = (currentPage - 1) * perPage;
    const pageCards = matchedCards.slice(start, start + perPage);

    cards.forEach((card) => {
      card.style.display = pageCards.includes(card) ? "" : "none";
    });

    empty.style.display = matchedCards.length ? "none" : "block";
    pagination.innerHTML = "";
    if (matchedCards.length <= perPage) return;

    for (let page = 1; page <= pageCount; page += 1) {
      const button = document.createElement("button");
      button.className = "page-button";
      button.type = "button";
      button.textContent = String(page);
      button.classList.toggle("active", page === currentPage);
      button.setAttribute("aria-label", `Go to product page ${page}`);
      button.addEventListener("click", () => {
        currentPage = page;
        renderCatalog();
        grid.scrollIntoView({ behavior: "smooth", block: "start" });
      });
      pagination.append(button);
    }
  }

  [themeFilter, stoneFilter, priceFilter].forEach((filter) => {
    filter.addEventListener("change", () => {
      currentPage = 1;
      renderCatalog();
    });
  });

  if (clearFilters) {
    clearFilters.addEventListener("click", () => {
      themeFilter.value = "all";
      stoneFilter.value = "all";
      priceFilter.value = "all";
      currentPage = 1;
      renderCatalog();
    });
  }

  window.addEventListener("hashchange", () => {
    currentPage = 1;
    renderCatalog();
  });

  renderCatalog();
}

function setupMaterialBrowser() {
  const grid = document.querySelector("#materialGrid");
  const cards = Array.from(document.querySelectorAll(".material-card"));
  const prevButton = document.querySelector("[data-material-prev]");
  const nextButton = document.querySelector("[data-material-next]");
  const status = document.querySelector("#materialPageStatus");
  const searchInput = document.querySelector("#materialSearch");
  if (!grid || !cards.length || !prevButton || !nextButton || !status) return;

  let currentPage = 1;

  function normalizedText(value) {
    return String(value).replace(/[’‘]/g, "'").toLowerCase().trim();
  }

  function cardNameText(card) {
    return normalizedText([
      card.querySelector("h2")?.textContent || "",
      card.querySelector(".zh")?.textContent || "",
      card.querySelector("img")?.alt || "",
      card.dataset.keywords || ""
    ].join(" "));
  }

  function renderMaterials() {
    const query = normalizedText(searchInput?.value || "");
    if (query) {
      const matchedCards = cards.filter((card) => cardNameText(card).includes(query));
      cards.forEach((card) => {
        card.style.display = matchedCards.includes(card) ? "" : "none";
      });
      prevButton.disabled = true;
      nextButton.disabled = true;
      status.textContent = matchedCards.length ? `Showing ${matchedCards.length} result${matchedCards.length > 1 ? "s" : ""}` : "No stones found";
      return;
    }

    const pageSize = 9;
    const pageCount = Math.max(1, Math.ceil(cards.length / pageSize));
    currentPage = Math.min(currentPage, pageCount);
    const start = (currentPage - 1) * pageSize;
    const visibleCards = cards.slice(start, start + pageSize);

    cards.forEach((card) => {
      card.style.display = visibleCards.includes(card) ? "" : "none";
    });

    prevButton.disabled = currentPage === 1;
    nextButton.disabled = currentPage === pageCount;
    status.textContent = `Page ${currentPage} of ${pageCount}`;
  }

  prevButton.addEventListener("click", () => {
    currentPage = Math.max(1, currentPage - 1);
    renderMaterials();
  });

  nextButton.addEventListener("click", () => {
    currentPage += 1;
    renderMaterials();
  });

  if (searchInput) {
    searchInput.addEventListener("input", () => {
      currentPage = 1;
      renderMaterials();
    });
  }

  window.addEventListener("resize", () => {
    currentPage = 1;
    renderMaterials();
  });

  renderMaterials();
}

document.addEventListener("click", (event) => {
  const toggle = event.target.closest("[data-material-toggle]");
  if (!toggle) return;
  const card = toggle.closest(".material-card");
  if (!card) return;
  const expanded = card.classList.toggle("is-expanded");
  toggle.textContent = expanded ? "Hide details" : "More details";
});

function setupBraceletDesigner() {
  const designer = document.querySelector("#braceletDesigner");
  const previewItems = document.querySelector("#braceletItems");
  const braceletPreview = document.querySelector("#braceletPreview");
  const totalNode = document.querySelector("#designerTotal");
  const stoneCountNode = document.querySelector("#designerStoneCount");
  const accessoryCountNode = document.querySelector("#designerAccessoryCount");
  const lengthNode = document.querySelector("#designerLength");
  const lengthSelect = document.querySelector("#designerLengthSelect");
  const materialsNode = document.querySelector("#designerMaterials");
  const trashZone = document.querySelector("#designerTrash");
  const stoneGrid = document.querySelector("#stoneOptionGrid");
  const stoneSearch = document.querySelector("#designerStoneSearch");
  const typeFilter = document.querySelector("#designerTypeFilter");
  const colorFilter = document.querySelector("#designerColorFilter");
  const sizeFilter = document.querySelector("#designerSizeFilter");
  const undoButton = document.querySelector("#undoDesignItem");
  const resetButton = document.querySelector("#resetDesign");
  const addButton = document.querySelector("#addCustomDesign");
  if (!designer || !previewItems || !totalNode || !stoneCountNode || !accessoryCountNode || !lengthNode || !lengthSelect || !materialsNode || !stoneGrid) return;

  const basePrice = 10;
  const selectedItems = [];
  const stoneLibrary = [
    { name: "Amethyst", zh: "紫水晶", colorGroup: "purple", color: "#8c73b4" },
    { name: "Rose Quartz", zh: "粉晶", colorGroup: "pink", color: "#e8b8bd" },
    { name: "Obsidian", zh: "黑曜石", colorGroup: "black", color: "#151313" },
    { name: "Moonstone", zh: "月光石", colorGroup: "white", color: "#dfe8f2" },
    { name: "Clear Quartz", zh: "白水晶", colorGroup: "white", color: "#f4f4ef" },
    { name: "Aquamarine", zh: "海蓝宝", colorGroup: "blue", color: "#b9dfe6" }
  ];
  const sizePrices = { 6: 5, 8: 7, 10: 10 };
  const sizePixels = { 6: 48, 8: 58, 10: 70 };
  const accessoryLibrary = [
    {
      type: "pendant",
      name: "Dangle Pendant",
      zh: "",
      colorGroup: "gold",
      color: "#d2a762",
      price: 6,
      symbol: "◇",
      image: `${SOUL_STONE_ASSET_BASE}assets/accessory-dangle-pendant.svg`
    },
    {
      type: "guru",
      name: "Three-way Bead",
      zh: "",
      colorGroup: "gold",
      color: "#c99a4c",
      price: 5,
      symbol: "T",
      image: `${SOUL_STONE_ASSET_BASE}assets/accessory-three-way-bead.svg`
    },
    {
      type: "spacer",
      name: "Spacer Bead",
      zh: "",
      colorGroup: "gold",
      color: "#d0a158",
      price: 2,
      symbol: "•",
      image: `${SOUL_STONE_ASSET_BASE}assets/accessory-spacer-bead.svg`
    },
    {
      type: "pendant",
      name: "Crystal Drop",
      zh: "",
      colorGroup: "gold",
      color: "#cfa260",
      price: 7,
      symbol: "◇",
      image: `${SOUL_STONE_ASSET_BASE}assets/accessory-dangle-pendant.svg`
    },
    {
      type: "spacer",
      name: "Rondelle Spacer",
      zh: "",
      colorGroup: "gold",
      color: "#d8b36d",
      price: 3,
      symbol: "•",
      image: `${SOUL_STONE_ASSET_BASE}assets/accessory-spacer-bead.svg`
    },
    {
      type: "guru",
      name: "Center Connector",
      zh: "",
      colorGroup: "gold",
      color: "#bd8f47",
      price: 6,
      symbol: "T",
      image: `${SOUL_STONE_ASSET_BASE}assets/accessory-three-way-bead.svg`
    }
  ];

  function stoneVariants() {
    return stoneLibrary.flatMap((stone) => [6, 8, 10].map((size) => {
      const premium = stone.name === "Moonstone" || stone.name === "Aquamarine" || stone.name === "Amethyst" || stone.name === "Obsidian" ? 1 : 0;
      return {
        type: "stone",
        name: stone.name,
        zh: stone.zh,
        colorGroup: stone.colorGroup,
        color: stone.color,
        size,
        price: sizePrices[size] + premium
      };
    }));
  }

  function normalizedDesignerText(value) {
    return String(value).replace(/[’‘]/g, "'").toLowerCase().trim();
  }

  function renderStoneOptions() {
    const query = normalizedDesignerText(stoneSearch?.value || "");
    const type = typeFilter?.value || "all";
    const color = colorFilter?.value || "all";
    const size = sizeFilter?.value || "all";
    const variants = [...stoneVariants(), ...accessoryLibrary].filter((item) => {
      const isStone = item.type === "stone";
      const itemType = isStone ? "stone" : "accessory";
      const text = normalizedDesignerText(`${item.name} ${item.zh} ${item.size ? `${item.size}mm` : "accessory"}`);
      const matchesQuery = !query || text.includes(query);
      const matchesType = type === "all" || type === itemType;
      const matchesColor = color === "all" || item.colorGroup === color;
      const matchesSize = size === "all" || (isStone && String(item.size) === size);
      return matchesQuery && matchesType && matchesColor && matchesSize;
    });

    stoneGrid.innerHTML = variants.length ? variants.map((item) => {
      if (item.type === "stone") {
        return `
      <button class="designer-option" type="button" data-design-type="stone" data-name="${item.name}" data-zh="${item.zh}" data-price="${item.price}" data-color="${item.color}" data-size="${item.size}" data-color-group="${item.colorGroup}">
        <span class="designer-stone-swatch" style="--swatch-color: ${item.color}; --swatch-size: ${Math.max(34, item.size * 5)}px"></span>
        <span>${item.name}</span>
        <em>${item.size}mm</em>
        <b>$${item.price}</b>
      </button>
    `;
      }
      return `
      <button class="designer-option is-accessory-option" type="button" data-design-type="${item.type}" data-name="${item.name}" data-zh="${item.zh}" data-price="${item.price}" data-color="${item.color}" data-symbol="${item.symbol}" data-image="${item.image}" data-color-group="${item.colorGroup}">
        <img src="${item.image}" alt="">
        <span>${item.name}</span>
        <em>Accessory</em>
        <b>$${item.price}</b>
      </button>
    `;
    }).join("") : '<p class="designer-empty">No pieces match this filter.</p>';
  }

  function itemFromButton(button) {
    return {
      type: button.dataset.designType,
      name: button.dataset.name,
      zh: button.dataset.zh,
      price: Number(button.dataset.price),
      color: button.dataset.color,
      size: Number(button.dataset.size || 0),
      symbol: button.dataset.symbol,
      image: button.dataset.image
    };
  }

  function itemLength(item) {
    if (item.type === "stone") return (item.size || 8) / 10;
    if (item.type === "pendant") return .5;
    if (item.type === "guru") return .7;
    if (item.type === "spacer") return .3;
    return .4;
  }

  function usedLength(items = selectedItems) {
    return items.reduce((sum, item) => sum + itemLength(item), 0);
  }

  function selectedLength() {
    return Number(lengthSelect.value || 18);
  }

  function canAddItem(item) {
    return usedLength([...selectedItems, item]) <= selectedLength();
  }

  function beadPosition(angle, radius = 39) {
    const radians = angle * Math.PI / 180;
    return {
      x: 50 + Math.cos(radians) * radius,
      y: 50 + Math.sin(radians) * radius
    };
  }

  function angleFromPointer(event) {
    const rect = previewItems.getBoundingClientRect();
    const centerX = rect.left + rect.width / 2;
    const centerY = rect.top + rect.height / 2;
    return Math.atan2(event.clientY - centerY, event.clientX - centerX) * 180 / Math.PI;
  }

  function pointerInTrash(event) {
    if (!trashZone) return false;
    const rect = trashZone.getBoundingClientRect();
    return event.clientX >= rect.left && event.clientX <= rect.right && event.clientY >= rect.top && event.clientY <= rect.bottom;
  }

  function itemSummary(items) {
    const counts = new Map();
    items.forEach((item) => {
      const sizeText = item.size ? ` ${item.size}mm` : "";
      const label = `${item.name}${item.zh ? ` / ${item.zh}` : ""}${sizeText}`;
      counts.set(label, (counts.get(label) || 0) + 1);
    });
    return Array.from(counts.entries()).map(([name, count]) => count > 1 ? `${name} x${count}` : name);
  }

  function renderBraceletDesigner() {
    const total = selectedItems.reduce((sum, item) => sum + item.price, basePrice);
    const stoneCount = selectedItems.filter((item) => item.type === "stone").length;
    const accessoryCount = selectedItems.length - stoneCount;
    const targetLength = selectedLength();
    const currentLength = usedLength();
    if (braceletPreview) {
      braceletPreview.style.setProperty("--bracelet-size", `${targetLength * 34}px`);
    }

    previewItems.innerHTML = "";
    selectedItems.forEach((item, index) => {
      const node = document.createElement("span");
      const count = selectedItems.length;
      const fillRatio = Math.min(1, currentLength / targetLength);
      const isClosed = fillRatio >= .95;
      const spread = count === 1 ? 0 : (isClosed ? 360 : Math.max(24, 330 * fillRatio));
      const angle = item.manualAngle ?? (count === 1 ? 90 : isClosed ? -90 + (360 / count) * index : 90 - spread / 2 + (spread / (count - 1)) * index);
      const { x, y } = beadPosition(angle);

      node.className = `bracelet-bead is-${item.type}`;
      node.dataset.designIndex = String(index);
      node.style.setProperty("--x", `${x}%`);
      node.style.setProperty("--y", `${y}%`);
      node.style.setProperty("--bead-color", item.color || "#d2a762");
      if (item.size) node.style.setProperty("--bead-size", `${sizePixels[item.size] || 58}px`);
      node.title = `${item.name}${item.zh ? ` / ${item.zh}` : ""}${item.size ? ` · ${item.size}mm` : ""}`;
      node.setAttribute("aria-label", node.title);
      if (item.symbol) node.textContent = item.symbol;
      previewItems.append(node);
    });

    stoneCountNode.textContent = String(stoneCount);
    accessoryCountNode.textContent = String(accessoryCount);
    lengthNode.textContent = `${currentLength.toFixed(1)} / ${targetLength} cm`;
    totalNode.textContent = `$${total} AUD`;
    materialsNode.textContent = selectedItems.length
      ? `${itemSummary(selectedItems).join(" · ")}. Bracelet length: ${targetLength} cm. Used bead length: ${currentLength.toFixed(1)} cm.`
      : "Start by choosing a stone or accessory from the left panel.";
  }

  previewItems.addEventListener("pointerdown", (event) => {
    const bead = event.target.closest(".bracelet-bead");
    if (!bead) return;
    const item = selectedItems[Number(bead.dataset.designIndex)];
    if (!item) return;

    bead.classList.add("is-dragging");
    trashZone?.classList.add("is-active");
    bead.setPointerCapture(event.pointerId);
    let lastPointerEvent = event;

    function moveBead(moveEvent) {
      lastPointerEvent = moveEvent;
      const angle = angleFromPointer(moveEvent);
      const { x, y } = beadPosition(angle);
      item.manualAngle = angle;
      bead.style.setProperty("--x", `${x}%`);
      bead.style.setProperty("--y", `${y}%`);
      trashZone?.classList.toggle("is-over", pointerInTrash(moveEvent));
    }

    function stopDragging() {
      const shouldDelete = pointerInTrash(lastPointerEvent);
      const itemIndex = Number(bead.dataset.designIndex);
      bead.classList.remove("is-dragging");
      trashZone?.classList.remove("is-active", "is-over");
      bead.removeEventListener("pointermove", moveBead);
      bead.removeEventListener("pointerup", stopDragging);
      bead.removeEventListener("pointercancel", stopDragging);
      if (shouldDelete && Number.isInteger(itemIndex)) {
        selectedItems.splice(itemIndex, 1);
        renderBraceletDesigner();
        showToast("Bead removed.");
      }
    }

    moveBead(event);
    bead.addEventListener("pointermove", moveBead);
    bead.addEventListener("pointerup", stopDragging);
    bead.addEventListener("pointercancel", stopDragging);
  });

  designer.addEventListener("click", (event) => {
    const button = event.target.closest("[data-design-type]");
    if (!button) return;
    const nextItem = itemFromButton(button);
    if (!canAddItem(nextItem)) {
      showToast(`This ${selectedLength()} cm bracelet is full. Choose a longer length or undo an item.`);
      return;
    }
    selectedItems.push(nextItem);
    renderBraceletDesigner();
  });

  [stoneSearch, typeFilter, colorFilter, sizeFilter].forEach((filter) => {
    if (!filter) return;
    filter.addEventListener("input", renderStoneOptions);
    filter.addEventListener("change", renderStoneOptions);
  });

  if (undoButton) {
    undoButton.addEventListener("click", () => {
      selectedItems.pop();
      renderBraceletDesigner();
    });
  }

  if (resetButton) {
    resetButton.addEventListener("click", () => {
      selectedItems.length = 0;
      renderBraceletDesigner();
    });
  }

  lengthSelect.addEventListener("change", () => {
    while (usedLength() > selectedLength() && selectedItems.length) {
      selectedItems.pop();
    }
    renderBraceletDesigner();
  });

  if (addButton) {
    addButton.addEventListener("click", async () => {
      if (!selectedItems.length) {
        showToast("Choose at least one stone or accessory first.");
        return;
      }
      const total = selectedItems.reduce((sum, item) => sum + item.price, basePrice);
      const stones = selectedItems.filter((item) => item.type === "stone").map((item) => item.name);
      const productName = stones.length ? `Custom Bracelet - ${stones.slice(0, 3).join(", ")}${stones.length > 3 ? "..." : ""}` : "Custom Bracelet - Mixed Design";
      const customDesign = {
        product: productName,
        price: total,
        quantity: 1,
        theme: "Custom Design",
        stone: stones.length ? stones.join(", ") : "Mixed stones",
        length: `${selectedLength()} cm`,
        materials: itemSummary(selectedItems).join(" · "),
        designItems: selectedItems.map((item) => ({
          type: item.type,
          name: item.name,
          zh: item.zh,
          color: item.color,
          size: item.size || 0,
          symbol: item.symbol || "",
          image: item.image || "",
          manualAngle: item.manualAngle ?? null
        }))
      };
      addButton.disabled = true;
      const result = await saveCustomDesignToAccount(customDesign);
      addButton.disabled = false;
      if (result?.success && result?.data?.cartUrl) {
        window.setTimeout(() => {
          window.location.href = result.data.cartUrl;
        }, 900);
      }
    });
  }

  renderStoneOptions();
  renderBraceletDesigner();
}

function escapeHTML(value) {
  return String(value).replace(/[&<>"']/g, (char) => ({
    "&": "&amp;",
    "<": "&lt;",
    ">": "&gt;",
    '"': "&quot;",
    "'": "&#39;"
  }[char]));
}

function cartItemDetails(item) {
  const catalogItem = productByName(item.product);
  return {
    image: item.image || catalogItem?.image || `${SOUL_STONE_ASSET_BASE}assets/collection-bracelets.png`,
    theme: item.theme || catalogItem?.theme || "Soul Stone",
    stone: item.stone || catalogItem?.stone || "Crystal"
  };
}

function cartDesignPreview(item) {
  if (!Array.isArray(item.designItems) || !item.designItems.length) {
    return `<img class="cart-thumb" src="${escapeHTML(cartItemDetails(item).image)}" alt="${escapeHTML(item.product)}">`;
  }

  const count = item.designItems.length;
  const beads = item.designItems.map((designItem, index) => {
    const angle = Number.isFinite(designItem.manualAngle)
      ? designItem.manualAngle
      : -90 + (360 / count) * index;
    const radians = angle * Math.PI / 180;
    const x = 50 + Math.cos(radians) * 38;
    const y = 50 + Math.sin(radians) * 38;
    const size = designItem.type === "stone" ? Math.max(16, Math.min(26, (designItem.size || 8) * 2.4)) : 16;
    const symbol = designItem.symbol ? escapeHTML(designItem.symbol) : "";
    return `<span class="cart-design-bead is-${escapeHTML(designItem.type || "stone")}" style="--x: ${x}%; --y: ${y}%; --bead-color: ${escapeHTML(designItem.color || "#d2a762")}; --bead-size: ${size}px">${symbol}</span>`;
  }).join("");

  return `<div class="cart-design-preview" role="img" aria-label="${escapeHTML(item.product)} preview"><span class="cart-design-ring"></span>${beads}</div>`;
}

function cartItemMeta(item) {
  const details = cartItemDetails(item);
  const specs = [
    ["Theme", details.theme],
    ["Main stone", details.stone],
    ["Quantity", item.quantity],
    ["Unit price", `$${item.price} AUD`]
  ];
  if (item.length) specs.splice(2, 0, ["Length", item.length]);
  const materialParts = item.materials ? item.materials.split(" · ").slice(0, 8) : [details.stone];
  const materials = materialParts.map((part) => `<span>${escapeHTML(part)}</span>`).join("");
  return `
    <div class="cart-spec-grid">
      ${specs.map(([label, value]) => `
        <div>
          <span>${escapeHTML(label)}</span>
          <strong>${escapeHTML(value)}</strong>
        </div>
      `).join("")}
    </div>
    <div class="cart-material-block">
      <em>Materials</em>
      <div class="cart-material-list" aria-label="Materials">${materials}</div>
    </div>
  `;
}

function setupCartSwipeActions() {
  document.querySelectorAll(".cart-swipe").forEach((wrapper) => {
    const line = wrapper.querySelector(".cart-line");
    if (!line) return;

    let startX = 0;
    let currentX = 0;
    let dragging = false;

    line.addEventListener("pointerdown", (event) => {
      if (event.target.closest("button, a")) return;
      startX = event.clientX;
      currentX = 0;
      dragging = true;
      wrapper.classList.add("is-dragging");
      line.setPointerCapture(event.pointerId);
    });

    line.addEventListener("pointermove", (event) => {
      if (!dragging) return;
      currentX = Math.min(0, Math.max(-112, event.clientX - startX));
      line.style.transform = `translateX(${currentX}px)`;
    });

    line.addEventListener("pointerup", () => {
      if (!dragging) return;
      dragging = false;
      wrapper.classList.remove("is-dragging");
      line.style.transform = "";
      wrapper.classList.toggle("is-open", currentX < -48);
    });

    line.addEventListener("pointercancel", () => {
      dragging = false;
      wrapper.classList.remove("is-dragging");
      line.style.transform = "";
    });
  });
}

function renderCartPage() {
  const cartItems = document.querySelector("#cartItems");
  const cartTotal = document.querySelector("#cartTotal");
  const cartHeroTotal = document.querySelector("#cartHeroTotal");
  if (!cartItems || !cartTotal) return;
  if (cartItems.dataset.serverCart === "1") return;

  const cart = getCart();
  if (!cart.length) {
    cartItems.innerHTML = '<p class="empty-cart">Your cart is empty. Start with a piece that matches your intention.</p>';
    cartTotal.textContent = "$0 AUD";
    if (cartHeroTotal) cartHeroTotal.textContent = "$0 AUD";
    return;
  }

  cartItems.innerHTML = cart.map((item) => `
    <div class="cart-swipe">
      <button class="cart-delete" type="button" data-remove-cart="${escapeHTML(item.product)}">Delete</button>
      <article class="cart-line">
        <div class="cart-media">${cartDesignPreview(item)}</div>
        <div class="cart-info">
          <span class="cart-kicker">${escapeHTML(cartItemDetails(item).theme)}</span>
          <strong>${escapeHTML(item.product)}</strong>
          ${cartItemMeta(item)}
        </div>
        <div class="cart-line-summary">
          <span>Subtotal</span>
          <b>$${item.price * item.quantity} AUD</b>
          <button class="cart-remove-inline" type="button" data-remove-cart="${escapeHTML(item.product)}">Remove</button>
        </div>
      </article>
    </div>
  `).join("");
  const total = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
  cartTotal.textContent = `$${total} AUD`;
  if (cartHeroTotal) cartHeroTotal.textContent = `$${total} AUD`;
  setupCartSwipeActions();
}

updateCartCount();
renderCartPage();
renderProductDetail();
setupCatalogFilters();
setupMaterialBrowser();
setupBraceletDesigner();

const customIntentData = {
  "Transformation": {
    label: "Transformation",
    title: "Release. Renew. Rise.",
    description: "A moonlit bracelet composition for personal growth, courage and new chapters.",
    image: `${SOUL_STONE_ASSET_BASE}assets/obsidian-preview.png`,
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
    image: `${SOUL_STONE_ASSET_BASE}assets/collection-bracelets.png`,
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
    image: `${SOUL_STONE_ASSET_BASE}assets/obsidian-preview.png`,
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
    image: `${SOUL_STONE_ASSET_BASE}assets/collection-bracelets.png`,
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
    image: `${SOUL_STONE_ASSET_BASE}assets/hero-bracelet.png`,
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
