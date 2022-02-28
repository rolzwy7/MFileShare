import React from "react";

import api from "../../../api/index.js";
import alerts from "../../../helpers/alerts.js";

export default class ButtonDelete extends React.Component {
  constructor(props) {
    super(props);
    this.state = {};
  }

  componentDidMount() {}

  render() {
    const { selected_count, loadFilesFunc, selected_data } = this.props;
    return (
      <button
        className="btn btn-danger btn-sm rounded-0"
        style={{
          display: selected_count !== 0 ? "inline-block" : "none",
        }}
        onClick={(e) => {
          //   loadFilesFunc();
          alerts
            .ShowDeletionConfirmationAlert(selected_count)
            .then((result) => {
              console.log(result);
              if (result.isConfirmed) {
                console.log(selected_data);
                api.deleteBatch(selected_data).then(
                  (response) => {
                    console.log(response);
                    loadFilesFunc();
                    alerts.ToastAlert("warning", "Objects deleted.");
                  },
                  (reason) => {
                    console.log(reason);
                  }
                );
              } else {
                alerts.ToastAlert("info", "No action taken");
              }
            });
        }}
      >
        <i className="jstree-icon-size fas fa-trash-alt"></i>
        <span className="msfp-button-font-size">
          Delete ( {selected_count} )
        </span>
      </button>
      //   <button
      //     className="btn btn-sm rounded-0"
      //     onClick={(e) => {
      //       // var dirname = prompt("Enter new folder name:", "New Folder");

      //       alerts
      //         .ShowInputAlert(
      //           "Add Folder",
      //           "text",
      //           "Enter new folder name:",
      //           "ex. NewFolder"
      //         )
      //         .then((dirname) => {
      //           console.log(dirname);
      //           if (dirname) {
      //             api.createFolder(dirname).then(
      //               (result) => {
      //                 console.log(result);
      //                 this.props.callbackSuccess();
      //                 alerts.ToastAlert("success", "Folder created successfully");
      //               },
      //               (reason) => {
      //                 console.log(reason);
      //               }
      //             );
      //           } else {
      //             alerts.ToastAlert(
      //               "info",
      //               "No action taken. Directory name wasn't provided"
      //             );
      //           }
      //         });
      //     }}
      //   >
      //     <i className="fas fa-folder-plus"></i>
      //     &nbsp;Add folder
      //   </button>
    );
  }
}
