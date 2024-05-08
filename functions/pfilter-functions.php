<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
// Handle AJAX request for loading categories
function ptf_filter_ajax_load_categories_function()
{
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash ( $_POST['nonce'] ) ) , 'ptf-filter-nonce' ) ){
        die('Nonce Verification failed!');
    }
    $taxonomy = 'portfolio_category';
    $categories = get_terms($taxonomy);
    foreach ($categories as $category) {
        echo '<button class="filter-button" data-category="' . esc_attr($category->slug) . '">' . esc_html($category->name) . '</button>';
    }
    die();
}
add_action('wp_ajax_ajax_load_categories', 'ptf_filter_ajax_load_categories_function');
add_action('wp_ajax_nopriv_ajax_load_categories', 'ptf_filter_ajax_load_categories_function');


//Handle AJAX request for filtering content
function ptf_filter_ajax_filter_function()
{

    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash ( $_POST['nonce'] ) ) , 'filter-button-nonce' ) ){
        die('Nonce Verification failed portfio!');
    }


    $category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : '';

    $args = array(
        'post_type'      => 'portfolios',
        'posts_per_page' => -1,
    );

    if (!empty($category)) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'portfolio_category',
                'field'    => 'slug',
                'terms'    => $category,
            ),
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
add_action('wp_ajax_ajax_filter', 'ptf_filter_ajax_filter_function');
add_action('wp_ajax_nopriv_ajax_filter', 'ptf_filter_ajax_filter_function');



//Handle AJAX request for loadmore button content
function ptf_filter_ajax_load_more_portfolio_function()
{

    if ( ! isset( $_POST['pt_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash ( $_POST['pt_nonce'] ) ) , 'pt_filter_load_more_action' ) ){
        print 'Verification failed. Try again.';
        exit;
    }

    $page = isset($_POST['page']) ? absint($_POST['page']) : 1;

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
add_action('wp_ajax_ajax_load_more_portfolio', 'ptf_filter_ajax_load_more_portfolio_function');
add_action('wp_ajax_nopriv_ajax_load_more_portfolio', 'ptf_filter_ajax_load_more_portfolio_function');