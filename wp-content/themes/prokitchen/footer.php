</main><!-- #pk-main -->

<!-- ═══════════════════════════════════
     PRE-FOOTER CTA STRIP
════════════════════════════════════ -->
<section class="pk-cta-strip">
    <div class="pk-container">
        <div class="pk-cta-strip__inner">
            <div class="pk-cta-strip__item">
                <?php echo pk_icon('truck'); ?>
                <div>
                    <strong>Free Delivery Over $2,500</strong>
                    <span>Nationwide commercial delivery</span>
                </div>
            </div>
            <div class="pk-cta-strip__item">
                <?php echo pk_icon('shield'); ?>
                <div>
                    <strong>NSF Certified Equipment</strong>
                    <span>Meets all health code standards</span>
                </div>
            </div>
            <div class="pk-cta-strip__item">
                <?php echo pk_icon('dollar'); ?>
                <div>
                    <strong>Flexible Financing</strong>
                    <span>$0 down, terms up to 60 months</span>
                </div>
            </div>
            <div class="pk-cta-strip__item">
                <?php echo pk_icon('wrench'); ?>
                <div>
                    <strong>White-Glove Install</strong>
                    <span>Professional setup included</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════
     FOOTER
════════════════════════════════════ -->
<footer class="pk-footer">
    <div class="pk-container">

        <div class="pk-footer__grid">

            <!-- Brand Column -->
            <div class="pk-footer__brand">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="pk-logo pk-logo--light">
                    <span class="pk-logo__icon">⚙</span>
                    <div class="pk-logo__text">
                        <span class="pk-logo__primary">ProKitchen</span>
                        <span class="pk-logo__secondary">Equipment Co.</span>
                    </div>
                </a>
                <p class="pk-footer__tagline">
                    Commercial-grade kitchen equipment for restaurants, hotels, and food service operations. We don't sell demo units. Every piece ships ready to work.
                </p>
                <div class="pk-footer__contact-info">
                    <a href="tel:8007765484"><?php echo pk_icon('phone'); ?> (800) 776-5484</a>
                    <a href="mailto:sales@prokitchen.com"><?php echo pk_icon('email'); ?> sales@prokitchen.com</a>
                </div>
            </div>

            <!-- Shop Column -->
            <div class="pk-footer__col">
                <h4 class="pk-footer__heading">Shop Equipment</h4>
                <ul class="pk-footer__links">
                    <li><a href="<?php echo home_url('/product-category/commercial-refrigeration'); ?>">Commercial Refrigeration</a></li>
                    <li><a href="<?php echo home_url('/product-category/ovens-ranges'); ?>">Ovens &amp; Ranges</a></li>
                    <li><a href="<?php echo home_url('/product-category/prep-tables'); ?>">Prep Tables</a></li>
                    <li><a href="<?php echo home_url('/product-category/work-tables'); ?>">Stainless Work Tables</a></li>
                    <li><a href="<?php echo home_url('/product-category/display-cases'); ?>">Display Cases</a></li>
                    <li><a href="<?php echo home_url('/shop'); ?>">View All</a></li>
                </ul>
            </div>

            <!-- Company Column -->
            <div class="pk-footer__col">
                <h4 class="pk-footer__heading">Company</h4>
                <ul class="pk-footer__links">
                    <li><a href="<?php echo home_url('/about'); ?>">About Us</a></li>
                    <li><a href="<?php echo home_url('/financing'); ?>">Financing Options</a></li>
                    <li><a href="<?php echo home_url('/delivery'); ?>">Delivery &amp; Install</a></li>
                    <li><a href="<?php echo home_url('/contact'); ?>">Contact Us</a></li>
                    <li><a href="<?php echo home_url('/shop'); ?>">Shop</a></li>
                </ul>
            </div>

            <!-- Hours Column -->
            <div class="pk-footer__col">
                <h4 class="pk-footer__heading">Hours &amp; Support</h4>
                <ul class="pk-footer__hours">
                    <li><span>Monday – Friday</span><span>7:00am – 6:00pm CT</span></li>
                    <li><span>Saturday</span><span>8:00am – 2:00pm CT</span></li>
                    <li><span>Sunday</span><span>Closed</span></li>
                </ul>
                <div class="pk-footer__badges">
                    <span class="pk-badge">NSF Certified</span>
                    <span class="pk-badge">Energy Star</span>
                    <span class="pk-badge">ETL Listed</span>
                </div>
            </div>

        </div>

        <!-- Footer Bottom -->
        <div class="pk-footer__bottom">
            <p>&copy; <?php echo date('Y'); ?> ProKitchen Equipment Co. All rights reserved.</p>
            <div class="pk-footer__bottom-links">
                <a href="<?php echo home_url('/privacy-policy'); ?>">Privacy Policy</a>
                <a href="<?php echo home_url('/terms'); ?>">Terms of Service</a>
                <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                <a href="<?php echo wc_get_cart_url(); ?>">Cart</a>
                <?php endif; ?>
            </div>
        </div>

    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
