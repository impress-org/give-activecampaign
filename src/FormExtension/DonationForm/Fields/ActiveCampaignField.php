<?php

namespace GiveActiveCampaign\FormExtension\DonationForm\Fields;

use Give\Framework\FieldsAPI\Checkbox;

/**
 * @since 2.0.0
 */
class ActiveCampaignField extends Checkbox
{
    /**
     * @since 2.0.0
     */
    protected $selectedLists = [];

    /**
     * @since 2.0.0
     */
    protected $selectedTags = [];

    public const TYPE = 'activecampaign';

    /**
     * @since 2.0.0
     */
    public function selectedLists(array $selectedLists): ActiveCampaignField
    {
        $this->selectedLists = $selectedLists;
        return $this;
    }

    /**
     * @since 2.0.0
     */
    public function getSelectedLists(): array
    {
        return $this->selectedLists;
    }
    /**
     * @since 2.0.0
     */
    public function selectedTags(array $selectedTags): ActiveCampaignField
    {
        $this->selectedTags = $selectedTags;
        return $this;
    }

    /**
     * @since 2.0.0
     */
    public function getSelectedTags(): array
    {
        return $this->selectedTags;
    }

}
