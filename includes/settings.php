<?php
/**
 * Give MailChimp Settings Page/Tab
 *
 * @package    Give_ActiveCampaign
 * @subpackage Give_ActiveCampaign/includes
 * @author     GiveWP <https://givewp.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Give_ActiveCampaign_Settings.
 *
 * @sine 1.0.0
 */
class Give_ActiveCampaign_Settings extends Give_Settings_Page {

	/**
	 * Give_ActiveCampaign_Settings constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		$this->id    = 'activecampaign';
		$this->label = __( 'ActiveCampaign', 'give-activecampaign' );
		$this->default_tab = 'activecampaign';

		parent::__construct();
	}

	/**
	 * Add setting sections.
	 *
	 * @return array
	 */
	function get_sections() {

		$sections = array(
			'activecampaign' => __( 'ActiveCampaign Settings', 'give-activecampaign' ),
		);

		return $sections;
	}


	/**
	 * Get settings array.
	 *
	 * @return array
	 * @since  1.0.0
	 * @access public
	 *
	 */
	public function get_settings() {
		$settings = array(
			array(
				'id'   => 'give_title_activecampaign',
				'type' => 'title',
			),
			array(
				'id'   => 'give_activecampaign_apiurl',
				'name' => __( 'API URL', 'give-activecampaign' ),
				'desc' => __( 'Enter your ActiveCampaign API URL. It is located in the Settings > Developer area of your ActiveCampaign account.', 'give-activecampaign' ),
				'type' => 'api_key',
			),
			array(
				'id'   => 'give_activecampaign_api',
				'name' => __( 'API Key', 'give-activecampaign' ),
				'desc' => __( 'Enter your ActiveCampaign API Key. It is located in the Settings > Developer area of your ActiveCampaign account.', 'give-activecampaign' ),
				'type' => 'api_key',
			),
			array(
				'id'   => 'give_activecampaign_checkout',
				'name' => __( 'Enable Globally', 'give-activecampaign' ),
				'desc' => __( 'Allow donors to sign up for the list selected below on all donation forms? Note: the list(s) can be customized per form.', 'give-activecampaign' ),
				'type' => 'checkbox',
			),
			array(
				'id'      => 'give_activecampaign_checkbox_default',
				'name'    => __( 'Opt-in Default', 'give-activecampaign' ),
				'desc'    => __( 'Would you like the newsletter opt-in checkbox checked by default? This option can be customized per form.', 'give-activecampaign' ),
				'options' => array(
					'yes' => __( 'Checked', 'give-activecampaign' ),
					'no'  => __( 'Unchecked', 'give-activecampaign' ),
				),
				'default' => 'no',
				'type'    => 'radio_inline',
			),
			array(
				'id'      => 'give_activecampaign_list',
				'name'    => __( 'Default List', 'give-activecampaign' ),
				'desc'    => __( 'Select the list you wish for all donors to subscribe to by default. Note: the list(s) can be customized per form.', 'give-activecampaign' ),
				'type'    => 'select',
				'options' => give_get_activecampaign_lists(),
			),
			array(
				'id'         => 'give_activecampaign_label',
				'name'       => __( 'Default Label', 'give-activecampaign' ),
				'desc'       => __( 'This is the text shown by default next to the ActiveCampaign sign up checkbox. Note: the text can be customized per form.',
					'give-activecampaign' ),
				'type'       => 'text',
				'attributes' => array(
					'placeholder' => __( 'Subscribe to our newsletter', 'give-activecampaign' ),
				),
			),
			array(
				'name'  => __( 'ActiveCampaign Docs Link', 'give-activecampaign' ),
				'id'    => 'activecampaign_settings_docs_link',
				'url'   => esc_url( 'http://docs.givewp.com/addon-activecampaign' ),
				'title' => __( 'ActiveCampaign Settings', 'give-activecampaign' ),
				'type'  => 'give_docs_link',
			),
			array(
				'id'   => 'give_activecampaign_settings',
				'type' => 'sectionend',
			),
		);

		/**
		 * Filter the Give - ActiveCampaign settings.
		 *
		 * @since  1.0.0
		 *
		 * @param  array $settings
		 */
		$settings = apply_filters( 'give_activecampaign_get_settings_' . $this->id, $settings );

		return $settings;
	}
}


return new Give_ActiveCampaign_Settings();
