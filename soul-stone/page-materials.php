<?php
/* Template Name: Soul Stone Materials */
get_header();
?>
<main>
      <section class="materials-hero">
        <div>
          <span class="eyebrow">Stone Guide</span>
          <h1>Materials</h1>
          <p>Every Soul Stone piece begins with natural stones, symbolic meaning and a quiet ritual. Explore the crystals we use, what they represent, and how to care for your bracelet so it stays beautiful for everyday wear.</p>
        </div>
        <div class="materials-hero-art">
          <img class="hero-stone hero-stone-a" src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/material-stone-amethyst.svg" alt="">
          <img class="hero-stone hero-stone-b" src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/material-stone-rose-quartz.svg" alt="">
          <img class="hero-stone hero-stone-c" src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/material-stone-moonstone.svg" alt="">
          <img class="hero-stone hero-stone-d" src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/material-stone-obsidian.svg" alt="">
          <img class="hero-stone hero-stone-e" src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/material-stone-clear-quartz.svg" alt="">
          <div class="hero-stone-note">
            <span>Crystal library</span>
            <strong>Stone meanings, care notes and pairings for intentional bracelets.</strong>
          </div>
        </div>
      </section>

      <section class="section">
        <span class="eyebrow center-title">Crystal Meanings</span>
        <h2 class="section-title center-title">Choose the energy you want to carry.</h2>
        <div class="material-search">
          <label for="materialSearch">Search by stone name</label>
          <input id="materialSearch" type="search" placeholder="Search Amethyst, 紫水晶, 白发晶...">
        </div>
        <div class="material-browser" id="materialBrowser">
          <button class="material-arrow material-arrow-prev" type="button" data-material-prev aria-label="Previous materials">‹</button>
          <div class="material-grid" id="materialGrid">
          <article class="material-card" data-keywords="紫水晶">
            <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/material-stone-amethyst.svg" alt="Amethyst gemstone">
            <div>
              <h2>Amethyst</h2>
              <p>Associated with calm, clarity and focus. A thoughtful stone for study, reflection and steady emotional energy.</p>
              <div class="material-tags"><span>Focus</span><span>Calm</span><span>Wisdom</span></div>
              <button class="material-more" type="button" data-material-toggle>More details</button>
              <div class="material-extra">Best for study days, meditation and moments when you want a quieter mind. Pair with Clear Quartz for a brighter clarity theme.</div>
            </div>
          </article>
          <article class="material-card" data-keywords="粉晶">
            <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/material-stone-rose-quartz.svg" alt="Rose Quartz gemstone">
            <div>
              <h2>Rose Quartz</h2>
              <p>A soft, heart-led stone connected with self-love, tenderness and compassion. Perfect for meaningful gifts.</p>
              <div class="material-tags"><span>Self Love</span><span>Healing</span><span>Gentle</span></div>
              <button class="material-more" type="button" data-material-toggle>More details</button>
              <div class="material-extra">A warm choice for self-love bracelets, friendship gifts and gentle emotional support. It works beautifully with Moonstone.</div>
            </div>
          </article>
          <article class="material-card" data-keywords="黑曜石">
            <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/material-stone-obsidian.svg" alt="Obsidian gemstone">
            <div>
              <h2>Obsidian</h2>
              <p>A grounding dark stone often chosen for protection, boundaries and releasing what no longer serves you.</p>
              <div class="material-tags"><span>Protection</span><span>Grounding</span><span>Boundary</span></div>
              <button class="material-more" type="button" data-material-toggle>More details</button>
              <div class="material-extra">Best for protection-led designs and stronger visual contrast. Pair with Clear Quartz when you want a balanced light-dark story.</div>
            </div>
          </article>
          <article class="material-card" data-keywords="月光石">
            <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/material-stone-moonstone.svg" alt="Moonstone gemstone">
            <div>
              <h2>Moonstone</h2>
              <p>Luminous and gentle, moonstone is linked with intuition, emotional balance and new beginnings.</p>
              <div class="material-tags"><span>New Beginning</span><span>Intuition</span><span>Balance</span></div>
              <button class="material-more" type="button" data-material-toggle>More details</button>
              <div class="material-extra">A signature stone for new chapters, graduations and life transitions. Its soft glow keeps the bracelet delicate and wearable.</div>
            </div>
          </article>
          <article class="material-card" data-keywords="白水晶">
            <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/material-stone-clear-quartz.svg" alt="Clear Quartz gemstone">
            <div>
              <h2>Clear Quartz</h2>
              <p>A clear, versatile stone connected with clarity and amplification. It pairs beautifully with every intention.</p>
              <div class="material-tags"><span>Clarity</span><span>Amplify</span><span>Clean Energy</span></div>
              <button class="material-more" type="button" data-material-toggle>More details</button>
              <div class="material-extra">Use it as a clean base stone or as an accent that brightens another crystal's meaning. It is one of the easiest stones to style.</div>
            </div>
          </article>
          <article class="material-card" data-keywords="粉猫眼">
            <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/material-stone-pink-catseye.svg" alt="Pink Cat's Eye gemstone">
            <div>
              <h2>Pink Cat’s Eye</h2>
              <p>Known for its silky glow, pink cat’s eye adds softness, charm and a sweet sense of confidence.</p>
              <div class="material-tags"><span>Charm</span><span>Confidence</span><span>Soft Glow</span></div>
              <button class="material-more" type="button" data-material-toggle>More details</button>
              <div class="material-extra">A polished, playful option when you want a feminine bracelet with a little shine. Pair with Rose Quartz for softness.</div>
            </div>
          </article>
          <article class="material-card" data-keywords="白猫眼">
            <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/material-stone-white-catseye.svg" alt="White Cat's Eye gemstone">
            <div>
              <h2>White Cat’s Eye</h2>
              <p>A pearly stone with a focused light band, chosen for purity, direction and quiet daily protection.</p>
              <div class="material-tags"><span>Purity</span><span>Direction</span><span>Daily Wear</span></div>
              <button class="material-more" type="button" data-material-toggle>More details</button>
              <div class="material-extra">A clean everyday choice that feels minimal and luminous. It is useful when the design needs softness without pink tones.</div>
            </div>
          </article>
          <article class="material-card" data-keywords="海蓝宝">
            <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/material-stone-aquamarine.svg" alt="Aquamarine gemstone">
            <div>
              <h2>Aquamarine</h2>
              <p>A pale blue stone associated with calm communication, courage and a fresh, flowing mindset.</p>
              <div class="material-tags"><span>Calm</span><span>Courage</span><span>Communication</span></div>
              <button class="material-more" type="button" data-material-toggle>More details</button>
              <div class="material-extra">Best for calm, refreshing designs and gifts related to speaking up, travel, study or emotional reset.</div>
            </div>
          </article>
          <article class="material-card" data-keywords="银曜石">
            <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/material-stone-silver-obsidian.svg" alt="Silver Obsidian gemstone">
            <div>
              <h2>Silver Obsidian</h2>
              <p>A reflective obsidian variation with a silver sheen, often chosen for insight, protection and inner strength.</p>
              <div class="material-tags"><span>Insight</span><span>Protection</span><span>Strength</span></div>
              <button class="material-more" type="button" data-material-toggle>More details</button>
              <div class="material-extra">A more dimensional protection stone than classic black obsidian. It suits designs that feel mysterious but still refined.</div>
            </div>
          </article>
          <article class="material-card" data-keywords="白发晶">
            <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/material-stone-white-rutilated-quartz.svg" alt="White Rutilated Quartz gemstone">
            <div>
              <h2>White Rutilated Quartz</h2>
              <p>A bright quartz variation with fine needle-like inclusions, often chosen for clarity, renewal and focused confidence.</p>
              <div class="material-tags"><span>Clarity</span><span>Renewal</span><span>Confidence</span></div>
              <button class="material-more" type="button" data-material-toggle>More details</button>
              <div class="material-extra">A refined choice for clean, luminous designs. Use it when the bracelet needs a brighter energy than Clear Quartz, with a more distinctive natural texture.</div>
            </div>
          </article>
          </div>
          <button class="material-arrow material-arrow-next" type="button" data-material-next aria-label="Next materials">›</button>
        </div>
        <div class="material-pager" aria-label="Material pages">
          <span id="materialPageStatus">Page 1 of 3</span>
        </div>
      </section>

      <section class="section" id="care-guide">
        <div class="care-layout">
          <div class="care-intro">
            <span class="eyebrow">Care Guide</span>
            <h2 class="section-title">How to care for your crystals.</h2>
            <p>Crystal jewelry is made to be worn, gifted and kept close. A few gentle habits will help the stones, elastic and metal details stay beautiful longer.</p>
          </div>
          <div class="care-steps">
            <article class="care-step">
              <b>1</b>
              <div><h3>Keep it dry.</h3><p>Remove your bracelet before showering, swimming, exercising or sleeping. Water and sweat can weaken elastic and dull metal details over time.</p></div>
            </article>
            <article class="care-step">
              <b>2</b>
              <div><h3>Avoid chemicals.</h3><p>Put your bracelet on after perfume, lotion, sunscreen and hand cream have fully absorbed. This helps preserve the natural shine of the stones.</p></div>
            </article>
            <article class="care-step">
              <b>3</b>
              <div><h3>Store with softness.</h3><p>Keep pieces in a pouch or separate box so stones and charms do not rub against keys, coins or harder jewelry.</p></div>
            </article>
            <article class="care-step">
              <b>4</b>
              <div><h3>Clean gently.</h3><p>Use a soft dry cloth for daily care. For deeper cleaning, wipe lightly with a barely damp cloth and dry immediately.</p></div>
            </article>
          </div>
        </div>
      </section>

      <section class="section">
        <span class="eyebrow center-title">Q&A</span>
        <h2 class="section-title center-title">Questions before choosing a stone?</h2>
        <div class="faq-wrap">
          <details class="faq-item" open>
            <summary>How do I choose the right crystal?</summary>
            <p>Start with your intention rather than the stone name. If you want softness, look at Rose Quartz. For protection, Obsidian or Silver Obsidian. For study and calm, Amethyst. For a new chapter, Moonstone or Clear Quartz.</p>
          </details>
          <details class="faq-item">
            <summary>Can I mix different stones in one bracelet?</summary>
            <p>Yes. Soul Stone bracelets are designed around combinations. Clear Quartz pairs well with almost everything, while Moonstone can soften darker stones like Obsidian.</p>
          </details>
          <details class="faq-item">
            <summary>Are natural stones exactly the same color?</summary>
            <p>No. Natural stones may vary in color, texture, inclusions and light reflection. These small differences are part of what makes each handmade piece personal.</p>
          </details>
          <details class="faq-item">
            <summary>Can I wear crystal bracelets every day?</summary>
            <p>Yes, but daily wear needs gentle care. Keep the bracelet dry, avoid chemicals, and store it separately when you are not wearing it.</p>
          </details>
        </div>
      </section>
    </main>
<?php get_footer(); ?>
