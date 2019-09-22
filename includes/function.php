<?php
if ( ! class_exists( 'snapchat_pixel_functions' ) ) {
    class snapchat_pixel_functions {

	    var $plugin_name = "";
	    var $pixel_id = "";
	    var $user_email = "";

	    var $homepage = "";
	    var $pages = "";
	    var $posts = "";
	    var $search = "";
	    var $categories = "";
	    var $tags = "";

	    var $addtocart_class = "";
	    var $addtowish_class = "";
	    var $viewcart = "";
	    var $checkout = "";
	    var $paymentinfo = "";
	    var $addtocart = "";
	    var $purchase = "";

	    var $snapchat_pixel_wooacces = "";

		public function __construct() {

			$this->plugin_name = "snapchat_pixel";
			$snapchat_pixel_code = get_option('snapchat_pixel_code');
			$this->homepage = (isset($snapchat_pixel_code['homepage']) ? $snapchat_pixel_code['homepage'] : '');
			$this->pages = (isset($snapchat_pixel_code['pages']) ? $snapchat_pixel_code['pages'] : '');
			$this->posts = (isset($snapchat_pixel_code['posts']) ? $snapchat_pixel_code['posts'] : '');
			$this->search = (isset($snapchat_pixel_code['search']) ? $snapchat_pixel_code['search'] : '');
			$this->categories = (isset($snapchat_pixel_code['categories']) ? $snapchat_pixel_code['categories'] : '');
			$this->tags = (isset($snapchat_pixel_code['tags']) ? $snapchat_pixel_code['tags'] : '');

			$this->addtocart_class = (isset($snapchat_pixel_code['addtocart_class']) ? $snapchat_pixel_code['addtocart_class'] : '');
			$this->addtowish_class = (isset($snapchat_pixel_code['addtowish_class']) ? $snapchat_pixel_code['addtowish_class'] : '');
			$this->viewcart = (isset($snapchat_pixel_code['viewcart']) ? $snapchat_pixel_code['viewcart'] : '');
			$this->checkout = (isset($snapchat_pixel_code['checkout']) ? $snapchat_pixel_code['checkout'] : '');
			$this->paymentinfo = (isset($snapchat_pixel_code['paymentinfo']) ? $snapchat_pixel_code['paymentinfo'] : '');
			$this->addtocart = (isset($snapchat_pixel_code['addtocart']) ? $snapchat_pixel_code['addtocart'] : '');
			$this->purchase = (isset($snapchat_pixel_code['purchase']) ? $snapchat_pixel_code['purchase'] : '');

			$this->pixel_id = (isset($snapchat_pixel_code['pixel_id']) ? $snapchat_pixel_code['pixel_id'] : '');
			$this->user_email = (isset($snapchat_pixel_code['user_email']) ? $snapchat_pixel_code['user_email'] : get_option('admin_email'));

			$this->snapchat_pixel_wooacces = get_option('snapchat_pixel_wooacces');

			if( $this->snapchat_pixel_wooacces == "yes" && $this->addtocart == "checked" && $this->snapchat_pixel_wooexist() && !isset($_GET['wc-ajax']) ){
				add_action( 'woocommerce_add_to_cart', array($this, 'snapchat_pixel_code_add_to_cart'), 10, 6);
			}
        }

        public function snapchat_pixel_code_add_to_cart ($cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data){
	        if($this->snapchat_pixel_wooacces == "yes" && $this->addtocart == "checked" && $this->snapchat_pixel_wooexist()){
		        $term_list = wp_get_post_terms($product_id,'product_cat',array('fields'=>'ids'));
				$cat_id = (int)$term_list[0];
				$category = get_term ($cat_id, 'product_cat');
				$product_category = $category->name;
				$_product = wc_get_product($product_id);
				$product_price = $_product->get_price();
				$product_currency = get_woocommerce_currency();
							?>
				<!-- ADD_CART pixel event by snapchat pixel Plugin -->
				<script>
					setTimeout(function(){
						snaptr('track', 'ADD_CART', {'currency': "<?php echo $product_currency;?>", 'price': <?php echo $product_price;?>,
						'item_category': "<?php echo $product_category;?>", 'item_ids': ["<?php echo $product_id;?>"] });
					}, 1500);
				</script>
				<!-- End ADD_CART pixel event by snapchat pixel Plugin -->
				<?php
	        }
		}

        public function snapchat_pixel_wooexist(){
			if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
				return true;
			} else {
				return false;
			}
		}

		public function snapchat_pixel_orderid( $key = false){
			global $wpdb;

			$query = "SELECT post_id FROM " .$wpdb->prefix. "postmeta WHERE
			meta_key='_order_key' and
			meta_value='".$key."' ORDER BY meta_id DESC Limit 1";

			$row = $wpdb->get_row($query);
			$order_id = $row->post_id;

			return $order_id;
		}

		public function snapchat_pixel_code_everywhere (){
			?>
			<script type='text/javascript'>
			  (function(win, doc, sdk_url){
			  if(win.snaptr) return;
			  var tr=win.snaptr=function(){
			  tr.handleRequest? tr.handleRequest.apply(tr, arguments):tr.queue.push(arguments);
			};
			  tr.queue = [];
			  var s='script';
			  var new_script_section=doc.createElement(s);
			  new_script_section.async=!0;
			  new_script_section.src=sdk_url;
			  var insert_pos=doc.getElementsByTagName(s)[0];
			  insert_pos.parentNode.insertBefore(new_script_section, insert_pos);
			})(window, document, 'https://sc-static.net/scevent.min.js');

			  snaptr('init','<?php echo $this->pixel_id; ?>',{
			  'user_email':'<?php echo $this->user_email; ?>'
			})
			  snaptr('track','PAGE_VIEW')
			</script>

			<!-- End Snapchat Pixel Code -->
			<?php
		}

		public function snapchat_pixel_code_checkout (){
			global $post;

			if( $this->snapchat_pixel_wooexist() && $this->snapchat_pixel_wooacces == 'yes' ){
				global $woocommerce;
			    $price = $woocommerce->cart->total;
			    $product_currency = get_woocommerce_currency();
			    $num_items = $woocommerce->cart->get_cart_contents_count();
			    $product_id = "";
				foreach( WC()->cart->get_cart() as $cart_item ){
				    $product_id .= $cart_item['product_id'].",";
				    break;
				}

			?>
			<!-- START_CHECKOUT pixel event by snapchat pixel Plugin -->
			<script>
			snaptr('track', 'START_CHECKOUT', {'currency': "<?php echo $product_currency;?>", 'price': <?php echo $price;?>,
						'number_items': "<?php echo $num_items;?>", 'item_ids': ["<?php if($product_id){echo $product_id;}?>"] });
			</script>
			<!-- End START_CHECKOUT pixel event by snapchat pixel Plugin -->
			<?php
			}
		}

		public function snapchat_pixel_code_paymentinfo ($order_id ){
			global $post,$wp;

			if( $this->snapchat_pixel_wooexist() && $this->snapchat_pixel_wooacces == 'yes' ){
				global $woocommerce;

				$key = isset($_GET['key']) ? esc_attr( $_GET['key'] ) : '';
				$order_id = $this->snapchat_pixel_orderid( $key );

				$orders = new WC_Order( $order_id );
				$order_shipping_total = $orders->get_total();

				$_order =   $orders->get_items(); //to get info about product
				$content_ids = "";
				if(is_array($_order)){
				    foreach($_order as $order_product_detail){
				        $content_ids .=$order_product_detail['product_id'].",";
				    }
				}

			    $product_currency = get_woocommerce_currency();
			    $num_items = $woocommerce->cart->get_cart_contents_count();



			?><!-- PURCHASE pixel event by snapchat pixel Plugin -->
			<script>
				snaptr('track', 'PURCHASE', {'currency': "<?php echo $product_currency;?>", 'price': <?php echo $order_shipping_total;?>,
							'number_items': "<?php echo $num_items;?>", 'transaction_id': "<?php if($order_id){echo $order_id;}?>", 'item_ids': ["<?php if($content_ids){echo $content_ids;}?>"] });
				snaptr( 'track', 'ADD_BILLING');
			</script>
			<!-- End PURCHASE pixel event by snapchat pixel Plugin -->
			<?php
			}
		}
		public function snapchat_pixel_code_addtocart_shop(){
			if($this->snapchat_pixel_wooexist() )
			{
				$product_currency = get_woocommerce_currency();

			?>
				<!-- ADD_CART pixel event by snapchat pixel Plugin -->
				<script>
				jQuery( 'body' ).on( 'added_to_cart', function( e,h, w, button ) {
					var product_id = button.data("product_id");
					snaptr('track', 'ADD_CART', {'currency': "<?php echo $product_currency;?>", 'price': '',
					'item_category': "", 'item_ids': [product_id] });
				 });
				</script>
				<!-- End ADD_CART pixel event by snapchat pixel Plugin -->
				<?php
			}
		}

		public function snapchat_pixel_code_viewcontent(){
			global $post;

			//Check if WooCommerce is installed and active
			if( $this->snapchat_pixel_wooexist() && $this->snapchat_pixel_wooacces == 'yes' ){
				global $woocommerce;
			    $price = $woocommerce->cart->total;
			    $product_currency = get_woocommerce_currency();

			?>
			<!-- VIEW_CONTENT pixel event by snapchat pixel Plugin -->
			<script>
				snaptr('track', 'VIEW_CONTENT', {'currency': "<?php echo $product_currency;?>", 'price': <?php echo $price;?> });
			</script>
			<!-- End VIEW_CONTENT pixel event by snapchat pixel Plugin -->
			<?php
			}
		}

		public function snapchat_pixel_code_search (){
			?>
			<!-- VIEW_CONTENT pixel event by snapchat pixel Plugin -->
					<script>
					snaptr('track', 'SEARCH', {'search_string': "<?php if(isset($_GET['s'])){echo $_GET['s'];}?>" });
					</script>
			<!-- End VIEW_CONTENT pixel event by snapchat pixel Plugin -->
		<?php
		}

	}
}