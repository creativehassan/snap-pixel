<?php
	$tab = "";
	if(isset($_REQUEST['tab'])){
		$tab = esc_attr($_REQUEST['tab']);
	}
?>
<div class="nav-tab-wrapper snapchat-wrapper">
	<a href="<?php echo admin_url( 'admin.php?page=snapchat-pixel&tab=general' ); ?>" class="nav-tab <?php echo (($tab == 'general' || $tab == '') ? 'nav-tab-active' : ''); ?>" data-tab-id="general" id="general"><?php echo __('Pixel Settings', $this->plugin_name); ?></a>
	<a href="<?php echo admin_url( 'admin.php?page=snapchat-pixel&tab=woocommerce' ); ?>" class="nav-tab <?php echo (($tab == 'woocommerce') ? 'nav-tab-active' : ''); ?>" data-tab-id="woocommerce" id="woocommerce"><?php echo __('WooCommerce', $this->plugin_name); ?></a>
</div>
