<?php

/**
 * The core plugin class.
 *
 * @package    Snap_Pixel
 * @subpackage Snap_Pixel/includes
 */

class Snap_Pixel {

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @var      Snap_Pixel_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @var      string    $version    The current version of the plugin.
     */
    protected $version;
    
    /**
     * Admin class instance.
     *
     * @var Snap_Pixel_Admin
     */
    protected $admin;

    /**
     * Core class instance.
     *
     * @var Snap_Pixel_Core
     */
    protected $core;

    /**
     * WooCommerce integration class instance.
     *
     * @var Snap_Pixel_WooCommerce
     */
    protected $woocommerce;

    /**
     * Easy Digital Downloads integration class instance.
     *
     * @var Snap_Pixel_EDD
     */
    protected $edd;

    /**
     * Registered add-ons for the plugin.
     *
     * @var      array    $addons    The registered add-ons.
     */
    protected $addons = array();

    /**
     * Initialize the plugin.
     */
    public function __construct() {
        if ( defined( 'SNAP_PIXEL_VERSION' ) ) {
            $this->version = SNAP_PIXEL_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        $this->plugin_name = 'snap-pixel';

        $this->load_dependencies();
        $this->define_admin_hooks();
        $this->define_public_hooks();
        
        // Initialize add-ons property
        $this->addons = array();
    }
    
    /**
     * Load the required dependencies for this plugin.
     */
    private function load_dependencies() {
        // No additional dependencies needed
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     */
    private function define_admin_hooks() {
        // Initialize admin class
        $this->admin = new Snap_Pixel_Admin();
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     */
    private function define_public_hooks() {
        // Initialize core functionality
        $this->core = new Snap_Pixel_Core();
        
        // Initialize WooCommerce integration if WooCommerce is active
        if ( $this->is_woocommerce_active() ) {
            $this->woocommerce = new Snap_Pixel_WooCommerce();
        }
        
        // Initialize Easy Digital Downloads integration if EDD is active
        if ( $this->is_edd_active() ) {
            $this->edd = new Snap_Pixel_EDD();
        }
    }
    
    /**
     * Check if WooCommerce is active.
     *
     * @return bool True if WooCommerce is active, false otherwise.
     */
    private function is_woocommerce_active() {
        return in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) );
    }
    
    /**
     * Check if Easy Digital Downloads is active.
     *
     * @return bool True if EDD is active, false otherwise.
     */
    private function is_edd_active() {
        return class_exists( 'Easy_Digital_Downloads' );
    }

    /**
     * Register an add-on for the plugin.
     *
     * @param string $addon_id The ID of the add-on.
     * @param string $addon_name The name of the add-on.
     * @param string $addon_description The description of the add-on.
     * @param string $addon_url The URL for the add-on.
     * @param string $addon_class The class name of the add-on.
     */
    public function register_addon( $addon_id, $addon_name, $addon_description = '', $addon_url = '', $addon_class = '' ) {
        $this->addons[ $addon_id ] = array(
            'name' => $addon_name,
            'description' => $addon_description,
            'url' => $addon_url,
            'class' => $addon_class
        );
        
        // Add filter to make add-ons available in the help tab
        add_filter( 'snap_pixel_available_addons', array( $this, 'get_available_addons' ) );
    }
    
    /**
     * Get all registered add-ons.
     *
     * @return array The registered add-ons.
     */
    public function get_addons() {
        return $this->addons;
    }
    
    /**
     * Get all available add-ons for display in the help tab.
     *
     * @param array $addons The existing add-ons.
     * @return array The available add-ons.
     */
    public function get_available_addons( $addons ) {
        return $this->addons;
    }
    
    /**
     * Check if an add-on is registered.
     *
     * @param string $addon_id The ID of the add-on.
     * @return bool Whether the add-on is registered.
     */
    public function is_addon_registered( $addon_id ) {
        return isset( $this->addons[ $addon_id ] );
    }
    
    /**
     * Check if an add-on is active.
     *
     * @param string $addon_id The ID of the add-on.
     * @return bool Whether the add-on is active.
     */
    public function is_addon_active( $addon_id ) {
        return $this->is_addon_registered( $addon_id ) && class_exists( $this->addons[ $addon_id ]['class'] );
    }
} 