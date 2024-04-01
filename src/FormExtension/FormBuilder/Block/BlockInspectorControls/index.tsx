import { PanelBody, TextControl, ToggleControl } from "@wordpress/components";
import { __ } from "@wordpress/i18n";
import { BlockNotice } from "@givewp/form-builder-library";
import { InspectorControls } from "@wordpress/block-editor";
import { getWindowData } from "../window";
import TagControls from "./TagsControl";
import ListsControl from "./ListsControl";

export default function BlockInspectorControls({ attributes, setAttributes }) {
  const { selectedLists, selectedTags, defaultChecked, label } = attributes;

  const { settingsUrl, requiresSetup } = getWindowData();
  const { lists, tags } = getWindowData();

  return (
    <InspectorControls>
      <PanelBody
        title={__("Field Settings", "give-activecampaign")}
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

            <ListsControl
              id={"givewp-activecampaign-tag-controls"}
              onChange={(values) =>
                setAttributes({ selectedEmailLists: values })
              }
              lists={lists}
              selectedLists={selectedLists}
            />

            <TagControls
              id={"givewp-activecampaign-controls-tags"}
              help={__(
                "These tags will be applied to Subscribers based on the form they used to sign up.",
                "give-activecampaign"
              )}
              label={__("Subscriber Tags", "give-mailchimp")}
              onChange={(tag) => setAttributes({ selectedTags: tag })}
              tagOptions={tags}
              selectedTags={selectedTags}
            />
          </div>
        )}
      </PanelBody>
    </InspectorControls>
  );
}
