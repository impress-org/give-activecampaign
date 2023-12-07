import {__} from '@wordpress/i18n';
import {BlockEditProps} from '@wordpress/blocks';
import {InspectorControls} from '@wordpress/block-editor';
import {PanelBody, CheckboxControl, SelectControl, TextControl, ToggleControl} from '@wordpress/components';

declare const window: {
    GiveActiveCampaign: {
            lists?: Array<{ id: string; name: string }>,
            tags?: Array<{ id: string; name: string }>
        }
} & Window

const listOptions = window.GiveActiveCampaign.lists;
const tagOptions = window.GiveActiveCampaign.tags;

/**
 * @unreleased
 */
export default function Edit({attributes, setAttributes}: BlockEditProps<any>) {
    const {defaultChecked, label, selectedLists, selectedTags} = attributes;
    // @ts-ignore
    return (
        <>
            <div className={'givewp-activecampaign-block-placeholder'}>
                <CheckboxControl checked={defaultChecked} label={label} onChange={null} disabled={true} />
            </div>
            <InspectorControls>
                <PanelBody title={__('Field Settings', 'give-activecampaign')} initialOpen={true}>
                    <div className={'givewp-activecampaign-controls'}>
                        <TextControl
                            label={__('Custom Label', 'give-activecampaign')}
                            value={label}
                            help={__('Customize the label for the ActiveCampaign opt-in checkbox', 'give-activecampaign')}
                            onChange={(value) => setAttributes({label: value})}
                        />

                        <ToggleControl
                            label={__('Opt-in Default', 'give-activecampaign')}
                            checked={defaultChecked}
                            onChange={() => setAttributes({defaultChecked: !defaultChecked})}
                            help={__(
                                'Customize the newsletter opt-in option for this form.',
                                'give-activecampaign'
                            )}
                        />

                        <SelectControl
                            multiple={true}
                            label={__('Lists', 'give-activecampaign')}
                            value={selectedLists}
                            onChange={(value) => setAttributes({selectedLists: value})}
                            // @ts-ignore
                            options={listOptions}
                        />

                        <SelectControl
                            multiple={true}
                            label={__('Tags', 'give-activecampaign')}
                            value={selectedTags}
                            onChange={(value) => setAttributes({selectedTags: value})}
                            // @ts-ignore
                            options={tagOptions}
                        />
                    </div>
                </PanelBody>
            </InspectorControls>
        </>
    );
}
