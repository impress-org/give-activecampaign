<?php

namespace GiveActiveCampaign\FormExtension\Actions;

class EnqueueFormBuilderScripts
{
    /**
     * @var \ActiveCampaign
     */
    protected $activecampaign;

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
    public function __construct(\ActiveCampaign $activecampaign)
    {
        $this->activecampaign = $activecampaign;
        $this->styleSrc = GIVE_ACTIVECAMPAIGN_URL . 'build/FormBuilder.css';
        $this->scriptSrc = GIVE_ACTIVECAMPAIGN_URL . 'build/FormBuilder.js';
        $this->scriptAsset = require GIVE_ACTIVECAMPAIGN_DIR . 'build/FormBuilder.asset.php';
    }

    /**
     * @unreleased
     */
    public function __invoke()
    {
        $tags = json_decode($this->activecampaign->api( 'tags/list', [ 'ids' => 'all' ] ));

        wp_enqueue_script('givewp-form-extension-activecampaign', $this->scriptSrc, $this->scriptAsset['dependencies']);
        wp_localize_script('givewp-form-extension-activecampaign', 'GiveActiveCampaign', [
            'lists' => $this->getLists(),
            'tags' => array_map(function ($tag) {
                return ['value' => $tag->name, 'label' => $tag->name];
            }, $tags),
        ]);
    }

    protected function getLists(): array
    {
        $lists = (array) $this->activecampaign->api( 'list/list', [ 'ids' => 'all' ] );

        $lists = array_filter($lists, function ($list) {
            return $list->name;
        });

        return array_map(function ($list) {
            return ['value' => $list->id, 'label' => $list->name];
        }, $lists );
    }
}
