<?php

// Handle AJAX request for loading categories
function ajax_load_categories_function()
{
    $taxonomy = 'portfolio_category';
    $categories = get_terms($taxonomy);
    foreach ($categories as $category) {
        echo '<button class="filter-button" data-category="' . esc_attr($category->slug) . '">' . esc_html($category->name) . '</button>';
    }
    die();
}
add_action('wp_ajax_ajax_load_categories', 'ajax_load_categories_function');
add_action('wp_ajax_nopriv_ajax_load_categories', 'ajax_load_categories_function');


//Handle AJAX request for filtering content
function ajax_filter_function()
{

    $category = $_POST['category'];

    if (!empty($category)) {
        $args = array(
            'post_type'      => 'portfolios',
            'posts_per_page' => -1,
            'tax_query'      => array(
                array(
                    'taxonomy' => 'portfolio_category',
                    'field'    => 'slug',
                    'terms'    => $category,
                ),
            ),
        );
    } else {
        $args = array(
            'post_type'      => 'portfolios',
            'posts_per_page' => -1,
        );
    }

    $query = new WP_Query($args);
    echo '<div class="container">';
        echo '<div class="row">';
            while ($query->have_posts()) : $query->the_post();
            ?>
                <div class="col-lg-4 grid-item">
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
            die();
        echo "</div>";
    echo "</div>";
}
add_action('wp_ajax_ajax_filter', 'ajax_filter_function');
add_action('wp_ajax_nopriv_ajax_filter', 'ajax_filter_function'); 



//Handle AJAX request for loadmore button content
function ajax_load_more_portfolio_function() {
    $page = $_POST['page'];

    $args = array(
        'post_type'      => 'portfolios',
        'posts_per_page' => 6,
        'paged'          => $page,
    );


    $query = new WP_Query($args);
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
        wp_reset_postdata();

        die();
        wp_die();
            else :
            die();
        endif;

}
add_action('wp_ajax_ajax_load_more_portfolio', 'ajax_load_more_portfolio_function');
add_action('wp_ajax_nopriv_ajax_load_more_portfolio', 'ajax_load_more_portfolio_function');

