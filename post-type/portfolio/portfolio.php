<?php

function create_custom_post_type() {
    register_post_type('portfolios',
        array(
            'labels' => array(
                'name' => 'Portfolios',
                'singular_name' => 'Portfolios',
            ),
            'public' => true,
            'has_archive' => true,
            'menu_icon' => 'dashicons-book',
            'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
            'rewrite' => array( 'slug' => 'portfolios' ),
        )
    );
}
add_action('init', 'create_custom_post_type');


function create_custom_taxonomy() {
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
add_action('init', 'create_custom_taxonomy');






		
		
				
		

