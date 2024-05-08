<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
function ptf_filter_create_custom_post_type()
{
    register_post_type(
        'portfolios',
        array(
            'labels' => array(
                'name' => __('Portfolios', 'ptf-filter'),
                'singular_name' => __('Portfolios', 'ptf-filter'),
            ),
            'public' => true,
            'has_archive' => true,
            'menu_icon' => 'dashicons-book',
            'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
            'rewrite' => array('slug' => 'portfolios'),
        )
    );
}
add_action('init', 'ptf_filter_create_custom_post_type');


function ptf_filter_create_custom_taxonomy()
{
    register_taxonomy(
        'portfolio_category',
        'portfolios',
        array(
            'label' => 'Portfolio Categories',
            'hierarchical' => true,
            'public' => true,
            'rewrite' => array('slug' => 'portfolio-portfolio'),
        )
    );
}
add_action('init', 'ptf_filter_create_custom_taxonomy');