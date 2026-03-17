<?php
/**
 * Help tab content for Pixel Integration for Snapchat settings.
 *
 * @package    Snap_Pixel
 * @subpackage Snap_Pixel/admin/partials
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<div class="snap-pixel-card">
    <h2><?php esc_html_e( 'About This Plugin', 'snap-pixel' ); ?></h2>
    <p><?php esc_html_e( 'The Snapchat tracking pixel is a piece of JavaScript code that helps you measure the cross-device impact of your campaigns. When someone takes an action on your website, the pixel is triggered and reports this action.', 'snap-pixel' ); ?></p>
    <p><?php esc_html_e( 'This allows you to see when customers take actions after seeing your Snapchat ad.', 'snap-pixel' ); ?></p>
    
    <h3><?php esc_html_e( 'Supported Events', 'snap-pixel' ); ?></h3>
    <ul class="snap-pixel-events-list">
        <li><strong>PAGE_VIEW</strong> - <?php esc_html_e( 'Triggered on all pages where the pixel is installed', 'snap-pixel' ); ?></li>
        <li><strong>VIEW_CONTENT</strong> - <?php esc_html_e( 'Triggered when a customer views a product', 'snap-pixel' ); ?></li>
        <li><strong>ADD_CART</strong> - <?php esc_html_e( 'Triggered when a customer adds a product to cart', 'snap-pixel' ); ?></li>
        <li><strong>START_CHECKOUT</strong> - <?php esc_html_e( 'Triggered when a customer starts the checkout process', 'snap-pixel' ); ?></li>
        <li><strong>ADD_BILLING</strong> - <?php esc_html_e( 'Triggered when a customer adds billing information', 'snap-pixel' ); ?></li>
        <li><strong>PURCHASE</strong> - <?php esc_html_e( 'Triggered when a customer completes a purchase', 'snap-pixel' ); ?></li>
        <li><strong>SEARCH</strong> - <?php esc_html_e( 'Triggered when a customer performs a search on your site', 'snap-pixel' ); ?></li>
    </ul>
</div>

<div class="snap-pixel-card">
    <h2><?php esc_html_e( 'How to Verify Your Pixel', 'snap-pixel' ); ?></h2>
    <p><?php esc_html_e( 'You can verify that your pixel is working correctly by using the Snap Pixel Helper Chrome extension (an official Snapchat tool).', 'snap-pixel' ); ?></p>
    <ol>
        <li><?php esc_html_e( 'Install the Snap Pixel Helper Chrome Extension', 'snap-pixel' ); ?></li>
        <li><?php esc_html_e( 'Visit your website', 'snap-pixel' ); ?></li>
        <li><?php esc_html_e( 'Click on the Snap Pixel Helper icon in your browser', 'snap-pixel' ); ?></li>
        <li><?php esc_html_e( 'Verify that your Pixel ID is shown and events are being tracked', 'snap-pixel' ); ?></li>
    </ol>
    <div class="snap-pixel-action-buttons">
        <a href="https://chrome.google.com/webstore/detail/snap-pixel-helper/hnlbfcoodjpconffdbddfglilhkhpdnf" class="snap-pixel-dark-button" target="_blank"><?php esc_html_e( 'Get Snap Pixel Helper', 'snap-pixel' ); ?></a>
    </div>
</div>

<div class="snap-pixel-card">
    <h2><?php esc_html_e( 'Support & Documentation', 'snap-pixel' ); ?></h2>
    <p><?php esc_html_e( 'For more information, please visit the following resources:', 'snap-pixel' ); ?></p>
    <ul>
        <li><a href="https://businesshelp.snapchat.com/en-US/article/snap-pixel-about" target="_blank"><?php esc_html_e( 'Snapchat Pixel Documentation (official)', 'snap-pixel' ); ?></a></li>
        <li><a href="https://github.com/creativehassan/snap-pixel" target="_blank"><?php esc_html_e( 'Plugin GitHub Repository', 'snap-pixel' ); ?></a></li>
        <li><a href="https://wordpress.org/support/plugin/snap-pixel/" target="_blank"><?php esc_html_e( 'WordPress Support Forum', 'snap-pixel' ); ?></a></li>
    </ul>
    <div class="snap-pixel-action-buttons">
        <a href="https://businesshelp.snapchat.com/en-US/article/snap-pixel-about" class="snap-pixel-dark-button" target="_blank"><?php esc_html_e( 'Learn More', 'snap-pixel' ); ?></a>
    </div>
</div>

<?php
// Display available add-ons if any
$addons = apply_filters( 'snap_pixel_available_addons', array() );
if ( ! empty( $addons ) ) :
?>
<div class="snap-pixel-card">
    <h2><?php esc_html_e( 'Available Add-ons', 'snap-pixel' ); ?></h2>
    <p><?php esc_html_e( 'Enhance your pixel integration with these premium add-ons:', 'snap-pixel' ); ?></p>
    
    <div class="snap-pixel-addons">
        <?php foreach ( $addons as $addon ) : ?>
            <div class="snap-pixel-addon">
                <h3><?php echo esc_html( $addon['name'] ); ?></h3>
                <p><?php echo esc_html( $addon['description'] ); ?></p>
                <?php if ( ! empty( $addon['url'] ) ) : ?>
                    <a href="<?php echo esc_url( $addon['url'] ); ?>" class="snap-pixel-dark-button" target="_blank"><?php esc_html_e( 'Learn More', 'snap-pixel' ); ?></a>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?> 