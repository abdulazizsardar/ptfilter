<?php

?>
<div class="portfolio-filter text-center">
    <button class="active" data-filter="*"> <?php echo esc_html__('All', 'pt-filter') ?> </button>

    <?php
    $taxonomy = 'ptfilter_portfolio_category';
    $select_cat = get_terms($taxonomy);

    foreach ($select_cat as $term) {
        $term_name = $term->name;
        $term_slug = $term->slug;
    ?>
    <button data-filter=".filter_<?php echo esc_attr($term_slug); ?>"><?php echo esc_html($term_name); ?></button>
    <?php
    }
    ?>

</div>
<div class="container">
    <div class="grid row ">
        <?php
        if (empty($cat)) {
            $best_wp = new wp_Query(array(
                'post_type'      => 'ptfilter_portfolios',
                'posts_per_page' => -1,
            ));
        } else {
            $best_wp = new wp_Query(array(
                'post_type'      => 'ptfilter_portfolios',
                'posts_per_page' => -1,
                'tax_query'      => array(
                    array(
                        'taxonomy' => 'ptfilter_portfolio_category',
                        'field'    => 'slug',
                        'terms'    => $cat
                    ),
                )
            ));
        }

        while ($best_wp->have_posts()) : $best_wp->the_post();

            $termsArray = get_the_terms($best_wp->ID, 'ptfilter_portfolio_category');
            $termsString = '';
            $termsSlug = '';
            if (!empty($termsArray)) {
                foreach ($termsArray as $term) {
                    $termsString .= 'filter_' . $term->slug . ' ';
                    $termsSlug .= $term->slug;
                }
            }

        ?>
        <div class="col-lg-4 col-md-6 col-sm-6 col-12 grid-item <?php echo esc_attr($termsString); ?>">
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
