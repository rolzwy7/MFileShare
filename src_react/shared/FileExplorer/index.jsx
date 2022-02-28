import { saveAs } from "file-saver";
import $ from "jquery";
import React from "react";
import api from "../../api";
import app_consts from "../../consts.js";
import alerts from "../../helpers/alerts.js";
import PanelCard from "../../shared/PanelCard";
import ButtonAddFolder from "../buttons/ButtonAddFolder";
import ButtonDelete from "../buttons/ButtonDelete";
import ButtonFoldAll from "../buttons/ButtonFoldAll";
import ButtonRefresh from "../buttons/ButtonRefresh";
import ButtonUnfoldAll from "../buttons/ButtonUnfoldAll";
import ButtonUploadNew from "../buttons/ButtonUploadNew";
import ShareModal from "../modals/ShareModal";
// Modals
import UploadModal from "../modals/UploadModal";
import LoadingSpinner from "../spinners/LoadingSpinner";
import ObjectDetails from "./components/ObjectDetails.jsx";
import PanelNotes from "./components/PanelNotes.jsx";
import PanelTags from "./components/PanelTags.jsx";

export default class FileExplorer extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      loading: true,
      tree_loaded: false,
      selected_count: 0,
      selected_data: [],
      single_select: {},
      modals: {
        show_upload: false,
        show_share: false,
      },
    };

    this.loadFiles = this.loadFiles.bind(this);
    this.jstreeChanged = this.jstreeChanged.bind(this);
    this.jstreeDropped = this.jstreeDropped.bind(this);
    this.customMenu = this.customMenu.bind(this);
    this.check_callback = this.check_callback.bind(this);

    // All Modals
    this.hideAllModals = this.hideAllModals.bind(this);
    this.showModal = this.showModal.bind(this);
  }

  hideAllModals() {
    this.setState({
      ...this.state,
      modals: {
        show_upload: false,
        show_share: false,
      },
    });
  }

  showModal(modal_name) {
    console.log("*", modal_name);
    switch (modal_name) {
      case "upload":
        console.log("ok");
        this.setState({
          ...this.state,
          modals: {
            show_upload: true,
          },
        });
        break;
      case "share":
        this.setState({
          ...this.state,
          modals: {
            show_share: true,
          },
        });
        break;
    }
  }

  check_callback(operation, node, node_parent, node_position, more) {
    console.log(operation, node, node_parent, node_position, more);
    // console.log(node_parent.id, node.id);
    // if (operation === "move_node") {
    //   return false;
    // }
    return true;
  }
  customMenu(node) {
    var items = {
      renameItem: {
        label: "Rename",
        action: function () {
          console.log("Rename", node);
          console.log("this", this);
          var id = Number(node.id);
          if (node.type.includes("folder")) {
            var current_name = node.text;
          } else {
            var current_name = node.text.split(".").slice(0, -1).join(".");
          }

          alerts
            .ShowInputAlert(
              "Rename Object",
              "text",
              "Enter new name:",
              "ex. NewFolder",
              true,
              current_name
            )
            .then((new_name) => {
              console.log(new_name);
              if (new_name) {
                api.renameObject(id, new_name).then(
                  (result) => {
                    console.log(result);
                    this.loadFiles();
                    alerts.ToastAlert("success", "Object renamed successfully");
                  },
                  (reason) => {
                    console.log(reason);
                  }
                );
              } else {
                alerts.ToastAlert("info", "No action taken");
              }
            });
        }.bind(this),
      },
      makeRoot: {
        label: "Make root",
        action: function () {
          console.log("Make root", node);
          var id = Number(node.id);
          api.makeRoot(id).then(
            (result) => {
              console.log(result);
              this.loadFiles();
            },
            (reason) => {
              console.log(reason);
            }
          );
        }.bind(this),
      },
      downloadItem: {
        label: "Download",
        action: function () {
          console.log("Download", node);
          var id = Number(node.id);
          // window.location = `/wp-json/msfp/v1/downloadSelected/${id}`;
          api
            .downloadFile(`msfp/v1/downloadSelected/${id}`)
            .then(({ data, headers }) => {
              console.log(headers);
              let content_disposition = headers["content-disposition"];
              let content_type = headers["content-type"];
              let filename = content_disposition
                .split("filename=")[1]
                .split('"')[1];

              var blob = new Blob([data], {
                type: `${content_type};charset=utf-8`,
              });
              saveAs(blob, filename);
            });
        }.bind(this),
      },
      shareItem: {
        label: "Share",
        action: function () {
          this.showModal("share");
          console.log("Share", node);
          console.log("single_select", this.state.single_select);
        }.bind(this),
      },
    };
    // if ($(node)[0].type.includes("file")) {
    //   delete items.shareItem;
    // }
    if (this.props.mode !== "admin") {
      delete items.shareItem;
      // delete items.makeRoot;
    }
    return items;
  }

  loadFiles() {
    this.setState({ loading: true });
    console.log("[DEBUG] Loading files");
    api.listFiles().then(
      (response) => {
        console.log(response);
        console.dir(response.data);
        $("#jstree_root").jstree(true).settings.core.data = response.data;
        $("#jstree_root").jstree(true).refresh();
        this.setState({ loading: false });
      },
      (reason) => {
        console.log(reason);
        this.setState({ loading: false });
      }
    );
  }

  jstreeChanged(e, data) {
    // Single Select
    if (data.selected.length == 1) {
      console.log("On Single Click :", data.selected);
      var file_id = Number(data.selected);
      if (data.node.type.includes("file")) {
        console.log("File");
        api.getUploadedFile(file_id).then(
          (result) => {
            console.log(result.data);
            this.setState({ ...this.state, single_select: result.data });
          },
          (reason) => {
            console.log(reason);
          }
        );
      } else {
        console.log("Folder");
        api.getFolder(file_id).then(
          (result) => {
            console.log(result.data);
            this.setState({ ...this.state, single_select: result.data });
          },
          (reason) => {
            console.log(reason);
          }
        );
      }
    }
    // Multi Select
    if (data.selected.length > 1) {
      console.log("Multi Select");
    }
    this.setState({
      ...this.state,
      selected_count: data.selected.length,
      selected_data: data.selected,
    });
  }

  jstreeDropped(e, data) {
    console.log("dnd_stop.vakata", data);
    var nodes = data.data.nodes;
    var sourceIDs = nodes.map((e) => {
      return Number(e);
    });
    var destinationID = Number(data.event.target.id.split("_")[0]);
    if (destinationID === 0) {
      destinationID = Number(data.event.target.parentElement.id.split("_")[0]);
    }
    console.log("Source IDs", sourceIDs);
    console.log("Destination ID", destinationID);
    api.moveObjects(sourceIDs, destinationID).then(
      (result) => {
        console.log(result);
        this.loadFiles();
      },
      (reason) => {
        console.log(reason);
        alerts.ToastAlert("error", `${reason.response.data.message}`);
        // alert(`${reason.response.statusText}: ${reason.response.data.message}`);
        this.loadFiles();
      }
    );
  }

  componentDidMount() {
    $("#jstree_root").jstree({
      contextmenu: { items: this.customMenu },
      types: app_consts.JSTREE_TYPES,
      plugins: ["types", "contextmenu", "dnd", "sort"],
      sort: function (a, b) {
        let a1 = this.get_node(a);
        let b1 = this.get_node(b);
        if (a1.icon == b1.icon) {
          return a1.text > b1.text ? 1 : -1;
        } else {
          return a1.icon > b1.icon ? 1 : -1;
        }
      },
      core: {
        check_callback: this.check_callback,
        // check_callback: true,
        data: [],
      },
    });
    $("#jstree_root").on("changed.jstree", this.jstreeChanged);
    $(document).on("dnd_stop.vakata", this.jstreeDropped);
    this.loadFiles();
  }

  render() {
    const { single_select, loading, selected_count, selected_data, modals } =
      this.state;
    const { mode } = this.props;
    return (
      <div>
        {/* Modals */}
        {modals.show_upload ? (
          <UploadModal
            hideAllModals={this.hideAllModals}
            loadFiles={this.loadFiles}
          />
        ) : (
          <></>
        )}
        {modals.show_share ? (
          <ShareModal
            hideAllModals={this.hideAllModals}
            fsObjectId={single_select.id}
          />
        ) : (
          <></>
        )}

        {/* Main container */}
        <div className="container">
          <div className="row">
            <div className="col-md-6">
              {/* <h4 className="mt-4">
                <i className="fas fa-project-diagram"></i>
                &nbsp;
              </h4> */}

              <h1 className="d-flex align-items-center my-1">
                <span className="text-dark fw-bolder fs-1">
                  File structure {mode === "admin" ? "(Admin)" : "(Share)"}
                </span>
              </h1>

              <LoadingSpinner loading={loading} />

              {loading ? (
                ""
              ) : (
                <div style={{ borderBottom: "1px solid lightgrey" }}>
                  <div className="mt-2">
                    <ButtonRefresh callbackSuccess={this.loadFiles} />
                    <ButtonAddFolder callbackSuccess={this.loadFiles} />
                    <ButtonUploadNew
                      callbackSuccess={() => {
                        this.showModal("upload");
                      }}
                    />

                    <button
                      className="btn btn-light-danger text-hover-white btn-sm rounded-0 text-danger"
                      style={{
                        display: selected_count === 0 ? "none" : "inline-block",
                      }}
                      onClick={(e) => {
                        $("#jstree_root").jstree().deselect_all(true);
                        this.setState({
                          ...this.state,
                          selected_count: 0,
                          single_select: {},
                        });
                      }}
                    >
                      <i className="jstree-icon-size fas fa-times"></i>
                      <span className="msfp-button-font-size">
                        Unselect ( {selected_count} )
                      </span>
                    </button>
                  </div>
                  <div className="mb-2">
                    <ButtonFoldAll />
                    <ButtonUnfoldAll />
                    <ButtonDelete
                      selected_count={selected_count}
                      selected_data={selected_data}
                      loadFilesFunc={this.loadFiles}
                    />
                  </div>
                </div>
              )}
              <div
                id="jstree_root"
                style={{ display: !loading ? "block" : "none" }}
              ></div>
            </div>
            <div className="col-md-6">
              <PanelCard
                title="Object information"
                icon_classname="fas fa-info-circle"
              >
                <ObjectDetails {...single_select} />
              </PanelCard>
              <PanelCard title="Tags" icon_classname="fas fa-tags">
                <PanelTags {...single_select} />
              </PanelCard>
              <PanelCard title="Notes" icon_classname="fas fa-sticky-note">
                <PanelNotes {...single_select} />
              </PanelCard>
              {/* {single_select.type == "DIR" || single_select.type == "FILE" ? (
                <div className="row my-2">
                  <div className="col-md-12">
                    <ButtonShare />
                  </div>
                </div>
              ) : (
                ""
              )} */}

              {/* <div className="row my-2">
                <div className="col-md-12">
                  <PanelTags {...single_select} />
                  
                  <PanelNotes {...single_select} />
                </div>
              </div> */}
            </div>
          </div>
        </div>
      </div>
    );
  }
}
