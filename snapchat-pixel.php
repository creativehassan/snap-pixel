<?php
/**
 * Plugin Name: Snap Pixel
 * Plugin URI:  https://wordpress.org/plugins/snap-pixel
 * Description: Snapchat (Snap Pixel) to measure the cross-device impact of campaigns. It is best suited for your direct response goals, such as driving leads, Subscriptions, or product sales.
 * Version:     1.0.0
 * Author:      Hassan Ali
 * Author URI:  https://hassanali.pro
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: snapchat_pixel
 */

if ( ! class_exists( 'snapchat_pixel' ) ) {
    class snapchat_pixel {
		var $plugin_name = "";
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
			add_filter( 'plugin_row_meta', array( __CLASS__, 'snapchat_pixel_row_meta' ), 10, 2 );

			//enqueue for the admin section styles and javascript
			add_action('admin_enqueue_scripts', array( $this, 'admin_style_scripts' ));

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
			if(isset($_REQUEST['woo_activate'])){
				$woo_activate = isset($_REQUEST['woo_activate']) ? esc_attr($_REQUEST['woo_activate']) : '';
				update_option('snapchat_pixel_wooacces', $woo_activate);
			}
			if(isset($_POST['save_snapchat_pixel'])){
				$snapchat_pixel_code = isset($_POST['snapchat_pixel_code']) ? wp_unslash($_POST['snapchat_pixel_code']) : '';
				update_option('snapchat_pixel_code', $snapchat_pixel_code);
			}
			ob_start();
			include_once('admin/snapchat_pixel_backend.php');
			$content = ob_get_clean();
			echo $content;
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
	$snapchat_pixel = new snapchat_pixel();
}
