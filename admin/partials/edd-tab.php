<?php
/**
 * Provide a admin area view for the EDD tab
 *
 * @package    Snap_Pixel
 * @subpackage Snap_Pixel/admin/partials
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Check if EDD is active
$edd_active = class_exists( 'Easy_Digital_Downloads' );
$edd_access = get_option( 'snapchat_pixel_edd_access', 'no' );
$edd_settings = get_option( 'snapchat_pixel_edd', array() );

// Default values
$view_content = isset( $edd_settings['view_content'] ) ? $edd_settings['view_content'] : 'no';
$add_cart = isset( $edd_settings['add_cart'] ) ? $edd_settings['add_cart'] : 'no';
$checkout = isset( $edd_settings['checkout'] ) ? $edd_settings['checkout'] : 'no';
$purchase = isset( $edd_settings['purchase'] ) ? $edd_settings['purchase'] : 'no';
?>

<?php if ( $edd_active ) : ?>
    <form method="post" action="">
        <?php wp_nonce_field( 'snapchat_pixel_edd_security', 'snapchat_pixel_edd_nonce' ); ?>
        <input type="hidden" name="page" value="snap-pixel">
        <input type="hidden" name="tab" value="edd">
        <input type="hidden" name="save_snapchat_pixel_edd" value="1">
        
        <div class="snap-pixel-card">
            <h2><?php esc_html_e( 'Easy Digital Downloads Integration', 'snap-pixel' ); ?></h2>
            <p class="description"><?php esc_html_e( 'Enable or disable Easy Digital Downloads integration with the Snapchat tracking pixel.', 'snap-pixel' ); ?></p>
            
            <div class="snap-pixel-field">
                <label for="edd_activate"><?php esc_html_e( 'EDD Integration', 'snap-pixel' ); ?></label>
                <select name="edd_activate" id="edd_activate">
                    <option value="yes" <?php selected( $edd_access, 'yes' ); ?>><?php esc_html_e( 'Enabled', 'snap-pixel' ); ?></option>
                    <option value="no" <?php selected( $edd_access, 'no' ); ?>><?php esc_html_e( 'Disabled', 'snap-pixel' ); ?></option>
                </select>
                <p class="description"><?php esc_html_e( 'Enable or disable Easy Digital Downloads integration. When disabled, no EDD events will be tracked.', 'snap-pixel' ); ?></p>
            </div>
        </div>
        
        <div id="edd-settings" class="snap-pixel-card" <?php echo 'yes' !== $edd_access ? 'style="display:none;"' : ''; ?>>
            <h2><?php esc_html_e( 'Easy Digital Downloads Events', 'snap-pixel' ); ?></h2>
            <p class="description"><?php esc_html_e( 'Select which Easy Digital Downloads events you want to track with the Snapchat tracking pixel.', 'snap-pixel' ); ?></p>
            
            <div class="snap-pixel-toggle-group">
                <div class="snap-pixel-toggle">
                    <input type="checkbox" id="view_content" name="snapchat_pixel_edd[view_content]" value="yes" <?php checked( 'yes', $view_content ); ?>>
                    <label for="view_content"><?php esc_html_e( 'View Content (Product Pages)', 'snap-pixel' ); ?></label>
                </div>
                
                <div class="snap-pixel-toggle">
                    <input type="checkbox" id="add_cart" name="snapchat_pixel_edd[add_cart]" value="yes" <?php checked( 'yes', $add_cart ); ?>>
                    <label for="add_cart"><?php esc_html_e( 'Add to Cart', 'snap-pixel' ); ?></label>
                </div>
                
                <div class="snap-pixel-toggle">
                    <input type="checkbox" id="checkout" name="snapchat_pixel_edd[checkout]" value="yes" <?php checked( 'yes', $checkout ); ?>>
                    <label for="checkout"><?php esc_html_e( 'Checkout', 'snap-pixel' ); ?></label>
                </div>
                
                <div class="snap-pixel-toggle">
                    <input type="checkbox" id="purchase" name="snapchat_pixel_edd[purchase]" value="yes" <?php checked( 'yes', $purchase ); ?>>
                    <label for="purchase"><?php esc_html_e( 'Purchase', 'snap-pixel' ); ?></label>
                </div>
            </div>
        </div>
        
        <div class="snap-pixel-submit-container">
            <input type="submit" name="submit" id="submit" class="button button-primary snap-pixel-submit" value="<?php esc_attr_e( 'Save EDD Settings', 'snap-pixel' ); ?>">
        </div>
    </form>
<?php else : ?>
    <div class="snap-pixel-card snap-pixel-notice">
        <h2><?php esc_html_e( 'Easy Digital Downloads Integration', 'snap-pixel' ); ?></h2>
        <p><?php esc_html_e( 'Easy Digital Downloads is not active. Please install and activate Easy Digital Downloads to use this integration.', 'snap-pixel' ); ?></p>
        <div class="snap-pixel-action-buttons">
            <a href="<?php echo esc_url( admin_url( 'plugin-install.php?s=easy-digital-downloads&tab=search&type=term' ) ); ?>" class="snap-pixel-dark-button"><?php esc_html_e( 'Install Easy Digital Downloads', 'snap-pixel' ); ?></a>
        </div>
    </div>
<?php endif; ?>

<style>
.snap-pixel-submit-container {
    margin-top: 20px;
}

.snap-pixel-submit {
    background-color: #fffc00 !important;
    color: #000 !important;
    border-color: #e6e300 !important;
    font-weight: 600 !important;
    padding: 8px 20px !important;
    height: auto !important;
    box-shadow: none !important;
    text-shadow: none !important;
    cursor: pointer !important;
}

.snap-pixel-submit:hover {
    background-color: #e6e300 !important;
    border-color: #e6e300 !important;
}
</style>

<script>
    // Toggle EDD settings visibility
    jQuery(document).ready(function($) {
        $('#edd_activate').on('change', function() {
            if ($(this).val() === 'yes') {
                $('#edd-settings').show();
            } else {
                $('#edd-settings').hide();
            }
        });
    });
</script> 