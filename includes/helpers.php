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
