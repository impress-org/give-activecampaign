<?php

namespace GiveActiveCampaign\FormExtension\Actions;

use Give\DonationForms\Models\DonationForm;
use Give\Framework\Blocks\BlockModel;

/**
 * @since 2.0.0
 */
class AddBlockToNewForms
{
    /**
     * @since 2.0.0
     */
    public function __invoke(DonationForm $form)
    {
        $activeCampaign = give(\ActiveCampaign::class);

        if (!$this->isEnabledGlobally() || !$activeCampaign->credentials_test()) {
            return;
        }

        $form->blocks->insertAfter('givewp/email', BlockModel::make([
            'name'       => 'give-activecampaign/activecampaign',
            'attributes' => [
                'label'          => $this->getLabel(),
                'defaultChecked' => $this->getDefaultChecked(),
                'selectedLists'  => $this->getSelectedLists(),
                'selectedTags'   => $this->getSelectedTags(),
            ],
        ]));
    }

    /**
     * @since 2.0.0
     */
    public function isEnabledGlobally(): bool
    {
        return give_is_setting_enabled(give_get_option( 'give_activecampaign_globally_enabled'));
    }

    /**
     * @since 2.0.0
     */
    public function getLabel(): string
    {
        return give_get_option('give_activecampaign_label');
    }

    /**
     * @since 2.0.0
     */
    protected function getDefaultChecked()
    {
        return give_is_setting_enabled(give_get_option('give_activecampaign_checkbox_default'));
    }

    /**
     * @since 2.0.0
     */
    protected function getSelectedLists()
    {
        return give_get_option('give_activecampaign_lists', []);
    }

    /**
     * @since 2.0.0
     */
    protected function getSelectedTags()
    {
        return give_get_option('give_activecampaign_tags');
    }
}
