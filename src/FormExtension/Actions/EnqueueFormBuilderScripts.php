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
        wp_enqueue_script('givewp-form-extension-activecampaign', $this->scriptSrc, $this->scriptAsset['dependencies']);
        wp_localize_script('givewp-form-extension-activecampaign', 'GiveActiveCampaign', [
            'requiresSetup' => !$this->activecampaign->credentials_test(),
            'settingsUrl'   =>  '/wp-admin/edit.php?post_type=give_forms&page=give-settings&tab=activecampaign',
            'lists' =>  $this->getLists(),
            'tags'  => $this->getTags(),
        ]);
        
        wp_enqueue_style('givewp-form-extension-active-campaign', $this->styleSrc);
    }

    /**
     * @unreleased
     */
    protected function getLists(): array
    {
        if ($this->activecampaign->credentials_test()) {
            $lists = (array)$this->activecampaign->api('list/list', ['ids' => 'all']);
            
            $lists = array_filter($lists, function ($list) {
                return $list->name;
            });
            
            return array_map(function ($list) {
                return ['value' => $list->id, 'label' => $list->name];
            }, $lists);
        }
        
        return [];
    }
    
    /**
     * @unreleased
     */
    protected function getTags(): array
    {
        if ($this->activecampaign->credentials_test()) {
            $tags = json_decode($this->activecampaign->api('tags/list', ['ids' => 'all']));
            return array_map(function ($tag) {
                return ['value' => $tag->name, 'label' => $tag->name];
            }, $tags);
        }
        
        return [];
    }
}