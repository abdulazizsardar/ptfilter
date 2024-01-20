<?php
/**
 * Plugin Name: PT Filter
 * Description: <a href="#">hello</a> is the most advanced frontend drag & drop page builder addon
 * Plugin URI:  https://wordpress.org/plugins/pfilter/
 * Version:     1.0.0
 * Author:      Abdul Aziz Sardar
 * Author URI:  https://abdulazizz.xyz
 * Text Domain: ptfilter
 */

if (!defined('ABSPATH')) {
    exit;
}
define( 'PTFILTER_PLUGIN_FILE', __FILE__ );
define( 'PTFILTER_DIR_PATH_PRO', plugin_dir_path( __FILE__ ) );
define( 'PTFILTER_PLUGIN_TEMPLATES_DIR', plugin_dir_path(__FILE__) . 'templates/');
define( 'PTFILTER_DIR_URL_PRO', plugin_dir_url( __FILE__ ) );
define( 'PTFILTER_VERSION','1.0.0');


require PTFILTER_DIR_PATH_PRO . 'post-type/post-type.php';
require PTFILTER_DIR_PATH_PRO . 'functions/pfilter-functions.php';

// Assets Load
function pfilter_enqueue_custom_scripts() {

        wp_enqueue_style('bootstrap', PTFILTER_DIR_URL_PRO . 'assets/css/bootstrap.min.css', array(), time());
        wp_enqueue_style('pfilter-template-style', PTFILTER_DIR_URL_PRO . 'assets/css/pfilter-template.css', array(), time());


        wp_enqueue_script('pfilter-isotop-scripts', PTFILTER_DIR_URL_PRO . 'assets/js/isotope.js', array('jquery', 'imagesloaded'), time(), true);
        wp_enqueue_script('pfilter-ajax-load', PTFILTER_DIR_URL_PRO . 'assets/js/pfilter-ajax-load.js', array('jquery'), null, true);
        wp_enqueue_script('pfilter-template-scripts', PTFILTER_DIR_URL_PRO . 'assets/js/pfilter-template.js', array('jquery'), time(), true);

    
        wp_localize_script('pfilter-ajax-load', 'ajax_filter_params', array(
                'ajax_url' => admin_url('admin-ajax.php'),
            ));
    
    
}

add_action('wp_enqueue_scripts', 'pfilter_enqueue_custom_scripts');



function add_custom_page_template($templates) {
    $templates['pfilter-template.php'] = 'Filter Template';
    $templates['pfilter-template-pagination.php'] = 'Pagination Template';
    $templates['pfilter-ajax-load.php'] = 'Ajax Load';
    $templates['pfilter-loadmore.php'] = 'Load More';
    $templates['pfilter-ajaxloadmoreandfilter.php'] = 'Ajax Filter And Loadmore';
    return $templates;
}
add_filter('theme_page_templates', 'add_custom_page_template');


function save_selected_template($post_id) {

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;
    
    if (isset($_POST['page_template']) && current_user_can('edit_post', $post_id)) {
        update_post_meta($post_id, '_wp_page_template', $_POST['page_template']);
    }
}
add_action('save_post', 'save_selected_template');


function apply_custom_page_template($template) {

        $selected_template = get_post_meta(get_the_ID(), '_wp_page_template', true);
        
        if ($selected_template === 'pfilter-template.php') {
            // Load your custom template file when the "Pfilter Template" template is selected.
            $template = PTFILTER_PLUGIN_TEMPLATES_DIR . 'pfilter-template.php';
        } elseif ($selected_template === 'pfilter-ajax-load.php') {
            $template = PTFILTER_PLUGIN_TEMPLATES_DIR . 'pfilter-ajax-load.php';
        }elseif ($selected_template === 'pfilter-loadmore.php') {
            $template = PTFILTER_PLUGIN_TEMPLATES_DIR . 'pfilter-loadmore.php';
        }elseif ($selected_template === 'pfilter-loadmoreandfilter.php') {
            $template = PTFILTER_PLUGIN_TEMPLATES_DIR . 'pfilter-ajaxloadmoreandfilter.php';
        }
        else {
            $template = PTFILTER_PLUGIN_TEMPLATES_DIR . 'pfilter-template-pagination.php';
        }
 

    return $template;
}
add_filter('template_include', 'apply_custom_page_template');
















