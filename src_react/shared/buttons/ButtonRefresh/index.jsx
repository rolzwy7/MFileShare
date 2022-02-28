import React from "react";

export default class ButtonRefresh extends React.Component {
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
        <i className="jstree-icon-size fas fa-sync-alt"></i>
        <span className="msfp-button-font-size">Refresh</span>
      </button>
    );
  }
}
