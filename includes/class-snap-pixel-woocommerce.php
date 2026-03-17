<?php
/**
 * The WooCommerce integration functionality of the plugin.
 *
 * @package    Snap_Pixel
 * @subpackage Snap_Pixel/includes
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * The WooCommerce integration functionality of the plugin.
 */
class Snap_Pixel_WooCommerce {

    /**
     * Settings array.
     *
     * @var array
     */
    private $settings = array();

    /**
     * User email.
     *
     * @var string
     */
    private $user_email;

    /**
     * Initialize the class and set its properties.
     */
    public function __construct() {
        // Check if WooCommerce is active
        if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ){
            return;
        }

        // Get settings
        $settings = get_option( 'snapchat_pixel_code', array() );
        $pixel_id = isset( $settings['pixel_id'] ) ? $settings['pixel_id'] : '';
        
        // If no pixel ID or WooCommerce integration is disabled, return
        $woo_access = get_option( 'snapchat_pixel_wooacces', 'no' );
        if ( empty( $pixel_id ) || 'yes' !== $woo_access ) {
            return;
        }

        // Add WooCommerce hooks
        $this->load_settings();
        $this->add_woocommerce_hooks();
    }

    /**
     * Load settings from database.
     */
    private function load_settings() {
        $settings = get_option( 'snapchat_pixel_code', array() );
        
        $this->settings = array(
            'addtocart'     => isset( $settings['addtocart'] ) ? $settings['addtocart'] : '',
            'ajax_addtocart' => isset( $settings['ajax_addtocart'] ) ? $settings['ajax_addtocart'] : '',
        );
        
        $this->user_email = isset( $settings['user_email'] ) ? $settings['user_email'] : get_option( 'admin_email' );
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
     * Add to cart event handler.
     *
     * @param string $cart_item_key Cart item key.
     * @param int    $product_id    Product ID.
     * @param int    $quantity      Quantity.
     * @param int    $variation_id  Variation ID.
     * @param array  $variation     Variation data.
     * @param array  $cart_item_data Cart item data.
     */
    public function add_to_cart_event( $cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data ) {
        if ( 'yes' !== get_option( 'snapchat_pixel_wooacces', 'no' ) || 'checked' !== $this->settings['addtocart'] ) {
            return;
        }
        
        // Get product category
        $term_list = wp_get_post_terms( $product_id, 'product_cat', array( 'fields' => 'ids' ) );
        $product_category = '';
        
        if ( ! empty( $term_list ) && ! is_wp_error( $term_list ) ) {
            $cat_id = (int) $term_list[0];
            $category = get_term( $cat_id, 'product_cat' );
            if ( $category && ! is_wp_error( $category ) ) {
                $product_category = $category->name;
            }
        }
        
        // Use variation ID if available
        if ( $variation_id ) {
            $product_id = $variation_id;
        }
        
        // Get product data
        $product = wc_get_product( $product_id );
        if ( ! $product ) {
            return;
        }
        
        $product_price = $product->get_price();
        $product_currency = get_woocommerce_currency();
        
        ?>
        <!-- ADD_CART pixel event by Pixel Integration for Snapchat -->
        <script>
            setTimeout(function() {
                snaptr('track', 'ADD_CART', {
                    'currency': "<?php echo esc_js( $product_currency ); ?>",
                    'price': <?php echo esc_js( $product_price ); ?>,
                    'item_category': "<?php echo esc_js( $product_category ); ?>",
                    'item_ids': ["<?php echo esc_js( $product_id ); ?>"]
                });
            }, 1500);
        </script>
        <!-- End ADD_CART pixel event by Pixel Integration for Snapchat -->
        <?php
        // Fire action for add-ons
        $event_data = array(
            'currency' => $product_currency,
            'price' => $product_price,
            'item_category' => $product_category,
            'item_ids' => array($product_id)
        );
        do_action( 'snap_pixel_event_fired', 'ADD_CART', $event_data );
        ?>
        <?php
    }

    /**
     * Add AJAX add to cart script.
     * This method adds JavaScript to handle AJAX add to cart events.
     */
    public function add_ajax_to_cart_script() {
        if ( 'yes' !== get_option( 'snapchat_pixel_wooacces', 'no' ) || 'checked' !== $this->settings['addtocart'] ) {
            return;
        }
        
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function($) {
                // Listen for the added_to_cart event
                $(document.body).on('added_to_cart', function(event, fragments, cart_hash, $button) {
                    // Get product data from the button
                    var product_id = $button.data('product_id');
                    if (!product_id && $button.closest('form.cart').length) {
                        // Try to get from the form
                        product_id = $button.closest('form.cart').find('input[name="product_id"]').val();
                        if (!product_id) {
                            product_id = $button.closest('form.cart').find('button[name="add-to-cart"]').val();
                        }
                    }
                    
                    if (product_id) {
                        // Make an AJAX call to get product details
                        $.ajax({
                            url: snappixel.ajaxurl,
                            type: 'POST',
                            data: {
                                action: 'snapchat_product_data',
                                product_id: product_id,
                                nonce: snappixel.nonce
                            },
                            success: function(response) {
                                if (response.success) {
                                    var data = response.data;
                                    // Track the ADD_CART event
                                    snaptr('track', 'ADD_CART', {
                                        'currency': data.currency,
                                        'price': data.price,
                                        'item_category': data.category,
                                        'item_ids': [data.product_id]
                                    });
                                }
                            }
                        });
                    }
                });
            });
        </script>
        <?php
    }

    /**
     * Add WooCommerce hooks.
     */
    private function add_woocommerce_hooks() {
        // Get settings
        $settings = get_option( 'snapchat_pixel_code', array() );
        
        // Add to cart event
        if ( isset( $settings['addtocart'] ) && 'checked' === $settings['addtocart'] ) {
            // Check if AJAX add to cart is enabled
            if ( isset( $settings['ajax_addtocart'] ) && 'checked' === $settings['ajax_addtocart'] ) {
                // For AJAX add to cart
                add_action( 'wp_footer', array( $this, 'add_ajax_to_cart_script' ) );
            } else {
                // For standard add to cart
                add_action( 'woocommerce_add_to_cart', array( $this, 'add_to_cart_event' ), 10, 6 );
            }
        }
        
        // Set user email after WordPress is fully loaded
        add_action( 'init', array( $this, 'set_user_email' ) );
    }
} 