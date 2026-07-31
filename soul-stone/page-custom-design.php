<?php
/* Template Name: Soul Stone Custom Design */
get_header();
?>
<main>
      <section class="custom-design-intro">
        <div>
          <span class="eyebrow">Design your piece</span>
          <h1>Custom Design</h1>
        </div>
        <div class="custom-intro-copy">
          <p><span>Choose stones, pendants and bracelet details, then watch your bracelet build itself in the center.</span><span>Prices and materials update as you design.</span></p>
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
            <p class="designer-save-note"><?php echo is_user_logged_in() ? 'This design will be saved to your account when added to cart.' : 'Login before adding to cart if you want this design saved to your account.'; ?></p>
          </aside>
        </div>
      </section>
    </main>
<?php get_footer(); ?>
