<?php
/**
 * Plugin Name: Snap Pixel
 * Plugin URI:  https://wordpress.org/plugins/snap-pixel
 * Description: Snapchat (Snap Pixel) to measure the cross-device impact of campaigns. It is best suited for your direct response goals, such as driving leads, Subscriptions, or product sales.
 * Version:     1.7.0
 * Author:      Hassan Ali
 * Author URI:  https://creativehassan.com
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: snapchat_pixel
 */

if ( ! class_exists( 'snapchat_pixel' ) ) {
    class snapchat_pixel {
		var $plugin_name = "";
		
		public static function init() {
	        $class = __CLASS__;
	        new $class;
	    }
        public function __construct() {
			$this->plugin_name = "snapchat_pixel";

			// Add Btn after 'Media'
			add_action( 'admin_menu', array($this, 'snapchat_pixel_menu') );

			// Add Btn after 'Media'
			add_action('template_redirect', array($this, 'snapchat_pixel_place_code') );

			// Admin notice for snap pixel id
			add_action( 'admin_notices', array( $this, 'snapchat_pixel_checks' ) );

			// Setting links on plugin page
			add_filter('plugin_action_links_'.plugin_basename(__FILE__), array( $this, 'snapchat_pixel_settings_link') );

			// Snap pixel about link on plugin row meta
			add_filter( 'plugin_row_meta', array( $this, 'snapchat_pixel_row_meta' ), 10, 2 );
			
			// Snap pixel frontend js and css
			add_action('wp_enqueue_scripts', array( $this, 'snapchat_pixel_assets') );

			//enqueue for the admin section styles and javascript
			add_action('admin_enqueue_scripts', array( $this, 'admin_style_scripts' ));
			
			add_action( 'wp_ajax_nopriv_snapchat_product_data', array( $this, 'snapchat_product_data') );
			add_action( 'wp_ajax_snapchat_product_data', array( $this, 'snapchat_product_data') );

			//language support
			add_action( 'plugins_loaded', array( $this, 'snapchat_pixel_plugin_textdomain' ));

			register_activation_hook( __FILE__, array( $this, 'snapchat_pixel_activate') );
			
			// all snap pixel functions
			include_once('includes/function.php');

			$snapchat_pixel = new snapchat_pixel_functions();
        }
		/**
		 * Set Plugin row meta
		 *
		 * @return array
		 */
		public function snapchat_pixel_row_meta( $links, $file ){
			if ( plugin_basename(__FILE__) === $file ) {
				$row_meta = array(
					'docs'    => '<a href="' . esc_url( 'https://businesshelp.snapchat.com/en-US/article/snap-pixel-about' ) . '" aria-label="' . esc_attr__( 'About Snap Pixel', "snapchat_pixel" ) . '" title="' . esc_attr__( 'About Snap Pixel', "snapchat_pixel" ) . '">' . esc_html__( 'About Snap Pixel', "snapchat_pixel" ) . '</a>',
				);

				return array_merge( $links, $row_meta );
			}

			return (array) $links;
		}
		function snapchat_pixel_assets(){
		    wp_register_script('snap-pixel', plugin_dir_url(__FILE__) . 'assets/js/snapchat-pixel.js', array( 'jquery' ), strtotime('now'), false);
		    wp_localize_script(
		        'snap-pixel',
		        'snappixel',
		        array(
		          'ajaxurl' => admin_url('admin-ajax.php')
		        )
		    );
		    wp_enqueue_script('snap-pixel');
		}
		/**
		 * Set setting link on plugin page
		 *
		 * @return array
		 */
		public function snapchat_pixel_settings_link($links){
			$links[] = '<a href="' .
				admin_url( 'admin.php?page=snapchat-pixel&tab=general' ) .
				'">' . __('Settings') . '</a>';
			return $links;
		}
		
		function snapchat_product_data(){
			global $wpdb;
			if(isset($_REQUEST['snap_product_id']) && $_REQUEST['snap_product_id'] != ""){
				$snap_product_id = $_REQUEST['snap_product_id'];
				$_product = wc_get_product($snap_product_id);
				if($_product){
					$product_price = $_product->get_price();
				}
				wp_send_json_success($product_price);
				wp_die();
			}

			wp_send_json_error( "Error" );
			wp_die();
		}
		
		/**
		 * Set admin notice for snap pixel id
		 *
		 * @return string
		 */
		public function snapchat_pixel_checks(){
			$snapchat_pixel_code = get_option('snapchat_pixel_code');
			$pixel_id = (isset($snapchat_pixel_code['pixel_id']) ? $snapchat_pixel_code['pixel_id'] : '');
			if ( ! $pixel_id ) {
				echo $this->get_message_html(
					sprintf(
						__(
							'%1$sSnapchat Pixel for WordPress
	        is almost ready.%2$s To complete your settings, add the %3$s
	        Snapchat Pixel ID%4$s.',
							$this->plugin_name
						),
						'<strong>',
						'</strong>',
						'<a href="' . esc_url( admin_url( 'admin.php?page=snapchat-pixel&tab=general' ) ) . '">',
						'</a>'
					),
					'info'
				);
			}
		}

		/**
		 * Get message
		 *
		 * @return string Error
		 */
		public function get_message_html( $message, $type = 'error' ) {
			ob_start();

			?>
			<div class="notice is-dismissible notice-<?php echo $type; ?>">
				<p><?php echo $message; ?></p>
			</div>
			<?php
			return ob_get_clean();
		}
		/**
		 * Plugin language support
		 *
		 * @return none
		 */
		public function snapchat_pixel_plugin_textdomain(){
			$plugin_rel_path = basename( dirname( __FILE__ ) ) . '/languages';
			load_plugin_textdomain( 'snapchat_pixel', false, $plugin_rel_path );
		}

		/**
		 * Conditions to place pixel code
		 *
		 * @return none
		 */
		public function snapchat_pixel_place_code(){
			include_once('admin/snapchat_pixel_place_code.php');
		}

		/**
		 * Admin Menu
		 *
		 * @return none
		 */
		public function snapchat_pixel_menu(){
			add_menu_page(__('Snapchat Pixel', $this->plugin_name), __('Snapchat Pixel', $this->plugin_name), 'manage_options', 'snapchat-pixel', array($this, 'snapchat_pixel_backend'), plugin_dir_url(__FILE__) . 'assets/images/snapchat-pixel.png');
		}

		/**
		 * Save Settings
		 *
		 * @return none
		 */

		public function snapchat_pixel_backend(){
            if ( isset( $_POST['snapchat_pixel_nonce'] ) && wp_verify_nonce( $_POST['snapchat_pixel_nonce'], 'snapchat_pixel_security' ) ) {

                if (isset($_POST['save_snapchat_pixel'])) {
                    if (isset($_POST['snapchat_pixel_code'])) {
                        $raw_data = wp_unslash($_POST['snapchat_pixel_code']);

                        $sanitized_data = array(
                            'pixel_id' => $this->sanitize_pixel_id($raw_data['pixel_id']),
                            'user_email' => sanitize_email($raw_data['user_email']),
                            'homepage' => isset($raw_data['homepage']) && $raw_data['homepage'] === 'checked' ? 'checked' : '',
                            'pages' => isset($raw_data['pages']) && $raw_data['pages'] === 'checked' ? 'checked' : '',
                            'posts' => isset($raw_data['posts']) && $raw_data['posts'] === 'checked' ? 'checked' : '',
                            'search' => isset($raw_data['search']) && $raw_data['search'] === 'checked' ? 'checked' : '',
                            'categories' => isset($raw_data['categories']) && $raw_data['categories'] === 'checked' ? 'checked' : '',
                            'tags' => isset($raw_data['tags']) && $raw_data['tags'] === 'checked' ? 'checked' : '',
                            'viewcart' => isset($raw_data['viewcart']) && $raw_data['viewcart'] === 'checked' ? 'checked' : '',
                            'checkout' => isset($raw_data['checkout']) && $raw_data['checkout'] === 'checked' ? 'checked' : '',
                            'paymentinfo' => isset($raw_data['paymentinfo']) && $raw_data['paymentinfo'] === 'checked' ? 'checked' : '',
                            'addtocart' => isset($raw_data['addtocart']) && $raw_data['addtocart'] === 'checked' ? 'checked' : '',
                            'ajax_addtocart' => isset($raw_data['ajax_addtocart']) && $raw_data['ajax_addtocart'] === 'checked' ? 'checked' : ''
                        );

                        // Additional sanitization for 'pixel_id' to prevent XSS
                        $sanitized_data['pixel_id'] = esc_attr($sanitized_data['pixel_id']);

                        update_option('snapchat_pixel_code', $sanitized_data);
                    }
                }
            }
            // Save WooCommerce Settings
            if ( isset($_REQUEST['woo_activate']) && isset($_GET['_wpnonce']) && wp_verify_nonce($_GET['_wpnonce'], 'disable_woocommerce_action') ) {
                $woo_activate = isset($_REQUEST['woo_activate']) ? esc_attr($_REQUEST['woo_activate']) : '';
                if( $woo_activate == 'yes' ){
                    $woo_activate = 'yes';
                } else if( $woo_activate == 'no' ){
                    $woo_activate = 'no';
                } else {
                    $woo_activate = 'no';
                }
                update_option('snapchat_pixel_wooacces', $woo_activate);
            }

			include_once('admin/snapchat_pixel_backend.php');
		}

        public function sanitize_pixel_id($input) {
            // Strip out all HTML tags
            $input = strip_tags($input);
            // Remove quotes and other special characters
            $input = sanitize_text_field( $input );

            $input = preg_replace('/[^a-zA-Z0-9_-]/', '', $input);

            return $input;
        }

		/**
		 * Load Backend Admin CSS & JS.
		 */
		public function admin_style_scripts( $page ) {
			wp_enqueue_script('snapchat-pixel-admin', plugin_dir_url( __FILE__ ) . 'assets/js/snapchat-pixel-admin.js', array('jquery'), '1.0.0', true);
			wp_enqueue_style('snapchat-pixel-admin', plugin_dir_url( __FILE__ ) . 'assets/css/snapchat-pixel-admin.css');
		}

		/**
		 * Activate plugin hook.
		 */
		public function snapchat_pixel_activate(){
			$snapchat_pixel_code_opt = get_option( 'snapchat_pixel_code' );
			if(empty($snapchat_pixel_code_opt) || $snapchat_pixel_code_opt == ""){
				$snapchat_pixel_code = array(
					'homepage' => 'checked',
					'pages' => 'checked',
					'posts' => 'checked',
					'search' => 'checked',
					'categories' => 'checked',
					'tags' => 'checked',
					'viewcart' => 'checked',
					'checkout' => 'checked',
					'paymentinfo' => 'checked',
					'addtocart' => 'checked'
				);
				update_option('snapchat_pixel_code', $snapchat_pixel_code);
			}
		}

    }
	
	add_action( 'plugins_loaded', array( 'snapchat_pixel', 'init' ));
}