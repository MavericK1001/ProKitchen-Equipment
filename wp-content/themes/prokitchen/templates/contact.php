<?php
/**
 * Page Template: Contact
 * Template Name: Contact
 */
get_header();
?>

<div class="pk-page-hero">
    <div class="pk-container pk-page-hero__inner">
        <span class="pk-label">We Respond Within 2 Hours</span>
        <h1>Contact Us</h1>
        <p class="pk-lead">Quote requests, volume pricing, delivery scheduling, technical questions. Real people, real answers.</p>
    </div>
</div>

<section class="pk-section pk-section--dark">
    <div class="pk-container">
        <div style="display:grid; grid-template-columns:1fr 400px; gap:4rem; align-items:start;">

            <!-- Contact Form -->
            <div>
                <h2 style="margin-bottom:1.5rem;">Send a Message</h2>
                <div style="background:var(--pk-dark-card); border:1px solid var(--pk-border); border-top:3px solid var(--pk-gold); border-radius:var(--pk-radius-lg); padding:2.5rem;">
                    <form class="pk-contact-form" id="main-contact-form">
                        <?php wp_nonce_field( 'pk_contact_submit', 'contact_nonce' ); ?>
                        <input type="hidden" name="form_type" value="contact">
                        <div class="pk-form-grid">
                            <div class="pk-form-group">
                                <label>Full Name *</label>
                                <input type="text" name="name" required placeholder="Your name">
                            </div>
                            <div class="pk-form-group">
                                <label>Restaurant / Company</label>
                                <input type="text" name="company" placeholder="Your business">
                            </div>
                            <div class="pk-form-group">
                                <label>Email Address *</label>
                                <input type="email" name="email" required placeholder="you@example.com">
                            </div>
                            <div class="pk-form-group">
                                <label>Phone Number</label>
                                <input type="tel" name="phone" placeholder="(555) 000-0000">
                            </div>
                            <div class="pk-form-group pk-form-group--full">
                                <label>Subject</label>
                                <select name="subject">
                                    <option value="quote">Request a Quote</option>
                                    <option value="order">Order Status / Tracking</option>
                                    <option value="technical">Technical Question</option>
                                    <option value="financing">Financing Inquiry</option>
                                    <option value="delivery">Delivery / Installation</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div class="pk-form-group pk-form-group--full">
                                <label>Message *</label>
                                <textarea name="message" required placeholder="Tell us about your project, equipment needs, or question..."></textarea>
                            </div>
                        </div>
                        <button type="submit" class="pk-btn pk-btn--gold pk-btn--full pk-btn--lg">
                            Send Message <?php echo pk_icon('arrow'); ?>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Contact Info -->
            <div>
                <h2 style="margin-bottom:1.5rem;">Get In Touch</h2>

                <div style="display:flex; flex-direction:column; gap:1.25rem;">
                    <div style="background:var(--pk-dark-card); border:1px solid var(--pk-border); border-radius:var(--pk-radius-lg); padding:1.5rem;">
                        <div style="color:var(--pk-gold); margin-bottom:0.75rem;"><?php echo pk_icon('phone'); ?></div>
                        <div class="pk-label" style="margin-bottom:0.25rem;">Sales &amp; Quotes</div>
                        <a href="tel:8007765484" style="font-size:1.5rem; font-weight:700; color:var(--pk-white); font-family:var(--pk-font-heading);">(800) 776-5484</a>
                        <p style="font-size:0.8rem; margin-top:0.25rem;">Mon–Fri 7am–6pm CT · Sat 8am–2pm CT</p>
                    </div>

                    <div style="background:var(--pk-dark-card); border:1px solid var(--pk-border); border-radius:var(--pk-radius-lg); padding:1.5rem;">
                        <div style="color:var(--pk-gold); margin-bottom:0.75rem;"><?php echo pk_icon('email'); ?></div>
                        <div class="pk-label" style="margin-bottom:0.25rem;">Email Us</div>
                        <a href="mailto:sales@prokitchen.com" style="font-size:1.05rem; color:var(--pk-gold);">sales@prokitchen.com</a>
                        <p style="font-size:0.8rem; margin-top:0.25rem;">We reply within 2 business hours</p>
                    </div>

                    <div style="background:var(--pk-dark-card); border:1px solid var(--pk-border); border-radius:var(--pk-radius-lg); padding:1.5rem;">
                        <div class="pk-label" style="margin-bottom:0.75rem;">Business Hours</div>
                        <ul style="display:flex;flex-direction:column;gap:0.4rem;">
                            <li style="display:flex;justify-content:space-between;font-size:0.875rem;color:var(--pk-text-2);"><span>Monday – Friday</span><span>7:00am – 6:00pm CT</span></li>
                            <li style="display:flex;justify-content:space-between;font-size:0.875rem;color:var(--pk-text-2);"><span>Saturday</span><span>8:00am – 2:00pm CT</span></li>
                            <li style="display:flex;justify-content:space-between;font-size:0.875rem;color:var(--pk-text-3);"><span>Sunday</span><span>Closed</span></li>
                        </ul>
                    </div>

                    <div style="background:var(--pk-gold-alpha); border:1px solid var(--pk-gold-dark); border-radius:var(--pk-radius-lg); padding:1.5rem;">
                        <div class="pk-label" style="margin-bottom:0.5rem;">Emergency Service</div>
                        <p style="font-size:0.875rem; color:var(--pk-text-2);">Equipment down during service? Call our emergency line for priority support.</p>
                        <a href="tel:8007765484" class="pk-btn pk-btn--gold pk-btn--sm" style="margin-top:0.875rem;">
                            <?php echo pk_icon('phone'); ?> Emergency Line
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<?php get_footer(); ?>
