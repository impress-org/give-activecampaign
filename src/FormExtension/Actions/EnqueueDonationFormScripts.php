<?php

namespace GiveActiveCampaign\FormExtension\Actions;

/**
 * @since 2.0.0
 */
class EnqueueDonationFormScripts
{
    /**
     * @since 2.0.0
     * @var string
     */
    protected $styleSrc;

    /**
     * @since 2.0.0
     * @var string
     */
    protected $scriptSrc;

    /**
     * @since 2.0.0
     * @var array
     */
    protected $scriptAsset;

    /**
     * @since 2.0.0
     */
    public function __construct()
    {
        $this->styleSrc = GIVE_ACTIVECAMPAIGN_URL . 'build/DonationForm.css';
        $this->scriptSrc = GIVE_ACTIVECAMPAIGN_URL . 'build/DonationForm.js';
        $this->scriptAsset = require GIVE_ACTIVECAMPAIGN_DIR . 'build/DonationForm.asset.php';
    }

    /**
     * @since 2.0.0
     */
    public function __invoke()
    {
        wp_enqueue_script('givewp-form-extension-activecampaign', $this->scriptSrc, $this->scriptAsset['dependencies'], false, true);
        wp_enqueue_style('givewp-form-extension-activecampaign', $this->styleSrc);
    }
}
