<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Custom Design | Soul Stone</title>
    <link rel="stylesheet" href="styles.css?v=custom-design-accessories">
  </head>
  <body>
    <header class="site-header">
      <a class="logo-link" href="index.php"><img class="logo-mark" src="assets/logo-mark.png" alt=""><span class="brand-name">Soul Stone</span></a>
      <button class="menu-button" aria-label="Open navigation" aria-expanded="false"><span></span></button>
      <div class="nav-wrap"><nav class="main-nav"><a href="index.php">Home</a><a href="products.php">Products</a><a href="materials.php">Materials</a><a href="custom-design.php" aria-current="page">Custom Design</a><a href="about.php">About Us</a><a href="login.php">Login</a><a class="cart-link" href="cart.php" aria-label="Cart"><span class="bag-icon" aria-hidden="true"></span><span class="cart-count">0</span></a></nav><a class="black-button" href="custom-design.php">Start Designing</a></div>
    </header>

    <main>
      <section class="custom-design-hero">
        <div>
          <span class="eyebrow">Design your piece</span>
          <h1>Custom Design</h1>
          <p>Choose stones, pendants and bracelet details, then watch your bracelet build itself in the center. Prices and materials update as you design.</p>
        </div>
        <div class="custom-hero-preview" aria-label="Custom bracelet preview">
          <div class="custom-hero-ring">
            <img src="assets/material-stone-amethyst.svg" alt="">
            <img src="assets/material-stone-rose-quartz.svg" alt="">
            <img src="assets/accessory-dangle-pendant.svg" alt="">
            <img src="assets/material-stone-clear-quartz.svg" alt="">
            <img src="assets/accessory-spacer-bead.svg" alt="">
          </div>
          <div class="custom-hero-note">
            <span>Stones + Accessories</span>
            <strong>Build a bracelet around meaning, color and small symbolic details.</strong>
          </div>
        </div>
      </section>

      <section class="section designer-section">
        <div class="bracelet-designer" id="braceletDesigner">
          <aside class="designer-panel">
            <span class="eyebrow">Choose Pieces</span>
            <h2>Build the energy.</h2>
            <div class="designer-filters" aria-label="Design piece filters">
              <input id="designerStoneSearch" type="search" placeholder="Search beads or accessories...">
              <select id="designerTypeFilter">
                <option value="all">All pieces</option>
                <option value="stone">Stones</option>
                <option value="accessory">Accessories</option>
              </select>
              <select id="designerColorFilter">
                <option value="all">All colors</option>
                <option value="white">White/Clear</option>
                <option value="pink">Pink</option>
                <option value="purple">Purple</option>
                <option value="black">Black</option>
                <option value="blue">Blue</option>
                <option value="gold">Gold/Metal</option>
              </select>
              <select id="designerSizeFilter">
                <option value="all">All sizes</option>
                <option value="6">6mm</option>
                <option value="8">8mm</option>
                <option value="10">10mm</option>
              </select>
            </div>
            <div class="designer-options stone-option-grid" id="stoneOptionGrid" aria-label="Design piece options">
            </div>
          </aside>

          <section class="bracelet-stage" aria-label="Custom bracelet preview">
            <div class="bracelet-preview" id="braceletPreview">
              <div class="bracelet-ring"></div>
              <div class="bracelet-brand"><strong>Soul Stone</strong><span>custom bracelet</span></div>
              <div class="bracelet-items" id="braceletItems"></div>
            </div>
            <div class="designer-stage-actions">
              <button class="outline-button" type="button" id="undoDesignItem">Undo</button>
              <button class="outline-button" type="button" id="resetDesign">Reset</button>
            </div>
            <div class="designer-trash" id="designerTrash" aria-label="Drag bead here to remove">
              <span class="trash-icon" aria-hidden="true"></span>
              <b>Drop to remove</b>
            </div>
          </section>

            <aside class="designer-panel designer-summary">
            <span class="eyebrow">Details</span>
            <h2>Finish the piece.</h2>
            <div class="designer-info">
              <div><span>Base</span><b>$10 AUD</b></div>
              <div><span>Stones</span><b id="designerStoneCount">0</b></div>
              <div><span>Accessories</span><b id="designerAccessoryCount">0</b></div>
              <div class="designer-length-row"><label for="designerLengthSelect">Length</label><select id="designerLengthSelect"><option value="16">16 cm</option><option value="17">17 cm</option><option value="18" selected>18 cm</option><option value="19">19 cm</option><option value="20">20 cm</option></select></div>
              <div><span>Used Length</span><b id="designerLength">0.0 / 18 cm</b></div>
              <div class="designer-total"><span>Total</span><b id="designerTotal">$10 AUD</b></div>
            </div>
            <div class="designer-materials">
              <h3>Bracelet Information</h3>
              <p id="designerMaterials">Start by choosing a stone from the left panel.</p>
            </div>
            <button class="black-button" type="button" id="addCustomDesign">Add Custom Bracelet to Cart</button>
          </aside>
        </div>
      </section>
    </main>

    <footer class="site-footer"><div class="footer-main"><div class="footer-brand"><img src="assets/logo-mark.png" alt=""><div><h2>Soul Stones</h2><div class="mini">Intentional Handmade Jewelry</div></div><p>Meaningful handmade jewelry crafted with crystals, natural gemstones and symbolic charms for every chapter of your life.</p><div class="socials"><a href="#">◎</a><a href="#">in</a><a href="#">X</a><a href="#">♪</a></div></div><div class="footer-col"><h3>Shop</h3><a href="shop.php#collection-gallery">Collection Gallery</a><a href="custom-design.php">Custom Design</a><a href="shop.php#new-arrivals">New Arrivals</a><a href="shop.php#gift-ideas">Gift Ideas</a></div><div class="footer-col"><h3>Themes</h3><a href="shop.php">Self Love</a><a href="shop.php">Protection</a><a href="shop.php">Transformation</a><a href="shop.php">Explore More...</a></div><div class="footer-col"><h3>About Us</h3><a href="about.php">Our Story</a><a href="materials.php">Materials</a><a href="materials.php#care-guide">Care guide</a><a href="about.php#contact">Contact</a></div></div><div class="footer-bottom"><span>© 2026 Soul Stones. All rights reserved.</span><div class="legal"><a href="#">Privacy</a><a href="#">Terms</a></div></div></footer>
    <div class="toast"></div><script src="script.js?v=custom-design-accessories"></script>
  </body>
</html>
