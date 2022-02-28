import React from "react";

import CustomFileDropzone from "../../shared/CustomFileDropzone";

export default class SharePage extends React.Component {
  constructor(props) {
    super(props);
    this.state = {};
  }

  render() {
    return (
      <div className="container">
        <div className="row">
          <div className="col-12">
            <h3 className="text-center my-3">Upload files</h3>
            <CustomFileDropzone />
          </div>
        </div>
      </div>
    );
  }
}
