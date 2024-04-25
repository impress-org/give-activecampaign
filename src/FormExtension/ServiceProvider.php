<?php

namespace GiveActiveCampaign\FormExtension;

use Give\Helpers\Hooks;
use Give\ServiceProviders\ServiceProvider as ServiceProviderInterface;

/**
 * @unreleased
 */
class ServiceProvider implements ServiceProviderInterface
{
    /**
     * @unreleased
     * @inheritDoc
     */
    public function register(): void
    {
        give()->singleton(\ActiveCampaign::class, static function () {
            return new \ActiveCampaign(
                $api_url = give_get_option( 'give_activecampaign_apiurl', false ),
                $api_key = give_get_option( 'give_activecampaign_api', false )
            );
        });
    }

    /**
     * @unreleased
     * @inheritDoc
     */
    public function boot(): void
    {
        Hooks::addAction('givewp_form_builder_new_form', Actions\AddBlockToNewForms::class);
        Hooks::addAction('givewp_form_builder_enqueue_scripts', Actions\EnqueueFormBuilderScripts::class);
        Hooks::addAction('givewp_donation_form_enqueue_scripts', Actions\EnqueueDonationFormScripts::class);

        Hooks::addFilter(
            'givewp_donation_form_block_render_give-activecampaign/activecampaign',
            Actions\RenderDonationFormBlock::class,
            '__invoke',
            10,
            4
        );
    }
}
