<?php
/**
 * Index template (fallback)
 */
get_header();
?>
<div class="pk-section">
    <div class="pk-container">
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
        <div class="pk-page-content" style="max-width:860px;">
            <?php the_content(); ?>
        </div>
        <?php endwhile; endif; ?>
    </div>
</div>
<?php get_footer(); ?>
