<?php
/*
 * Template Name: Filter Template
 */

get_header(); 
?>

<div class="apon-filter portfolio-filter text-center">
    <button class="filter-button filter-all active" data-category=""><?php echo esc_html('All', 'pfilter'); ?></button>

    <?php
         $taxonomy = 'portfolio_category';
         $select_cat = get_terms($taxonomy);

         foreach ($select_cat as $category) {
            ?>
             <button class="filter-button" data-category="<?php echo esc_attr($category->slug); ?>"><?php echo esc_html($category->name); ?></button> 
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
get_footer();
?>