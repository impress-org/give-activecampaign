/**
 * @unreleased
 */

type windowData = {
  requiresSetup: boolean;
  settingsUrl: string;
  lists: lists[];
  tags: tag[];
};

declare const window: {
  GiveActiveCampaign: windowData;
} & Window;

export function getWindowData(): windowData {
  return window.GiveActiveCampaign;
}

export type tag = {
  value: string;
  label: string;
};

export type lists = {
  id: string;
  name: string;
};
