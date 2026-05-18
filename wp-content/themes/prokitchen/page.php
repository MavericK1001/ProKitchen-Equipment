<?php
/**
 * Default page template
 */
get_header();
?>
<div class="pk-page-hero">
    <div class="pk-container pk-page-hero__inner">
        <?php woocommerce_breadcrumb(); ?>
        <h1><?php the_title(); ?></h1>
    </div>
</div>
<div class="pk-section pk-section--dark">
    <div class="pk-container">
        <?php
        while ( have_posts() ) :
            the_post();
        ?>
        <div class="pk-page-content" style="max-width:860px;">
            <?php the_content(); ?>
        </div>
        <?php endwhile; ?>
    </div>
</div>
<?php get_footer(); ?>
