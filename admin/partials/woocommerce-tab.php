<?php
/**
 * WooCommerce tab content for Pixel Integration for Snapchat settings.
 *
 * @package    Snap_Pixel
 * @subpackage Snap_Pixel/admin/partials
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<?php if ( $woo_active ) : ?>
    <form method="post" action="">
        <?php wp_nonce_field( 'snapchat_pixel_woo_security', 'snapchat_pixel_woo_nonce' ); ?>
        <input type="hidden" name="page" value="snap-pixel">
        <input type="hidden" name="tab" value="woocommerce">
        <input type="hidden" name="save_snapchat_pixel_woo" value="1">
        
        <div class="snap-pixel-card">
            <h2><?php esc_html_e( 'WooCommerce Integration', 'snap-pixel' ); ?></h2>
            <p class="description"><?php esc_html_e( 'Enable or disable WooCommerce integration with the Snapchat tracking pixel.', 'snap-pixel' ); ?></p>
            
            <div class="snap-pixel-field">
                <label for="woo_activate"><?php esc_html_e( 'WooCommerce Integration', 'snap-pixel' ); ?></label>
                <select name="woo_activate" id="woo_activate">
                    <option value="yes" <?php selected( $woo_access, 'yes' ); ?>><?php esc_html_e( 'Enabled', 'snap-pixel' ); ?></option>
                    <option value="no" <?php selected( $woo_access, 'no' ); ?>><?php esc_html_e( 'Disabled', 'snap-pixel' ); ?></option>
                </select>
                <p class="description"><?php esc_html_e( 'Enable or disable WooCommerce integration. When disabled, no WooCommerce events will be tracked.', 'snap-pixel' ); ?></p>
            </div>
        </div>
        
        <div id="woocommerce-settings" class="snap-pixel-card" <?php echo 'yes' !== $woo_access ? 'style="display:none;"' : ''; ?>>
            <h2><?php esc_html_e( 'WooCommerce Events', 'snap-pixel' ); ?></h2>
            <p class="description"><?php esc_html_e( 'Select which WooCommerce events you want to track with the Snapchat tracking pixel.', 'snap-pixel' ); ?></p>
            
            <div class="snap-pixel-toggle-group">
                <div class="snap-pixel-toggle">
                    <input type="checkbox" id="viewcart" name="snapchat_pixel_code[viewcart]" value="checked" <?php checked( 'checked', $viewcart ); ?>>
                    <label for="viewcart"><?php esc_html_e( 'View Content (Product Pages)', 'snap-pixel' ); ?></label>
                </div>
                
                <div class="snap-pixel-toggle">
                    <input type="checkbox" id="addtocart" name="snapchat_pixel_code[addtocart]" value="checked" <?php checked( 'checked', $addtocart ); ?>>
                    <label for="addtocart"><?php esc_html_e( 'Add to Cart', 'snap-pixel' ); ?></label>
                </div>
                
                <div class="snap-pixel-toggle">
                    <input type="checkbox" id="ajax_addtocart" name="snapchat_pixel_code[ajax_addtocart]" value="checked" <?php checked( 'checked', $ajax_addtocart ); ?>>
                    <label for="ajax_addtocart"><?php esc_html_e( 'Ajax Add to Cart', 'snap-pixel' ); ?></label>
                </div>
                
                <div class="snap-pixel-toggle">
                    <input type="checkbox" id="checkout" name="snapchat_pixel_code[checkout]" value="checked" <?php checked( 'checked', $checkout ); ?>>
                    <label for="checkout"><?php esc_html_e( 'Start Checkout', 'snap-pixel' ); ?></label>
                </div>
                
                <div class="snap-pixel-toggle">
                    <input type="checkbox" id="paymentinfo" name="snapchat_pixel_code[paymentinfo]" value="checked" <?php checked( 'checked', $paymentinfo ); ?>>
                    <label for="paymentinfo"><?php esc_html_e( 'Purchase & Add Billing', 'snap-pixel' ); ?></label>
                </div>
            </div>
        </div>
        
        <div class="snap-pixel-actions">
            <input type="submit" class="button button-primary snap-pixel-woo-save" value="<?php esc_attr_e( 'Save WooCommerce Settings', 'snap-pixel' ); ?>">
        </div>
    </form>
    
    <div class="snap-pixel-card">
        <h2><?php esc_html_e( 'WooCommerce Events Information', 'snap-pixel' ); ?></h2>
        <p><?php esc_html_e( 'The following pixel events are available for WooCommerce:', 'snap-pixel' ); ?></p>
        
        <ul class="snap-pixel-events-list">
            <li><strong>VIEW_CONTENT</strong> - <?php esc_html_e( 'Triggered when a customer views a product', 'snap-pixel' ); ?></li>
            <li><strong>ADD_CART</strong> - <?php esc_html_e( 'Triggered when a customer adds a product to cart', 'snap-pixel' ); ?></li>
            <li><strong>START_CHECKOUT</strong> - <?php esc_html_e( 'Triggered when a customer starts the checkout process', 'snap-pixel' ); ?></li>
            <li><strong>ADD_BILLING</strong> - <?php esc_html_e( 'Triggered when a customer adds billing information', 'snap-pixel' ); ?></li>
            <li><strong>PURCHASE</strong> - <?php esc_html_e( 'Triggered when a customer completes a purchase', 'snap-pixel' ); ?></li>
        </ul>
    </div>
<?php else : ?>
    <div class="snap-pixel-card snap-pixel-notice">
        <h2><?php esc_html_e( 'WooCommerce Not Installed', 'snap-pixel' ); ?></h2>
        <p><?php esc_html_e( 'WooCommerce is not installed or activated. Please install and activate WooCommerce to use these features.', 'snap-pixel' ); ?></p>
        <div class="snap-pixel-action-buttons">
            <a href="<?php echo esc_url( admin_url( 'plugin-install.php?s=woocommerce&tab=search&type=term' ) ); ?>" class="snap-pixel-dark-button"><?php esc_html_e( 'Install WooCommerce', 'snap-pixel' ); ?></a>
        </div>
    </div>
<?php endif; ?> 