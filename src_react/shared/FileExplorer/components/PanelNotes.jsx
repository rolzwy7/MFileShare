import React from "react";
import api from "../../../api";
import alerts from "../../../helpers/alerts.js";

export default class PanelNotes extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      notes: [],
      loading: false,
    };

    this.loadNotes = this.loadNotes.bind(this);
    this.deleteNote = this.deleteNote.bind(this);
    this.addNote = this.addNote.bind(this);
  }

  addNote() {
    this.setState({ ...this.state, loading: true });
    alerts
      .ShowInputAlert(
        "Add Note",
        "textarea",
        "Enter new note text:",
        "ex. This is my note"
      )
      .then((note_text) => {
        console.log(note_text);
        if (note_text) {
          api.createNote(this.props.id, note_text).then((result) => {
            console.log(result.data);
            this.loadNotes();
          });
        } else {
          alerts.ToastAlert("info", "No action taken.");
        }
        this.setState({ ...this.state, loading: false });
      });
  }

  loadNotes() {
    console.log("[PanelNotes] loading notes with", this.props.id);
    this.setState({ ...this.state, loading: true });
    api.listNotesForUpload(this.props.id).then((result) => {
      console.log("[PanelNotes] Loaded notes", result.data);
      this.setState({ ...this.state, notes: result.data, loading: false });
    });
  }

  deleteNote(id) {
    alerts.ShowDeletionConfirmationAlert(1).then((result) => {
      console.log(result);
      if (result.isConfirmed) {
        api.deleteNote(id).then((result) => {
          console.log(result);
          this.loadNotes();
        });
      } else {
        alerts.ToastAlert("info", "No action taken");
      }
    });
  }

  componentDidMount() {
    console.log("[PanelNotes] mounted");
  }

  componentDidUpdate(prevProps) {
    if (this.props.id !== prevProps.id) {
      this.loadNotes();
    }
  }

  render() {
    const { notes, loading } = this.state;
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
        <span
          className={`cursor-pointer custom-badge rounded-5 badge ${
            loading ? "bg-secondary" : "bg-success"
          }`}
          onClick={(e) => {
            if (!loading) {
              this.addNote();
            }
          }}
        >
          Add note
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

        {notes.map((note, idx) => (
          <div
            key={`note-${idx}`}
            className="p-3 rounded text-dark mt-3"
            style={{
              position: "relative",
              backgroundColor: "#fff7d1",
            }}
          >
            {note.text}
            <br />~ {note.fullname}
            <div
              style={{
                position: "absolute",
                right: "12px",
                top: "4px",
              }}
            >
              <i
                className="far fa-trash-alt text-danger"
                style={{ cursor: "pointer" }}
                onClick={(e) => {
                  this.deleteNote(note.id);
                }}
              ></i>
            </div>
          </div>
        ))}
      </div>
    );
  }
}
