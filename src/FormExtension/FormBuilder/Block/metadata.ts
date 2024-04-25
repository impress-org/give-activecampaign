import type {BlockConfiguration} from '@wordpress/blocks';
import {__} from '@wordpress/i18n';

/**
 * @unreleased
 */
const metadata: BlockConfiguration = {
    name: 'give-activecampaign/activecampaign',
    title: __('Active Campaign', 'give-activecampaign'), // Note: The brand is "ActiveCampaign" (one word), but in this context the word break is more legible.
    description: __(
        'Easily integrate ActiveCampaign opt-ins within your Give donation forms.',
        'give-activecampaign'
    ),
    category: 'addons',
    supports: {
        multiple: false,
    },
    attributes: {
        label: {
            type: 'string',
            default: __('Subscribe to our newsletter?', 'give'),
        },
        defaultChecked: {
            type: 'boolean',
            default: true,
        },
        selectedLists: {
            type: 'array',
            default: [],
        },
        selectedTags: {
            type: 'array',
            default: [],
        },
    },
};

export default metadata;
