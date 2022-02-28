import React from "react";

import api from "../../api";

import Dropzone from "react-dropzone";

export default class CustomFileDropzone extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      files_loaded: false,
      files: [],
      files_statuses: [],
      hovering: false,
      uploading: false,
      handled_so_far: 0,
    };

    this.upload = this.upload.bind(this);
  }

  componentDidMount() {}

  upload() {
    this.setState({ ...this.state, uploading: true });
    const { files, files_statuses } = this.state;

    files.map((file, idx) => {
      console.log(`Trying to upload file idx := ${idx}`);
      //
      var formData = new FormData();
      formData.append("file", file);
      //
      var files_statuses_copy = Object.assign({}, files_statuses);
      //
      files_statuses_copy[idx][0] = "Uploading";
      files_statuses_copy[idx][1] = "File is being uploaded";
      files_statuses_copy[idx][2] = "table-light";
      this.setState({
        ...this.state,
        files_statuses: files_statuses_copy,
      });
      api.uploadFile(formData).then(
        (result) => {
          const { success } = result.data;
          if (success) {
            console.log(result);
            files_statuses_copy[idx][0] = "Success";
            files_statuses_copy[idx][1] = "File uploaded successfully";
            files_statuses_copy[idx][2] = "table-success";
            this.setState({
              ...this.state,
              files_statuses: files_statuses_copy,
              handled_so_far: this.state.handled_so_far + 1,
            });
          } else {
            files_statuses_copy[idx][0] = "Error";
            files_statuses_copy[idx][1] = result.data.error.en;
            files_statuses_copy[idx][2] = "table-danger";
            this.setState({
              ...this.state,
              files_statuses: files_statuses_copy,
              handled_so_far: this.state.handled_so_far + 1,
            });
          }
        },
        (reason) => {
          console.log(reason);
          files_statuses_copy[idx][0] = "API Error";
          files_statuses_copy[idx][1] = "Error reason here";
          files_statuses_copy[idx][2] = "table-danger";
          this.setState({
            ...this.state,
            files_statuses: files_statuses_copy,
            handled_so_far: this.state.handled_so_far + 1,
          });
        }
      );
    });
  }

  render() {
    const { hovering, files, files_statuses, uploading, handled_so_far } =
      this.state;
    const can_upload = files.length !== 0;

    const progress_value = Math.ceil((100 * handled_so_far) / files.length);

    var dropzoneID = "mfspDropzone";
    if (hovering === true) {
      dropzoneID = "mfspDropzoneHovering";
    }
    if (can_upload) {
      dropzoneID = "mfspDropzoneHoveringDisabled";
    }

    return (
      <div className="container">
        <div className="row">
          <div className="col-md-12">
            {can_upload ? (
              <div className="d-flex justify-content-end">
                {handled_so_far === files.length ? (
                  <div style={{ paddingLeft: "0.5rem" }}>
                    <button
                      className="btn btn-secondary rounded-0"
                      onClick={(e) => {
                        this.setState({
                          ...this.state,
                          uploading: false,
                          handled_so_far: 0,
                          files: [],
                          files_statuses: [],
                        });
                      }}
                    >
                      <i className="fas fa-redo"></i>
                      &nbsp;Start uploading again
                    </button>
                  </div>
                ) : (
                  ""
                )}

                {handled_so_far === files.length ? (
                  ""
                ) : (
                  <div style={{ paddingLeft: "0.5rem" }}>
                    <button
                      disabled={!can_upload || uploading}
                      className="btn btn-success rounded-0"
                      onClick={(e) => {
                        this.upload();
                      }}
                    >
                      {uploading ? (
                        <span>
                          {handled_so_far === files.length ? (
                            ""
                          ) : (
                            <i className="fas fa-spinner fa-pulse"></i>
                          )}
                          &nbsp;Uploading&nbsp;({handled_so_far})
                        </span>
                      ) : (
                        <span>
                          <i className="fas fa-cloud-upload-alt"></i>
                          &nbsp;Upload&nbsp;
                          {`${files.length} ${
                            files.length > 1 ? "files" : "file"
                          }`}
                        </span>
                      )}
                    </button>
                  </div>
                )}
                {handled_so_far === files.length ? (
                  ""
                ) : (
                  <div style={{ paddingLeft: "0.5rem" }}>
                    <button
                      className="btn btn-danger rounded-0"
                      disabled={uploading}
                      onClick={(e) => {
                        this.setState({ ...this.state, files: [] });
                      }}
                    >
                      <i className="fas fa-trash-alt"></i>
                      &nbsp;Clear
                    </button>
                  </div>
                )}
              </div>
            ) : (
              ""
            )}
            <div className="d-flex justify-content-center align-items-center">
              <Dropzone
                disabled={can_upload}
                onDrop={(acceptedFiles) => {
                  console.log(acceptedFiles);
                  this.setState({
                    ...this.state,
                    files: acceptedFiles,
                    files_statuses: acceptedFiles.map((file) => {
                      return [
                        "Queued",
                        "This file waits for upload",
                        "table-queue",
                      ];
                    }),
                  });
                }}
                onDragEnter={() => {
                  console.log("Drag enter");
                  this.setState({ ...this.state, hovering: true });
                }}
                onDragLeave={() => {
                  console.log("Drag Leave");
                  this.setState({ ...this.state, hovering: false });
                }}
              >
                {({ getRootProps, getInputProps }) => (
                  <div
                    {...getRootProps()}
                    className={
                      can_upload
                        ? "d-none"
                        : "border rounded-1 p-5 d-flex flex-column justify-content-center align-items-center"
                    }
                    id={dropzoneID}
                    style={{ width: "50%", cursor: "pointer" }}
                  >
                    <input {...getInputProps()} />
                    <div className="p-5">
                      <i
                        className="fas fa-cloud-upload-alt"
                        style={{ fontSize: "4.5rem" }}
                      ></i>
                    </div>
                    <div>
                      <p>
                        Drag &amp; Drop files here, or click to select files
                      </p>
                    </div>
                  </div>
                )}
              </Dropzone>
            </div>
            {can_upload ? (
              <div className="row my-3">
                <div className="col-12">
                  <div class="progress">
                    <div
                      style={{
                        position: "absolute",
                        left: "50%",
                        color: progress_value == 100 ? "white" : "black",
                      }}
                    >
                      {progress_value}%
                    </div>
                    <div
                      class={`progress-bar ${
                        progress_value == 100 ? "bg-success" : ""
                      }`}
                      role="progressbar"
                      style={{
                        width: `${progress_value}%`,
                      }}
                    ></div>
                  </div>
                </div>
              </div>
            ) : (
              ""
            )}

            {can_upload ? (
              <table className="table table-sm">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Filename</th>
                    <th scope="col">Size</th>
                    <th scope="col">Status</th>
                  </tr>
                </thead>
                <tbody>
                  {files.map((file, idx) => (
                    <tr className={files_statuses[idx][2]}>
                      <th scope="row">{idx + 1}</th>
                      <td>
                        <b>{file.name}</b>
                      </td>

                      {Number(file.size / 1024) < 1 ? (
                        <td>{file.size} B</td>
                      ) : Number(file.size / 1024) > 1024 ? (
                        <td>
                          {Math.round(Number(file.size / 1024 / 1024))} MB
                        </td>
                      ) : (
                        <td>{Math.round(Number(file.size / 1024))} KB</td>
                      )}

                      <td>
                        <b>
                          {files_statuses[idx][0] == "Uploading" ? (
                            <span>
                              <i className="fas fa-spinner fa-pulse"></i>&nbsp;
                            </span>
                          ) : (
                            ""
                          )}

                          {files_statuses[idx][0]}
                          {files_statuses[idx][0] == "Uploading" ? (
                            <span>...</span>
                          ) : (
                            ""
                          )}
                        </b>{" "}
                        <br />
                        <p className="m-0">
                          <small>{files_statuses[idx][1]}</small>
                        </p>
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            ) : (
              ""
            )}
          </div>
        </div>
      </div>
    );
  }
}
