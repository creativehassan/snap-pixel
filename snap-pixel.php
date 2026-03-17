<?php
/**
 * Plugin Name: Pixel Integration for Snapchat
 * Plugin URI:  https://wordpress.org/plugins/snap-pixel
 * Description: Integrate the Snapchat tracking pixel into your WordPress and WooCommerce site to measure the cross-device impact of campaigns. Best suited for direct response goals such as driving leads, subscriptions, or product sales. This plugin is not affiliated with or endorsed by Snap Inc.
 * Version:     1.9.2
 * Author:      Hassan Ali
 * Author URI:  https://creativehassan.com
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: snap-pixel
 * Domain Path: /languages
 *
 * @package Snap_Pixel
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define plugin constants
define( 'SNAP_PIXEL_VERSION', '1.9.2' );
define( 'SNAP_PIXEL_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'SNAP_PIXEL_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'SNAP_PIXEL_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );



/**
 * Activation hook.
 */
function snap_pixel_activate() {
    // Preserve existing settings
    $existing_settings = get_option( 'snapchat_pixel_code', array() );
    if ( empty( $existing_settings ) ) {
        // Set default settings if none exist
        $default_settings = array(
            'pixel_id' => '',
            'user_email' => get_option( 'admin_email' ),
            'homepage' => '',
            'pages' => '',
            'posts' => '',
            'search' => '',
            'categories' => '',
            'tags' => '',
            'viewcart' => '',
            'checkout' => '',
            'paymentinfo' => '',
            'addtocart' => '',
            'ajax_addtocart' => ''
        );
        update_option( 'snapchat_pixel_code', $default_settings );
    }
}

/**
 * Deactivation hook.
 */
function snap_pixel_deactivate() {
    // We keep settings on deactivation for better user experience
}

// Register activation and deactivation hooks
register_activation_hook( __FILE__, 'snap_pixel_activate' );
register_deactivation_hook( __FILE__, 'snap_pixel_deactivate' );

/**
 * Include required files.
 */
function snap_pixel_includes() {
    // Core classes
    require_once SNAP_PIXEL_PLUGIN_DIR . 'includes/class-snap-pixel.php';
    require_once SNAP_PIXEL_PLUGIN_DIR . 'includes/class-snap-pixel-admin.php';
    require_once SNAP_PIXEL_PLUGIN_DIR . 'includes/class-snap-pixel-core.php';
    
    // WooCommerce integration (always include the file, but the class will check if WooCommerce is active)
    require_once SNAP_PIXEL_PLUGIN_DIR . 'includes/class-snap-pixel-woocommerce.php';
    
    // Easy Digital Downloads integration (always include the file, but the class will check if EDD is active)
    require_once SNAP_PIXEL_PLUGIN_DIR . 'includes/class-snap-pixel-edd.php';
}

// Include required files
snap_pixel_includes();

/**
 * Global instance of the plugin.
 */
global $snap_pixel;

/**
 * Begins execution of the plugin.
 */
function run_snap_pixel() {
    global $snap_pixel;
    
    // Only initialize if not already initialized
    if ( ! isset( $snap_pixel ) || ! $snap_pixel instanceof Snap_Pixel ) {
        $snap_pixel = new Snap_Pixel();
    }
    
    return $snap_pixel;
}

// Initialize the plugin on plugins_loaded to ensure proper loading order
add_action( 'plugins_loaded', 'run_snap_pixel', 10 );

/**
 * Register an add-on for the Pixel Integration for Snapchat plugin.
 *
 * @param string $addon_id The ID of the add-on.
 * @param string $addon_name The name of the add-on.
 * @param string $addon_description The description of the add-on.
 * @param string $addon_url The URL for the add-on.
 * @param string $addon_class The class name of the add-on.
 */
function snap_pixel_register_addon( $addon_id, $addon_name, $addon_description = '', $addon_url = '', $addon_class = '' ) {
    global $snap_pixel;
    
    if ( $snap_pixel instanceof Snap_Pixel && method_exists( $snap_pixel, 'register_addon' ) ) {
        $snap_pixel->register_addon( $addon_id, $addon_name, $addon_description, $addon_url, $addon_class );
    }
} 