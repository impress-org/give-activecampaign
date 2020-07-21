<?php


/**
 * Add metabox form settings for ActiveCampaign.
 *
 * @param $settings
 * @param $post_id
 */
function give_activecampaign_add_metabox_setting_fields( $settings, $post_id ) {

	$settings['activecampaign_options'] = array(
		'id'        => 'activecampaign_options',
		'title'     => esc_html__( 'ActiveCampaign', 'give-razorpay' ),
		'icon-html' => '<i class="fas fa-envelope"></i>',
		'fields'    => array(
			array(
				'name'    => esc_html__( 'Account Options', 'give-razorpay' ),
				'id'      => 'activecampaign_per_form_options',
				'type'    => 'radio_inline',
				'desc'    => esc_html__( 'This allows you to customize the ActiveCampaign settings for just this donation form. You can disable the opt-in for just this form as well or simply use the global settings.',
					'give-activecampaign' ),
				'default' => 'global',
				'options' => array(
					'global'   => esc_html__( 'Global Options', 'give-razorpay' ),
					'enabled'  => esc_html__( 'Customize', 'give-razorpay' ),
					'disabled' => esc_html__( 'Disable', 'give-razorpay' ),
				),
			),
			array(
				'id'      => 'give_activecampaign_checkbox_default',
				'name'    => esc_html__( 'Opt-in Default', 'give-activecampaign' ),
				'desc'    => esc_html__( 'Would you like the newsletter opt-in checkbox checked by default? This will override the global opt-in default setting.', 'give-activecampaign' ),
				'options' => array(
					'yes' => esc_html__( 'Checked', 'give-activecampaign' ),
					'no'  => esc_html__( 'Unchecked', 'give-activecampaign' ),
				),
				'default' => 'no',
				'type'    => 'radio_inline',
				'wrapper_class' => 'give-activecampaign-metabox-field give-hidden',
			),
			array(
				'id'      => 'give_activecampaign_tags',
				'name'    => esc_html__( 'Assign Tags', 'give-activecampaign' ),
				'desc'    => esc_html__( 'The selected tags will be applied to anyone who donates. This will override the global tags setting.', 'give-activecampaign' ),
				'type'     => 'chosen',
				'data_type' => 'multiselect',
				'style' => 'width: 40%',
				'options' => give_get_activecampaign_tags(),
				'wrapper_class' => 'give-activecampaign-metabox-field give-hidden',
			),
			array(
				'id'      => 'give_activecampaign_lists',
				'name'    => esc_html__( 'Lists', 'give-activecampaign' ),
				'desc'    => esc_html__( 'Select the list you wish for all donors to subscribe to by default. This will override the global lists setting.', 'give-activecampaign' ),
				'type'     => 'chosen',
				'data_type' => 'multiselect',
				'style' => 'width: 40%',
				'options' => give_get_activecampaign_lists(),
				'wrapper_class' => 'give-activecampaign-metabox-field give-hidden',
			),
			array(
				'id'         => 'give_activecampaign_label',
				'name'       => esc_html__( 'Default Label', 'give-activecampaign' ),
				'desc'       => esc_html__( 'This is the text shown by default next to the ActiveCampaign sign up checkbox. This will override the global label setting.',
					'give-activecampaign' ),
				'type'       => 'text',
				'attributes' => array(
					'placeholder' => esc_html__( 'Subscribe to our newsletter', 'give-activecampaign' ),
				),
				'wrapper_class' => 'give-activecampaign-metabox-field give-hidden',
			),
			array(
				'name'  => esc_html__( 'ActiveCampaign Docs Link', 'give-activecampaign' ),
				'id'    => 'activecampaign_settings_docs_link',
				'url'   => esc_url( 'http://docs.givewp.com/addon-activecampaign' ),
				'title' => esc_html__( 'ActiveCampaign Settings', 'give-activecampaign' ),
				'type'  => 'give_docs_link',
			),
		),
	);

	return $settings;

}

add_filter( 'give_metabox_form_data_settings', 'give_activecampaign_add_metabox_setting_fields', 10, 2 );



