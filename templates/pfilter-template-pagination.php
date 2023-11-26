<?php
/*
 * Template Name: Pagination Template
 */

get_header(); 

?>

<div class="container">

<div class="grid row">
<?php

$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

    if(empty($cat)){
        $best_wp = new wp_Query(array(
            'post_type'      => 'portfolios',
            'paged'          => $paged,
            'posts_per_page' => 3,
        ));  
    }  
    else{
        $best_wp = new wp_Query(array(
                'post_type'      => 'portfolios',
                'posts_per_page' => 3,
                'paged'          => $paged,      
                'tax_query'      => array(
                    array(
                        'taxonomy' => 'portfolio-category',
                        'field'    => 'slug',
                        'terms'    => $cat
                    ),
                )
        ));  
    }

while ($best_wp->have_posts()) : $best_wp->the_post();
   
    $termsArray = get_the_terms($best_wp->ID, 'portfolio_category');
    $termsString = '';
    $termsSlug = '';
    if (!empty($termsArray)) {
        foreach ($termsArray as $term) {
            $termsString .= 'filter_' . $term->slug . ' ';
            $termsSlug .= $term->slug;
        }
    }

    ?>

    <div class="col-lg-4 grid-item <?php echo $termsString; ?>">
        <div class="portfolio-item content-overlay">
            <?php if (has_post_thumbnail()) : ?>
                <a href="<?php the_permalink(); ?>">
                    <div class="portfolio-img">
                        <?php the_post_thumbnail();?>
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
    $paginate = paginate_links( array(
        'total' => $best_wp->max_num_pages
    ));
    ?>
   
        <div class="best_wp-pagination-area text-center"><div class="nav-links"><?php echo wp_kses_post($paginate); ?></div></div>
<?php
get_footer();

