<?php
/*
 * Template Name: Load More Template
 */

get_header();

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
        <div class="load-more-container text-center pt-5">
            <button id="load-more-button"><?php echo esc_html('Load More', 'pfilter') ?></button>
        </div>
        <?php
            wp_reset_postdata();
        else :
            echo '<span class="text-center">'.esc_html('No posts found','pfilter').'</span>';
        endif;
    echo '</div>';
echo '</div>';

get_footer();
?>
