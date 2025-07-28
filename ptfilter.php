<?php
/**
 * Plugin Name: PT Filter
 * Plugin URI: devhiv.com/ptfilter
 * Description: This is custom post ajax filter
 * Version:     1.0.1
 * Author:      aponwpdev
 * Author URI:  https://profiles.wordpress.org/aponwpdev/
 * Text Domain: pt-filter
 * License: GPL v2 or later
 * License URI:http://www.gnu.org/licenses/gpl-2.0.txt
 * Requires at least: 6.2
 * Tested up to: 6.6.2
 * Requires PHP: 7.4
 * Domain Path: /languages
 */

if (!defined('ABSPATH')) {
    exit;
}
define('PTFILTER_PLUGIN_FILE', __FILE__);
define('PTFILTER_DIR_PATH_PRO', plugin_dir_path(__FILE__));
define('PTFILTER_PLUGIN_TEMPLATES_DIR', plugin_dir_path(__FILE__) . 'templates/');
define('PTFILTER_DIR_URL_PRO', plugin_dir_url(__FILE__));
define('PTFILTER_VERSION', '1.0.1');

require PTFILTER_DIR_PATH_PRO . 'post-type/post-type.php';
require PTFILTER_DIR_PATH_PRO . 'functions/pfilter-functions.php';

// Assets Load
function ptfilter_enqueue_custom_scripts()
{

    wp_enqueue_style('bootstrap', PTFILTER_DIR_URL_PRO . 'assets/css/bootstrap.min.css', array(), PTFILTER_VERSION);
    wp_enqueue_style('ptf-template-style', PTFILTER_DIR_URL_PRO . 'assets/css/pfilter-template.css', array(), PTFILTER_VERSION);

    wp_enqueue_script('ptf-isotop-scripts', PTFILTER_DIR_URL_PRO . 'assets/js/isotope.pkgd.min.js', array('jquery', 'imagesloaded'), PTFILTER_VERSION, true);
    wp_enqueue_script('ptf-ajax-load', PTFILTER_DIR_URL_PRO . 'assets/js/pfilter-ajax-load.js', array('jquery'), PTFILTER_VERSION, true);
    wp_enqueue_script('pfilter-template-scripts', PTFILTER_DIR_URL_PRO . 'assets/js/pfilter-template.js', array('jquery'), PTFILTER_VERSION, true);


    wp_localize_script('ptf-ajax-load', 'ajax_filter_params', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('ptf-filter-nonce'),
    ));
}

add_action('wp_enqueue_scripts', 'ptfilter_enqueue_custom_scripts');

add_action('plugins_loaded', 'ptfilter_load_text_domain');

function ptfilter_load_text_domain() {
    load_plugin_textdomain('pt-filter', false, plugin_basename(dirname(__FILE__)) . '/languages');  
}

function ptfilter_custom_page_template($templates)
{
    $templates['pfilter-template.php'] = esc_html__("Filter Template", "pt-filter");
    $templates['pfilter-template-pagination.php'] = esc_html__("Pagination Template", "pt-filter");
    $templates['pfilter-ajax-load.php'] = esc_html__("Ajax Load", "pt-filter");
    $templates['pfilter-loadmore.php'] = esc_html__("Load More", "pt-filter");
    return $templates;
}

add_filter('theme_page_templates', 'ptfilter_custom_page_template');


function ptfilter_template_nonce_field() {
    
    $nonce = wp_create_nonce( 'ptf-filter-template-nonce' ); ?>
<input type="hidden" name="ptf-filter-template-nonce" value="<?php echo esc_attr( $nonce );?>" />
<?php 
}

add_action( 'edit_form_after_title', 'ptfilter_template_nonce_field' );

function ptfilter_save_selected_template($post_id)
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

add_action('save_post', 'ptfilter_save_selected_template');

// Function to load the AJAX Load Template
if ( ! function_exists( 'ptfilter_ajax_load_shortcode' ) ) {
    function ptfilter_ajax_load_shortcode() {
        ob_start();
        $template_path = plugin_dir_path( __FILE__ ) . 'shortcode-templates/ajax-load-template.php';
        // Check if the template file exists before including it
        if ( file_exists( $template_path ) ) {
            include $template_path;
        }
        return ob_get_clean();
    }
}
// Function to load the AJAX Load More button Template
if( ! function_exists( 'ptfilter_ajax_load_more_shortcode' )){
    function ptfilter_ajax_load_more_shortcode(){
        ob_start();
        $template_path = plugin_dir_path(__FILE__) . 'shortcode-templates/ajax-loadmore-template.php';
         // Check if the template file exists before including it
         if ( file_exists( $template_path ) ) {
            include $template_path;
        }
        return ob_get_clean();
    }
}

// Function to load the isotop filter template
if( ! function_exists( 'ptfilter_isotop_filter_shortcode' )){
    function ptfilter_isotop_filter_shortcode(){
        ob_start();
        $template_path = plugin_dir_path(__FILE__) . 'shortcode-templates/isotop-load-template.php';
         // Check if the template file exists before including it
         if ( file_exists( $template_path ) ) {
            include $template_path;
        }
        return ob_get_clean();
    }
}

// Function to pagination template
if( ! function_exists( 'ptfilter_pagination_shortcode' )){
    function ptfilter_pagination_shortcode(){
        ob_start();
        $template_path = plugin_dir_path(__FILE__) . 'shortcode-templates/pagination-template.php';
         // Check if the template file exists before including it
         if ( file_exists( $template_path ) ) {
            include $template_path;
        }
        return ob_get_clean();
    }
}


// Register the shortcode
function ptfilter_register_shortcodes() {
    add_shortcode('ptfilter_pagination', 'ptfilter_pagination_shortcode');
    add_shortcode('ptfilter_ajax_load_more_button', 'ptfilter_ajax_load_more_shortcode');
    add_shortcode('ptfilter_load_iso_filter', 'ptfilter_isotop_filter_shortcode');
    add_shortcode('ptfilter_ajax_load', 'ptfilter_ajax_load_shortcode');
}
add_action('init', 'ptfilter_register_shortcodes');


// testing
