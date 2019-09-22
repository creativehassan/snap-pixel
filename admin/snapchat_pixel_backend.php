<?php
$tab = "";
if(isset($_REQUEST['tab'])){
	$tab = $_REQUEST['tab'];
}
$snapchat_pixel_code = get_option('snapchat_pixel_code');
$snapchat_pixel_wooacces = get_option('snapchat_pixel_wooacces');

?>

<div class="wrap snapchat-pixel-wrapper">
   <h1><img src="<?php echo plugin_dir_url(__FILE__) . '../assets/images/snapchat-pixel.png'; ?>" alt="snapchat" class="img-heading" /> <?php echo __('Snapchat Settings', $this->plugin_name); ?></h1>
   <?php include_once("setting-tabs.php"); ?>
   <form method="post" action="">
      <div class="tab-content general <?php echo (($tab == 'general' || $tab == '') ? 'active' : ''); ?>">
	      <h2><?php echo __('Snapchat Pixel Settings', $this->plugin_name); ?></h2>
	      <table class="form-table" role="presentation">
	         <tbody>
	            <tr>
	               <td>
		               <strong><?php echo __('Snapchat Pixel ID', $this->plugin_name); ?></strong><br>
					  <input type="text" name="snapchat_pixel_code[pixel_id]" class="regular-text" value="<?php echo (isset($snapchat_pixel_code['pixel_id']) ? $snapchat_pixel_code['pixel_id'] : ''); ?>" />
				 	  <br>
				 	  <span class="smallfont"><?php printf( __( "You can get from snapchat <a href='%s' target='_blank'> Get Pixel ID </a> .", $this->plugin_name ) , "https://ads.snapchat.com"); ?></span>
				 		<hr>
				 </td>
				</tr>
				<tr>
	               <td>
		               <strong><?php echo __('Snapchat Pixel User Email', $this->plugin_name); ?></strong><br>
					  <input type="text" name="snapchat_pixel_code[user_email]" class="regular-text" value="<?php echo (isset($snapchat_pixel_code['user_email']) ? $snapchat_pixel_code['user_email'] : ''); ?>" />
				 	  <br>
				 	  <span class="smallfont"><?php __( "This user email will be sent with pixels firing", $this->plugin_name ); ?></span>
				 		<hr>
				 </td>
				</tr>
				<tr>
				 <td>
				   <strong><?php echo __('Where do you want to place snapchat pixel code?', $this->plugin_name); ?></strong><br>
				   <input type="checkbox" name="snapchat_pixel_code[homepage]" value="checked" id="snapchat_pixel_code_homepage" <?php echo (isset($snapchat_pixel_code['homepage']) ? 'checked="checked"' : ''); ?>>
				   <label for="snapchat_pixel_code_homepage"><?php echo __('Home or FrontPage', $this->plugin_name); ?></label><br>
				   <input type="checkbox" name="snapchat_pixel_code[pages]" value="checked" id="snapchat_pixel_code_pages" <?php echo (isset($snapchat_pixel_code['pages']) ? 'checked="checked"' : ''); ?>>
				   <label for="snapchat_pixel_code_pages"><?php echo __('Pages', $this->plugin_name); ?></label><br>
				   <input type="checkbox" name="snapchat_pixel_code[posts]" value="checked" id="snapchat_pixel_code_posts" <?php echo (isset($snapchat_pixel_code['posts']) ? 'checked="checked"' : ''); ?>>
				   <label for="snapchat_pixel_code_posts"><?php echo __('Posts', $this->plugin_name); ?></label><br>
				   <input type="checkbox" name="snapchat_pixel_code[search]" value="checked" id="snapchat_pixel_code_search" <?php echo (isset($snapchat_pixel_code['search']) ? 'checked="checked"' : ''); ?>>
				   <label for="snapchat_pixel_code_search"><?php echo __('Search Results', $this->plugin_name); ?></label><br>
				   <input type="checkbox" name="snapchat_pixel_code[categories]" value="checked" id="snapchat_pixel_code_categories" <?php echo (isset($snapchat_pixel_code['categories']) ? 'checked="checked"' : ''); ?>>
				   <label for="snapchat_pixel_code_categories"><?php echo __('Categories', $this->plugin_name); ?></label><br>
				   <input type="checkbox" name="snapchat_pixel_code[tags]" value="checked" id="snapchat_pixel_code_tags" <?php echo (isset($snapchat_pixel_code['tags']) ? 'checked="checked"' : ''); ?>>
				   <label for="snapchat_pixel_code_tags"><?php echo __('Tags', $this->plugin_name); ?></label><br><br/>
				   <hr>
				</td>
	            </tr>
	         </tbody>
	      </table>
      </div>
      <div class="tab-content woocommerce <?php echo (($tab == 'woocommerce') ? 'active' : ''); ?>">
	      <h2><?php echo __('Woocommerce Settings', $this->plugin_name); ?></h2>
	      <table class="form-table" role="presentation">
	         <tbody>
	            <tr>
				   <td>
				   	<?php
					   if($snapchat_pixel_wooacces == "yes"){ ?>
					   	<a class="enable-woocommerce" href="<?php echo admin_url( 'admin.php?page=snapchat-pixel&tab=woocommerce&woo_activate=no' ); ?>"> <?php echo __('Disable for WooCommerce', $this->plugin_name); ?> </a> <br><br><br>
					  <!--<strong><?php echo __('WooCommerce Settings', $this->plugin_name); ?></strong><br>
				      <?php echo __('CSS class name for (add to cart button)', $this->plugin_name); ?>:<br>
				      <input type="text" name="snapchat_pixel_code[addtocart_class]" value="<?php echo (isset($snapchat_pixel_code['addtocart_class']) ? $snapchat_pixel_code['addtocart_class'] : ''); ?>"><br>
				      <br>
				      <?php echo __('CSS class name for (add to wishlist button)', $this->plugin_name); ?>:<br>
				      <input type="text" name="snapchat_pixel_code[addtowish_class]" value="<?php echo (isset($snapchat_pixel_code['addtowish_class']) ? $snapchat_pixel_code['addtowish_class'] : ''); ?>"><br>-->
				      <br><strong><?php echo __('Standard Events for WooCommerce', $this->plugin_name); ?></strong>:<br><br>
				      <input type="checkbox" name="snapchat_pixel_code[viewcart]" value="checked" id="snap_pixel_places_woocommerce_cart" <?php echo (isset($snapchat_pixel_code['viewcart']) ? 'checked="checked"' : ''); ?>>
				      <label for="snap_pixel_places_woocommerce_cart"><?php echo __('VIEW_CONTENT (on woocommerce product page)', $this->plugin_name); ?></label><br>
				      <input type="checkbox" name="snapchat_pixel_code[checkout]" value="checked" id="snap_pixel_places_woocommerce_checkout" <?php echo (isset($snapchat_pixel_code['checkout']) ? 'checked="checked"' : ''); ?>>
				      <label for="snap_pixel_places_woocommerce_checkout"><?php echo __('START_CHECKOUT (on checkout page for all woocommerce products)', $this->plugin_name); ?></label><br>
				      <input type="checkbox" name="snapchat_pixel_code[paymentinfo]" value="checked" id="snap_pixel_places_woocommerce_paymentinfo" <?php echo (isset($snapchat_pixel_code['paymentinfo']) ? 'checked="checked"' : ''); ?>>
				      <label for="snap_pixel_places_woocommerce_paymentinfo"><?php echo __('AddPaymentInfo PURCHASE order recieve page for all products)', $this->plugin_name); ?></label><br>
				      <input type="checkbox" name="snapchat_pixel_code[addtocart]" value="checked" id="snap_pixel_places_woocommerce_addtocart" <?php echo (isset($snapchat_pixel_code['addtocart']) ? 'checked="checked"' : ''); ?>>
				      <label for="snap_pixel_places_woocommerce_addtocart"><?php echo __('ADD_CART (on all woocommerce products)', $this->plugin_name); ?></label><br><hr>
					   <?php	} else { ?>
					   		<a class="enable-woocommerce" href="<?php echo admin_url( 'admin.php?page=snapchat-pixel&tab=woocommerce&woo_activate=yes' ); ?>"> <?php echo __('Enable for WooCommerce', $this->plugin_name); ?> </a>
					   <?php } ?>
				   </td>
				</tr>
	         </tbody>
	      </table>
      </div>
      <p class="submit"><input type="submit" name="save_snapchat_pixel" id="submit" class="button button-primary" value="<?php echo __('Save Changes', $this->plugin_name); ?>"></p>
   </form>
</div>