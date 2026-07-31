<?php get_header(); ?>
<main>
      <section class="hero">
        <div class="hero-copy">
          <span class="hero-eyebrow">Intentional handmade jewelry</span>
          <h1>Soul Stone</h1>
          <div class="tagline">Designed with intention. Worn with meaning.</div>
          <p>Crystal bracelets and symbolic pieces made for self-love, protection, clarity and new beginnings.</p>
          <div class="hero-actions">
            <a class="gold-button" href="<?php echo esc_url(soul_stone_page_url('custom-design')); ?>">Start Designing <span aria-hidden="true">→</span></a>
            <a class="outline-button" href="<?php echo esc_url(soul_stone_page_url('shop')); ?>">Shopping</a>
          </div>
        </div>
      </section>

      <section class="section" id="intentions">
        <span class="eyebrow center-title">Our Signature Intentions</span>
        <h2 class="section-title center-title">Five intentions. Infinite possibilities.</h2>
        <div class="intention-row">
          <article class="intention-item">
            <div>
              <div class="symbol">T</div>
              <h3>Transformation</h3>
              <p>Release the old and step into your power.</p>
              <a class="text-link" href="<?php echo esc_url(soul_stone_page_url('shop')); ?>">Explore <span>→</span></a>
            </div>
            <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/collection-bracelets.png" alt="Transformation crystal bracelet">
          </article>
          <article class="intention-item">
            <div>
              <div class="symbol">S</div>
              <h3>Self Love</h3>
              <p>Nurture your heart and honor your worth.</p>
              <a class="text-link" href="<?php echo esc_url(soul_stone_page_url('shop')); ?>">Explore <span>→</span></a>
            </div>
            <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/collection-bracelets.png" alt="Self Love crystal bracelet">
          </article>
          <article class="intention-item">
            <div>
              <div class="symbol">P</div>
              <h3>Protection</h3>
              <p>Set boundaries. Stay grounded. Feel safe.</p>
              <a class="text-link" href="<?php echo esc_url(soul_stone_page_url('shop')); ?>">Explore <span>→</span></a>
            </div>
            <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/collection-bracelets.png" alt="Protection crystal bracelet">
          </article>
          <article class="intention-item">
            <div>
              <div class="symbol">F</div>
              <h3>Focus</h3>
              <p>Clear your mind and manifest with clarity.</p>
              <a class="text-link" href="<?php echo esc_url(soul_stone_page_url('shop')); ?>">Explore <span>→</span></a>
            </div>
            <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/collection-bracelets.png" alt="Focus crystal bracelet">
          </article>
          <article class="intention-item">
            <div>
              <div class="symbol">N</div>
              <h3>New Beginning</h3>
              <p>Embrace change and move forward with grace.</p>
              <a class="text-link" href="<?php echo esc_url(soul_stone_page_url('shop')); ?>">Explore <span>→</span></a>
            </div>
            <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/collection-bracelets.png" alt="New Beginning crystal bracelet">
          </article>
        </div>
      </section>

      <section class="section" id="custom-preview">
        <div class="custom-panel">
          <div class="custom-copy">
            <span class="eyebrow">Design Your Bracelet</span>
            <h2>Create with intention, wear with meaning.</h2>
            <p>Choose your intention, select the stones that speak to you, and add a charm that carries your story.</p>
            <div class="design-steps" aria-label="Custom design steps">
              <span><b>1</b>Choose Intention</span>
              <span><b>2</b>Select Stones</span>
              <span><b>3</b>Choose Charm</span>
              <span><b>4</b>Review & Create</span>
            </div>
            <div class="intent-buttons" role="tablist" aria-label="Choose a custom bracelet intention">
              <button class="active" type="button" data-custom-intent="Transformation" role="tab" aria-selected="true"><b>T</b>Transformation</button>
              <button type="button" data-custom-intent="Self Love" role="tab" aria-selected="false"><b>S</b>Self Love</button>
              <button type="button" data-custom-intent="Protection" role="tab" aria-selected="false"><b>P</b>Protection</button>
              <button type="button" data-custom-intent="Focus" role="tab" aria-selected="false"><b>F</b>Focus</button>
              <button type="button" data-custom-intent="New Beginning" role="tab" aria-selected="false"><b>N</b>New Beginning</button>
            </div>
          </div>
          <div class="custom-detail">
            <div class="custom-image" id="customImage" role="img" aria-label="Transformation bracelet preview"></div>
            <aside class="custom-dark">
              <span class="eyebrow" id="customIntentLabel">Transformation</span>
              <h3 id="customIntentTitle">Release. Renew. Rise.</h3>
              <p id="customIntentDescription">A moonlit bracelet composition for personal growth, courage and new chapters.</p>
              <div class="stone-mini-list" id="customStoneList">
                <span>Moonstone · New beginnings and intuition</span>
                <span>Amethyst · Calm and clarity</span>
                <span>Rose Quartz · Self-love and compassion</span>
                <span>Obsidian · Protection and grounding</span>
              </div>
              <a class="black-button" href="<?php echo esc_url(soul_stone_page_url('custom-design')); ?>">Start Custom Design <span>→</span></a>
            </aside>
          </div>
        </div>
      </section>

      <section class="section" id="stone-guide">
        <span class="eyebrow center-title">The Meaning Behind The Stones</span>
        <h2 class="section-title center-title">Natural beauty. Deeper meaning.</h2>
        <div class="stone-guide-row">
          <article>
            <img class="stone-photo" src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/stone-moonstone.png" alt="Moonstone gemstone">
            <div>
              <h3>Moonstone</h3>
              <strong>New beginnings · intuition</strong>
              <p>A gentle reminder to trust your intuition and embrace change with grace.</p>
            </div>
          </article>
          <article>
            <img class="stone-photo" src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/stone-rose-quartz.png" alt="Rose quartz gemstone">
            <div>
              <h3>Rose Quartz</h3>
              <strong>Self love · compassion</strong>
              <p>Opens the heart to love yourself and others with tenderness.</p>
            </div>
          </article>
          <article>
            <img class="stone-photo" src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/stone-obsidian.png" alt="Obsidian gemstone">
            <div>
              <h3>Obsidian</h3>
              <strong>Protection · grounding</strong>
              <p>Shields against negativity and helps you release what no longer serves you.</p>
            </div>
          </article>
          <article>
            <img class="stone-photo" src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/stone-amethyst.png" alt="Amethyst gemstone">
            <div>
              <h3>Amethyst</h3>
              <strong>Calm · clarity</strong>
              <p>Soothes the mind and supports focus through your spiritual journey.</p>
            </div>
          </article>
        </div>
             <a href="<?php echo esc_url(soul_stone_page_url('materials')); ?>" style="display: flex; justify-content: center; align-items: center ; margin-top: 30px; color: rgb(174, 138, 94); text-decoration: underline" > Know more  →  </a>
      </section>

      <section class="section review-panel" id="reviews">
        <div class="review-image" role="img" aria-label="Soul Stone collection bracelets"></div>
        <div>
          <span class="eyebrow">Customer Love</span>
          <h2>Loved for meaning</h2>
          <h2>made for everyday wear.</h2>
          <p>“I love that each bracelet is designed around an intention. It feels personal, not just decorative.”</p>
          <p>Join the first Soul Stone list for new drops, custom openings, stone guides, and meaningful gift ideas.</p>
          <form class="form-grid" data-form="Welcome to Soul Stone. Your first stone guide is on the way.">
            <input type="email" required placeholder="Your email address" aria-label="Email address">
            <button class="black-button" type="submit">Join The List <span>→</span></button>
          </form>
        </div>
      </section>
    </main>
<?php get_footer(); ?>
