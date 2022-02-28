import React from "react";

export default class ButtonUploadNew extends React.Component {
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
          this.props.callbackSuccess();
        }}
      >
        <i className="jstree-icon-size fas fa-cloud-upload-alt"></i>
        <span className="msfp-button-font-size">Upload new</span>
      </button>
    );
  }
}
