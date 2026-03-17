<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @package    Snap_Pixel
 * @subpackage Snap_Pixel/admin
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * The admin-specific functionality of the plugin.
 */
class Snap_Pixel_Admin {

    /**
     * Initialize the class and set its properties.
     */
    public function __construct() {
        // Add admin menu
        add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
        
        // Add settings link on plugin page
        add_filter( 'plugin_action_links_' . SNAP_PIXEL_PLUGIN_BASENAME, array( $this, 'add_settings_link' ) );
        
        // Add plugin row meta
        add_filter( 'plugin_row_meta', array( $this, 'add_plugin_row_meta' ), 10, 2 );
        
        // Admin notice for snap pixel ID
        add_action( 'admin_notices', array( $this, 'admin_notices' ) );
        
        // Enqueue admin scripts and styles
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
        
        // Register settings
        add_action( 'admin_init', array( $this, 'register_settings' ) );
    }

    /**
     * Register the admin menu.
     */
    public function add_admin_menu() {
        add_menu_page(
            __( 'Pixel Integration for Snapchat', 'snap-pixel' ),
            __( 'Pixel for Snapchat', 'snap-pixel' ),
            'manage_options',
            'snap-pixel',
            array( $this, 'display_admin_page' ),
            SNAP_PIXEL_PLUGIN_URL . 'assets/images/snapchat-logo.svg'
        );
    }

    /**
     * Add settings link to plugin action links.
     *
     * @param array $links Plugin action links.
     * @return array Modified plugin action links.
     */
    public function add_settings_link( $links ) {
        $settings_link = '<a href="' . admin_url( 'admin.php?page=snap-pixel&tab=general' ) . '">' . __( 'Settings', 'snap-pixel' ) . '</a>';
        array_unshift( $links, $settings_link );
        return $links;
    }

    /**
     * Add plugin row meta links.
     *
     * @param array  $links Plugin row meta links.
     * @param string $file  Plugin base file.
     * @return array Modified plugin row meta links.
     */
    public function add_plugin_row_meta( $links, $file ) {
        if ( SNAP_PIXEL_PLUGIN_BASENAME === $file ) {
            $row_meta = array(
                'docs' => '<a href="' . esc_url( 'https://businesshelp.snapchat.com/en-US/article/snap-pixel-about' ) . '" aria-label="' . esc_attr__( 'Snapchat Pixel Docs', 'snap-pixel' ) . '" target="_blank">' . esc_html__( 'Snapchat Pixel Docs', 'snap-pixel' ) . '</a>',
            );
            return array_merge( $links, $row_meta );
        }
        return $links;
    }

    /**
     * Display admin notices.
     */
    public function admin_notices() {
        $settings = get_option( 'snapchat_pixel_code', array() );
        $pixel_id = isset( $settings['pixel_id'] ) ? $settings['pixel_id'] : '';
        
        if ( empty( $pixel_id ) ) {
            $message = sprintf(
                /* translators: 1: Opening strong tag, 2: Closing strong tag, 3: Opening link tag, 4: Closing link tag */
                esc_html__( '%1$sPixel Integration for Snapchat is almost ready.%2$s To complete your settings, add the %3$sPixel ID%4$s.', 'snap-pixel' ),
                '<strong>',
                '</strong>',
                '<a href="' . esc_url( admin_url( 'admin.php?page=snap-pixel&tab=general' ) ) . '">',
                '</a>'
            );
            
            echo '<div class="notice notice-info is-dismissible"><p>' . wp_kses_post( $message ) . '</p></div>';
        }
    }

    /**
     * Enqueue admin scripts and styles.
     *
     * @param string $hook The current admin page.
     */
    public function enqueue_admin_assets( $hook ) {
        // Load global admin styles on all admin pages
        wp_enqueue_style(
            'snap-pixel-global-admin',
            SNAP_PIXEL_PLUGIN_URL . 'assets/css/snap-pixel-global-admin.css',
            array(),
            SNAP_PIXEL_VERSION
        );
        
        // Only load plugin-specific styles and scripts on our plugin's admin page
        if ( 'toplevel_page_snap-pixel' !== $hook ) {
            return;
        }
        
        // Enqueue admin styles
        wp_enqueue_style(
            'snap-pixel-admin',
            SNAP_PIXEL_PLUGIN_URL . 'assets/css/snap-pixel-admin.css',
            array(),
            SNAP_PIXEL_VERSION
        );
        
        // Enqueue admin scripts
        wp_enqueue_script(
            'snap-pixel-admin',
            SNAP_PIXEL_PLUGIN_URL . 'assets/js/snap-pixel-admin.js',
            array( 'jquery' ),
            SNAP_PIXEL_VERSION,
            true
        );
    }

    /**
     * Display the admin page.
     */
    public function display_admin_page() {
        // Process form submission
        $this->process_form_submission();
        
        // Get current tab
        $current_tab = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : 'general';
        
        // Get available tabs
        $tabs = $this->get_admin_tabs();
        
        // Include admin view
        require_once SNAP_PIXEL_PLUGIN_DIR . 'admin/admin-display.php';
    }

    /**
     * Get admin tabs.
     *
     * @return array Array of admin tabs.
     */
    public function get_admin_tabs() {
        $tabs = array(
            'general' => __( 'General Settings', 'snap-pixel' ),
            'woocommerce' => __( 'WooCommerce Integration', 'snap-pixel' ),
            'edd' => __( 'EDD Integration', 'snap-pixel' ),
            'help' => __( 'Help & Support', 'snap-pixel' )
        );
        
        // Allow add-ons to add their own tabs
        return apply_filters( 'snap_pixel_admin_tabs', $tabs );
    }
    
    /**
     * Display tab content.
     * 
     * @param string $tab The tab to display.
     */
    public function display_tab_content( $tab ) {
        // Check if there's a custom action for this tab
        if ( has_action( 'snap_pixel_tab_content_' . $tab ) ) {
            do_action( 'snap_pixel_tab_content_' . $tab );
            return;
        }
        
        // Otherwise, use the default tab content
        switch ( $tab ) {
            case 'general':
                $this->display_general_tab();
                break;
                
            case 'woocommerce':
                $this->display_woocommerce_tab();
                break;
                
            case 'edd':
                $this->display_edd_tab();
                break;
                
            case 'help':
                $this->display_help_tab();
                break;
                
            default:
                $this->display_general_tab();
                break;
        }
    }
    
    /**
     * Display general tab content.
     */
    private function display_general_tab() {
        // Get settings
        $settings = get_option( 'snapchat_pixel_code', array() );
        $pixel_id = isset( $settings['pixel_id'] ) ? $settings['pixel_id'] : '';
        $user_email = isset( $settings['user_email'] ) ? $settings['user_email'] : get_option( 'admin_email' );
        $homepage = isset( $settings['homepage'] ) ? $settings['homepage'] : '';
        $pages = isset( $settings['pages'] ) ? $settings['pages'] : '';
        $posts = isset( $settings['posts'] ) ? $settings['posts'] : '';
        $search = isset( $settings['search'] ) ? $settings['search'] : '';
        $categories = isset( $settings['categories'] ) ? $settings['categories'] : '';
        $tags = isset( $settings['tags'] ) ? $settings['tags'] : '';
        
        include SNAP_PIXEL_PLUGIN_DIR . 'admin/partials/general-tab.php';
    }
    
    /**
     * Display WooCommerce tab content.
     */
    private function display_woocommerce_tab() {
        // WooCommerce settings
        $woo_active = in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) );
        $woo_access = get_option( 'snapchat_pixel_wooacces', 'no' );
        
        // Get settings
        $settings = get_option( 'snapchat_pixel_code', array() );
        $viewcart = isset( $settings['viewcart'] ) ? $settings['viewcart'] : '';
        $checkout = isset( $settings['checkout'] ) ? $settings['checkout'] : '';
        $paymentinfo = isset( $settings['paymentinfo'] ) ? $settings['paymentinfo'] : '';
        $addtocart = isset( $settings['addtocart'] ) ? $settings['addtocart'] : '';
        $ajax_addtocart = isset( $settings['ajax_addtocart'] ) ? $settings['ajax_addtocart'] : '';
        
        include SNAP_PIXEL_PLUGIN_DIR . 'admin/partials/woocommerce-tab.php';
    }
    
    /**
     * Display EDD tab content.
     */
    private function display_edd_tab() {
        // EDD settings
        $edd_active = class_exists( 'Easy_Digital_Downloads' );
        $edd_access = get_option( 'snapchat_pixel_edd_access', 'no' );
        $edd_settings = get_option( 'snapchat_pixel_edd', array() );
        
        include SNAP_PIXEL_PLUGIN_DIR . 'admin/partials/edd-tab.php';
    }
    
    /**
     * Display help tab content.
     */
    private function display_help_tab() {
        include SNAP_PIXEL_PLUGIN_DIR . 'admin/partials/help-tab.php';
    }

    /**
     * Process form submission.
     */
    private function process_form_submission() {
        // Process WooCommerce settings
        if ( isset( $_POST['save_snapchat_pixel_woo'] ) ) {
            // Verify nonce
            if ( ! isset( $_POST['snapchat_pixel_woo_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['snapchat_pixel_woo_nonce'] ) ), 'snapchat_pixel_woo_security' ) ) {
                add_settings_error( 'snap-pixel-woocommerce', 'snap-pixel-woo-error', __( 'Security check failed. Please try again.', 'snap-pixel' ), 'error' );
                return;
            }
            
            // Save WooCommerce access setting
            if ( isset( $_POST['woo_activate'] ) ) {
                $woo_activate = sanitize_text_field( wp_unslash( $_POST['woo_activate'] ) );
                update_option( 'snapchat_pixel_wooacces', $woo_activate );
            } else {
                update_option( 'snapchat_pixel_wooacces', 'no' );
            }
            
            // Save WooCommerce settings
            if ( isset( $_POST['snapchat_pixel_code'] ) && is_array( $_POST['snapchat_pixel_code'] ) ) {
                // Get existing settings
                $existing_settings = get_option( 'snapchat_pixel_code', array() );
                
                // Sanitize the WooCommerce settings
                $woo_settings = array();
                $raw_data = wp_unslash( $_POST['snapchat_pixel_code'] );
                
                // Process checkbox fields
                $checkbox_fields = array( 'viewcart', 'checkout', 'paymentinfo', 'addtocart', 'ajax_addtocart' );
                foreach ( $checkbox_fields as $field ) {
                    $existing_settings[$field] = isset( $raw_data[$field] ) && 'checked' === $raw_data[$field] ? 'checked' : '';
                }
                
                // Update the option
                update_option( 'snapchat_pixel_code', $existing_settings );
            }
            
            add_settings_error( 'snap-pixel-woocommerce', 'snap-pixel-woo-updated', __( 'WooCommerce settings updated.', 'snap-pixel' ), 'success' );
        }
        
        // Process EDD settings
        if ( isset( $_POST['save_snapchat_pixel_edd'] ) && '1' === $_POST['save_snapchat_pixel_edd'] ) {
            
            if ( ! isset( $_POST['snapchat_pixel_edd_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['snapchat_pixel_edd_nonce'] ) ), 'snapchat_pixel_edd_security' ) ) {
                add_settings_error( 'snap-pixel-edd', 'snap-pixel-edd-error', __( 'Security check failed. Please try again.', 'snap-pixel' ), 'error' );
                return;
            }
            
            // Save EDD access setting
            $edd_access = isset( $_POST['edd_activate'] ) ? sanitize_text_field( wp_unslash( $_POST['edd_activate'] ) ) : 'no';
            update_option( 'snapchat_pixel_edd_access', $edd_access );
            
            // Save EDD settings
            if ( isset( $_POST['snapchat_pixel_edd'] ) && is_array( $_POST['snapchat_pixel_edd'] ) ) {
                $edd_settings = array_map( 'sanitize_text_field', wp_unslash( $_POST['snapchat_pixel_edd'] ) );
                update_option( 'snapchat_pixel_edd', $edd_settings );
            }
            
            add_settings_error( 'snap-pixel-edd', 'snap-pixel-edd-updated', __( 'EDD settings updated.', 'snap-pixel' ), 'success' );
        }
        
        // Process general settings
        if ( isset( $_POST['snapchat_pixel_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['snapchat_pixel_nonce'] ) ), 'snapchat_pixel_security' ) ) {
            if ( isset( $_POST['snapchat_pixel_code'] ) && current_user_can( 'manage_options' ) ) {
                // Sanitize the entire array using array_map
                $raw_data = array_map( 'sanitize_text_field', wp_unslash( $_POST['snapchat_pixel_code'] ) );
                
                // Get existing settings to preserve WooCommerce settings
                $existing_settings = get_option( 'snapchat_pixel_code', array() );
                
                $sanitized_data = array(
                    'pixel_id'      => $this->sanitize_pixel_id( isset( $raw_data['pixel_id'] ) ? $raw_data['pixel_id'] : '' ),
                    'user_email'    => sanitize_email( isset( $raw_data['user_email'] ) ? $raw_data['user_email'] : '' ),
                    'homepage'      => isset( $raw_data['homepage'] ) && 'checked' === $raw_data['homepage'] ? 'checked' : '',
                    'pages'         => isset( $raw_data['pages'] ) && 'checked' === $raw_data['pages'] ? 'checked' : '',
                    'posts'         => isset( $raw_data['posts'] ) && 'checked' === $raw_data['posts'] ? 'checked' : '',
                    'search'        => isset( $raw_data['search'] ) && 'checked' === $raw_data['search'] ? 'checked' : '',
                    'categories'    => isset( $raw_data['categories'] ) && 'checked' === $raw_data['categories'] ? 'checked' : '',
                    'tags'          => isset( $raw_data['tags'] ) && 'checked' === $raw_data['tags'] ? 'checked' : '',
                    // Preserve WooCommerce settings
                    'viewcart'      => isset( $existing_settings['viewcart'] ) ? $existing_settings['viewcart'] : '',
                    'checkout'      => isset( $existing_settings['checkout'] ) ? $existing_settings['checkout'] : '',
                    'paymentinfo'   => isset( $existing_settings['paymentinfo'] ) ? $existing_settings['paymentinfo'] : '',
                    'addtocart'     => isset( $existing_settings['addtocart'] ) ? $existing_settings['addtocart'] : '',
                    'ajax_addtocart' => isset( $existing_settings['ajax_addtocart'] ) ? $existing_settings['ajax_addtocart'] : '',
                );

                update_option( 'snapchat_pixel_code', $sanitized_data );
                
                add_settings_error(
                    'snap-pixel',
                    'settings_updated',
                    __( 'Settings saved successfully.', 'snap-pixel' ),
                    'updated'
                );
            }
        }
    }

    /**
     * Sanitize the Pixel ID
     *
     * @param string $input The Pixel ID to sanitize
     * @return string The sanitized Pixel ID
     */
    private function sanitize_pixel_id( $input ) {
        // Basic sanitization
        $pixel_id = sanitize_text_field( $input );
        
        // Validate pixel ID format using regex (lowercase letters, numbers, and hyphens only)
        $re = '/^[a-z0-9\-]*$/m';
        if ( ! empty( $pixel_id ) && ! preg_match( $re, $pixel_id ) ) {
            // Add settings error for invalid pixel ID format
            add_settings_error(
                'snap-pixel',
                'snap-pixel-invalid-pixel-id',
                __( 'Invalid Pixel ID format. Pixel ID should only contain lowercase letters, numbers, and hyphens.', 'snap-pixel' ),
                'error'
            );
            // Return empty string to prevent saving invalid pixel ID
            return '';
        }
        
        return $pixel_id;
    }

    /**
     * Sanitize general settings.
     *
     * @param array $input The settings to sanitize.
     * @return array Sanitized settings.
     */
    public function sanitize_general_settings( $input ) {
        if ( ! is_array( $input ) ) {
            return array();
        }
        
        $sanitized = array();
        
        // Sanitize pixel ID
        $sanitized['pixel_id'] = isset( $input['pixel_id'] ) ? $this->sanitize_pixel_id( $input['pixel_id'] ) : '';
        
        // Sanitize user email
        $sanitized['user_email'] = isset( $input['user_email'] ) ? sanitize_email( $input['user_email'] ) : '';
        
        // Sanitize checkboxes
        $checkbox_fields = array(
            'homepage',
            'pages',
            'posts',
            'search',
            'categories',
            'tags',
            'viewcart',
            'checkout',
            'paymentinfo',
            'addtocart',
            'ajax_addtocart'
        );
        
        foreach ( $checkbox_fields as $field ) {
            $sanitized[$field] = isset( $input[$field] ) && 'checked' === $input[$field] ? 'checked' : '';
        }
        
        // Preserve any other fields that might be set by add-ons
        foreach ( $input as $key => $value ) {
            if ( ! isset( $sanitized[$key] ) ) {
                $sanitized[$key] = sanitize_text_field( $value );
            }
        }
        
        return $sanitized;
    }

    /**
     * Register settings.
     */
    public function register_settings() {
        // Register general settings
        // The sanitize_general_settings method properly sanitizes all fields in the snapchat_pixel_code option
        register_setting( 'snap-pixel-general', 'snapchat_pixel_code', array( $this, 'sanitize_general_settings' ) );
        
        // Register WooCommerce settings
        // The sanitize_checkbox method ensures the value is either 'yes' or 'no'
        register_setting( 'snap-pixel-woocommerce', 'snapchat_pixel_wooacces', array( $this, 'sanitize_checkbox' ) );
        
        // Register EDD settings
        // The sanitize_checkbox method ensures the value is either 'yes' or 'no'
        register_setting( 'snap-pixel-edd', 'snapchat_pixel_edd_access', array( $this, 'sanitize_checkbox' ) );
        register_setting( 'snap-pixel-edd', 'snapchat_pixel_edd', array( $this, 'sanitize_edd_settings' ) );
    }
    
    /**
     * Sanitize checkbox value.
     *
     * @param string $value The value to sanitize.
     * @return string Sanitized value.
     */
    public function sanitize_checkbox( $value ) {
        return ( 'yes' === $value ) ? 'yes' : 'no';
    }
    
    /**
     * Sanitize EDD settings.
     *
     * @param array $settings The settings to sanitize.
     * @return array Sanitized settings.
     */
    public function sanitize_edd_settings( $settings ) {
        if ( ! is_array( $settings ) ) {
            return array();
        }
        
        $sanitized = array();
        
        foreach ( $settings as $key => $value ) {
            if ( in_array( $key, array( 'view_content', 'add_cart', 'checkout', 'purchase' ), true ) ) {
                $sanitized[ $key ] = ( 'yes' === $value ) ? 'yes' : 'no';
            } else {
                $sanitized[ $key ] = sanitize_text_field( $value );
            }
        }
        
        return $sanitized;
    }
} 