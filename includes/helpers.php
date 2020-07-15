<?php

/**
 * Get the lists from ActiveCampaign
 *
 * @return array
 */
function give_get_activecampaign_lists() {

	$api_url = give_get_option( 'give_activecampaign_apiurl', false );
	$api_key = give_get_option( 'give_activecampaign_api', false );

	if ( ! $api_url || ! $api_key ) {
		return array();
	}

	// Load ActiveCampaign API
	if ( ! class_exists( 'ActiveCampaign' ) ) {
		require_once( GIVE_ACTIVECAMPAIGN_PATH . '/vendor/ActiveCampaign.class.php' );
	}

	$ac = new ActiveCampaign( $api_url, $api_key );

	$lists = $ac->api( 'list/list', array( 'ids' => 'all' ) );

	if ( (int) $lists->success ) {

		// We need to cast the object to an array because ActiveCampaign returns invalid JSON.
		$lists = (array) $lists;

		$output = array();

		foreach ( $lists as $key => $list ) {
			if ( ! is_numeric( $key ) ) {
				continue;
			}

			$output[ $list->id ] = $list->name;
		}

		return $output;
	} else {
		return array();
	}
}

/**
 * Display the opt-in checkbox oon donation forms.
 */
function give_activecampaign_display_optin( $form_id ) {

	$label = give_get_option( 'give_activecampaign_label' );

	if( ! empty( $label ) ) {
		$checkout_label = trim( $label );
	} else {
		$checkout_label = __( 'Subscribe to our newsletter', 'edd-activecampaign' );
	}

	// Should the opt-on be checked or unchecked by default?
	$form_option    = give_get_meta( $form_id, '_give_mailchimp_checked_default', true );

	$checked = give_get_option( 'give_activecampaign_checkbox_default', false );



	ob_start(); ?>
	<fieldset id="give_activecampaign<?php echo $form_id; ?>" class="give-mailchimp-fieldset">
		<label for="give_activecampaign<?php echo $form_id; ?>_signup" class="give-activecampaign-optin-label">
			<input name="give_activecampaign<?php echo $form_id; ?>_signup"
			       id="give_activecampaign<?php echo $form_id; ?>_signup"
			       type="checkbox" <?php echo( $checked !== 'no' ? 'checked="checked"' : '' ); ?>/>
			<span class="give-activecampaign-message-text"><?php echo $checkout_label; ?></span>
		</label>

	</fieldset>
	<?php
	echo ob_get_clean();
}


add_action( 'give_donation_form_before_submit', 'give_activecampaign_display_optin', 10, 1 );
