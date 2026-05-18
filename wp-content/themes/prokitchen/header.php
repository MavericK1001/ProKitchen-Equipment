<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- ═══════════════════════════════════
     TOP BAR
════════════════════════════════════ -->
<div class="pk-topbar">
    <div class="pk-container">
        <div class="pk-topbar__inner">
            <div class="pk-topbar__left">
                <span><?php echo pk_icon('phone'); ?> (800) 776-5484</span>
                <span><?php echo pk_icon('email'); ?> sales@prokitchen.com</span>
                <span>Mon–Fri 7am–6pm CT · Sat 8am–2pm CT</span>
            </div>
            <div class="pk-topbar__right">
                <span class="pk-topbar__tag">NSF Certified Equipment</span>
                <span class="pk-topbar__tag">Commercial-Grade Only</span>
                <span class="pk-topbar__tag">Financing Available</span>
            </div>
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════
     MAIN HEADER
════════════════════════════════════ -->
<header class="pk-header" id="pk-header">
    <div class="pk-container">
        <div class="pk-header__inner">

            <!-- Logo -->
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="pk-logo" aria-label="<?php bloginfo( 'name' ); ?> Home">
                <span class="pk-logo__icon">⚙</span>
                <div class="pk-logo__text">
                    <span class="pk-logo__primary">ProKitchen</span>
                    <span class="pk-logo__secondary">Equipment Co.</span>
                </div>
            </a>

            <!-- Primary Nav -->
            <nav class="pk-nav" id="pk-nav" aria-label="Primary navigation">
                <?php
                wp_nav_menu( [
                    'theme_location' => 'primary',
                    'container'      => false,
                    'menu_class'     => 'pk-nav__menu',
                    'fallback_cb'    => function () {
                        ?>
                        <ul class="pk-nav__menu">
                            <li><a href="<?php echo home_url('/shop'); ?>">Shop</a></li>
                            <li><a href="<?php echo home_url('/about'); ?>">About</a></li>
                            <li><a href="<?php echo home_url('/financing'); ?>">Financing</a></li>
                            <li><a href="<?php echo home_url('/delivery'); ?>">Delivery</a></li>
                            <li><a href="<?php echo home_url('/contact'); ?>">Contact</a></li>
                        </ul>
                        <?php
                    },
                ] );
                ?>
            </nav>

            <!-- Header Actions -->
            <div class="pk-header__actions">
                <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                <a href="<?php echo wc_get_cart_url(); ?>" class="pk-cart-btn" aria-label="View cart">
                    <?php echo pk_icon('cart'); ?>
                    <span class="pk-cart-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
                </a>
                <?php endif; ?>

                <a href="<?php echo home_url('/contact'); ?>" class="pk-btn pk-btn--gold pk-btn--sm">
                    Request Quote
                </a>

                <!-- Mobile menu toggle -->
                <button class="pk-menu-toggle" id="pk-menu-toggle" aria-label="Toggle menu" aria-expanded="false">
                    <?php echo pk_icon('menu'); ?>
                </button>
            </div>

        </div>
    </div>
</header>

<!-- Mobile Nav Overlay -->
<div class="pk-mobile-nav" id="pk-mobile-nav" aria-hidden="true">
    <div class="pk-mobile-nav__header">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="pk-logo">
            <span class="pk-logo__icon">⚙</span>
            <div class="pk-logo__text">
                <span class="pk-logo__primary">ProKitchen</span>
                <span class="pk-logo__secondary">Equipment Co.</span>
            </div>
        </a>
        <button class="pk-mobile-nav__close" id="pk-mobile-close" aria-label="Close menu">
            <?php echo pk_icon('close'); ?>
        </button>
    </div>
    <nav>
        <ul class="pk-mobile-nav__menu">
            <li><a href="<?php echo home_url('/shop'); ?>">Shop All Equipment</a></li>
            <li><a href="<?php echo home_url('/shop'); ?>?cat=commercial-refrigeration">Refrigeration</a></li>
            <li><a href="<?php echo home_url('/shop'); ?>?cat=ovens-ranges">Ovens &amp; Ranges</a></li>
            <li><a href="<?php echo home_url('/shop'); ?>?cat=prep-tables">Prep Tables</a></li>
            <li><a href="<?php echo home_url('/shop'); ?>?cat=work-tables">Work Tables</a></li>
            <li><a href="<?php echo home_url('/shop'); ?>?cat=display-cases">Display Cases</a></li>
            <li class="pk-mobile-nav__divider"></li>
            <li><a href="<?php echo home_url('/about'); ?>">About Us</a></li>
            <li><a href="<?php echo home_url('/financing'); ?>">Financing</a></li>
            <li><a href="<?php echo home_url('/delivery'); ?>">Delivery &amp; Install</a></li>
            <li><a href="<?php echo home_url('/contact'); ?>">Contact</a></li>
        </ul>
        <div class="pk-mobile-nav__cta">
            <a href="<?php echo home_url('/contact'); ?>" class="pk-btn pk-btn--gold pk-btn--full">
                Request a Quote
            </a>
            <a href="tel:8007765484" class="pk-btn pk-btn--outline pk-btn--full">
                <?php echo pk_icon('phone'); ?> (800) 776-5484
            </a>
        </div>
    </nav>
</div>
<div class="pk-overlay" id="pk-overlay"></div>

<main class="pk-main" id="pk-main">
