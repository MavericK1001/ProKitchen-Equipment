<?php
/**
 * ProKitchen Equipment — functions.php
 * Theme bootstrap, WooCommerce support, enqueues, helpers.
 */

defined( 'ABSPATH' ) || exit;

define( 'PKE_VERSION', '1.0.0' );
define( 'PKE_DIR', get_template_directory() );
define( 'PKE_URI', get_template_directory_uri() );

/* ─────────────────────────────────────────────
   THEME SETUP
───────────────────────────────────────────── */
add_action( 'after_setup_theme', function () {
    load_theme_textdomain( 'prokitchen', PKE_DIR . '/languages' );

    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', [ 'search-form', 'comment-form', 'gallery', 'caption', 'style', 'script' ] );
    add_theme_support( 'customize-selective-refresh-widgets' );
    add_theme_support( 'responsive-embeds' );
    add_theme_support( 'align-wide' );

    // Custom image sizes
    add_image_size( 'prokitchen-product-thumb', 600, 600, true );
    add_image_size( 'prokitchen-product-large', 900, 900, false );
    add_image_size( 'prokitchen-hero', 1920, 800, true );
    add_image_size( 'prokitchen-category', 600, 400, true );

    // Navigation menus
    register_nav_menus( [
        'primary'  => __( 'Primary Navigation', 'prokitchen' ),
        'footer_1' => __( 'Footer Column 1', 'prokitchen' ),
        'footer_2' => __( 'Footer Column 2', 'prokitchen' ),
    ] );

    // WooCommerce
    add_theme_support( 'woocommerce', [
        'thumbnail_image_width'         => 600,
        'single_image_width'            => 900,
        'product_grid'                  => [
            'default_rows'    => 3,
            'min_rows'        => 1,
            'max_rows'        => 6,
            'default_columns' => 3,
            'min_columns'     => 2,
            'max_columns'     => 4,
        ],
    ] );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
} );

/* ─────────────────────────────────────────────
   ENQUEUE SCRIPTS & STYLES
───────────────────────────────────────────── */
add_action( 'wp_enqueue_scripts', function () {
    // Google Fonts
    wp_enqueue_style(
        'prokitchen-fonts',
        'https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700;900&family=Inter:wght@300;400;500;600;700&family=Rajdhani:wght@400;500;600;700&display=swap',
        [],
        null
    );

    // Main stylesheet
    wp_enqueue_style(
        'prokitchen-main',
        PKE_URI . '/assets/css/main.css',
        [ 'prokitchen-fonts' ],
        PKE_VERSION
    );

    // WooCommerce custom styles (only on shop pages)
    if ( class_exists( 'WooCommerce' ) ) {
        wp_enqueue_style(
            'prokitchen-woocommerce',
            PKE_URI . '/assets/css/woocommerce.css',
            [ 'prokitchen-main' ],
            PKE_VERSION
        );
    }

    // Main JS
    wp_enqueue_script(
        'prokitchen-main',
        PKE_URI . '/assets/js/main.js',
        [ 'jquery' ],
        PKE_VERSION,
        true
    );

    // Pass data to JS
    wp_localize_script( 'prokitchen-main', 'pkData', [
        'ajaxUrl' => admin_url( 'admin-ajax.php' ),
        'nonce'   => wp_create_nonce( 'prokitchen_nonce' ),
        'siteUrl' => home_url(),
    ] );
} );

/* ─────────────────────────────────────────────
   REMOVE WC DEFAULT STYLES (we handle everything)
───────────────────────────────────────────── */
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

/* ─────────────────────────────────────────────
   WOOCOMMERCE: PRODUCT COLUMNS
───────────────────────────────────────────── */
add_filter( 'loop_shop_columns', function () { return 3; } );
add_filter( 'loop_shop_per_page', function () { return 12; } );

/* ─────────────────────────────────────────────
   WOOCOMMERCE: REMOVE SIDEBAR FROM SHOP
───────────────────────────────────────────── */
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

/* ─────────────────────────────────────────────
   WOOCOMMERCE: CUSTOM BREADCRUMBS
───────────────────────────────────────────── */
add_filter( 'woocommerce_breadcrumb_defaults', function ( $args ) {
    $args['delimiter']   = ' <span class="pk-bc-sep">›</span> ';
    $args['wrap_before'] = '<nav class="pk-breadcrumb" aria-label="Breadcrumb"><ol>';
    $args['wrap_after']  = '</ol></nav>';
    $args['before']      = '<li>';
    $args['after']       = '</li>';
    return $args;
} );

/* ─────────────────────────────────────────────
   WOOCOMMERCE: CART FRAGMENTS (AJAX cart count)
───────────────────────────────────────────── */
add_filter( 'woocommerce_add_to_cart_fragments', function ( $fragments ) {
    ob_start();
    ?>
    <span class="pk-cart-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
    <?php
    $fragments['span.pk-cart-count'] = ob_get_clean();
    return $fragments;
} );

/* ─────────────────────────────────────────────
   CUSTOM EXCERPT LENGTH
───────────────────────────────────────────── */
add_filter( 'excerpt_length', function () { return 25; } );
add_filter( 'excerpt_more', function () { return '&hellip;'; } );

/* ─────────────────────────────────────────────
   WOOCOMMERCE HPOS COMPATIBILITY
───────────────────────────────────────────── */
add_action( 'before_woocommerce_init', function () {
    if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
    }
} );

/* ─────────────────────────────────────────────
   HELPER: render SVG icon
───────────────────────────────────────────── */
function pk_icon( string $name ): string {
    $icons = [
        'cart'     => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>',
        'phone'    => '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.14l3-.1a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L7.91 8.6a16 16 0 0 0 6 6l.91-.91a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 21.5 16v.92z"/></svg>',
        'email'    => '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>',
        'check'    => '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>',
        'star'     => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="1"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>',
        'arrow'    => '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>',
        'shield'   => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>',
        'truck'    => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>',
        'wrench'   => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>',
        'dollar'   => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>',
        'close'    => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>',
        'menu'     => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>',
    ];
    return $icons[ $name ] ?? '';
}

/* ─────────────────────────────────────────────
   HELPER: format price display
───────────────────────────────────────────── */
function pk_format_price( float $price ): string {
    return '$' . number_format( $price, 0, '.', ',' );
}

/* ─────────────────────────────────────────────
   WIDGETIZE FOOTER
───────────────────────────────────────────── */
add_action( 'widgets_init', function () {
    register_sidebar( [
        'name'          => __( 'Footer Widget Area', 'prokitchen' ),
        'id'            => 'footer-widgets',
        'before_widget' => '<div class="footer-widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="footer-widget__title">',
        'after_title'   => '</h4>',
    ] );
} );
