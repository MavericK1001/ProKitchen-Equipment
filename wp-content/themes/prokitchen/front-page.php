<?php
/**
 * Front Page Template — ProKitchen Equipment
 */
get_header();
?>

<!-- ═══════════════════════════════════
     HERO
════════════════════════════════════ -->
<section class="pk-hero">
    <div class="pk-hero__bg">
        <img src="https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=1920&q=80&auto=format"
             alt="Commercial kitchen" loading="eager">
    </div>
    <div class="pk-hero__grid-overlay"></div>

    <div class="pk-container">
        <div class="pk-hero__content pk-animate">
            <span class="pk-label pk-hero__label">Commercial Kitchen Equipment</span>

            <h1 class="pk-hero__headline">
                Built For<br>
                <em>Professional</em><br>
                Kitchens.
            </h1>

            <p class="pk-hero__sub">
                NSF-certified refrigerators, ovens, prep tables, and display cases for restaurants, hotels, and food service operations. Ships ready to work — no setup fees, no surprises.
            </p>

            <div class="pk-hero__actions">
                <a href="<?php echo home_url('/shop'); ?>" class="pk-btn pk-btn--gold pk-btn--lg">
                    Shop All Equipment <?php echo pk_icon('arrow'); ?>
                </a>
                <a href="<?php echo home_url('/contact'); ?>" class="pk-btn pk-btn--outline pk-btn--lg">
                    Request a Quote
                </a>
            </div>

            <div class="pk-hero__stats">
                <div class="pk-hero__stat">
                    <span class="pk-hero__stat-num">2,400+</span>
                    <span class="pk-hero__stat-label">Units Sold</span>
                </div>
                <div class="pk-hero__stat">
                    <span class="pk-hero__stat-num">850+</span>
                    <span class="pk-hero__stat-label">Restaurant Clients</span>
                </div>
                <div class="pk-hero__stat">
                    <span class="pk-hero__stat-num">48hr</span>
                    <span class="pk-hero__stat-label">Avg. Ship Time</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════
     CATEGORIES
════════════════════════════════════ -->
<section class="pk-categories">
    <div class="pk-container">
        <div class="pk-section-header pk-section-header--center">
            <span class="pk-label">Browse by Category</span>
            <h2>Every Station. Every Need.</h2>
            <p>From walk-in fridges to deck ovens — full commercial kitchen buildouts, single pieces, or complete replacements.</p>
        </div>

        <div class="pk-categories__grid">

            <a href="<?php echo home_url('/product-category/commercial-refrigeration'); ?>" class="pk-cat-card">
                <span class="pk-cat-card__icon">🧊</span>
                <span class="pk-cat-card__name">Commercial Refrigeration</span>
                <span class="pk-cat-card__count">Reach-ins, Freezers, Prep Units</span>
            </a>

            <a href="<?php echo home_url('/product-category/ovens-ranges'); ?>" class="pk-cat-card">
                <span class="pk-cat-card__icon">🔥</span>
                <span class="pk-cat-card__name">Ovens &amp; Ranges</span>
                <span class="pk-cat-card__count">Convection, Deck, Gas Ranges</span>
            </a>

            <a href="<?php echo home_url('/product-category/prep-tables'); ?>" class="pk-cat-card">
                <span class="pk-cat-card__icon">🥗</span>
                <span class="pk-cat-card__name">Prep Tables</span>
                <span class="pk-cat-card__count">Sandwich, Salad, Mega-Top</span>
            </a>

            <a href="<?php echo home_url('/product-category/work-tables'); ?>" class="pk-cat-card">
                <span class="pk-cat-card__icon">⚙️</span>
                <span class="pk-cat-card__name">Stainless Work Tables</span>
                <span class="pk-cat-card__count">NSF Certified, 16-18 Gauge</span>
            </a>

            <a href="<?php echo home_url('/product-category/display-cases'); ?>" class="pk-cat-card">
                <span class="pk-cat-card__icon">🪟</span>
                <span class="pk-cat-card__name">Display Cases</span>
                <span class="pk-cat-card__count">Refrigerated, Open-Air, Curved</span>
            </a>

        </div>
    </div>
</section>

<!-- ═══════════════════════════════════
     FEATURED PRODUCTS
════════════════════════════════════ -->
<section class="pk-featured-products pk-section">
    <div class="pk-container">
        <div class="pk-section-header pk-flex-between">
            <div>
                <span class="pk-label">Top Sellers</span>
                <h2>Featured Equipment</h2>
            </div>
            <a href="<?php echo home_url('/shop'); ?>" class="pk-btn pk-btn--ghost">
                View All <?php echo pk_icon('arrow'); ?>
            </a>
        </div>

        <?php
        if ( class_exists( 'WooCommerce' ) ) :
            $products = wc_get_products( [
                'limit'    => 6,
                'status'   => 'publish',
                'featured' => true,
                'orderby'  => 'date',
                'order'    => 'DESC',
            ] );

            if ( empty( $products ) ) {
                $products = wc_get_products( [
                    'limit'   => 6,
                    'status'  => 'publish',
                    'orderby' => 'date',
                    'order'   => 'DESC',
                ] );
            }

            if ( ! empty( $products ) ) :
        ?>
        <div class="pk-featured-products__grid">
            <?php foreach ( $products as $product ) : ?>
            <div class="pk-product-card">
                <div class="pk-product-card__image">
                    <a href="<?php echo get_permalink( $product->get_id() ); ?>">
                        <?php echo $product->get_image( 'prokitchen-product-thumb', [ 'loading' => 'lazy' ] ); ?>
                    </a>
                    <?php if ( $product->is_on_sale() ) : ?>
                    <span class="pk-product-card__badge">Sale</span>
                    <?php elseif ( $product->is_featured() ) : ?>
                    <span class="pk-product-card__badge">Featured</span>
                    <?php endif; ?>
                </div>

                <div class="pk-product-card__body">
                    <?php
                    $cats = get_the_terms( $product->get_id(), 'product_cat' );
                    if ( $cats && ! is_wp_error( $cats ) ) :
                    ?>
                    <span class="pk-product-card__cat"><?php echo esc_html( $cats[0]->name ); ?></span>
                    <?php endif; ?>

                    <h3 class="pk-product-card__name">
                        <a href="<?php echo get_permalink( $product->get_id() ); ?>">
                            <?php echo esc_html( $product->get_name() ); ?>
                        </a>
                    </h3>

                    <?php
                    $brand = get_post_meta( $product->get_id(), 'pk_brand', true );
                    $power = get_post_meta( $product->get_id(), 'pk_power', true );
                    if ( $brand || $power ) :
                    ?>
                    <div class="pk-product-card__meta">
                        <?php if ( $brand ) : ?>
                        <span class="pk-product-card__meta-tag"><?php echo esc_html( $brand ); ?></span>
                        <?php endif; ?>
                        <?php if ( $power ) : ?>
                        <span class="pk-product-card__meta-tag"><?php echo esc_html( $power ); ?></span>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="pk-product-card__footer">
                    <div>
                        <span class="pk-product-card__price">
                            <?php echo $product->get_price_html(); ?>
                        </span>
                        <span class="pk-product-card__price-note">or finance from $<?php echo round( (float) $product->get_price() / 60, 0 ); ?>/mo</span>
                    </div>
                    <div class="pk-product-card__cta">
                        <a href="<?php echo get_permalink( $product->get_id() ); ?>" class="pk-btn pk-btn--gold pk-btn--sm">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else : ?>
        <div class="pk-notice pk-notice--info">
            <?php echo pk_icon('check'); ?>
            Products are loading. <a href="<?php echo admin_url('edit.php?post_type=product'); ?>">Add products in the admin</a>.
        </div>
        <?php endif; else : ?>
        <div class="pk-notice pk-notice--info">
            WooCommerce is not active. <a href="<?php echo admin_url('plugins.php'); ?>">Activate it here</a>.
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- ═══════════════════════════════════
     WHY PROKITCHEN
════════════════════════════════════ -->
<section class="pk-features">
    <div class="pk-container">
        <div class="pk-section-header pk-section-header--center">
            <span class="pk-label">Why ProKitchen</span>
            <h2>The Standard for Commercial Kitchens</h2>
            <p>We've outfitted over 850 restaurants, hotels, and ghost kitchens. Here's why they keep coming back.</p>
        </div>

        <div class="pk-features__grid">
            <div class="pk-feature-item">
                <div class="pk-feature-item__icon"><?php echo pk_icon('shield'); ?></div>
                <h4 class="pk-feature-item__title">NSF Certified Only</h4>
                <p class="pk-feature-item__text">Every unit meets NSF/ANSI standards. Pass your health inspection or we'll replace it.</p>
            </div>
            <div class="pk-feature-item">
                <div class="pk-feature-item__icon"><?php echo pk_icon('truck'); ?></div>
                <h4 class="pk-feature-item__title">48-Hour Ship Time</h4>
                <p class="pk-feature-item__text">In-stock items ship within 48 hours. We track every order and update you automatically.</p>
            </div>
            <div class="pk-feature-item">
                <div class="pk-feature-item__icon"><?php echo pk_icon('wrench'); ?></div>
                <h4 class="pk-feature-item__title">White-Glove Install</h4>
                <p class="pk-feature-item__text">Our crews handle delivery, positioning, hookup, and testing. You run your kitchen.</p>
            </div>
            <div class="pk-feature-item">
                <div class="pk-feature-item__icon"><?php echo pk_icon('dollar'); ?></div>
                <h4 class="pk-feature-item__title">Flexible Financing</h4>
                <p class="pk-feature-item__text">$0 down, terms from 12–60 months. Don't let cash flow stop you from opening on time.</p>
            </div>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════
     FINANCING BANNER
════════════════════════════════════ -->
<section class="pk-financing-banner">
    <div class="pk-container">
        <div class="pk-financing-banner__inner">
            <div>
                <span class="pk-label pk-financing-banner__label">Equipment Financing</span>
                <h2>Don't Let Budget Stop Your Buildout</h2>
                <p style="color: var(--pk-text-2); max-width: 480px;">
                    We partner with commercial equipment lenders to get your kitchen open faster. Approvals in 24 hours. Terms that work around your revenue cycles.
                </p>
                <div class="pk-financing-banner__perks">
                    <div class="pk-financing-banner__perk"><?php echo pk_icon('check'); ?> $0 Down Options</div>
                    <div class="pk-financing-banner__perk"><?php echo pk_icon('check'); ?> 12–60 Month Terms</div>
                    <div class="pk-financing-banner__perk"><?php echo pk_icon('check'); ?> Same-Day Approval</div>
                    <div class="pk-financing-banner__perk"><?php echo pk_icon('check'); ?> All Credit Considered</div>
                </div>
            </div>
            <div class="pk-financing-banner__cta">
                <span class="pk-financing-banner__rate">From<br>$89<span style="font-size:1.5rem">/mo</span></span>
                <span class="pk-financing-banner__rate-note">On orders over $3,000</span>
                <a href="<?php echo home_url('/financing'); ?>" class="pk-btn pk-btn--gold pk-btn--lg">
                    Explore Financing <?php echo pk_icon('arrow'); ?>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════
     TESTIMONIALS
════════════════════════════════════ -->
<section class="pk-testimonials pk-section pk-section--dark">
    <div class="pk-container">
        <div class="pk-section-header pk-section-header--center">
            <span class="pk-label">Client Reviews</span>
            <h2>What Operators Say</h2>
        </div>

        <div class="pk-testimonials__grid">
            <div class="pk-testimonial-card">
                <div class="pk-testimonial-card__stars">
                    <?php for($i=0;$i<5;$i++) echo pk_icon('star'); ?>
                </div>
                <p class="pk-testimonial-card__text">"Outfitted our new steakhouse with two True T-49s and a Vulcan convection oven. Everything was on-site in 3 days. Install crew was professional. Zero issues after 8 months."</p>
                <div class="pk-testimonial-card__author">
                    <div class="pk-testimonial-card__avatar">MR</div>
                    <div>
                        <span class="pk-testimonial-card__name">Marco Rodriguez</span>
                        <span class="pk-testimonial-card__biz">Ember &amp; Oak Steakhouse, Dallas TX</span>
                    </div>
                </div>
            </div>

            <div class="pk-testimonial-card">
                <div class="pk-testimonial-card__stars">
                    <?php for($i=0;$i<5;$i++) echo pk_icon('star'); ?>
                </div>
                <p class="pk-testimonial-card__text">"Used their financing to get $18k of equipment with $0 down. 36 months, manageable payments. The approval took less than an hour. This is how it should work."</p>
                <div class="pk-testimonial-card__author">
                    <div class="pk-testimonial-card__avatar">SK</div>
                    <div>
                        <span class="pk-testimonial-card__name">Sarah Kim</span>
                        <span class="pk-testimonial-card__biz">Ghost Kitchen Group, Chicago IL</span>
                    </div>
                </div>
            </div>

            <div class="pk-testimonial-card">
                <div class="pk-testimonial-card__stars">
                    <?php for($i=0;$i<5;$i++) echo pk_icon('star'); ?>
                </div>
                <p class="pk-testimonial-card__text">"Third buildout I've done with ProKitchen. Same experience every time — clear pricing, fast shipping, they actually know the equipment. Recommended to all my colleagues."</p>
                <div class="pk-testimonial-card__author">
                    <div class="pk-testimonial-card__avatar">JP</div>
                    <div>
                        <span class="pk-testimonial-card__name">James Patel</span>
                        <span class="pk-testimonial-card__biz">Patel Hospitality Group, Atlanta GA</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════
     BRANDS
════════════════════════════════════ -->
<section class="pk-brands">
    <div class="pk-container">
        <p class="pk-label pk-text-center" style="margin-bottom:2rem;">Authorized Dealer — Trusted Brands</p>
        <div class="pk-brands__grid">
            <span class="pk-brand-logo">TRUE</span>
            <span class="pk-brand-logo">ATOSA</span>
            <span class="pk-brand-logo">VULCAN</span>
            <span class="pk-brand-logo">BLODGETT</span>
            <span class="pk-brand-logo">COOLTECH</span>
            <span class="pk-brand-logo">IMPERIAL</span>
            <span class="pk-brand-logo">FEDERAL IND.</span>
        </div>
    </div>
</section>

<?php get_footer(); ?>
