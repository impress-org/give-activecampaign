import { __ } from "@wordpress/i18n";
import { BlockEditProps } from "@wordpress/blocks";
import { InspectorControls } from "@wordpress/block-editor";
import {
  PanelBody,
  SelectControl,
  TextControl,
  ToggleControl,
} from "@wordpress/components";
import BlockPlaceholder from "./BlockPlaceholder";
import { getWindowData } from "./window";
import { BlockNotice } from "@givewp/form-builder-library";

const { lists, tags } = getWindowData();

const formsListOptions = lists.map(function ({ id, name }) {
  return { value: id, label: name };
});
const tagsListOptions = tags.map(function ({ id, name }) {
  return { value: id, label: name };
});

/**
 * @unreleased
 */
export default function Edit({
  attributes,
  setAttributes,
}: BlockEditProps<any>) {
  const { defaultChecked, label, selectedLists, selectedTags } = attributes;
  const { settingsUrl, requiresSetup } = getWindowData();

  // @ts-ignore
  return (
    <>
      <BlockPlaceholder {...{ defaultChecked, label }} />
      <InspectorControls>
        <PanelBody
          title={__("Field Settings", "give-convertkit")}
          initialOpen={true}
        >
          {requiresSetup ? (
            <BlockNotice
              title={__("ActiveCampaign requires setup", "give")}
              description={__(
                "This block requires your settings to be configured in order to use.",
                "give"
              )}
              anchorText={__("Connect your ActiveCampaign account", "give")}
              href={settingsUrl}
            />
          ) : (
            <div className={"givewp-activecampaign-controls"}>
              <TextControl
                label={__("Custom Label", "give-activecampaign")}
                value={label}
                help={__(
                  "Customize the label for the ActiveCampaign opt-in checkbox",
                  "give-activecampaign"
                )}
                onChange={(value) => setAttributes({ label: value })}
              />

              <ToggleControl
                label={__("Opt-in Default", "give-activecampaign")}
                checked={defaultChecked}
                onChange={() =>
                  setAttributes({ defaultChecked: !defaultChecked })
                }
                help={__(
                  "Customize the newsletter opt-in option for this form.",
                  "give-activecampaign"
                )}
              />

              <SelectControl
                multiple={true}
                label={__("Lists", "give-activecampaign")}
                value={selectedLists}
                onChange={(value) => setAttributes({ selectedLists: value })}
                // @ts-ignore
                options={formsListOptions}
              />

              <SelectControl
                multiple={true}
                label={__("Tags", "give-activecampaign")}
                value={selectedTags}
                onChange={(value) => setAttributes({ selectedTags: value })}
                // @ts-ignore
                options={tagsListOptions}
              />
            </div>
          )}
        </PanelBody>
      </InspectorControls>
    </>
  );
}
