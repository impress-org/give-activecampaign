jQuery( document ).ready( function( $ ) {

	var giveActiveCampaignPerFormConfiguration = $( 'input[name="activecampaign_per_form_options"]:radio' ),
		giveActiveCampaignMetaboxField = $( '.give-activecampaign-metabox-field' );

	/**
	 * Show / Hide ActiveCampaign metabox options.
	 */
	giveActiveCampaignPerFormConfiguration.on( 'change', function() {

		// Get the value of checked radio button.
		var customizeOption = $( 'input[name="activecampaign_per_form_options"]:radio:checked' ).val();

		if ( 'customized' === customizeOption ) {
			giveActiveCampaignMetaboxField.show();
		} else {
			giveActiveCampaignMetaboxField.hide();
		}

	} ).change();

} );
