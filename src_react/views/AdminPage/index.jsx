import React from "react";
import FileExplorer from "../../shared/FileExplorer";

export default class AdminPage extends React.Component {
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
              {/* <button
                onClick={(e) => {
                  api.testEndpoint().then(({ data }) => {
                    console.log(data);
                  });
                }}
              >
                Test
              </button> */}
              <FileExplorer mode="admin" />
            </div>
          </div>
        </div>
      </div>
    );
  }
}
