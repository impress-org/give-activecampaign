<?php
/**
 * Plugin Name: Give - ActiveCampaign
 * Plugin URI: https://givewp.com/addons/activecampaign/
 * Description: Easily display an ActiveCampaign opt-in option within your donation forms.
 * Author: GiveWP
 * Author URI: https://givewp.com/
 * Version: 1.0.0
 * Text Domain: give-activecampaign
 * Domain Path: languages
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Plugin constants.
if ( ! defined( 'GIVE_ACTIVECAMPAIGN_VERSION' ) ) {
	define( 'GIVE_ACTIVECAMPAIGN_VERSION', '1.0.0' );
}
if ( ! defined( 'GIVE_ACTIVECAMPAIGN_MIN_GIVE_VER' ) ) {
	define( 'GIVE_ACTIVECAMPAIGN_MIN_GIVE_VER', '2.7.0' );
}
if ( ! defined( 'GIVE_ACTIVECAMPAIGN_FILE' ) ) {
	define( 'GIVE_ACTIVECAMPAIGN_FILE', __FILE__ );
}
if ( ! defined( 'GIVE_ACTIVECAMPAIGN_PATH' ) ) {
	define( 'GIVE_ACTIVECAMPAIGN_PATH', dirname( GIVE_ACTIVECAMPAIGN_FILE ) );
}
if ( ! defined( 'GIVE_ACTIVECAMPAIGN_URL' ) ) {
	define( 'GIVE_ACTIVECAMPAIGN_URL', plugin_dir_url( GIVE_ACTIVECAMPAIGN_FILE ) );
}
if ( ! defined( 'GIVE_ACTIVECAMPAIGN_BASENAME' ) ) {
	define( 'GIVE_ACTIVECAMPAIGN_BASENAME', plugin_basename( GIVE_ACTIVECAMPAIGN_FILE ) );
}
if ( ! defined( 'GIVE_ACTIVECAMPAIGN_DIR' ) ) {
	define( 'GIVE_ACTIVECAMPAIGN_DIR', plugin_dir_path( GIVE_ACTIVECAMPAIGN_FILE ) );
}


if ( ! class_exists( 'Give_ActiveCampaign' ) ) {

	/**
	 * Class Give_ActiveCampaign
	 *
	 * @since 1.0.0
	 */
	class Give_ActiveCampaign {

		/**
		 * @since 1.0.0
		 *
		 * @var Give_ActiveCampaign The reference the singleton instance of this class.
		 */
		private static $instance;

		/**
		 * Notices (array)
		 *
		 * @since 1.0.0
		 *
		 * @var array
		 */
		public $notices = array();

		/**
		 * Returns the singleton instance of this class.
		 *
		 * @return Give_ActiveCampaign The singleton instance.
		 * @since 1.0.0
		 */
		public static function get_instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
				self::$instance->setup();
			}

			return self::$instance;
		}

		/**
		 * Setup Give ActiveCampaign.
		 *
		 * @since  1.0.0
		 * @access private
		 */
		private function setup() {

			add_action( 'give_init', array( $this, 'init' ), 10 );
			add_action( 'admin_init', array( $this, 'check_environment' ) );
			add_action( 'admin_notices', array( $this, 'admin_notices' ), 15 );
			add_action( 'give_add_email_tags', array( $this, 'add_email_tags' ), 9999999 );

			add_action( 'give_complete_donation', array( $this, 'completed_donation_optin' ), 10, 1 );


			add_filter( 'give-settings_get_settings_pages', array( $this, 'global_settings' ), 10, 1 );

		}

		/**
		 * Init the plugin after plugins_loaded so environment variables are set.
		 *
		 * @since 1.0.0
		 */
		public function init() {

			if ( ! $this->get_environment_warning() ) {
				return false;
			}

			$this->licensing();
			$this->textdomain();
			$this->activation_banner();

			require_once GIVE_ACTIVECAMPAIGN_PATH . '/includes/activation.php';

			if ( ! class_exists( 'Give' ) ) {
				return false;
			}

			require_once GIVE_ACTIVECAMPAIGN_PATH. '/vendor/autoload.php';
			require_once GIVE_ACTIVECAMPAIGN_PATH . '/includes/helpers.php';

		}

		/**
		 * Load the plugin's textdomain
		 *
		 * @since 1.0.0
		 */
		public function textdomain() {

			// Set filter for language directory.
			$lang_dir = GIVE_ACTIVECAMPAIGN_DIR . '/languages/';
			$lang_dir = apply_filters( 'give_activecampaign_languages_directory', $lang_dir );

			// Traditional WordPress plugin locale filter.
			$locale = apply_filters( 'plugin_locale', get_locale(), 'give-activecampaign' );
			$mofile = sprintf( '%1$s-%2$s.mo', 'give-activecampaign', $locale );

			// Setup paths to current locale file.
			$mofile_local  = $lang_dir . $mofile;
			$mofile_global = WP_LANG_DIR . '/give-activecampaign/' . $mofile;

			if ( file_exists( $mofile_global ) ) {
				// Look in global /wp-content/languages/give-activecampaign/ folder.
				load_textdomain( 'give-activecampaign', $mofile_global );
			} elseif ( file_exists( $mofile_local ) ) {
				// Look in local /wp-content/plugins/give-activecampaign/languages/ folder.
				load_textdomain( 'give-activecampaign', $mofile_local );
			} else {
				// Load the default language files.
				load_plugin_textdomain( 'give-activecampaign', false, $lang_dir );
			}

			// Load the translations
			load_plugin_textdomain( 'give-activecampaign', false, GIVE_ACTIVECAMPAIGN_PATH . '/languages/' );
		}

		/**
		 * Check plugin environment.
		 *
		 * @return bool
		 * @since  1.0.0
		 * @access public
		 */
		public function check_environment() {
			// Flag to check whether plugin file is loaded or not.
			$is_working = true;

			// Load plugin helper functions.
			if ( ! function_exists( 'is_plugin_active' ) ) {
				require_once ABSPATH . '/wp-admin/includes/plugin.php';
			}

			// Check to see if Give is activated, if it isn't deactivate and show a banner.
			$is_give_active = defined( 'GIVE_PLUGIN_BASENAME' ) ? is_plugin_active( GIVE_PLUGIN_BASENAME ) : false;

			if ( empty( $is_give_active ) ) {
				// Show admin notice.
				$this->add_admin_notice( 'prompt_give_activate', 'error',
					sprintf( __( '<strong>Activation Error:</strong> You must have the <a href="%s" target="_blank">Give</a> plugin installed and activated for Give - ActiveCampaign to activate.',
						'give-activecampaign' ), 'https://givewp.com' ) );
				$is_working = false;
			}

			return $is_working;
		}

		/**
		 * Check plugin for Give environment.
		 *
		 * @return bool
		 * @since  1.0.0
		 * @access public
		 */
		public function get_environment_warning() {
			// Flag to check whether plugin file is loaded or not.
			$is_working = true;

			// Verify dependency cases.
			if (
				defined( 'GIVE_VERSION' )
				&& version_compare( GIVE_VERSION, GIVE_ACTIVECAMPAIGN_MIN_GIVE_VER, '<' )
			) {

				/*
				 Min. Give. plugin version. */
				// Show admin notice.
				$this->add_admin_notice( 'prompt_give_incompatible', 'error',
					sprintf( __( '<strong>Activation Error:</strong> You must have the <a href="%1$s" target="_blank">Give</a> core version %2$s for the Give - ActiveCampaign add-on to activate.',
						'give-activecampaign' ), 'https://givewp.com', GIVE_ACTIVECAMPAIGN_MIN_GIVE_VER ) );

				$is_working = false;
			}

			return $is_working;
		}

		/**
		 * Implement Give Licensing for Give MailChimp Add On.
		 *
		 * @since  1.0.0
		 * @access private
		 */
		private function licensing() {
			if ( class_exists( 'Give_License' ) ) {
				new Give_License( GIVE_ACTIVECAMPAIGN_FILE, 'ActiveCampaign', GIVE_ACTIVECAMPAIGN_VERSION, 'WordImpress' );
			}
		}

		/**
		 * Show activation banner for this add-on.
		 *
		 * @return bool
		 * @since 1.0.0
		 *
		 */
		public function activation_banner() {

			// Check for activation banner inclusion.
			if (
				! class_exists( 'Give_Addon_Activation_Banner' )
				&& file_exists( GIVE_PLUGIN_DIR . 'includes/admin/class-addon-activation-banner.php' )
			) {
				include GIVE_PLUGIN_DIR . 'includes/admin/class-addon-activation-banner.php';
			}

			// Initialize activation welcome banner.
			if ( class_exists( 'Give_Addon_Activation_Banner' ) ) {
				// Only runs on admin.
				$args = array(
					'file'              => GIVE_ACTIVECAMPAIGN_FILE,
					'name'              => esc_html__( 'ActiveCampaign', 'give-activecampaign' ),
					'version'           => GIVE_ACTIVECAMPAIGN_VERSION,
					'settings_url'      => admin_url( 'edit.php?post_type=give_forms&page=give-settings&tab=give-activecampaign&section=activecampaign-settings' ),
					'documentation_url' => 'http://docs.givewp.com/addon-activecampaign',
					'support_url'       => 'https://givewp.com/support/',
					'testing'           => false, // Never leave true.
				);
				new Give_Addon_Activation_Banner( $args );
			}

			return true;
		}

		/**
		 * Allow this class and other classes to add notices.
		 *
		 * @param $slug
		 * @param $class
		 * @param $message
		 *
		 * @since 1.0.0
		 */
		public function add_admin_notice( $slug, $class, $message ) {
			$this->notices[ $slug ] = array(
				'class'   => $class,
				'message' => $message,
			);
		}

		/**
		 * Display admin notices.
		 *
		 * @since 1.0.0
		 */
		public function admin_notices() {

			$allowed_tags = array(
				'a'      => array(
					'href'  => array(),
					'title' => array(),
					'class' => array(),
					'id'    => array(),
				),
				'br'     => array(),
				'em'     => array(),
				'span'   => array(
					'class' => array(),
				),
				'strong' => array(),
			);

			foreach ( (array) $this->notices as $notice_key => $notice ) {
				echo "<div class='" . esc_attr( $notice['class'] ) . "'><p>";
				echo wp_kses( $notice['message'], $allowed_tags );
				echo '</p></div>';
			}

		}

		/**
		 * Add plugin setting.
		 *
		 * @since  1.0.0
		 * @access public
		 *
		 * @param array $settings Give Settings.
		 *
		 * @return array $settings Give Settings.
		 */
		public function global_settings( $settings ) {

			$settings[] = require_once GIVE_ACTIVECAMPAIGN_PATH . '/includes/settings.php';

			return $settings;
		}

		/**
		 * Check if a donor needs to be subscribed on completed donation.
		 *
		 * @since  1.0.0
		 * @access public
		 *
		 * @param $payment_id int
		 * @param $payment_data array
		 */
		public function completed_donation_optin( $payment_id, $payment_data  ) {

			// Get the Payment object
//			$payment = give_get_donation( $payment_id );
			$meta = $payment->get_meta( 'eddactivecampaign_activecampaign_signup', true );


			$form_lists = give_get_meta( $payment_data['give_form_id'], '_give_activecampaign', true );

			// Check if $form_lists is set if not use global list(s).
			if ( empty( $form_lists ) ) {

				// Get lists.
				$lists = give_get_option( 'give_activecampaign_lists' );

				// Not set so use global list.
				$form_lists = ! is_array( $lists ) ? array( 0 => $lists ) : $lists;

			}

				if ( empty ( $lists ) ) {

					// No Download list set so return global list ID
					$list_id = give_get_option( 'eddactivecampaign_list', false );
					if( ! $list_id ) {
						return false;
					}

					$this->subscribe_email( $user_info['email'], $user_info['first_name'], $user_info['last_name'], $list_id );

					return;

				$lists = array_unique( $lists );

				foreach ( $lists as $list ) {
					$this->subscribe_email( $user_info['email'], $user_info['first_name'], $user_info['last_name'], $list );
				}

			}
		}

		/**
		 *  Register Email Tag
		 *
		 * @since 1.0.0
		 */
		public function add_email_tags() {
			// Adds an email tag called {give_activecampaign_status} to indicate whether the donor opted in or not.
			give_add_email_tag(
				array(
					'tag'      => 'give_activecampaign_status', // The tag name.
					'desc'     => __( 'This outputs whether the donor opted-in to the Newsletter', 'give-activecampaign' ), // For admins.
					'func'     => [ $this, 'status_email_tag' ], // Callback to function below.
					'context'  => 'donation',
					'is_admin' => false, // default is false. This is here to simply display it as an option.
				)
			);
		}

		/**
		 * Render give_activecampaign_status email tag
		 *
		 * @param array $tag_args Array of arguments
		 *
		 * @return string
		 * @since 1.0.0
		 */
		public function status_email_tag( $tag_args ) {
			$opt_in_meta = give_get_meta( $tag_args['payment_id'], '_give_mc_donation_optin_status', true );
			$output      = __( 'Did not subscribe to newsletter', 'give-activecampaign' );

			if ( ! empty( $opt_in_meta ) ) {
				$output = __( 'Subscribed to newsletter', 'give-activecampaign' );
			}

			/**
			 * Filter is used change outputs whether the donor opted-in to the Newsletter.
			 *
			 * @param string $output string whether the donor opted-in to the Newsletter.
			 *
			 * @since 1.0.0
			 *
			 */
			return apply_filters( 'give_email_tag_give_activecampaign_status', $output, $tag_args );
		}

	}

	/**
	 * Returns class object instance.
	 *
	 * @return Give_ActiveCampaign bool|object
	 * @since 1.0.0
	 *
	 */
	function Give_ActiveCampaign() {
		return Give_ActiveCampaign::get_instance();
	}

	Give_ActiveCampaign();
}
