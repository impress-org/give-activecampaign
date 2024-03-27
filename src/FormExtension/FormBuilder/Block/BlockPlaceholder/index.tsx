import { CheckboxControl } from "@wordpress/components";
import { __ } from "@wordpress/i18n";
import { getWindowData } from "../window";
import { createInterpolateElement } from "@wordpress/element";
import "./styles.scss";

/**
 * @unreleased
 */
export default function BlockPlaceholder({ defaultChecked, label }) {
  const { requiresSetup, settingsUrl } = getWindowData();

  return (
    <div
      className={`givewp-active-campaign-block-placeholder
			${requiresSetup && "givewp-active-campaign-block-placeholder--invalid"}`}
    >
      {requiresSetup ? (
        createInterpolateElement(
          __(
            "This block requires additional setup. Go to your <a>Settings</a> to connect your ActiveCampaign account.",
            "give"
          ),
          {
            a: (
              <a href={settingsUrl} target="_blank" rel="noopener noreferrer" />
            ),
          }
        )
      ) : (
        <CheckboxControl
          checked={defaultChecked}
          label={label}
          onChange={null}
          disabled={true}
        />
      )}
    </div>
  );
}
