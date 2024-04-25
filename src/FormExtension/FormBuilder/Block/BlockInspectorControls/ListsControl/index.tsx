import { BaseControl, CheckboxControl } from "@wordpress/components";
import { __ } from "@wordpress/i18n";
import { Fragment, useEffect } from "react";
import { lists } from "../../window";

import "./styles.scss";

type ListControlProps = {
  id: string;
  onChange: (values: string[]) => void;
  lists: lists[];
  selectedLists: selectedLists;
};

type selectedLists = string[];

export default function ListsControl({
  id,
  onChange,
  lists,
  selectedLists,
}: ListControlProps) {
  useEffect(() => {
    if (selectedLists.length === 0 && lists.length > 0) {
      const minListsRequired = [lists[0].id];
      onChange(minListsRequired);
    }
  }, [selectedLists, lists]);

  const handleListSelection = (isChecked: boolean, id: string) => {
    let updatedList: string[];
    if (isChecked) {
      updatedList = [...selectedLists, id];
    } else {
      updatedList = selectedLists.filter((list) => list !== id);
    }

    // Ensure at least one checkbox is checked
    if (updatedList.length === 0 && lists.length > 0) {
      updatedList.push(lists[0].id);
    }

    onChange(updatedList);
  };

  return (
    <BaseControl
      id={id}
      className={"givewp-activecampaign-lists-control"}
      help={
        lists
          ? __(
              "Customize the list(s) you wish donors to subscribe to if they opt-in.",
              "give-activecampaign"
            )
          : __(
              "We were unable to find any email list's for your account. Please visit Constant Contact and verify you have created at least one mailing list to use this block.",
              "give-activecampaign"
            )
      }
      label={__("List opt-in", "give-activecampaign")}
    >
      {lists &&
        lists.map(({ id, name }) => (
          <Fragment key={id}>
            <ListCheckboxControl
              id={id}
              name={name}
              checked={selectedLists.includes(id)}
              handleListSelection={handleListSelection}
            />
          </Fragment>
        ))}
    </BaseControl>
  );
}

type ListCheckboxProps = {
  id: string;
  name: string;
  checked: boolean;
  handleListSelection: (isChecked: boolean, id: string) => void;
};

function ListCheckboxControl({
  name,
  id,
  checked,
  handleListSelection,
}: ListCheckboxProps) {
  const handleChange = () => {
    handleListSelection(!checked, id);
  };

  return (
    <CheckboxControl
      checked={checked}
      id={id}
      label={name}
      onChange={handleChange}
    />
  );
}
