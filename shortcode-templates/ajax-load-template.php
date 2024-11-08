<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

$nonce = wp_create_nonce("filter-button-nonce"); ?>

<div class="portfolio-filter text-center">
    <button class="filter-button filter-all active" data-nonce="<?php echo esc_attr($nonce); ?>" data-category=""><?php echo esc_html__('All', 'pt-filter'); ?></button>
    <?php
    $taxonomy = 'ptfilter_portfolio_category';
    $categories = get_terms($taxonomy);
    foreach ($categories as $category) { ?>
        <button class="filter-button" data-nonce="<?php echo esc_attr($nonce); ?>" data-category="<?php echo esc_attr($category->slug); ?>"><?php echo esc_html($category->name); ?></button>
    <?php } ?>
</div>

<div class="container">
    <div class="items row" id="ajax-filter-container">
        <?php
        $all_posts = new WP_Query(array(
            'post_type' => 'ptfilter_portfolios',
            'posts_per_page' => -1,
        ));
        while ($all_posts->have_posts()) : $all_posts->the_post(); ?>
            <div class="col-lg-4 col-md-6 col-sm-6 col-12 item all">
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
        <?php endwhile;
        wp_reset_postdata(); ?>
    </div>
</div>
