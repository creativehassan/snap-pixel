<?php
/**
 * The Easy Digital Downloads integration functionality of the plugin.
 *
 * @package    Snap_Pixel
 * @subpackage Snap_Pixel/includes
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * The Easy Digital Downloads integration functionality of the plugin.
 */
class Snap_Pixel_EDD {

    /**
     * Initialize the class and set its properties.
     */
    public function __construct() {
        // Check if EDD is active
        if ( ! class_exists( 'Easy_Digital_Downloads' ) ) {
            return;
        }

        // Get settings
        $settings = get_option( 'snapchat_pixel_code', array() );
        $pixel_id = isset( $settings['pixel_id'] ) ? $settings['pixel_id'] : '';
        
        // If no pixel ID or EDD integration is disabled, return
        $edd_access = get_option( 'snapchat_pixel_edd_access', 'no' );
        if ( empty( $pixel_id ) || 'no' === $edd_access ) {
            return;
        }

        // Add EDD hooks
        $this->add_edd_hooks();
    }

    /**
     * Add EDD hooks.
     */
    private function add_edd_hooks() {
        // Get EDD settings
        $settings = get_option( 'snapchat_pixel_edd', array() );
        
        // View content (product view)
        if ( isset( $settings['view_content'] ) && 'yes' === $settings['view_content'] ) {
            add_action( 'wp_footer', array( $this, 'view_content_event' ) );
        }
        
        // Add to cart
        if ( isset( $settings['add_cart'] ) && 'yes' === $settings['add_cart'] ) {
            add_action( 'edd_post_add_to_cart', array( $this, 'add_to_cart_event' ), 10, 3 );
        }
        
        // Checkout
        if ( isset( $settings['checkout'] ) && 'yes' === $settings['checkout'] ) {
            add_action( 'edd_after_checkout_cart', array( $this, 'checkout_event' ) );
        }
        
        // Purchase
        if ( isset( $settings['purchase'] ) && 'yes' === $settings['purchase'] ) {
            add_action( 'edd_complete_purchase', array( $this, 'purchase_event' ) );
        }
    }

    /**
     * Track view content event.
     */
    public function view_content_event() {
        // Only on single download pages
        if ( ! is_singular( 'download' ) ) {
            return;
        }
        
        global $post;
        $download_id = $post->ID;
        $download = new EDD_Download( $download_id );
        
        // Get download details
        $price = $download->get_price();
        $currency = edd_get_currency();
        $categories = get_the_terms( $download_id, 'download_category' );
        $category = '';
        
        if ( $categories && ! is_wp_error( $categories ) ) {
            $category = $categories[0]->name;
        }
        
        // Prepare event data
        $event_data = array(
            'item_ids' => array( $download_id ),
            'currency' => $currency,
            'price' => $price,
            'item_category' => $category,
            'content_name' => get_the_title( $download_id ),
        );
        
        // Add JavaScript to track the event
        ?>
        <script>
        snaptr('track', 'VIEW_CONTENT', <?php echo wp_json_encode( $event_data ); ?>);
        </script>
        <?php
        
        // Fire action for add-ons
        do_action( 'snap_pixel_event_fired', 'VIEW_CONTENT', $event_data );
    }

    /**
     * Track add to cart event.
     *
     * @param int   $download_id The download ID.
     * @param array $options     The download options.
     * @param array $items       The cart items.
     */
    public function add_to_cart_event( $download_id, $options, $items ) {
        $download = new EDD_Download( $download_id );
        
        // Get download details
        $price = $download->get_price();
        $currency = edd_get_currency();
        $categories = get_the_terms( $download_id, 'download_category' );
        $category = '';
        
        if ( $categories && ! is_wp_error( $categories ) ) {
            $category = $categories[0]->name;
        }
        
        // Prepare event data
        $event_data = array(
            'item_ids' => array( $download_id ),
            'currency' => $currency,
            'price' => $price,
            'item_category' => $category,
            'content_name' => get_the_title( $download_id ),
        );
        
        // Add JavaScript to track the event
        ?>
        <script>
        snaptr('track', 'ADD_CART', <?php echo wp_json_encode( $event_data ); ?>);
        </script>
        <?php
        
        // Fire action for add-ons
        do_action( 'snap_pixel_event_fired', 'ADD_CART', $event_data );
    }

    /**
     * Track checkout event.
     */
    public function checkout_event() {
        $cart_items = edd_get_cart_contents();
        $currency = edd_get_currency();
        $total = edd_get_cart_total();
        $item_ids = array();
        
        // Get all download IDs in cart
        foreach ( $cart_items as $item ) {
            $item_ids[] = $item['id'];
        }
        
        // Prepare event data
        $event_data = array(
            'item_ids' => $item_ids,
            'currency' => $currency,
            'price' => $total,
            'num_items' => count( $cart_items ),
        );
        
        // Add JavaScript to track the event
        ?>
        <script>
        snaptr('track', 'START_CHECKOUT', <?php echo wp_json_encode( $event_data ); ?>);
        </script>
        <?php
        
        // Fire action for add-ons
        do_action( 'snap_pixel_event_fired', 'START_CHECKOUT', $event_data );
    }

    /**
     * Track purchase event.
     *
     * @param int $payment_id The payment ID.
     */
    public function purchase_event( $payment_id ) {
        $payment = new EDD_Payment( $payment_id );
        $payment_meta = $payment->get_meta();
        $downloads = $payment->downloads;
        $currency = $payment->currency;
        $total = $payment->total;
        $item_ids = array();
        
        // Get all download IDs in purchase
        foreach ( $downloads as $download ) {
            $item_ids[] = $download['id'];
        }
        
        // Prepare event data
        $event_data = array(
            'item_ids' => $item_ids,
            'currency' => $currency,
            'price' => $total,
            'transaction_id' => $payment_id,
            'num_items' => count( $downloads ),
        );
        
        // Add JavaScript to track the event
        ?>
        <script>
        snaptr('track', 'PURCHASE', <?php echo wp_json_encode( $event_data ); ?>);
        </script>
        <?php
        
        // Fire action for add-ons
        do_action( 'snap_pixel_event_fired', 'PURCHASE', $event_data );
    }
} 