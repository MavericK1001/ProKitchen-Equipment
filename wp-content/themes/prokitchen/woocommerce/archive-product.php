<?php
/**
 * Shop Archive Template — ProKitchen Equipment
 * Overrides: woocommerce/archive-product.php
 */
defined( 'ABSPATH' ) || exit;
get_header( 'shop' );
?>

<!-- Shop Header -->
<div class="pk-shop-header">
    <div class="pk-container">
        <?php woocommerce_breadcrumb(); ?>
        <h1>
            <?php
            if ( is_product_category() ) {
                echo single_term_title();
            } elseif ( is_search() ) {
                echo 'Search: "' . esc_html( get_search_query() ) . '"';
            } else {
                echo 'Shop All Equipment';
            }
            ?>
        </h1>
        <?php
        $desc = get_queried_object() ? term_description() : '';
        if ( $desc ) : ?>
        <p class="pk-lead" style="max-width:560px;margin-top:0.75rem;"><?php echo wp_kses_post($desc); ?></p>
        <?php endif; ?>
    </div>
</div>

<div class="pk-shop-page">
    <div class="pk-container">
        <div class="pk-shop-layout">

            <!-- ─── SIDEBAR / FILTERS ─── -->
            <aside class="pk-shop-sidebar" aria-label="Product filters">

                <!-- Categories -->
                <div class="pk-filter-panel">
                    <div class="pk-filter-panel__head">
                        Categories <span class="pk-filter-toggle-icon">−</span>
                    </div>
                    <div class="pk-filter-panel__body">
                        <?php
                        $cats = get_terms( [
                            'taxonomy'   => 'product_cat',
                            'hide_empty' => true,
                            'parent'     => 0,
                            'exclude'    => get_option('default_product_cat'),
                        ] );
                        if ( $cats && ! is_wp_error( $cats ) ) :
                        ?>
                        <ul class="pk-filter-list">
                            <li <?php if ( ! is_product_category() ) echo 'class="current-cat"'; ?>>
                                <a href="<?php echo get_permalink( wc_get_page_id('shop') ); ?>">
                                    All Equipment
                                    <span class="pk-filter-count"><?php echo wp_count_posts('product')->publish; ?></span>
                                </a>
                            </li>
                            <?php foreach ( $cats as $cat ) :
                                $active = ( is_product_category( $cat->slug ) ) ? 'class="current-cat"' : '';
                            ?>
                            <li <?php echo $active; ?>>
                                <a href="<?php echo get_term_link($cat); ?>">
                                    <?php echo esc_html($cat->name); ?>
                                    <span class="pk-filter-count"><?php echo $cat->count; ?></span>
                                </a>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Price Range -->
                <div class="pk-filter-panel">
                    <div class="pk-filter-panel__head">
                        Price Range <span class="pk-filter-toggle-icon">−</span>
                    </div>
                    <div class="pk-filter-panel__body">
                        <form method="get" action="<?php echo esc_url( wc_get_page_permalink('shop') ); ?>">
                            <div class="pk-price-inputs">
                                <div>
                                    <label for="min-price">Min ($)</label>
                                    <input type="number" id="min-price" name="min_price" value="<?php echo esc_attr(isset($_GET['min_price']) ? $_GET['min_price'] : ''); ?>" placeholder="0" min="0">
                                </div>
                                <div>
                                    <label for="max-price">Max ($)</label>
                                    <input type="number" id="max-price" name="max_price" value="<?php echo esc_attr(isset($_GET['max_price']) ? $_GET['max_price'] : ''); ?>" placeholder="Any" min="0">
                                </div>
                            </div>
                            <button type="submit" class="pk-btn pk-btn--gold pk-btn--sm pk-btn--full" style="margin-top:1rem;">
                                Apply Filter
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Brands -->
                <div class="pk-filter-panel">
                    <div class="pk-filter-panel__head">
                        Brand <span class="pk-filter-toggle-icon">−</span>
                    </div>
                    <div class="pk-filter-panel__body">
                        <?php
                        $brand_terms = get_terms( [
                            'taxonomy'   => 'pa_brand',
                            'hide_empty' => true,
                        ] );
                        if ( $brand_terms && ! is_wp_error( $brand_terms ) ) :
                        ?>
                        <ul class="pk-filter-list">
                            <?php foreach ( $brand_terms as $brand ) : ?>
                            <li>
                                <a href="<?php echo get_term_link($brand); ?>">
                                    <?php echo esc_html($brand->name); ?>
                                    <span class="pk-filter-count"><?php echo $brand->count; ?></span>
                                </a>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <?php else : ?>
                        <p style="font-size:0.8rem;color:var(--pk-text-3);">Filter by brand coming soon.</p>
                        <?php endif; ?>
                    </div>
                </div>

            </aside><!-- /.pk-shop-sidebar -->

            <!-- ─── MAIN PRODUCTS ─── -->
            <div class="pk-shop-main">

                <!-- Toolbar -->
                <div class="pk-shop-toolbar">
                    <span class="pk-shop-result-count">
                        <?php woocommerce_result_count(); ?>
                    </span>
                    <div class="pk-shop-sort">
                        <?php woocommerce_catalog_ordering(); ?>
                    </div>
                </div>

                <?php if ( woocommerce_product_loop() ) : ?>
                <div class="pk-products-grid">
                    <?php
                    while ( have_posts() ) :
                        the_post();
                        global $product;
                        $brand = get_post_meta( $product->get_id(), 'pk_brand', true );
                        $power = get_post_meta( $product->get_id(), 'pk_power', true );
                        $cats  = get_the_terms( $product->get_id(), 'product_cat' );
                    ?>
                    <div class="pk-product-card">
                        <div class="pk-product-card__image">
                            <a href="<?php the_permalink(); ?>">
                                <?php echo $product->get_image( 'prokitchen-product-thumb', [ 'loading' => 'lazy' ] ); ?>
                            </a>
                            <?php if ( $product->is_on_sale() ) : ?>
                            <span class="pk-product-card__badge">Sale</span>
                            <?php endif; ?>
                        </div>

                        <div class="pk-product-card__body">
                            <?php if ( $cats && ! is_wp_error($cats) ) : ?>
                            <span class="pk-product-card__cat"><?php echo esc_html( $cats[0]->name ); ?></span>
                            <?php endif; ?>

                            <h2 class="pk-product-card__name">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h2>

                            <?php if ( $brand || $power ) : ?>
                            <div class="pk-product-card__meta">
                                <?php if ($brand) : ?><span class="pk-product-card__meta-tag"><?php echo esc_html($brand); ?></span><?php endif; ?>
                                <?php if ($power) : ?><span class="pk-product-card__meta-tag"><?php echo esc_html($power); ?></span><?php endif; ?>
                            </div>
                            <?php endif; ?>
                        </div>

                        <div class="pk-product-card__footer">
                            <div>
                                <span class="pk-product-card__price"><?php echo $product->get_price_html(); ?></span>
                                <?php $p = (float) $product->get_price(); if ($p > 0) : ?>
                                <span class="pk-product-card__price-note">From $<?php echo number_format(round($p/60,0)); ?>/mo</span>
                                <?php endif; ?>
                            </div>
                            <a href="<?php the_permalink(); ?>" class="pk-btn pk-btn--gold pk-btn--sm">View</a>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>

                <!-- Pagination -->
                <div class="pk-shop-pagination">
                    <?php woocommerce_pagination(); ?>
                </div>

                <?php else : ?>
                <div style="text-align:center; padding:4rem 2rem;">
                    <p style="font-size:1.2rem; color:var(--pk-text-2); margin-bottom:1.5rem;">No products found.</p>
                    <a href="<?php echo get_permalink(wc_get_page_id('shop')); ?>" class="pk-btn pk-btn--gold">
                        View All Equipment
                    </a>
                </div>
                <?php endif; ?>

            </div><!-- /.pk-shop-main -->

        </div><!-- /.pk-shop-layout -->
    </div>
</div>

<?php get_footer( 'shop' ); ?>
