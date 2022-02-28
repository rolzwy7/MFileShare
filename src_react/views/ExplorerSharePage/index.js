import React from "react";
import FileExplorer from "../../shared/FileExplorer";

export default class ExplorerSharePage extends React.Component {
  constructor(props) {
    super(props);
    this.state = {};
  }

  render() {
    return (
      <div className="container-fluid">
        <div className="row">
          <div className="card shadow-sm">
            <div className="card-body">
              <FileExplorer mode="share" />
            </div>
          </div>
        </div>
      </div>
    );
  }
}
