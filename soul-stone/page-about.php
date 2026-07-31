<?php
/* Template Name: Soul Stone About */
get_header();
?>
<main>
      <section class="about-hero">
        <div>
          <span class="eyebrow">About Soul Stone</span>
          <h1>Jewelry for the chapters you are becoming.</h1>
          <p>Soul Stone creates personalized crystal jewelry inspired by spirituality, self-growth and connection. Each bracelet is designed to feel like a small ritual: a reminder of what you are protecting, healing, focusing on or beginning again.</p>
          <a class="black-button" href="<?php echo esc_url(soul_stone_page_url('custom-design')); ?>">Start Designing <span>→</span></a>
        </div>
        <div class="about-hero-image" role="img" aria-label="Soul Stone studio materials and bracelet making surface"></div>
      </section>

      <section class="section about-story" id="our-story">
        <div>
          <span class="eyebrow">Our Story</span>
          <h2>Meaning first, beauty always.</h2>
        </div>
        <div class="about-story-copy">
          <p>Soul Stone began with a simple idea: a bracelet can be more than something pretty. It can hold a wish, mark a turning point, or become a quiet object you reach for when life feels uncertain.</p>
          <p>Our pieces are made for young women, students, new graduates, working women and anyone moving through a new stage. We build each design around an intention, then pair natural stones, symbolic charms and soft color stories to make that intention wearable.</p>
          <p>Whether you choose Self Love, Protection, Focus, Transformation or New Beginning, the goal is the same: jewelry that feels personal, meaningful and easy to wear every day.</p>
        </div>
      </section>

      <section class="about-quote">
        <p>“A Soul Stone piece is not only an accessory. It is a small promise you can carry with you.”</p>
      </section>

      <section class="section">
        <span class="eyebrow center-title">What We Believe</span>
        <h2 class="section-title center-title">Soft energy, thoughtful design.</h2>
        <div class="values-grid">
          <article class="value-card">
            <span>01</span>
            <h3>Intention</h3>
            <p>Every piece starts with a feeling or life moment, not just a color palette.</p>
          </article>
          <article class="value-card">
            <span>02</span>
            <h3>Personalization</h3>
            <p>Stones, charms and combinations can be chosen around your own story.</p>
          </article>
          <article class="value-card">
            <span>03</span>
            <h3>Everyday Ritual</h3>
            <p>We design pieces that feel special, but still comfortable for daily wear.</p>
          </article>
          <article class="value-card">
            <span>04</span>
            <h3>Meaningful Gifts</h3>
            <p>A bracelet can say “I see you” when words are hard to find.</p>
          </article>
        </div>
      </section>

      <section class="section">
        <span class="eyebrow center-title">How We Create</span>
        <h2 class="section-title center-title">From intention to bracelet.</h2>
        <div class="process-list">
          <article class="process-step">
            <b>1</b>
            <h3>Choose the intention</h3>
            <p>We begin with the energy you want to carry: love, protection, focus, transformation or a new beginning.</p>
          </article>
          <article class="process-step">
            <b>2</b>
            <h3>Select the stones</h3>
            <p>Each stone is chosen for visual harmony and symbolic meaning, then balanced into a wearable composition.</p>
          </article>
          <article class="process-step">
            <b>3</b>
            <h3>Add a charm</h3>
            <p>Moon, star, heart, butterfly or minimal metal details add a final layer of personal meaning.</p>
          </article>
          <article class="process-step">
            <b>4</b>
            <h3>Finish with care</h3>
            <p>The bracelet is prepared as a meaningful keepsake, ready to gift or keep close.</p>
          </article>
        </div>
      </section>

      <section class="section about-contact" id="contact">
        <div>
          <span class="eyebrow">Contact</span>
          <h2>Have a story you want to turn into a piece?</h2>
          <p>Reach out for custom bracelets, gifting ideas, market collaborations, wholesale enquiries or styling questions. Tell us the intention, occasion and colors you love, and we will help shape a piece around it.</p>
        </div>
        <form class="form-grid" data-form="Thank you. Your Soul Stone enquiry has been saved.">
          <input required placeholder="Your name" aria-label="Your name">
          <input type="email" required placeholder="Email address" aria-label="Email address">
          <input required placeholder="What intention or occasion is this for?" aria-label="Custom intention or occasion">
          <button class="black-button" type="submit">Send Enquiry <span>→</span></button>
        </form>
      </section>
    </main>
<?php get_footer(); ?>
