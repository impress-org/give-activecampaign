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

		$this->id          = 'activecampaign';
		$this->label       = __( 'ActiveCampaign', 'give-activecampaign' );
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
				'name' => esc_html__( 'API URL', 'give-activecampaign' ),
				'desc' => esc_html__( 'Enter your ActiveCampaign API URL. It is located in the Settings > Developer area of your ActiveCampaign account.', 'give-activecampaign' ),
				'type' => 'api_key',
			),
			array(
				'id'   => 'give_activecampaign_api',
				'name' => esc_html__( 'API Key', 'give-activecampaign' ),
				'desc' => esc_html__( 'Enter your ActiveCampaign API Key. It is located in the Settings > Developer area of your ActiveCampaign account.', 'give-activecampaign' ),
				'type' => 'api_key',
			),
			array(
				'id'   => 'give_activecampaign_checkout',
				'name' => esc_html__( 'Enable Globally', 'give-activecampaign' ),
				'desc' => esc_html__( 'Allow donors to sign up for the list selected below on all donation forms? Note: the list(s) can be customized per form.',
					'give-activecampaign' ),
				'type' => 'checkbox',
			),
			array(
				'id'      => 'give_activecampaign_checkbox_default',
				'name'    => esc_html__( 'Opt-in Default', 'give-activecampaign' ),
				'desc'    => esc_html__( 'Would you like the newsletter opt-in checkbox checked by default? This option can be customized per form.', 'give-activecampaign' ),
				'options' => array(
					'yes' => esc_html__( 'Checked', 'give-activecampaign' ),
					'no'  => esc_html__( 'Unchecked', 'give-activecampaign' ),
				),
				'default' => 'no',
				'type'    => 'radio_inline',
			),
			array(
				'id'       => 'give_activecampaign_tags',
				'name'     => esc_html__( 'Assign Tags', 'give-activecampaign' ),
				'desc'     => esc_html__( 'The selected tags will be applied to anyone who donates. Note: the tag(s) can be customized per form.', 'give-activecampaign' ),
				'type'     => 'chosen',
				'data_type' => 'multiselect',
				'style' => 'width: 40%',
				'options'  => give_get_activecampaign_tags(),
			),
			array(
				'id'      => 'give_activecampaign_lists',
				'name'    => esc_html__( 'Lists', 'give-activecampaign' ),
				'desc'    => esc_html__( 'Select the list you wish for all donors to subscribe to by default. Note: the list(s) can be customized per form.',
					'give-activecampaign' ),
				'type'    => 'multiselect',
				'options' => give_get_activecampaign_lists(),
			),
			array(
				'id'         => 'give_activecampaign_label',
				'name'       => esc_html__( 'Default Label', 'give-activecampaign' ),
				'desc'       => esc_html__( 'This is the text shown by default next to the ActiveCampaign sign up checkbox. Note: the text can be customized per form.',
					'give-activecampaign' ),
				'type'       => 'text',
				'attributes' => array(
					'placeholder' => esc_html__( 'Subscribe to our newsletter', 'give-activecampaign' ),
				),
			),
			array(
				'name'  => esc_html__( 'ActiveCampaign Docs Link', 'give-activecampaign' ),
				'id'    => 'activecampaign_settings_docs_link',
				'url'   => esc_url( 'http://docs.givewp.com/addon-activecampaign' ),
				'title' => esc_html__( 'ActiveCampaign Settings', 'give-activecampaign' ),
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
		 * @param array $settings
		 *
		 * @since  1.0.0
		 *
		 */
		$settings = apply_filters( 'give_activecampaign_get_settings_' . $this->id, $settings );

		return $settings;
	}


}


return new Give_ActiveCampaign_Settings();
