<?php
/**
 * Admin display for Pixel Integration for Snapchat settings.
 *
 * @package    Snap_Pixel
 * @subpackage Snap_Pixel/admin
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

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
$viewcart = isset( $settings['viewcart'] ) ? $settings['viewcart'] : '';
$checkout = isset( $settings['checkout'] ) ? $settings['checkout'] : '';
$paymentinfo = isset( $settings['paymentinfo'] ) ? $settings['paymentinfo'] : '';
$addtocart = isset( $settings['addtocart'] ) ? $settings['addtocart'] : '';
$ajax_addtocart = isset( $settings['ajax_addtocart'] ) ? $settings['ajax_addtocart'] : '';

// Get current tab - this is just for UI display, not processing form data, so nonce verification is not needed
$current_tab = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : 'general';

// WooCommerce settings
$woo_active = in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) );
$woo_access = get_option( 'snapchat_pixel_wooacces', 'no' );

// Display settings errors
settings_errors( 'snap-pixel' );
?>

<div class="wrap snap-pixel-admin">
    <h1 class="snap-pixel-title">
        <img src="<?php echo esc_url( SNAP_PIXEL_PLUGIN_URL . 'assets/images/snapchat-logo.svg' ); ?>" alt="Pixel Integration for Snapchat" class="snap-pixel-logo">
        <?php esc_html_e( 'Pixel Integration for Snapchat', 'snap-pixel' ); ?>
    </h1>

    <div class="snap-pixel-tabs">
        <?php foreach ( $tabs as $tab_id => $tab_name ) : ?>
            <?php 
            $is_active = $current_tab === $tab_id;
            $is_disabled = $tab_id === 'woocommerce' && ! $woo_active;
            ?>
            <a href="?page=snap-pixel&tab=<?php echo esc_attr( $tab_id ); ?>" class="snap-pixel-tab <?php echo $is_active ? 'active' : ''; ?>" <?php echo $is_disabled ? 'disabled' : ''; ?>>
                <?php echo esc_html( $tab_name ); ?>
            </a>
        <?php endforeach; ?>
    </div>

    <div class="snap-pixel-content">
        <?php $this->display_tab_content( $current_tab ); ?>
    </div>
</div> 