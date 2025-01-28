<?php
$tab = "";
if (isset($_REQUEST['tab'])) {
    $tab = $_REQUEST['tab'];
}
$snapchat_pixel_code = get_option('snapchat_pixel_code');
$snapchat_pixel_wooacces = get_option('snapchat_pixel_wooacces');

?>

<div class="wrap snapchat-pixel-wrapper">
    <h1><img src="<?php echo plugin_dir_url(__FILE__) . '../assets/images/snapchat-pixel.png'; ?>" alt="snapchat"
             class="img-heading"/> <?php echo __('Snapchat Pixel', $this->plugin_name); ?></h1>
    <?php include_once("setting-tabs.php"); ?>
    <form method="post" action="">
        <div class="tab-content general <?php echo(($tab == 'general' || $tab == '') ? 'active' : ''); ?>">
            <h2><?php echo __('Settings', $this->plugin_name); ?></h2>
            <div class="form-table" role="presentation">

                <div class="form-row">
                    <strong><?php echo __('Snapchat Pixel ID', $this->plugin_name); ?></strong>
                    <input type="text" name="snapchat_pixel_code[pixel_id]" class="regular-text"
                           value="<?php echo(isset($snapchat_pixel_code['pixel_id']) ? $snapchat_pixel_code['pixel_id'] : ''); ?>" placeholder="<?php echo __('Pixel ID', $this->plugin_name); ?>"/>
                    <span class="smallfont"><?php printf(__("You can get from snapchat <a href='%s' target='_blank'> Get Pixel ID </a> .", $this->plugin_name), "https://ads.snapchat.com"); ?></span>
                </div>

                <div class="form-row">
                    <strong><?php echo __('User Email', $this->plugin_name); ?></strong>
                    <input type="email" name="snapchat_pixel_code[user_email]" class="regular-text"
                           value="<?php echo(isset($snapchat_pixel_code['user_email']) ? $snapchat_pixel_code['user_email'] : ''); ?>" placeholder="<?php echo __('User Email', $this->plugin_name); ?>"/>
                    <span class="smallfont"><?php __("This user email will be sent with pixels firing", $this->plugin_name); ?></span>
                </div>

                <div class="form-row full">
                    <strong><?php echo __('Where do you want to place snapchat pixel code?', $this->plugin_name); ?></strong>

                    <div class="snap-chat-switch">
                        <input type="checkbox" name="snapchat_pixel_code[homepage]" value="checked"
                               id="snapchat_pixel_code_homepage"
                               class="hidden-checkbox" <?php echo(isset($snapchat_pixel_code['homepage']) ? 'checked="checked"' : ''); ?>>
                        <label for="snapchat_pixel_code_homepage" class="ant-switch"></label>
                        <span class="label"><?php echo __('Home or FrontPage', $this->plugin_name); ?></span>
                    </div>

                    <div class="snap-chat-switch">
                        <input type="checkbox" name="snapchat_pixel_code[pages]" value="checked"
                               id="snapchat_pixel_code_pages"
                               class="hidden-checkbox" <?php echo(isset($snapchat_pixel_code['pages']) ? 'checked="checked"' : ''); ?>>
                        <label for="snapchat_pixel_code_pages" class="ant-switch"></label>
                        <span class="label"><?php echo __('Pages', $this->plugin_name); ?></span>
                    </div>

                    <div class="snap-chat-switch">
                        <input type="checkbox" name="snapchat_pixel_code[posts]" value="checked"
                               id="snapchat_pixel_code_posts"
                               class="hidden-checkbox" <?php echo(isset($snapchat_pixel_code['posts']) ? 'checked="checked"' : ''); ?>>
                        <label for="snapchat_pixel_code_posts" class="ant-switch"></label>
                        <span class="label"><?php echo __('Posts', $this->plugin_name); ?></span>
                    </div>

                    <div class="snap-chat-switch">
                        <input type="checkbox" name="snapchat_pixel_code[search]" value="checked"
                               id="snapchat_pixel_code_search"
                               class="hidden-checkbox" <?php echo(isset($snapchat_pixel_code['search']) ? 'checked="checked"' : ''); ?>>
                        <label for="snapchat_pixel_code_search" class="ant-switch"></label>
                        <span class="label"><?php echo __('Search Results', $this->plugin_name); ?></span>
                    </div>

                    <div class="snap-chat-switch">
                        <input type="checkbox" name="snapchat_pixel_code[categories]" value="checked"
                               id="snapchat_pixel_code_categories"
                               class="hidden-checkbox" <?php echo(isset($snapchat_pixel_code['categories']) ? 'checked="checked"' : ''); ?>>
                        <label for="snapchat_pixel_code_categories" class="ant-switch"></label>
                        <span class="label"><?php echo __('Categories', $this->plugin_name); ?></span>
                    </div>

                    <div class="snap-chat-switch">
                        <input type="checkbox" name="snapchat_pixel_code[tags]" value="checked"
                               id="snapchat_pixel_code_tags"
                               class="hidden-checkbox" <?php echo(isset($snapchat_pixel_code['tags']) ? 'checked="checked"' : ''); ?>>
                        <label for="snapchat_pixel_code_tags" class="ant-switch"></label>
                        <span class="label"><?php echo __('Tags', $this->plugin_name); ?></span>
                    </div>
                </div>


            </div>
        </div>
        <div class="tab-content woocommerce <?php echo(($tab == 'woocommerce') ? 'active' : ''); ?>">
            <h2><?php echo __('Woocommerce Settings', $this->plugin_name); ?></h2>
            <div class="form-table" role="presentation">
                <div class="form-row">
                    <?php
                    if ($snapchat_pixel_wooacces == "yes") { ?>
                        <a class="enable-woocommerce"
                           href="<?php echo wp_nonce_url(admin_url('admin.php?page=snapchat-pixel&tab=woocommerce&woo_activate=no'), 'disable_woocommerce_action'); ?>"> <?php echo __('Disable for WooCommerce', $this->plugin_name); ?> </a>
                        <strong><?php echo __('Standard Events for WooCommerce', $this->plugin_name); ?></strong>
                        <div class="snap-chat-switch">
                            <input type="checkbox" name="snapchat_pixel_code[viewcart]" value="checked" id="snap_pixel_places_woocommerce_cart"
                                   class="hidden-checkbox" <?php echo(isset($snapchat_pixel_code['viewcart']) ? 'checked="checked"' : ''); ?>>
                            <label for="snap_pixel_places_woocommerce_cart" class="ant-switch"></label>
                            <span class="label"><?php echo __('VIEW_CONTENT (on woocommerce product page)', $this->plugin_name); ?></span>
                        </div>
                        <div class="snap-chat-switch">
                            <input type="checkbox" name="snapchat_pixel_code[checkout]" value="checked" id="snap_pixel_places_woocommerce_checkout"
                                   class="hidden-checkbox" <?php echo(isset($snapchat_pixel_code['checkout']) ? 'checked="checked"' : ''); ?>>
                            <label for="snap_pixel_places_woocommerce_checkout" class="ant-switch"></label>
                            <span class="label"><?php echo __('START_CHECKOUT (on checkout page for all woocommerce products)', $this->plugin_name); ?></span>
                        </div>
                        <div class="snap-chat-switch">
                            <input type="checkbox" name="snapchat_pixel_code[paymentinfo]" value="checked" id="snap_pixel_places_woocommerce_paymentinfo"
                                   class="hidden-checkbox" <?php echo(isset($snapchat_pixel_code['paymentinfo']) ? 'checked="checked"' : ''); ?>>
                            <label for="snap_pixel_places_woocommerce_paymentinfo" class="ant-switch"></label>
                            <span class="label"><?php echo __('START_CHECKOUT (on checkout page for all woocommerce products)', $this->plugin_name); ?></span>
                        </div>
                        <div class="snap-chat-switch">
                            <input type="checkbox" name="snapchat_pixel_code[addtocart]" value="checked" id="snap_pixel_places_woocommerce_addtocart"
                                   class="hidden-checkbox" <?php echo(isset($snapchat_pixel_code['addtocart']) ? 'checked="checked"' : ''); ?>>
                            <label for="snap_pixel_places_woocommerce_addtocart" class="ant-switch"></label>
                            <span class="label"><?php echo __('ADD_CART (on all woocommerce products)', $this->plugin_name); ?></span>
                        </div>
                        <div class="snap-chat-switch">
                            <input type="checkbox" name="snapchat_pixel_code[ajax_addtocart]" value="checked" id="snap_pixel_places_woocommerce_ajax_addtocart"
                                   class="hidden-checkbox" <?php echo(isset($snapchat_pixel_code['ajax_addtocart']) ? 'checked="checked"' : ''); ?>>
                            <label for="snap_pixel_places_woocommerce_ajax_addtocart" class="ant-switch"></label>
                            <span class="label"><?php echo __('Allow Ajax ADD_CART (on all woocommerce products)', $this->plugin_name); ?></span>
                        </div>
                    <?php } else { ?>
                        <a class="enable-woocommerce"
                           href="<?php echo wp_nonce_url(admin_url('admin.php?page=snapchat-pixel&tab=woocommerce&woo_activate=yes'), 'disable_woocommerce_action'); ?>"> <?php echo __('Enable for WooCommerce', $this->plugin_name); ?> </a>
                    <?php } ?>
                </div>
            </div>
        </div>
        <?php echo wp_nonce_field('snapchat_pixel_security', 'snapchat_pixel_nonce'); ?>
        <p class="submit"><input type="submit" name="save_snapchat_pixel" id="submit" class="button button-primary" value="<?php echo __('Save Changes', $this->plugin_name); ?>"></p>
    </form>
</div>