<?php
/**
 * General tab content for Pixel Integration for Snapchat settings.
 *
 * @package    Snap_Pixel
 * @subpackage Snap_Pixel/admin/partials
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<form method="post" action="">
    <?php wp_nonce_field( 'snapchat_pixel_security', 'snapchat_pixel_nonce' ); ?>
    <input type="hidden" name="page" value="snap-pixel">
    <input type="hidden" name="tab" value="general">
    
    <div class="snap-pixel-card">
        <h2><?php esc_html_e( 'Pixel Configuration', 'snap-pixel' ); ?></h2>
        
        <div class="snap-pixel-field">
            <label for="pixel_id"><?php esc_html_e( 'Pixel ID', 'snap-pixel' ); ?></label>
            <input type="text" id="pixel_id" name="snapchat_pixel_code[pixel_id]" value="<?php echo esc_attr( $pixel_id ); ?>" class="regular-text" placeholder="<?php esc_attr_e( 'Enter your Pixel ID', 'snap-pixel' ); ?>" required>
            <p class="description"><?php _e( 'You can find your Pixel ID in your <a href="https://ads.snapchat.com/manage/ads/campaigns" target="_blank">Snapchat Ads Manager</a>.', 'snap-pixel' ); ?></p>
            <p class="description"><?php esc_html_e( 'The Pixel ID looks similar to this:', 'snap-pixel' ); ?> <code>7b2345b6-cf87-4014-e435-tc6h7890ij14</code></p>
        </div>
        
        <div class="snap-pixel-field">
            <label for="user_email"><?php esc_html_e( 'Admin Email', 'snap-pixel' ); ?></label>
            <input type="email" id="user_email" name="snapchat_pixel_code[user_email]" value="<?php echo esc_attr( $user_email ); ?>" class="regular-text" placeholder="<?php esc_attr_e( 'Enter your email', 'snap-pixel' ); ?>">
            <p class="description"><?php esc_html_e( 'This email will be used for tracking if no user is logged in.', 'snap-pixel' ); ?></p>
        </div>
    </div>
    
    <div class="snap-pixel-card">
        <h2><?php esc_html_e( 'WordPress Integration', 'snap-pixel' ); ?></h2>
        <p class="description"><?php esc_html_e( 'Select where you want to enable pixel tracking on your WordPress site.', 'snap-pixel' ); ?></p>
        
        <div class="snap-pixel-toggle-group">
            <div class="snap-pixel-toggle">
                <input type="checkbox" id="homepage" name="snapchat_pixel_code[homepage]" value="checked" <?php checked( 'checked', $homepage ); ?>>
                <label for="homepage"><?php esc_html_e( 'Homepage', 'snap-pixel' ); ?></label>
            </div>
            
            <div class="snap-pixel-toggle">
                <input type="checkbox" id="pages" name="snapchat_pixel_code[pages]" value="checked" <?php checked( 'checked', $pages ); ?>>
                <label for="pages"><?php esc_html_e( 'Pages', 'snap-pixel' ); ?></label>
            </div>
            
            <div class="snap-pixel-toggle">
                <input type="checkbox" id="posts" name="snapchat_pixel_code[posts]" value="checked" <?php checked( 'checked', $posts ); ?>>
                <label for="posts"><?php esc_html_e( 'Posts', 'snap-pixel' ); ?></label>
            </div>
            
            <div class="snap-pixel-toggle">
                <input type="checkbox" id="search" name="snapchat_pixel_code[search]" value="checked" <?php checked( 'checked', $search ); ?>>
                <label for="search"><?php esc_html_e( 'Search Results', 'snap-pixel' ); ?></label>
            </div>
            
            <div class="snap-pixel-toggle">
                <input type="checkbox" id="categories" name="snapchat_pixel_code[categories]" value="checked" <?php checked( 'checked', $categories ); ?>>
                <label for="categories"><?php esc_html_e( 'Categories', 'snap-pixel' ); ?></label>
            </div>
            
            <div class="snap-pixel-toggle">
                <input type="checkbox" id="tags" name="snapchat_pixel_code[tags]" value="checked" <?php checked( 'checked', $tags ); ?>>
                <label for="tags"><?php esc_html_e( 'Tags', 'snap-pixel' ); ?></label>
            </div>
        </div>
    </div>
    
    <div class="snap-pixel-actions">
        <input type="submit" class="button button-primary" value="<?php esc_attr_e( 'Save Settings', 'snap-pixel' ); ?>">
    </div>
</form> 