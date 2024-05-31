<?php
/*
 * Template Name: Load More Template
 */
if (!defined('ABSPATH')) exit; // Exit if accessed directly
// Header inclusion
if (function_exists('get_header')) {
    if (file_exists(get_template_directory() . '/header.php')) {
        get_header(); // Include the currently active theme's header.
    } else {
        // Fallback for block themes
        echo '<!DOCTYPE html><html><head>';
        wp_head();
        echo '</head><body>';
    }
}

$args = array(
    'post_type'      => 'portfolios',
    'posts_per_page' => 6,
);

$query = new WP_Query($args);
echo '<div class="container">';
echo '<div class="row">';
if ($query->have_posts()) :
    while ($query->have_posts()) : $query->the_post();
?>
<div class="col-lg-4">
    <div class="portfolio-item">
        <?php if (has_post_thumbnail()) : ?>
        <a href="<?php the_permalink(); ?>">
            <div class="portfolio-img">
                <?php the_post_thumbnail(); ?>
            </div>
        </a>
        <?php endif; ?>
    </div>
</div>
<?php
    endwhile;
    ?>
<?php wp_nonce_field('pt_filter_load_more_action', 'pt_filter_nonce_field'); ?>
<div class="load-more-container text-center pt-5">
    <button id="load-more-button"><?php echo esc_html('Load More', 'ptfilter') ?></button>
</div>
<?php
    wp_reset_postdata();
else :
    echo '<span class="text-center">' . esc_html('No posts found', 'ptfilter') . '</span>';
endif;
echo '</div>';
echo '</div>';

// Footer inclusion
if (function_exists('get_footer')) {
    if (file_exists(get_template_directory() . '/footer.php')) {
        get_footer(); // Include the currently active theme's footer.
    } else {
        // Fallback for block themes
        wp_footer();
        echo '</body></html>';
    }
}
?>