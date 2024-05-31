<?php
/**
 * Plugin Name: PT Filter
 * Plugin URI: https://github.com/abdulazizsardar/ptfilter
 * Description: <a href="#">hello</a> is the most advanced frontend drag & drop page builder addon
 * Version:     1.0.0
 * Author:      Abdul Aziz Sardar
 * Author URI:  https://profiles.wordpress.org/aponwpdev/
 * License: GPLv2 or later
 * Text Domain: ptfilter
 */

if (!defined('ABSPATH')) {
    exit;
}
define('PTF_FILTER_PLUGIN_FILE', __FILE__);
define('PTF_FILTER_DIR_PATH_PRO', plugin_dir_path(__FILE__));
define('PTF_FILTER_PLUGIN_TEMPLATES_DIR', plugin_dir_path(__FILE__) . 'templates/');
define('PTF_FILTER_DIR_URL_PRO', plugin_dir_url(__FILE__));
define('PTF_FILTER_VERSION', '1.0.0');


require PTF_FILTER_DIR_PATH_PRO . 'post-type/post-type.php';
require PTF_FILTER_DIR_PATH_PRO . 'functions/pfilter-functions.php';

// Assets Load
function ptf_filter_enqueue_custom_scripts()
{

    wp_enqueue_style('bootstrap', PTF_FILTER_DIR_URL_PRO . 'assets/css/bootstrap.min.css', array(), PTF_FILTER_VERSION);
    wp_enqueue_style('ptf-template-style', PTF_FILTER_DIR_URL_PRO . 'assets/css/pfilter-template.css', array(), PTF_FILTER_VERSION);

    wp_enqueue_script('ptf-isotop-scripts', PTF_FILTER_DIR_URL_PRO . 'assets/js/isotope.js', array('jquery', 'imagesloaded'), PTF_FILTER_VERSION, true);
    wp_enqueue_script('ptf-ajax-load', PTF_FILTER_DIR_URL_PRO . 'assets/js/pfilter-ajax-load.js', array('jquery'), PTF_FILTER_VERSION, true);
    wp_enqueue_script('pfilter-template-scripts', PTF_FILTER_DIR_URL_PRO . 'assets/js/pfilter-template.js', array('jquery'), PTF_FILTER_VERSION, true);


    wp_localize_script('ptf-ajax-load', 'ajax_filter_params', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('ptf-filter-nonce'),
    ));
}

add_action('wp_enqueue_scripts', 'ptf_filter_enqueue_custom_scripts');

add_action('plugins_loaded', 'ptf_filter_load_text_domain');

function ptf_filter_load_text_domain() {

    load_plugin_textdomain('ptfilter', false, plugin_basename(dirname(__FILE__)) . '/languages');  
    
}

function ptf_filter_custom_page_template($templates)
{
    $templates['pfilter-template.php'] = __("Filter Template", "ptfilter");
    $templates['pfilter-template-pagination.php'] = __("Pagination Template", "ptfilter");
    $templates['pfilter-ajax-load.php'] = __("Ajax Load", "ptfilter");
    $templates['pfilter-loadmore.php'] = __("Load More", "ptfilter");
    $templates['pfilter-ajaxloadmoreandfilter.php'] = __("Ajax Filter And Loadmore", "ptfilter");
    return $templates;
}

add_filter('theme_page_templates', 'ptf_filter_custom_page_template');


function ptf_filter_template_nonce_field() {
    
    $nonce = wp_create_nonce( 'ptf-filter-template-nonce' ); ?>
<input type="hidden" name="ptf-filter-template-nonce" value="<?php echo esc_attr( $nonce );?>" />
<?php 
}

add_action( 'edit_form_after_title', 'ptf_filter_template_nonce_field' );

function ptf_filter_save_selected_template($post_id)
{

    // Check if nonce is set and verify it
    if ( ! isset( $_POST['ptf-filter-template-nonce'] ) 
    || ! wp_verify_nonce( sanitize_text_field( wp_unslash ( $_POST['ptf-filter-template-nonce'] ) ), 'ptf-filter-template-nonce' ) ) {
        return $post_id;
    }

    // Check if autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }

    // Check if current user can edit the post
    if (!current_user_can('edit_post', $post_id)) {
        return $post_id;
    }

    // Sanitize and update page template meta
    $page_template = isset($_POST['page_template']) ? sanitize_text_field($_POST['page_template']) : '';
    update_post_meta($post_id, '_wp_page_template', $page_template);
}

add_action('save_post', 'ptf_filter_save_selected_template');


function ptf_filter_apply_custom_page_template($template)
{

    $selected_template = get_post_meta(get_the_ID(), '_wp_page_template', true);

    if ($selected_template === 'pfilter-template.php') {
        // Load your custom template file when the "Pfilter Template" template is selected.
        $template = PTF_FILTER_PLUGIN_TEMPLATES_DIR . 'pfilter-template.php';
    } elseif ($selected_template === 'pfilter-ajax-load.php') {
        $template = PTF_FILTER_PLUGIN_TEMPLATES_DIR . 'pfilter-ajax-load.php';
    } elseif ($selected_template === 'pfilter-loadmore.php') {
        $template = PTF_FILTER_PLUGIN_TEMPLATES_DIR . 'pfilter-loadmore.php';
    } elseif ($selected_template === 'pfilter-loadmoreandfilter.php') {
        $template = PTF_FILTER_PLUGIN_TEMPLATES_DIR . 'pfilter-ajaxloadmoreandfilter.php';
    } else {
        $template = PTF_FILTER_PLUGIN_TEMPLATES_DIR . 'pfilter-template-pagination.php';
    }


    return $template;
}
add_filter('template_include', 'ptf_filter_apply_custom_page_template');