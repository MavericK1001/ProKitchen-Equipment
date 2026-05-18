<?php
/**
 * WooCommerce — Custom Single Product Page
 * Overrides: woocommerce/single-product.php + content-single-product.php
 */
defined( 'ABSPATH' ) || exit;
get_header( 'shop' );

while ( have_posts() ) :
    the_post();
    global $product;
    $product_id = $product->get_id();

    // Custom meta
    $pk_brand       = get_post_meta( $product_id, 'pk_brand',       true );
    $pk_power       = get_post_meta( $product_id, 'pk_power',       true );
    $pk_capacity    = get_post_meta( $product_id, 'pk_capacity',    true );
    $pk_dimensions  = get_post_meta( $product_id, 'pk_dimensions',  true );
    $pk_nsf         = get_post_meta( $product_id, 'pk_nsf',         true );
    $pk_weight      = get_post_meta( $product_id, 'pk_weight',      true );
    $pk_sku         = $product->get_sku();

    $price       = (float) $product->get_price();
    $monthly_est = $price > 0 ? round( $price / 60, 0 ) : 0;

    $cats = get_the_terms( $product_id, 'product_cat' );
    $cat_name = ( $cats && ! is_wp_error( $cats ) ) ? $cats[0]->name : '';
?>

<!-- Breadcrumb -->
<div style="background: var(--pk-black); border-bottom: 1px solid var(--pk-border); padding: 0.875rem 0;">
    <div class="pk-container">
        <?php woocommerce_breadcrumb(); ?>
    </div>
</div>

<!-- ═══════════════════════════════════
     PRODUCT LAYOUT
════════════════════════════════════ -->
<article class="pk-product-page pk-section--sm" id="product-<?php the_ID(); ?>" <?php wc_product_class( '', $product ); ?>>
    <div class="pk-container">
        <div class="pk-product-layout">

            <!-- ─── LEFT: GALLERY ─── -->
            <div class="pk-product-gallery">
                <?php
                add_filter( 'woocommerce_single_product_image_thumbnail_html', '__return_false' );
                woocommerce_show_product_images();
                remove_filter( 'woocommerce_single_product_image_thumbnail_html', '__return_false' );
                ?>

                <!-- Thumbnail strip -->
                <div class="pk-product-thumbs" id="pk-thumbs">
                    <?php
                    $attachment_ids = $product->get_gallery_image_ids();
                    $main_thumb = $product->get_image_id();
                    $all_imgs   = $main_thumb ? array_merge( [ $main_thumb ], $attachment_ids ) : $attachment_ids;
                    foreach ( $all_imgs as $i => $img_id ) :
                        $src = wp_get_attachment_image_url( $img_id, 'prokitchen-product-thumb' );
                        $full = wp_get_attachment_image_url( $img_id, 'prokitchen-product-large' );
                    ?>
                    <button class="pk-product-thumb <?php echo $i === 0 ? 'is-active' : ''; ?>"
                            data-full="<?php echo esc_url($full); ?>"
                            aria-label="Product image <?php echo $i+1; ?>">
                        <img src="<?php echo esc_url($src); ?>" alt="" loading="lazy">
                    </button>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- ─── RIGHT: DETAILS ─── -->
            <div class="pk-product-details">

                <!-- Header -->
                <div class="pk-product-details__head">
                    <?php if ( $cat_name ) : ?>
                    <span class="pk-label"><?php echo esc_html( $cat_name ); ?></span>
                    <?php endif; ?>

                    <h1 class="pk-product-details__name"><?php the_title(); ?></h1>

                    <div class="pk-product-details__meta">
                        <?php if ( $pk_brand ) : ?>
                        <span class="pk-tag pk-tag--gold">Brand: <?php echo esc_html($pk_brand); ?></span>
                        <?php endif; ?>
                        <?php if ( $pk_sku ) : ?>
                        <span class="pk-tag">SKU: <?php echo esc_html($pk_sku); ?></span>
                        <?php endif; ?>
                        <?php if ( $pk_nsf ) : ?>
                        <span class="pk-tag pk-tag--gold"><?php echo pk_icon('shield'); ?> NSF Certified</span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Price -->
                <div class="pk-product-details__price-block">
                    <div class="pk-product-details__price">
                        <?php echo $product->get_price_html(); ?>
                    </div>
                    <?php if ( $monthly_est > 0 ) : ?>
                    <div class="pk-product-details__finance-note">
                        <?php echo pk_icon('dollar'); ?>
                        <span>Finance from <strong>$<?php echo number_format($monthly_est); ?>/mo</strong> — <a href="<?php echo home_url('/financing'); ?>">See financing options</a></span>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Short description -->
                <?php if ( $product->get_short_description() ) : ?>
                <div class="pk-product-details__short-desc">
                    <?php echo wp_kses_post( $product->get_short_description() ); ?>
                </div>
                <?php endif; ?>

                <!-- Quick Specs -->
                <?php if ( $pk_power || $pk_capacity || $pk_dimensions ) : ?>
                <div class="pk-product-details__quick-specs">
                    <h4 class="pk-product-details__specs-label">Key Specifications</h4>
                    <ul class="pk-product-details__specs-list">
                        <?php if ( $pk_capacity ) : ?>
                        <li>
                            <span>Capacity</span>
                            <strong><?php echo esc_html($pk_capacity); ?></strong>
                        </li>
                        <?php endif; ?>
                        <?php if ( $pk_power ) : ?>
                        <li>
                            <span>Power</span>
                            <strong><?php echo esc_html($pk_power); ?></strong>
                        </li>
                        <?php endif; ?>
                        <?php if ( $pk_dimensions ) : ?>
                        <li>
                            <span>Dimensions</span>
                            <strong><?php echo esc_html($pk_dimensions); ?></strong>
                        </li>
                        <?php endif; ?>
                        <?php if ( $pk_weight ) : ?>
                        <li>
                            <span>Weight</span>
                            <strong><?php echo esc_html($pk_weight); ?></strong>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
                <?php endif; ?>

                <!-- CTA Buttons -->
                <div class="pk-product-details__actions">
                    <?php if ( $product->is_purchasable() && $product->is_in_stock() ) : ?>
                    <form class="cart pk-add-to-cart-form" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data'>
                        <div class="pk-qty-row">
                            <div class="pk-qty-control">
                                <button type="button" class="pk-qty-btn pk-qty-minus" aria-label="Decrease">−</button>
                                <input type="number" name="quantity" value="1" min="1" max="<?php echo esc_attr( $product->get_max_purchase_quantity() ); ?>" class="pk-qty-input" aria-label="Quantity">
                                <button type="button" class="pk-qty-btn pk-qty-plus" aria-label="Increase">+</button>
                            </div>
                            <button type="submit" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" class="pk-btn pk-btn--gold pk-btn--lg pk-add-to-cart">
                                <?php echo pk_icon('cart'); ?>
                                Add to Cart
                            </button>
                        </div>
                        <?php wc_get_template( 'global/quantity-input.php' ); ?>
                    </form>
                    <?php endif; ?>

                    <button class="pk-btn pk-btn--outline pk-btn--lg pk-open-quote" data-product="<?php echo esc_attr( $product->get_name() ); ?>">
                        Request Quote / Volume Pricing
                    </button>
                </div>

                <!-- Trust Signals -->
                <div class="pk-product-details__trust">
                    <div class="pk-trust-item"><?php echo pk_icon('truck'); ?> Free delivery over $2,500</div>
                    <div class="pk-trust-item"><?php echo pk_icon('shield'); ?> NSF certified</div>
                    <div class="pk-trust-item"><?php echo pk_icon('check'); ?> Ships in 48 hours</div>
                    <div class="pk-trust-item"><?php echo pk_icon('wrench'); ?> Install available</div>
                </div>

            </div><!-- /.pk-product-details -->

        </div><!-- /.pk-product-layout -->

        <!-- ═══ PRODUCT TABS ═══ -->
        <div class="pk-product-tabs">
            <div class="pk-product-tabs__nav" role="tablist">
                <button class="pk-tab-btn is-active" role="tab" aria-selected="true"  data-tab="description">Description</button>
                <button class="pk-tab-btn"             role="tab" aria-selected="false" data-tab="specs">Full Specifications</button>
                <button class="pk-tab-btn"             role="tab" aria-selected="false" data-tab="financing">Financing</button>
                <?php if ( comments_open() ) : ?>
                <button class="pk-tab-btn"             role="tab" aria-selected="false" data-tab="reviews">Reviews (<?php echo $product->get_review_count(); ?>)</button>
                <?php endif; ?>
            </div>

            <!-- Description Tab -->
            <div class="pk-tab-panel is-active" id="tab-description" role="tabpanel">
                <div class="pk-product-description">
                    <?php the_content(); ?>
                    <?php if ( ! $product->get_description() && ! get_the_content() ) : ?>
                    <p>
                        The <?php the_title(); ?> is designed for the demands of professional kitchen environments.
                        Built with commercial-grade stainless steel and engineered for continuous operation,
                        this unit meets all NSF/ANSI health code requirements.
                    </p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Specs Tab -->
            <div class="pk-tab-panel" id="tab-specs" role="tabpanel">
                <div class="pk-full-specs">
                    <h3 class="pk-full-specs__heading">Technical Specifications</h3>
                    <table class="pk-specs-table">
                        <tbody>
                            <?php if ( $pk_brand ) : ?>
                            <tr><td>Brand / Manufacturer</td><td><?php echo esc_html($pk_brand); ?></td></tr>
                            <?php endif; ?>
                            <?php if ( $pk_sku ) : ?>
                            <tr><td>Model / SKU</td><td><?php echo esc_html($pk_sku); ?></td></tr>
                            <?php endif; ?>
                            <?php if ( $pk_capacity ) : ?>
                            <tr><td>Capacity</td><td><?php echo esc_html($pk_capacity); ?></td></tr>
                            <?php endif; ?>
                            <?php if ( $pk_power ) : ?>
                            <tr><td>Power / Electrical</td><td><?php echo esc_html($pk_power); ?></td></tr>
                            <?php endif; ?>
                            <?php if ( $pk_dimensions ) : ?>
                            <tr><td>Dimensions (W×D×H)</td><td><?php echo esc_html($pk_dimensions); ?></td></tr>
                            <?php endif; ?>
                            <?php if ( $pk_weight ) : ?>
                            <tr><td>Ship Weight</td><td><?php echo esc_html($pk_weight); ?></td></tr>
                            <?php endif; ?>
                            <?php if ( $pk_nsf ) : ?>
                            <tr><td>Certifications</td><td><?php echo esc_html($pk_nsf); ?></td></tr>
                            <?php endif; ?>
                            <?php
                            // WooCommerce attributes
                            $attributes = $product->get_attributes();
                            foreach ( $attributes as $attribute ) :
                                if ( $attribute->get_visible() ) :
                            ?>
                            <tr>
                                <td><?php echo wc_attribute_label( $attribute->get_name() ); ?></td>
                                <td><?php echo wc_get_product_attribute_list( $product_id, $attribute ); ?></td>
                            </tr>
                            <?php
                                endif;
                            endforeach;
                            ?>
                        </tbody>
                    </table>
                    <p style="font-size:0.8rem; color:var(--pk-text-3); margin-top:1rem;">
                        * Specifications subject to manufacturer changes. Contact us to confirm before purchase on large orders.
                    </p>
                </div>
            </div>

            <!-- Financing Tab -->
            <div class="pk-tab-panel" id="tab-financing" role="tabpanel">
                <div class="pk-financing-tab">
                    <h3>Equipment Financing Options</h3>
                    <p>We work with commercial equipment lenders to offer flexible payment options. Get approved in as little as 24 hours.</p>

                    <?php if ( $price > 0 ) : ?>
                    <div class="pk-financing-estimates">
                        <h4>Estimated Monthly Payments</h4>
                        <div class="pk-financing-grid">
                            <div class="pk-financing-option">
                                <span class="pk-financing-option__term">24 Months</span>
                                <span class="pk-financing-option__payment">$<?php echo number_format(round($price/24,0)); ?>/mo</span>
                            </div>
                            <div class="pk-financing-option is-featured">
                                <span class="pk-financing-option__term">36 Months</span>
                                <span class="pk-financing-option__payment">$<?php echo number_format(round($price/36,0)); ?>/mo</span>
                                <span class="pk-financing-option__note">Most Popular</span>
                            </div>
                            <div class="pk-financing-option">
                                <span class="pk-financing-option__term">48 Months</span>
                                <span class="pk-financing-option__payment">$<?php echo number_format(round($price/48,0)); ?>/mo</span>
                            </div>
                            <div class="pk-financing-option">
                                <span class="pk-financing-option__term">60 Months</span>
                                <span class="pk-financing-option__payment">$<?php echo number_format(round($price/60,0)); ?>/mo</span>
                            </div>
                        </div>
                        <p style="font-size:0.8rem;color:var(--pk-text-3);">* Estimates based on 0% promotional rate. Actual rates vary by credit profile. Contact us for exact terms.</p>
                    </div>
                    <?php endif; ?>

                    <a href="<?php echo home_url('/financing'); ?>" class="pk-btn pk-btn--gold" style="margin-top:1.5rem;">
                        Apply for Financing <?php echo pk_icon('arrow'); ?>
                    </a>
                </div>
            </div>

            <!-- Reviews Tab -->
            <?php if ( comments_open() ) : ?>
            <div class="pk-tab-panel" id="tab-reviews" role="tabpanel">
                <?php comments_template(); ?>
            </div>
            <?php endif; ?>

        </div><!-- /.pk-product-tabs -->

        <!-- ═══ RELATED PRODUCTS ═══ -->
        <?php
        $related_ids = wc_get_related_products( $product_id, 4 );
        if ( $related_ids ) :
            $related = wc_get_products( [ 'include' => $related_ids, 'limit' => 4 ] );
        ?>
        <div class="pk-related-products">
            <div class="pk-section-header">
                <span class="pk-label">You May Also Need</span>
                <h2>Related Equipment</h2>
            </div>
            <div class="pk-related-grid">
                <?php foreach ( $related as $rel ) : ?>
                <a href="<?php echo get_permalink($rel->get_id()); ?>" class="pk-product-card">
                    <div class="pk-product-card__image">
                        <?php echo $rel->get_image('prokitchen-product-thumb', ['loading'=>'lazy']); ?>
                    </div>
                    <div class="pk-product-card__body">
                        <span class="pk-product-card__name"><?php echo esc_html($rel->get_name()); ?></span>
                    </div>
                    <div class="pk-product-card__footer">
                        <span class="pk-product-card__price"><?php echo $rel->get_price_html(); ?></span>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

    </div><!-- /.pk-container -->
</article>

<!-- ═══ QUOTE MODAL ═══ -->
<div class="pk-quote-modal" id="pk-quote-modal" role="dialog" aria-modal="true" aria-labelledby="quote-modal-title">
    <div class="pk-quote-modal__backdrop" id="pk-quote-backdrop"></div>
    <div class="pk-quote-modal__box">
        <button class="pk-quote-modal__close" id="pk-quote-close" aria-label="Close"><?php echo pk_icon('close'); ?></button>
        <div class="pk-quote-modal__header">
            <span class="pk-label">Volume &amp; Trade Pricing</span>
            <h3 id="quote-modal-title">Request a Quote</h3>
            <p>For orders of 2+ units, trade accounts, or custom configurations — we'll respond within 2 business hours.</p>
        </div>

        <?php
        // Use shortcode if plugin active, fallback to basic form
        if ( function_exists( 'pk_quote_form' ) ) {
            echo pk_quote_form( [ 'product' => get_the_title() ] );
        } else {
        ?>
        <form class="pk-quote-form" id="pk-quote-form" method="post">
            <?php wp_nonce_field( 'pk_quote_request', 'pk_quote_nonce' ); ?>
            <input type="hidden" name="product_name" value="<?php echo esc_attr( get_the_title() ); ?>">
            <input type="hidden" name="product_url"  value="<?php echo esc_url( get_permalink() ); ?>">
            <div class="pk-form-grid">
                <div class="pk-form-group">
                    <label for="qf-name">Full Name *</label>
                    <input type="text" id="qf-name" name="name" required placeholder="Chef Mario Rossi">
                </div>
                <div class="pk-form-group">
                    <label for="qf-company">Restaurant / Company *</label>
                    <input type="text" id="qf-company" name="company" required placeholder="Mario's Trattoria">
                </div>
                <div class="pk-form-group">
                    <label for="qf-email">Email Address *</label>
                    <input type="email" id="qf-email" name="email" required placeholder="mario@example.com">
                </div>
                <div class="pk-form-group">
                    <label for="qf-phone">Phone Number</label>
                    <input type="tel" id="qf-phone" name="phone" placeholder="(555) 000-0000">
                </div>
                <div class="pk-form-group">
                    <label for="qf-qty">Quantity Needed</label>
                    <input type="number" id="qf-qty" name="quantity" min="1" value="1">
                </div>
                <div class="pk-form-group">
                    <label for="qf-timeline">Timeline</label>
                    <select id="qf-timeline" name="timeline">
                        <option value="">Select timeline</option>
                        <option value="asap">ASAP / Immediate</option>
                        <option value="30days">Within 30 days</option>
                        <option value="60days">Within 60 days</option>
                        <option value="90days">90+ days</option>
                    </select>
                </div>
                <div class="pk-form-group pk-form-group--full">
                    <label for="qf-message">Additional Notes</label>
                    <textarea id="qf-message" name="message" placeholder="Custom voltage requirements, color preferences, volume discount inquiry..."></textarea>
                </div>
            </div>
            <button type="submit" class="pk-btn pk-btn--gold pk-btn--full">
                Send Quote Request <?php echo pk_icon('arrow'); ?>
            </button>
        </form>
        <?php } ?>
    </div>
</div>

<?php
endwhile;
get_footer( 'shop' );
?>
