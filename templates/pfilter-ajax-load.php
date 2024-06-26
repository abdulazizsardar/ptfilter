<?php
/*
 * Template Name: Filter Template
*/
if (!defined('ABSPATH')) exit; // Exit if accessed directly
// Header inclusion
if (function_exists('get_header')) {
    if (file_exists(get_template_directory() . '/header.php')) {
        get_header(); // Include the currently active theme's header.
    } else {
        // Fallback for block themes
        ?><!DOCTYPE html><html><head><?php
        wp_head();
        ?></head><body><?php
    }
}

?>

<div class="apon-filter portfolio-filter text-center">
    <?php $nonce =  wp_create_nonce( "filter-button-nonce" ); ?>
    <button class="filter-button filter-all active" data-nonce="<?php echo esc_attr($nonce );?>"
        data-category=""><?php echo esc_html('All', 'ptfilter'); ?></button>

    <?php
    $taxonomy = 'portfolio_category';
    $select_cat = get_terms($taxonomy);

    foreach ($select_cat as $category) {
    ?>
    <button class="filter-button" data-nonce="<?php echo esc_attr($nonce );?>"
        data-category="<?php echo esc_attr($category->slug); ?>"><?php echo esc_html($category->name); ?></button>
    <?php
    }
    ?>
</div>


<div class="container">
    <div class="grid row" id="ajax-filter-container">
        <?php
        $all_posts = new WP_Query(array(
            'post_type'      => 'portfolios',
            'posts_per_page' => -1,
        ));

        while ($all_posts->have_posts()) : $all_posts->the_post();
        ?>
        <div class="col-lg-4 grid-item all">
            <div class="portfolio-item content-overlay">
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
        wp_reset_postdata();
        ?>
    </div>
</div>

<?php
// Footer inclusion
if (function_exists('get_footer')) {
    if (file_exists(get_template_directory() . '/footer.php')) {
        get_footer(); // Include the currently active theme's footer.
    } else {
        // Fallback for block themes
        wp_footer();
        ?></body></html> <?php
    }
}