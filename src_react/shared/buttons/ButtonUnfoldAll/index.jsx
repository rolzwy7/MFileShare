import React from "react";

import $ from "jquery";
import jstree from "jstree";

export default class ButtonUnfoldAll extends React.Component {
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
          $("#jstree_root").jstree("open_all");
        }}
      >
        <i className="jstree-icon-size fas fa-chevron-down"></i>
        <span className="msfp-button-font-size">Unfold All</span>
      </button>
    );
  }
}
