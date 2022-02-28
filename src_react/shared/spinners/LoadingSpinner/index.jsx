import React from "react";

export default class LoadingSpinner extends React.Component {
  constructor(props) {
    super(props);
    this.state = {};
  }

  componentDidMount() {}

  render() {
    const { loading } = this.props;
    return (
      <div>
        {loading ? (
          <div className="fa-2x mt-2">
            <i className="fas fa-spinner fa-pulse"></i> Loading ...
          </div>
        ) : (
          ""
        )}
      </div>
    );
  }
}
