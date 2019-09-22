<?php
global $query, $post;

$snapchat_pixel_code = get_option('snapchat_pixel_code');
$homepage = (isset($snapchat_pixel_code['homepage']) ? $snapchat_pixel_code['homepage'] : '');
$pages = (isset($snapchat_pixel_code['pages']) ? $snapchat_pixel_code['pages'] : '');
$posts = (isset($snapchat_pixel_code['posts']) ? $snapchat_pixel_code['posts'] : '');
$search = (isset($snapchat_pixel_code['search']) ? $snapchat_pixel_code['search'] : '');
$categories = (isset($snapchat_pixel_code['categories']) ? $snapchat_pixel_code['categories'] : '');
$tags = (isset($snapchat_pixel_code['tags']) ? $snapchat_pixel_code['tags'] : '');

$addtocart_class = (isset($snapchat_pixel_code['addtocart_class']) ? $snapchat_pixel_code['addtocart_class'] : '');
$addtowish_class = (isset($snapchat_pixel_code['addtowish_class']) ? $snapchat_pixel_code['addtowish_class'] : '');
$viewcart = (isset($snapchat_pixel_code['viewcart']) ? $snapchat_pixel_code['viewcart'] : '');
$checkout = (isset($snapchat_pixel_code['checkout']) ? $snapchat_pixel_code['checkout'] : '');
$paymentinfo = (isset($snapchat_pixel_code['paymentinfo']) ? $snapchat_pixel_code['paymentinfo'] : '');

$snapchat_pixel_wooacces = get_option('snapchat_pixel_wooacces');

$snapchat_pixel = new snapchat_pixel_functions();

if(is_home() or is_front_page()){
	if( $homepage == 'checked' ){
		add_action ('wp_head', array($snapchat_pixel, 'snapchat_pixel_code_everywhere'), 2);
	}
} else if(is_page() or is_page_template()){
	$page_slug = $post->post_name;

	if($pages == 'checked'){
		add_action ('wp_head', array($snapchat_pixel, 'snapchat_pixel_code_everywhere'), 2);

		if( $snapchat_pixel_wooacces == "yes" ){
			if($page_slug == 'checkout' ){
				if (!isset($_GET['key']) && $checkout == 'checked'){
					add_action('wp_footer', array($snapchat_pixel, 'snapchat_pixel_code_checkout') );
				} else {
					if ( $paymentinfo == 'checked' ){
						if( is_wc_endpoint_url( 'order-received' ) ){
							add_action('wp_footer', array($snapchat_pixel, 'snapchat_pixel_code_paymentinfo'));
						}
					}
				}
			}
		}
	}
} else if( is_shop() ){
	if( $addtocart == 'checked' ){
		add_action ('wp_head', array($snapchat_pixel, 'snapchat_pixel_code_everywhere'), 2);
		add_action ('wp_footer', array($snapchat_pixel, 'snapchat_pixel_code_addtocart_shop'), 2);
	}
} else if( is_single() ){

	if( $posts == 'checked' ){
		add_action ('wp_head', array($snapchat_pixel, 'snapchat_pixel_code_everywhere'), 2);
	}
	//Check if is not post
	if( !is_singular( 'post' ) ){
		if( $addtocart == 'checked' ){
			// add_action( 'woocommerce_add_to_cart', array($snapchat_pixel, 'snapchat_pixel_code_add_to_cart'), 10, 6);
		}
	}
	if(is_product()){
		if( $viewcart == 'checked' ){
			add_action('wp_footer', array($snapchat_pixel, 'snapchat_pixel_code_viewcontent'));
		}
	}
} else if(is_search()){
	if( $search == 'checked'){
		add_action ('wp_head', array($snapchat_pixel, 'snapchat_pixel_code_everywhere'), 2);
		add_action ('wp_footer', array($snapchat_pixel, 'snapchat_pixel_code_search'), 2);
	}
} else if(is_category()){
	if( $categories == 'checked'){
		add_action ('wp_head', array($snapchat_pixel, 'snapchat_pixel_code_everywhere'), 2);
	}
} else if(is_tag()){
	if( $tags == 'checked'){
		add_action ('wp_head', array($snapchat_pixel, 'snapchat_pixel_code_everywhere'), 2);
	}
}
?>