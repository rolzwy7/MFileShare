import React from "react";

export default class ObjectDetails extends React.Component {
  constructor(props) {
    super(props);
    this.state = {};
  }

  componentDidMount() {}

  render() {
    const {
      type,
      created_at,
      updated_at,
      filename,
      mime_type,
      is_shared,
      text,
    } = this.props;

    if (type === undefined) {
      return <p>No object selected</p>;
    }

    if (type === "DIR") {
      return (
        <div className="table-responsive">
          <table className="table table-sm-custom">
            <tbody>
              {/* <tr>
                <td>
                  <b>Is Shared</b>
                </td>
                <td>{is_shared ? "Yes" : "No"}</td>
              </tr> */}
              <tr>
                <td>
                  <b>Dirname</b>
                </td>
                <td>{text}</td>
              </tr>
              <tr>
                <td>
                  <b>Type</b>
                </td>
                <td>Directory</td>
              </tr>
              {/* <tr>
                <td>
                  <b>Updated</b>
                </td>
                <td>{updated_at}</td>
              </tr> */}
              <tr>
                <td>
                  <b>Created</b>
                </td>
                <td>{created_at}</td>
              </tr>
            </tbody>
          </table>
        </div>
      );
    }

    return (
      <div className="table-responsive">
        <table className="table table-sm-custom">
          <tbody>
            {/* <tr>
              <td>
                <b>Is Shared</b>
              </td>
              <td>{is_shared ? "Yes" : "No"}</td>
            </tr> */}
            <tr>
              <td>
                <b>Filename</b>
              </td>
              <td>{text}</td>
            </tr>
            <tr>
              <td>
                <b>Type</b>
              </td>
              <td>File</td>
            </tr>
            <tr>
              <td>
                <b>MIME Type</b>
              </td>
              <td>{mime_type}</td>
            </tr>

            <tr>
              <td>
                <b>Original name</b>
              </td>
              <td>{filename}</td>
            </tr>
            {/* <tr>
              <td>
                <b>Updated</b>
              </td>
              <td>{updated_at}</td>
            </tr> */}
            <tr>
              <td>
                <b>Created</b>
              </td>
              <td>{created_at}</td>
            </tr>
          </tbody>
        </table>
      </div>
    );
  }
}
