import React from "react";
import api from "../../../api";
import alerts from "../../../helpers/alerts.js";

export default class PanelTags extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      tags: [],
      loading: false,
    };

    this.loadTags = this.loadTags.bind(this);
    this.deleteTag = this.deleteTag.bind(this);
    this.addTag = this.addTag.bind(this);
  }

  addTag() {
    this.setState({ ...this.state, loading: true });
    alerts
      .ShowInputAlert("Add Tag", "text", "Enter new tag name:", "ex. MyTag")
      .then((tag_text) => {
        console.log(tag_text);
        if (tag_text) {
          api.createTag(this.props.id, tag_text).then((result) => {
            console.log(result.data);
            this.loadTags();
          });
        } else {
          alerts.ToastAlert("info", "No action taken.");
        }
        this.setState({ ...this.state, loading: false });
      });
  }

  loadTags() {
    console.log("[PanelTags] loading tags with", this.props.id);
    this.setState({ ...this.state, loading: true });
    api.listTagsForUpload(this.props.id).then((result) => {
      console.log("[PanelTags] Loaded tags", result.data);
      this.setState({ ...this.state, tags: result.data, loading: false });
    });
  }

  deleteTag(id) {
    api.deleteTag(id).then((result) => {
      console.log(result);
      this.loadTags();
    });
  }

  componentDidMount() {
    console.log("[PanelTags] mounted");
  }

  componentDidUpdate(prevProps) {
    if (this.props.id !== prevProps.id) {
      this.loadTags();
    }
  }

  render() {
    const { tags, loading } = this.state;
    const {
      id,
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
    return (
      <div>
        {tags.map((tag, idx) => (
          <span
            className="custom-badge rounded-5 badge bg-info"
            id={idx}
            key={`tag-${idx}`}
          >
            {tag.text}
            <span
              className="badge badge-button bg-info"
              onClick={(e) => {
                this.deleteTag(tag.id);
              }}
            >
              <i className="fas fa-times text-white"></i>
            </span>
          </span>
        ))}
        <span
          className={`cursor-pointer custom-badge rounded-5 badge ${
            loading ? "bg-secondary" : "bg-success"
          }`}
          onClick={(e) => {
            if (!loading) {
              this.addTag();
            }
          }}
        >
          Add tag
          <span
            className={`badge badge-button ${
              loading ? "bg-secondary" : "bg-success"
            }`}
          >
            {loading ? (
              <i className="fas fa-spinner fa-pulse text-black"></i>
            ) : (
              <i className="fas fa-plus text-white"></i>
            )}
          </span>
        </span>
      </div>
    );
  }
}
