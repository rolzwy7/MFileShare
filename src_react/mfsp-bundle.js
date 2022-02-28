import React from "react";
import { render } from "react-dom";
import "./assets/style.bundle.css";
import AdminPage from "./views/AdminPage";
import ExplorerSharePage from "./views/ExplorerSharePage";
import UploadPage from "./views/UploadPage";

if (document.querySelector("#mfsp_admin") !== null) {
  render(<AdminPage />, document.querySelector("#mfsp_admin"));
}

if (document.querySelector("#mfsp_upload_hook") !== null) {
  render(<UploadPage />, document.querySelector("#mfsp_upload_hook"));
}

if (document.querySelector("#mfsp_clientgui") !== null) {
  render(<ExplorerSharePage />, document.querySelector("#mfsp_clientgui"));
}
