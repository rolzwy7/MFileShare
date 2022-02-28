import Swal from "sweetalert2/dist/sweetalert2.js";

function SimpleCallback(title, text, icon, callback) {
  Swal.fire(title, text, icon).then((result) => {
    callback();
  });
}

function BulkEmailSummary(success_array, failure_array, callback) {
  let success_html = "";
  let failure_html = "";

  success_array.forEach((element) => {
    success_html += `<li>${element.email}</li>`;
  });

  failure_array.forEach((element) => {
    failure_html += `<li>${element.email} (${element.msg})</li>`;
  });

  Swal.fire({
    title: "Bulk Send Summary",
    icon: "info",
    html:
      "<b>Successfully sent:</b>, " +
      "<ul>" +
      success_html +
      "</ul>" +
      "<b>Failures:</b>, " +
      "<ul>" +
      failure_html +
      "</ul>",
    showCloseButton: false,
    showCancelButton: false,
    focusConfirm: false,
    confirmButtonText: "OK",
  }).then(callback);
}

function ToastAlert(icon, title, timer = 3000, position = "top-end") {
  const Toast = Swal.mixin({
    toast: true,
    position: position,
    showConfirmButton: false,
    timer: timer,
    timerProgressBar: true,
    didOpen: (toast) => {
      toast.addEventListener("mouseenter", Swal.stopTimer);
      toast.addEventListener("mouseleave", Swal.resumeTimer);
    },
  });

  Toast.fire({
    icon: icon,
    title: title,
  });
}

async function ShowInputAlert(
  title,
  input,
  inputLabel,
  inputPlaceholder,
  showCancelButton = true,
  inputValue = ""
) {
  const { value: dirname } = await Swal.fire({
    title: title,
    input: input,
    inputLabel: inputLabel,
    showCancelButton: showCancelButton,
    inputPlaceholder: inputPlaceholder,
    inputValue: inputValue,
    inputAttributes: {
      autocomplete: "off",
    },
  });
  return dirname;
}

async function ShowDeletionSharingConfirmationAlert() {
  const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
      confirmButton: "btn btn-danger rounded-0",
      cancelButton: "btn btn-secondary rounded-0",
    },
    buttonsStyling: false,
  });

  const result = await swalWithBootstrapButtons.fire({
    title: "Are you sure?",
    html: `Are you sure you want to <b>delete</b> this sharing?<br />You won't be able to revert this!`,
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Yes, delete it!",
    cancelButtonText: "No, cancel!",
    reverseButtons: false,
    focusConfirm: false,
    focusCancel: true,
  });
  return result;
}

async function ShowBulkEmailConfirmationAlert() {
  const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
      confirmButton: "btn btn-primary rounded-0",
      cancelButton: "btn btn-secondary rounded-0",
    },
    buttonsStyling: false,
  });

  const result = await swalWithBootstrapButtons.fire({
    title: "Are you sure?",
    html: `Are you sure you want to <b>bulk send</b> sharing URL to users?`,
    icon: "info",
    showCancelButton: true,
    confirmButtonText: "Yes, send it",
    cancelButtonText: "No, cancel",
    reverseButtons: false,
    focusConfirm: false,
    focusCancel: true,
  });
  return result;
}

async function ShowDeletionConfirmationAlert(deletion_count) {
  const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
      confirmButton: "btn btn-danger rounded-0",
      cancelButton: "btn btn-secondary rounded-0",
    },
    buttonsStyling: false,
  });

  const result = await swalWithBootstrapButtons.fire({
    title: "Are you sure?",
    html: `Are you sure you want to <b>delete ${deletion_count} ${
      deletion_count === 1 ? "object" : "objects"
    }</b>? <br />You won't be able to revert this!`,
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Yes, delete it!",
    cancelButtonText: "No, cancel!",
    reverseButtons: false,
    focusConfirm: false,
    focusCancel: true,
  });
  return result;
}

async function ShowCustomTextDeletionConfirmationAlert(
  html,
  confirmButtonText,
  cancelButtonText
) {
  const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
      confirmButton: "btn btn-danger rounded-0",
      cancelButton: "btn btn-secondary rounded-0",
    },
    buttonsStyling: false,
  });

  const result = await swalWithBootstrapButtons.fire({
    title: "Are you sure?",
    html: html,
    showCancelButton: true,
    confirmButtonText: confirmButtonText,
    cancelButtonText: cancelButtonText,
    reverseButtons: false,
    focusConfirm: false,
    focusCancel: true,
  });
  return result;
}

const alerts = {
  SimpleCallback,
  ToastAlert,
  ShowInputAlert,
  ShowDeletionConfirmationAlert,
  ShowCustomTextDeletionConfirmationAlert,
  ShowDeletionSharingConfirmationAlert,
  ShowBulkEmailConfirmationAlert,
  BulkEmailSummary,
};

export default alerts;
