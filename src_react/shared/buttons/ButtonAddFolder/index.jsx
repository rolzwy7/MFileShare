import React from "react";

import api from "../../../api/index.js";
import alerts from "../../../helpers/alerts.js";

export default class ButtonAddFolder extends React.Component {
  constructor(props) {
    super(props);
    this.state = {};
  }

  componentDidMount() {}

  render() {
    return (
      <button
        className="btn btn-secondary btn-sm rounded-0"
        onClick={(e) => {
          // var dirname = prompt("Enter new folder name:", "New Folder");

          alerts
            .ShowInputAlert(
              "Add Folder",
              "text",
              "Enter new folder name:",
              "ex. NewFolder"
            )
            .then((dirname) => {
              console.log(dirname);
              if (dirname) {
                api.createFolder(dirname).then(
                  (result) => {
                    console.log(result);
                    this.props.callbackSuccess();
                    alerts.ToastAlert("success", "Folder created successfully");
                  },
                  (reason) => {
                    console.log(reason);
                  }
                );
              } else {
                alerts.ToastAlert(
                  "info",
                  "No action taken. Directory name wasn't provided"
                );
              }
            });
        }}
      >
        <i className="jstree-icon-size fas fa-folder-plus"></i>
        <span className="msfp-button-font-size">Add folder</span>
      </button>
    );
  }
}
