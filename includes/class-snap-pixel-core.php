<?php
/**
 * The core functionality of the plugin.
 *
 * @package    Snap_Pixel
 * @subpackage Snap_Pixel/includes
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * The core functionality of the plugin.
 */
class Snap_Pixel_Core {

    /**
     * Pixel ID.
     *
     * @var string
     */
    private $pixel_id;

    /**
     * User email.
     *
     * @var string
     */
    private $user_email;

    /**
     * Settings for different page types.
     *
     * @var array
     */
    private $settings = array();
    
    /**
     * Flag to track if base pixel code has been output.
     *
     * @var bool
     */
    private $base_pixel_output = false;

    /**
     * Initialize the class and set its properties.
     */
    public function __construct() {
        $this->load_settings();
        
        // Add pixel code to appropriate pages
        add_action( 'template_redirect', array( $this, 'place_pixel_code' ) );
        
        // Register AJAX handler for product data
        add_action( 'wp_ajax_snapchat_product_data', array( $this, 'get_product_data' ) );
        add_action( 'wp_ajax_nopriv_snapchat_product_data', array( $this, 'get_product_data' ) );
        
        // Enqueue frontend assets
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_assets' ) );
        
        // Set user email after WordPress is fully loaded
        add_action( 'init', array( $this, 'set_user_email' ) );
    }

    /**
     * Load settings from database.
     */
    private function load_settings() {
        $settings = get_option( 'snapchat_pixel_code', array() );
        
        $this->pixel_id = isset( $settings['pixel_id'] ) ? $settings['pixel_id'] : '';
        $this->user_email = isset( $settings['user_email'] ) ? $settings['user_email'] : get_option( 'admin_email' );
        
        // Load page type settings
        $this->settings = array(
            'homepage'      => isset( $settings['homepage'] ) ? $settings['homepage'] : '',
            'pages'         => isset( $settings['pages'] ) ? $settings['pages'] : '',
            'posts'         => isset( $settings['posts'] ) ? $settings['posts'] : '',
            'search'        => isset( $settings['search'] ) ? $settings['search'] : '',
            'categories'    => isset( $settings['categories'] ) ? $settings['categories'] : '',
            'tags'          => isset( $settings['tags'] ) ? $settings['tags'] : '',
            'viewcart'      => isset( $settings['viewcart'] ) ? $settings['viewcart'] : '',
            'checkout'      => isset( $settings['checkout'] ) ? $settings['checkout'] : '',
            'paymentinfo'   => isset( $settings['paymentinfo'] ) ? $settings['paymentinfo'] : '',
            'addtocart'     => isset( $settings['addtocart'] ) ? $settings['addtocart'] : '',
            'ajax_addtocart' => isset( $settings['ajax_addtocart'] ) ? $settings['ajax_addtocart'] : '',
        );
    }

    /**
     * Set user email after WordPress is fully loaded.
     */
    public function set_user_email() {
        // Override user email if user is logged in
        if ( function_exists( 'is_user_logged_in' ) && is_user_logged_in() ) {
            $current_user = wp_get_current_user();
            $this->user_email = $current_user->user_email;
        }
    }

    /**
     * Enqueue frontend assets.
     */
    public function enqueue_frontend_assets() {
        wp_register_script(
            'snap-pixel',
            SNAP_PIXEL_PLUGIN_URL . 'assets/js/snap-pixel.js',
            array( 'jquery' ),
            SNAP_PIXEL_VERSION,
            false // Load in header
        );
        
        wp_localize_script(
            'snap-pixel',
            'snappixel',
            array(
                'ajaxurl' => admin_url( 'admin-ajax.php' ),
                'nonce'   => wp_create_nonce( 'snapchat_product_data' ),
                'prevent_duplicates' => true,
            )
        );
        
        wp_enqueue_script( 'snap-pixel' );
    }

    /**
     * Place pixel code on appropriate pages.
     */
    public function place_pixel_code() {
        global $post;
        
        // Don't proceed if pixel ID is not set
        if ( empty( $this->pixel_id ) ) {
            return;
        }
        
        $woo_active = $this->is_woocommerce_active();
        $woo_access = get_option( 'snapchat_pixel_wooacces', 'no' );
        
        // Track if we've added the base pixel code
        $base_pixel_added = false;
        
        // Homepage
        if ( ( is_home() || is_front_page() ) && 'checked' === $this->settings['homepage'] ) {
            add_action( 'wp_head', array( $this, 'output_base_pixel_code' ), 2 );
            $base_pixel_added = true;
        } 
        // Pages
        elseif ( is_page() && 'checked' === $this->settings['pages'] ) {
            add_action( 'wp_head', array( $this, 'output_base_pixel_code' ), 2 );
            $base_pixel_added = true;
            
            // WooCommerce checkout page
            if ( $woo_active && 'yes' === $woo_access && function_exists( 'is_checkout' ) ) {
                $page_slug = $post->post_name;
                
                if ( 'checkout' === $page_slug ) {
                    // This is checking for the existence of a URL parameter, not processing form data,
                    // so nonce verification is not applicable here
                    if ( ! isset( $_GET['key'] ) && 'checked' === $this->settings['checkout'] ) {
                        add_action( 'wp_footer', array( $this, 'output_checkout_pixel_code' ) );
                    } elseif ( 'checked' === $this->settings['paymentinfo'] ) {
                        if ( is_wc_endpoint_url( 'order-received' ) ) {
                            add_action( 'wp_footer', array( $this, 'output_purchase_pixel_code' ) );
                        } else {
                            add_action( 'wp_footer', array( $this, 'output_billing_pixel_code' ) );
                        }
                    }
                }
            }
        } 
        // Posts
        elseif ( ( is_single() && 'checked' === $this->settings['posts'] ) || ( function_exists( 'is_product' ) && is_product() ) ) {
            add_action( 'wp_head', array( $this, 'output_base_pixel_code' ), 2 );
            $base_pixel_added = true;
            
            // WooCommerce product page
            if ( $woo_active && 'yes' === $woo_access ) {
                add_action( 'wp_footer', array( $this, 'output_viewcontent_pixel_code' ) );
            }
        } 
        // Search
        elseif ( is_search() && 'checked' === $this->settings['search'] ) {
            add_action( 'wp_head', array( $this, 'output_base_pixel_code' ), 2 );
            $base_pixel_added = true;
            add_action( 'wp_footer', array( $this, 'output_search_pixel_code' ) );
        } 
        // Category archives
        elseif ( is_category() && 'checked' === $this->settings['categories'] ) {
            add_action( 'wp_head', array( $this, 'output_base_pixel_code' ), 2 );
            $base_pixel_added = true;
        } 
        // Tag archives
        elseif ( is_tag() && 'checked' === $this->settings['tags'] ) {
            add_action( 'wp_head', array( $this, 'output_base_pixel_code' ), 2 );
            $base_pixel_added = true;
        }
        
        // If we haven't added the base pixel code yet, but we're on a WooCommerce page that needs it
        if ( !$base_pixel_added && $woo_active && 'yes' === $woo_access ) {
            // Cart page
            if ( function_exists( 'is_cart' ) && is_cart() ) {
                add_action( 'wp_head', array( $this, 'output_base_pixel_code' ), 2 );
            }
            // Checkout page not covered above
            elseif ( function_exists( 'is_checkout' ) && is_checkout() && !is_page() ) {
                add_action( 'wp_head', array( $this, 'output_base_pixel_code' ), 2 );
            }
        }
    }

    /**
     * Output base pixel code.
     */
    public function output_base_pixel_code() {
        // Check if base pixel has already been output
        if ( $this->base_pixel_output ) {
            return;
        }
        
        // Set flag to prevent duplicate output
        $this->base_pixel_output = true;
        
        global $post;
        
        $product_id = '';
        
        // Check if we're on a product page
        if ( $this->is_woocommerce_active() && function_exists( 'is_product' ) && is_product() && $post ) {
            $product = wc_get_product( $post->ID );
            if ( $product ) {
                $product_id = $product->get_id();
            }
        }
        
        ?>
        <!-- Pixel Integration for Snapchat -->
        <script type='text/javascript'>
            (function(win, doc, sdk_url) {
                if (win.snaptr) return;
                var tr = win.snaptr = function() {
                    tr.handleRequest ? tr.handleRequest.apply(tr, arguments) : tr.queue.push(arguments);
                };
                tr.queue = [];
                var s = 'script';
                var new_script_section = doc.createElement(s);
                new_script_section.async = !0;
                new_script_section.src = sdk_url;
                var insert_pos = doc.getElementsByTagName(s)[0];
                insert_pos.parentNode.insertBefore(new_script_section, insert_pos);
            })(window, document, 'https://sc-static.net/scevent.min.js');

            snaptr('init', '<?php echo esc_js( $this->pixel_id ); ?>', {
                'user_email': '<?php echo esc_js( $this->user_email ); ?>'
            });
            
            var item_ids = <?php if ( $product_id ) { ?> {'item_ids': ["<?php echo esc_js( $product_id ); ?>"]} <?php } else { echo "0"; } ?>;
            snaptr('track', 'PAGE_VIEW', item_ids);
        </script>
        <!-- End Pixel Integration for Snapchat -->
        <?php
        // Fire action for add-ons
        $event_data = array();
        if (isset($item_ids)) {
            $event_data['item_ids'] = $item_ids;
        }
        do_action( 'snap_pixel_event_fired', 'PAGE_VIEW', $event_data );
        ?>
        <?php
    }

    /**
     * Output search pixel code.
     */
    public function output_search_pixel_code() {
        global $wp_query;
        
        $search_term = get_search_query();
        
        ?>
        <!-- SEARCH pixel event by Pixel Integration for Snapchat -->
        <script>
            snaptr('track', 'SEARCH', {
                'search_string': "<?php echo esc_js( $search_term ); ?>"
            });
        </script>
        <!-- End SEARCH pixel event by Pixel Integration for Snapchat -->
        <?php
        // Fire action for add-ons
        $event_data = array(
            'search_string' => $search_term
        );
        do_action( 'snap_pixel_event_fired', 'SEARCH', $event_data );
        ?>
        <?php
    }

    /**
     * Output view content pixel code for product pages.
     */
    public function output_viewcontent_pixel_code() {
        global $post;
        
        if ( ! $this->is_woocommerce_active() ) {
            return;
        }
        
        $product = wc_get_product( $post->ID );
        if ( ! $product ) {
            return;
        }
        
        $product_id = $product->get_id();
        $product_price = $product->get_price();
        $product_currency = get_woocommerce_currency();
        
        // Get product category
        $terms = get_the_terms( $product_id, 'product_cat' );
        $product_category = '';
        
        if ( $terms && ! is_wp_error( $terms ) ) {
            $product_category = $terms[0]->name;
        }
        
        ?>
        <!-- VIEW_CONTENT pixel event by Pixel Integration for Snapchat -->
        <script>
            snaptr('track', 'VIEW_CONTENT', {
                'item_category': "<?php echo esc_js( $product_category ); ?>",
                'item_ids': ["<?php echo esc_js( $product_id ); ?>"],
                'price': <?php echo esc_js( $product_price ); ?>,
                'currency': "<?php echo esc_js( $product_currency ); ?>"
            });
        </script>
        <!-- End VIEW_CONTENT pixel event by Pixel Integration for Snapchat -->
        <?php
        // Fire action for add-ons
        $event_data = array(
            'item_category' => $product_category,
            'item_ids' => array($product_id),
            'price' => $product_price,
            'currency' => $product_currency
        );
        do_action( 'snap_pixel_event_fired', 'VIEW_CONTENT', $event_data );
        ?>
        <?php
    }

    /**
     * Output checkout pixel code.
     */
    public function output_checkout_pixel_code() {
        if ( ! $this->is_woocommerce_active() ) {
            return;
        }
        
        global $woocommerce;
        
        $price = $woocommerce->cart->total;
        $product_currency = get_woocommerce_currency();
        $num_items = $woocommerce->cart->get_cart_contents_count();
        $product_id = '';
        
        foreach ( WC()->cart->get_cart() as $cart_item ) {
            $product_id = $cart_item['product_id'];
            break;
        }
        
        ?>
        <!-- START_CHECKOUT pixel event by Pixel Integration for Snapchat -->
        <script>
            snaptr('track', 'START_CHECKOUT', {
                'currency': "<?php echo esc_js( $product_currency ); ?>",
                'price': <?php echo esc_js( $price ); ?>,
                'num_items': <?php echo esc_js( $num_items ); ?>,
                'item_ids': ["<?php echo esc_js( $product_id ); ?>"]
            });
        </script>
        <!-- End START_CHECKOUT pixel event by Pixel Integration for Snapchat -->
        <?php
        // Fire action for add-ons
        $event_data = array(
            'currency' => $product_currency,
            'price' => $price,
            'num_items' => $num_items,
            'item_ids' => array($product_id)
        );
        do_action( 'snap_pixel_event_fired', 'START_CHECKOUT', $event_data );
        ?>
        <?php
    }

    /**
     * Output purchase pixel code.
     */
    public function output_purchase_pixel_code() {
        if ( ! $this->is_woocommerce_active() ) {
            return;
        }
        
        global $woocommerce;
        
        // Check if key exists and verify nonce if it's from a form submission
        if ( isset( $_GET['key'] ) ) {
            $key = sanitize_text_field( wp_unslash( $_GET['key'] ) );
            
            // For WooCommerce order-received endpoint, the key is part of the URL and not from a form submission
            // so we don't need to verify a nonce in this specific case. This is a standard WooCommerce URL parameter
            // used for order identification and not for processing form submissions.
        } else {
            $key = '';
        }
        
        $order_id = $this->get_order_id_by_key( $key );
        
        if ( ! $order_id ) {
            return;
        }
        
        $order = wc_get_order( $order_id );
        if ( ! $order ) {
            return;
        }
        
        $order_total = $order->get_total();
        $product_currency = get_woocommerce_currency();
        $num_items = $woocommerce->cart->get_cart_contents_count();
        
        // Get product IDs from the order items
        $product_ids = array();
        foreach ( $order->get_items() as $item ) {
            // Access product_id directly from the item data
            $item_data = $item->get_data();
            $product_id = isset( $item_data['product_id'] ) ? $item_data['product_id'] : 0;
            
            if ( $product_id ) {
                $product_ids[] = $product_id;
            }
        }
        if(count($product_ids) > 0){
            $content_ids_string = $product_ids;
        } else {
            $content_ids_string = $order_id;
        }
        
        ?>
        <!-- PURCHASE pixel event by Pixel Integration for Snapchat -->
        <script>
            snaptr('track', 'PURCHASE', {
                'currency': "<?php echo esc_js( $product_currency ); ?>",
                'price': <?php echo esc_js( $order_total ); ?>,
                'transaction_id': "<?php echo esc_js( $order_id ); ?>",
                'item_ids': content_ids_string
            });
            
            snaptr('track', 'ADD_BILLING');
        </script>
        <!-- End PURCHASE pixel event by Pixel Integration for Snapchat -->
        <?php
        // Fire action for add-ons
        $event_data = array(
            'currency' => $product_currency,
            'price' => $order_total,
            'transaction_id' => $order_id,
            'item_ids' => $content_ids_string
        );
        do_action( 'snap_pixel_event_fired', 'PURCHASE', $event_data );
        
        // Fire action for ADD_BILLING event
        do_action( 'snap_pixel_event_fired', 'ADD_BILLING', array() );
        ?>
        <?php
    }

    /**
     * Output add to cart pixel code.
     */
    public function output_addtocart_pixel_code() {
        if ( ! $this->is_woocommerce_active() ) {
            return;
        }
        
        global $post;
        
        $product_currency = get_woocommerce_currency();
        $product_price = '';
        
        if ( $post && function_exists( 'wc_get_product' ) ) {
            $product = wc_get_product( $post->ID );
            if ( $product ) {
                $product_price = $product->get_price();
            }
        }
        
        ?>
        <!-- ADD_CART pixel event by Pixel Integration for Snapchat -->
        <script>
            jQuery('body').on('added_to_cart', function(e, h, w, button) {
                var product_id = button.data("product_id");
                var product_result = get_product_record(product_id);
                var product_price = "<?php echo esc_js( $product_price ); ?>";
                
                setTimeout(function() {
                    product_price = jQuery("#return_response").val();
                    snaptr('track', 'ADD_CART', {
                        'currency': "<?php echo esc_js( $product_currency ); ?>",
                        'price': product_price,
                        'item_category': "",
                        'item_ids': [product_id]
                    });
                }, 1500);
            });
        </script>
        <!-- End ADD_CART pixel event by Pixel Integration for Snapchat -->
        <?php
        // Fire action for add-ons
        $event_data = array(
            'currency' => $product_currency,
            'price' => $product_price
        );
        do_action( 'snap_pixel_event_fired', 'ADD_CART', $event_data );
        ?>
        <?php
    }

    /**
     * Output billing pixel code.
     */
    public function output_billing_pixel_code() {
        ?>
        <script type='text/javascript'>
            snaptr('track', 'ADD_BILLING');
        </script>
        <?php
        // Fire action for add-ons
        do_action( 'snap_pixel_event_fired', 'ADD_BILLING', array() );
    }

    /**
     * AJAX handler for getting product data.
     */
    public function get_product_data() {
        // Verify nonce - required for security
        check_ajax_referer( 'snapchat_product_data', 'security' );
        
        if ( isset( $_REQUEST['snap_product_id'] ) && ! empty( $_REQUEST['snap_product_id'] ) ) {
            $product_id = absint( $_REQUEST['snap_product_id'] );
            
            if ( function_exists( 'wc_get_product' ) ) {
                $product = wc_get_product( $product_id );
                
                if ( $product ) {
                    $product_price = $product->get_price();
                    wp_send_json_success( $product_price );
                    wp_die();
                }
            }
        }
        
        wp_send_json_error( 'Error' );
        wp_die();
    }

    /**
     * Get order ID by order key.
     *
     * @param string $key Order key.
     * @return int|bool Order ID or false if not found.
     */
    private function get_order_id_by_key( $key ) {
        if ( empty( $key ) ) {
            return false;
        }
        
        // Check cache first
        $cache_key = 'snap_pixel_order_' . md5($key);
        $order_id = wp_cache_get( $cache_key, 'snap_pixel' );
        
        if ( false !== $order_id ) {
            return $order_id;
        }
        
        global $wpdb;
        
        $result = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT post_id FROM {$wpdb->prefix}postmeta WHERE meta_key='_order_key' AND meta_value=%s ORDER BY meta_id DESC LIMIT 1",
                $key
            )
        );
        
        if ( $result ) {
            // Cache the result for future use
            wp_cache_set( $cache_key, $result->post_id, 'snap_pixel', 3600 ); // Cache for 1 hour
            return $result->post_id;
        }
        
        // Cache the negative result as well
        wp_cache_set( $cache_key, 0, 'snap_pixel', 3600 ); // Cache for 1 hour
        return false;
    }

    /**
     * Check if WooCommerce is active.
     *
     * @return bool
     */
    private function is_woocommerce_active() {
        return in_array( 
            'woocommerce/woocommerce.php', 
            apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) 
        );
    }
} 