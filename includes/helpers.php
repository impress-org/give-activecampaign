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
 * Pull tags into
 *
 * @return array
 */
function give_get_activecampaign_tags() {

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

	$tags = $ac->api( 'tags/list', array( 'ids' => 'all' ) );

	$tags = json_decode( $tags, true );

	if ( ! empty( $tags ) ) {

		$output = array();

		foreach ( $tags as $key => $tag ) {
			if ( ! is_numeric( $key ) ) {
				continue;
			}

			$output[ $tag['name'] ] = $tag['name'];
		}

		return $output;

	} else {
		return array();
	}

}

/**
 * Display the opt-in checkbox oon donation forms.
 *
 * @param $form_id
 */
function give_activecampaign_display_optin( $form_id ) {

	$label = give_get_option( 'give_activecampaign_label' );

	if ( ! empty( $label ) ) {
		$checkout_label = trim( $label );
	} else {
		$checkout_label = __( 'Subscribe to our newsletter', 'edd-activecampaign' );
	}

	// Should the opt-on be checked or unchecked by default?
	$form_option = give_get_meta( $form_id, '_give_mailchimp_checked_default', true );

	$checked = give_get_option( 'give_activecampaign_checkbox_default', false );

	ob_start(); ?>
	<fieldset id="give_activecampaign_<?php echo $form_id; ?>" class="give-activecampaign-fieldset">
		<label for="give_activecampaign_<?php echo $form_id; ?>_signup" class="give-activecampaign-optin-label">
			<input name="give_activecampaign_signup"
			       class="give-activecampaign-optin-input"
			       id="give_activecampaign_<?php echo $form_id; ?>_signup"
			       type="checkbox" <?php echo( $checked !== 'no' ? 'checked="checked"' : '' ); ?>/>
			<span class="give-activecampaign-message-text"><?php echo $checkout_label; ?></span>
		</label>

	</fieldset>
	<?php
	echo ob_get_clean();
}


add_action( 'give_donation_form_before_submit', 'give_activecampaign_display_optin', 10, 1 );


/**
 * Show Line item on donation details screen if the donor opted-in to the newsletter.
 *
 * @param $payment_id
 */
function give_activecampaign_donation_metabox_notification( $payment_id ) {

	$opt_in_meta = give_get_meta( $payment_id, '_give_activecampaign_donation_optin_status', false );

	if ( $opt_in_meta ) { ?>
		<div class="give-admin-box-inside">
			<p>
				<span class="label"><?php _e( 'ActiveCampaign', 'give-activecampaign' ); ?>:</span>&nbsp;
				<span><?php _e( 'Opted-in', 'give-activecampaign' ); ?></span>
			</p>
		</div>
	<?php }

}

add_filter( 'give_view_donation_details_totals_after', 'give_activecampaign_donation_metabox_notification', 999, 1 );


/**
 * Load the admin scripts.
 */
function give_activecampaign_enqueue_admin_scripts() {
	if ( give_is_admin_page() ) {
		$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

		wp_register_script( 'give-activecampaign-admin', GIVE_ACTIVECAMPAIGN_URL . 'assets/js/give-activecampaign-admin' . $suffix . '.js', array( 'give-admin-scripts' ),
			GIVE_ACTIVECAMPAIGN_VERSION, false );
		wp_enqueue_script( 'give-activecampaign-admin' );
	}

}

add_action( 'admin_enqueue_scripts', 'give_activecampaign_enqueue_admin_scripts' );
