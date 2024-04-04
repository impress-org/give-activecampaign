<?php

namespace GiveActiveCampaign\FormExtension\Actions;

use Give\DonationForms\Models\DonationForm;
use Give\Framework\Blocks\BlockModel;

/**
 * @unreleased
 */
class AddBlockToNewForms
{
    /**
     * @unreleased
     */
    public function __invoke(DonationForm $form)
    {
        $activeCampaign = give(\ActiveCampaign::class);
        
        if ($this->isEnabledGlobally() && $activeCampaign->credentials_test()) {
            $form->blocks->insertAfter('givewp/email', BlockModel::make([
                'name' => 'give-activecampaign/activecampaign',
                'attributes' => [
                    'label' => $this->getLabel(),
                    'defaultChecked' => $this->getDefaultChecked(),
                    'selectedLists' => $this->getSelectedLists(),
                    'selectedTags' => $this->getSelectedTags(),
                ],
            ]));
        }
    }

    /**
     * @unreleased
     */
    public function isEnabledGlobally(): bool
    {
        return give_is_setting_enabled(give_get_option( 'give_activecampaign_globally_enabled'));
    }

    /**
     * @unreleased
     */
    public function getLabel(): string
    {
        return give_get_option('give_activecampaign_label');
    }

    /**
     * @unreleased
     */
    protected function getDefaultChecked()
    {
        return give_is_setting_enabled(give_get_option('give_activecampaign_checkbox_default'));
    }

    /**
     * @unreleased
     */
    protected function getSelectedLists()
    {
        return give_get_option('give_activecampaign_lists', []);
    }

    /**
     * @unreleased
     */
    protected function getSelectedTags()
    {
        return give_get_option('give_activecampaign_tags');
    }
}
