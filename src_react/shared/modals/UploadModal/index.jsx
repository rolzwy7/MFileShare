import React from "react";

import CustomFileDropzone from "../../CustomFileDropzone";

export default class UploadModal extends React.Component {
  constructor(props) {
    super(props);
    this.state = {};
  }

  render() {
    return (
      <div className="custom-mfsp-overlay">
        <div className="container mt-5">
          <div className="row">
            <div className="col-md-12 text-center">
              <button
                className="btn btn-sm rounded-0 my-5"
                onClick={(e) => {
                  console.log("*Exit uploading panel");
                  this.props.hideAllModals();
                  this.props.loadFiles();
                }}
              >
                <i className="fas fa-times"></i>
                &nbsp;Exit uploading panel
              </button>
            </div>
            <div className="col-md-12">
              <CustomFileDropzone />
            </div>
          </div>
        </div>
      </div>
    );
  }
}
