<?php

namespace GiveActiveCampaign\FormExtension\Actions;

/**
 * @unreleased
 */
class EnqueueDonationFormScripts
{
    /**
     * @unreleased
     * @var string
     */
    protected $styleSrc;

    /**
     * @unreleased
     * @var string
     */
    protected $scriptSrc;

    /**
     * @unreleased
     * @var array
     */
    protected $scriptAsset;

    /**
     * @unreleased
     */
    public function __construct()
    {
        $this->styleSrc = GIVE_ACTIVECAMPAIGN_URL . 'build/DonationForm.css';
        $this->scriptSrc = GIVE_ACTIVECAMPAIGN_URL . 'build/DonationForm.js';
        $this->scriptAsset = require GIVE_ACTIVECAMPAIGN_DIR . 'build/DonationForm.asset.php';
    }

    /**
     * @unreleased
     */
    public function __invoke()
    {
        wp_enqueue_script('givewp-form-extension-activecampaign', $this->scriptSrc, $this->scriptAsset['dependencies']);
        wp_enqueue_style('givewp-form-extension-activecampaign', $this->styleSrc);
    }
}
