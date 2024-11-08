<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly


function ptfilter_create_custom_post_type()
{
    register_post_type(
        'ptfilter_portfolios',
        array(
            'labels' => array(
                'name' => esc_html__('Ptfilter Portfolios', 'pt-filter'),
                'singular_name' => esc_html__('Ptfilter Portfolios', 'pt-filter'),
            ),
            'public' => true,
            'has_archive' => true,
            'menu_icon' => 'dashicons-book',
            'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
            'rewrite' => array('slug' => 'ptfilter-portfolios'),
        )
    );
}
add_action('init', 'ptfilter_create_custom_post_type');


function ptfilter_create_custom_taxonomy()
{
    register_taxonomy(
        'ptfilter_portfolio_category',
        'ptfilter_portfolios',
        array(
            'label' => 'Ptfilter Portfolio Categories',
            'hierarchical' => true,
            'public' => true,
            'rewrite' => array('slug' => 'ptfilter-portfolio-category'),
        )
    );
}
add_action('init', 'ptfilter_create_custom_taxonomy');