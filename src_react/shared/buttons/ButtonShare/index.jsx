import React from "react";

export default class ButtonShare extends React.Component {
  constructor(props) {
    super(props);
    this.state = {};
  }

  componentDidMount() {}

  render() {
    return (
      <span
        className={`cursor-pointer custom-badge rounded-0 badge bg-primary`}
        onClick={(e) => {
          this.props.callbackSuccess();
        }}
      >
        Share
        <span className={`badge badge-button bg-primary`}>
          <i className="fas fa-share-alt"></i>
        </span>
      </span>
    );
  }
}
