/**
 * @unreleased
 */

type windowData = {
  requiresSetup: boolean;
  settingsUrl: string;
  lists: [],
  tags: []
};

declare const window: {
  GiveActiveCampaign: windowData;
} & Window;

export function getWindowData(): windowData {
  return window.GiveActiveCampaign;
}
