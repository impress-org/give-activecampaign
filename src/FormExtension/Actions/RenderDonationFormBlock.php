<?php

namespace GiveActiveCampaign\FormExtension\Actions;

use Give\Donations\Models\Donation;
use Give\Framework\Blocks\BlockModel;
use Give\Framework\FieldsAPI\Contracts\Node;
use Give\Framework\FieldsAPI\Exceptions\EmptyNameException;
use GiveActiveCampaign\FormExtension\DonationForm\Fields\ActiveCampaignField;

class RenderDonationFormBlock
{
    /**
     * Renders the ConvertKit field for the donation form block.
     *
     * @param Node|null $node The node instance.
     * @param BlockModel $block The block model instance.
     * @param int $blockIndex The index of the block.
     *
     * @return ActiveCampaignField
     * @throws EmptyNameException
     */
    public function __invoke($node, BlockModel $block, int $blockIndex):? ActiveCampaignField
    {
        $activeCampaign = give(\ActiveCampaign::class);
        
        if ($activeCampaign->credentials_test()) {
            return ActiveCampaignField::make('activecampaign')
                ->label((string)$block->getAttribute('label'))
                ->checked((bool)$block->getAttribute('defaultChecked'))
                ->selectedLists((array)$block->getAttribute('selectedLists'))
                ->selectedTags((array)$block->getAttribute('selectedTags'))
                ->scope(function(ActiveCampaignField $field, $value, Donation $donation) {
    
                    // If the field is checked, subscribe the donor to the list.
                    if(filter_var($value, FILTER_VALIDATE_BOOLEAN)) {
    
                        $subscriber = [
                            "email"      => $donation->donor->email,
                            "first_name" => $donation->donor->firstName,
                            "last_name"  => $donation->donor->lastName,
                            "tags"       => implode( ', ', $field->getSelectedTags() ),
                        ];
    
                        foreach ( $field->getSelectedLists() as $list ) {
                            $subscriber["p[$list]"] = $list;
                        }
    
                        $response = give(\ActiveCampaign::class)->api( "contact/add", $subscriber );
    
                        // Add meta to the donation post that this donation opted-in to ActiveCampaign.
                        if ( ! empty( $field->getSelectedTags() ) || ! empty( $field->getSelectedLists() ) ) {
                            add_post_meta( $donation->id, '_give_activecampaign_donation_optin_status', 'true' );
                        }
                    }
                });
            }
        
        return null;
    }
}
