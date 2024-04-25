<?php

namespace GiveActiveCampaign\FormExtension\DonationForm\Fields;

use Give\Framework\FieldsAPI\Checkbox;

/**
 * @unreleased
 */
class ActiveCampaignField extends Checkbox
{
    /**
     * @unreleased
     */
    protected $selectedLists = [];

    /**
     * @unreleased
     */
    protected $selectedTags = [];

    public const TYPE = 'activecampaign';

    /**
     * @unreleased
     */
    public function selectedLists(array $selectedLists): ActiveCampaignField
    {
        $this->selectedLists = $selectedLists;
        return $this;
    }

    /**
     * @unreleased
     */
    public function getSelectedLists(): array
    {
        return $this->selectedLists;
    }
    /**
     * @unreleased
     */
    public function selectedTags(array $selectedTags): ActiveCampaignField
    {
        $this->selectedTags = $selectedTags;
        return $this;
    }

    /**
     * @unreleased
     */
    public function getSelectedTags(): array
    {
        return $this->selectedTags;
    }

}
