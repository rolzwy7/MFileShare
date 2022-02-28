import { axiosApi } from "./config";

const listFiles = () => {
  return new Promise((resolve, reject) => {
    axiosApi.get(`/msfp/v1/upload`, {}).then(resolve, reject);
  });
};

const getUploadedFile = (id) => {
  return new Promise((resolve, reject) => {
    axiosApi.get(`/msfp/v1/upload/${id}`, {}).then(resolve, reject);
  });
};

const getFolder = (id) => {
  return new Promise((resolve, reject) => {
    axiosApi.get(`/msfp/v1/folder/${id}`, {}).then(resolve, reject);
  });
};

const deleteFolder = (id) => {
  return new Promise((resolve, reject) => {
    axiosApi.delete(`/msfp/v1/folder/${id}`, {}).then(resolve, reject);
  });
};

const uploadFile = (formData) => {
  return new Promise((resolve, reject) => {
    axiosApi
      .post(`/msfp/v1/upload`, formData, {
        headers: { "Content-Type": "multipart/form-data" },
      })
      .then(resolve, reject);
  });
};

const createFolder = (folder_name) => {
  return new Promise((resolve, reject) => {
    axiosApi
      .post(`/msfp/v1/folder`, { name: folder_name })
      .then(resolve, reject);
  });
};

const renameObject = (id, new_name) => {
  return new Promise((resolve, reject) => {
    axiosApi
      .post(`/msfp/v1/rename/${id}`, { new_name: new_name })
      .then(resolve, reject);
  });
};

const moveObjects = (source_ids, destination_id) => {
  return new Promise((resolve, reject) => {
    axiosApi
      .post(`/msfp/v1/move`, {
        source_ids: source_ids,
        destination_id: destination_id,
      })
      .then(resolve, reject);
  });
};

const deleteBatch = (deletion_ids) => {
  return new Promise((resolve, reject) => {
    axiosApi
      .post(`/msfp/v1/deleteBatch`, {
        deletion_ids: deletion_ids,
      })
      .then(resolve, reject);
  });
};

const makeRoot = (id) => {
  return new Promise((resolve, reject) => {
    axiosApi
      .post(`/msfp/v1/makeroot`, {
        id: id,
      })
      .then(resolve, reject);
  });
};

const createTag = (fs_object, text) => {
  return new Promise((resolve, reject) => {
    axiosApi
      .post(`/msfp/v1/tag`, { fs_object: fs_object, text: text })
      .then(resolve, reject);
  });
};

const deleteTag = (id) => {
  return new Promise((resolve, reject) => {
    axiosApi.delete(`/msfp/v1/tag/${id}`, {}).then(resolve, reject);
  });
};

const listTagsForUpload = (id) => {
  return new Promise((resolve, reject) => {
    axiosApi.get(`/msfp/v1/upload/${id}/tags`, {}).then(resolve, reject);
  });
};

const createNote = (fs_object, text) => {
  return new Promise((resolve, reject) => {
    axiosApi
      .post(`/msfp/v1/notes`, { fs_object: fs_object, text: text })
      .then(resolve, reject);
  });
};

const listNotesForUpload = (id) => {
  return new Promise((resolve, reject) => {
    axiosApi.get(`/msfp/v1/upload/${id}/notes`).then(resolve, reject);
  });
};

const deleteNote = (id) => {
  return new Promise((resolve, reject) => {
    axiosApi.delete(`/msfp/v1/notes/${id}`, {}).then(resolve, reject);
  });
};

const getSharing = (id) => {
  return new Promise((resolve, reject) => {
    axiosApi.get(`/msfp/v1/sharing/${id}`).then(resolve, reject);
  });
};

const updateSharing = (
  id,
  is_sharing,
  max_num_of_downloads,
  passphrase,
  email_text
) => {
  return new Promise((resolve, reject) => {
    axiosApi
      .patch(`/msfp/v1/sharing/${id}`, {
        is_sharing,
        max_num_of_downloads,
        passphrase,
        email_text,
      })
      .then(resolve, reject);
  });
};

const listSharingUsers = (id) => {
  return new Promise((resolve, reject) => {
    axiosApi.get(`/msfp/v1/sharing/${id}/users`).then(resolve, reject);
  });
};

const createSharingUser = (id, email) => {
  return new Promise((resolve, reject) => {
    axiosApi
      .post(`/msfp/v1/sharing/${id}/users`, { email })
      .then(resolve, reject);
  });
};

const deleteSharingUser = (sharing_object, id) => {
  return new Promise((resolve, reject) => {
    axiosApi
      .delete(`/msfp/v1/sharing/${sharing_object}/users/${id}`)
      .then(resolve, reject);
  });
};

const actionSetExpireSharing = (sharing_object, expires) => {
  return new Promise((resolve, reject) => {
    axiosApi
      .post(`/msfp/v1/sharing/${sharing_object}/set-expire`, { expires })
      .then(resolve, reject);
  });
};

const actionUnsetExpireSharing = (sharing_object) => {
  return new Promise((resolve, reject) => {
    axiosApi
      .post(`/msfp/v1/sharing/${sharing_object}/unset-expire`)
      .then(resolve, reject);
  });
};

const actionSharingUserToggleSharing = (sharing_object, id) => {
  return new Promise((resolve, reject) => {
    axiosApi
      .post(`/msfp/v1/sharing/${sharing_object}/users/${id}/toggle-sharing`)
      .then(resolve, reject);
  });
};

const actionSharingUserSendEmail = (sharing_object, id) => {
  return new Promise((resolve, reject) => {
    axiosApi
      .post(`/msfp/v1/sharing/${sharing_object}/users/${id}/send-email`)
      .then(resolve, reject);
  });
};

const downloadFile = (url) => {
  // return new Promise((resolve, reject) => {
  //   axiosApi.get(path).then(resolve, reject);
  // });
  return new Promise((resolve, reject) => {
    axiosApi({
      method: "get",
      url: url,
      responseType: "blob",
    }).then(resolve, reject);
  });
};

const testEndpoint = () => {
  return new Promise((resolve, reject) => {
    axiosApi.get(`/msfp/v1/test`).then(resolve, reject);
  });
};

const sharingSendBulkEmails = (sharing_object) => {
  return new Promise((resolve, reject) => {
    axiosApi
      .post(`/msfp/v1/sharing/${sharing_object}/send-bulk-emails`)
      .then(resolve, reject);
  });
};

const sharingDeleteProcedure = (sharing_object) => {
  return new Promise((resolve, reject) => {
    axiosApi
      .post(`/msfp/v1/sharing/${sharing_object}/delete-procedure`)
      .then(resolve, reject);
  });
};

const api = {
  listFiles,
  uploadFile,
  createFolder,
  getUploadedFile,
  getFolder,
  renameObject,
  moveObjects,
  deleteBatch,
  makeRoot,
  deleteFolder,
  createTag,
  deleteTag,
  listTagsForUpload,
  createNote,
  listNotesForUpload,
  deleteNote,
  getSharing,
  updateSharing,
  listSharingUsers,
  createSharingUser,
  deleteSharingUser,
  actionSetExpireSharing,
  actionUnsetExpireSharing,
  actionSharingUserToggleSharing,
  actionSharingUserSendEmail,
  downloadFile,
  sharingSendBulkEmails,
  sharingDeleteProcedure,
  testEndpoint,
};

export default api;
