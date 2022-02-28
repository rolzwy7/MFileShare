import { Field, Formik } from "formik";
import React, { useCallback, useEffect, useState } from "react";
import { OverlayTrigger, Tooltip } from "react-bootstrap";
import DatePicker from "react-datepicker";
import "react-datepicker/dist/react-datepicker.css";
import * as Yup from "yup";
import api from "../../../api";
import alerts from "../../../helpers/alerts.js";

const CreateUserButton = ({ callbackRefreshData, shareObj }) => {
  return (
    <OverlayTrigger
      placement={`top`}
      overlay={<Tooltip id={`tooltip-top`}>Add new user</Tooltip>}
    >
      <button
        className="btn btn-sm btn-icon btn-success"
        style={{ borderRadius: "50%", height: "2rem", width: "2rem" }}
        onClick={(e) => {
          alerts
            .ShowInputAlert(
              "Add Sharing User",
              "text",
              "Enter user email:",
              "email@example.com"
            )
            .then((email) => {
              console.log(email);
              if (email) {
                console.log(email);
                api.createSharingUser(shareObj.id, email).then(({ data }) => {
                  console.log(data);
                  if (data.success) {
                    callbackRefreshData();
                  } else {
                    alerts.ToastAlert("warning", data.msg);
                  }
                });
              } else {
                alerts.ToastAlert(
                  "info",
                  "No action taken. Email wasn't provided"
                );
              }
            });
        }}
      >
        <i className="fas fa-plus fs-6"></i>
      </button>
    </OverlayTrigger>
  );
};

const DeleteUserButton = ({ callbackRefreshData, shareObj, userObj }) => {
  return (
    <OverlayTrigger
      placement={`top`}
      overlay={<Tooltip id={`tooltip-top`}>Delete this user</Tooltip>}
    >
      <button
        className="btn btn-sm btn-icon btn-danger rounded-0"
        style={{ marginRight: "0.1rem" }}
        onClick={(e) => {
          alerts.ShowDeletionConfirmationAlert(1).then((result) => {
            console.log(result);
            if (result.isConfirmed) {
              api
                .deleteSharingUser(shareObj.id, userObj.id)
                .then(({ data }) => {
                  console.log(data);
                  callbackRefreshData();
                });
            } else {
              alerts.ToastAlert("info", "No action taken");
            }
          });
        }}
      >
        <i className="far fa-trash-alt fs-4"></i>
      </button>
    </OverlayTrigger>
  );
};

const FollowSharingLinkButton = ({
  callbackRefreshData,
  shareObj,
  userObj,
}) => {
  return (
    <OverlayTrigger
      placement={`top`}
      overlay={
        <Tooltip id={`tooltip-top`}>Follow sharing link in new tab</Tooltip>
      }
    >
      <a
        className="btn btn-sm btn-icon btn-secondary rounded-0"
        style={{ marginRight: "0.1rem" }}
        href={`/mfsp-code/${userObj.secret}`}
        target={"_blank"}
      >
        <i className="far fa-eye fs-4"></i>
      </a>
    </OverlayTrigger>
  );
};

const ToggleSharingButton = ({ callbackRefreshData, shareObj, userObj }) => {
  const [isLoading, setIsLoading] = useState(false);
  return (
    <OverlayTrigger
      placement={`top`}
      overlay={
        <Tooltip id={`tooltip-top`}>Toggle sharing for this user</Tooltip>
      }
    >
      <button
        className="btn btn-sm btn-icon btn-primary rounded-0"
        style={{ marginRight: "0.1rem" }}
        disabled={isLoading}
        onClick={(e) => {
          setIsLoading(true);
          api
            .actionSharingUserToggleSharing(shareObj.id, userObj.id)
            .then(({ data }) => {
              console.log(data);
              setIsLoading(false);
              callbackRefreshData();
            });
        }}
      >
        {isLoading ? (
          <i className="fas fa-spinner fa-pulse"></i>
        ) : (
          <i className="fas fa-share-alt-square fs-4"></i>
        )}
      </button>
    </OverlayTrigger>
  );
};

const SendSingleEmailButton = ({ callbackRefreshData, shareObj, userObj }) => {
  const [isLoading, setIsLoading] = useState(false);
  return (
    <OverlayTrigger
      placement={`top`}
      overlay={
        <Tooltip id={`tooltip-top`}>Send sharing link to this user</Tooltip>
      }
    >
      <button
        className="btn btn-sm btn-icon btn-info rounded-0 mr-2"
        style={{ marginRight: "0.1rem" }}
        disabled={isLoading}
        onClick={(e) => {
          setIsLoading(true);
          api
            .actionSharingUserSendEmail(shareObj.id, userObj.id)
            .then(({ data }) => {
              console.log(data);
              if (data.success) {
                alerts.ToastAlert("success", data.msg);
              } else {
                alerts.ToastAlert("danger", data.msg);
              }
              setIsLoading(false);
              callbackRefreshData();
            });
        }}
      >
        {isLoading ? (
          <i className="fas fa-spinner fa-pulse"></i>
        ) : (
          <i className="far fa-envelope fs-4"></i>
        )}
      </button>
    </OverlayTrigger>
  );
};

const RefreshButton = ({ callbackRefreshData }) => {
  return (
    <OverlayTrigger
      placement={`top`}
      overlay={<Tooltip id={`tooltip-top`}>Refresh users</Tooltip>}
    >
      <button
        className="btn btn-sm"
        onClick={() => {
          callbackRefreshData();
        }}
      >
        <i className="fas fa-sync-alt fs-4"></i>
        Refresh
      </button>
    </OverlayTrigger>
  );
};

const SendBulkEmailsButton = ({ callbackRefreshData, shareObj }) => {
  const [isLoading, setIsLoading] = useState(false);
  return (
    <OverlayTrigger
      placement={`top`}
      overlay={
        <Tooltip id={`tooltip-top`}>
          Send email with sharing URL to all users
        </Tooltip>
      }
    >
      <button
        className="btn btn-sm"
        disabled={isLoading}
        onClick={() => {
          setIsLoading(true);
          alerts.ShowBulkEmailConfirmationAlert().then((result) => {
            if (result.isConfirmed) {
              api.sharingSendBulkEmails(shareObj.id).then(({ data }) => {
                console.log(data);
                alerts.BulkEmailSummary(data.success, data.failure, () => {
                  callbackRefreshData();
                  setIsLoading(false);
                });
              });
            } else {
              alerts.ToastAlert("info", "No action taken");
              setIsLoading(false);
            }
          });
        }}
      >
        {isLoading ? (
          <i className="fas fa-spinner fa-pulse"></i>
        ) : (
          <i className="far fa-envelope fs-4"></i>
        )}
        Send Bulk Emails
      </button>
    </OverlayTrigger>
  );
};

const DeleteSharingButton = ({
  callbackRefreshData,
  shareObj,
  hideAllModals,
}) => {
  const [isLoading, setIsLoading] = useState(false);
  return (
    <OverlayTrigger
      placement={`top`}
      overlay={
        <Tooltip id={`tooltip-top`}>Delete this sharing and all users</Tooltip>
      }
    >
      <button
        className="btn btn-sm text-danger"
        disabled={isLoading}
        onClick={() => {
          setIsLoading(true);

          alerts.ShowDeletionSharingConfirmationAlert().then((result) => {
            if (result.isConfirmed) {
              api.sharingDeleteProcedure(shareObj.id).then(({ data }) => {
                console.log(data);
                setIsLoading(false);
                alerts.ToastAlert("success", "Sharing deleted");
                alerts.SimpleCallback(
                  "Sharing deleted",
                  "Sharing object and all its users have been deleted",
                  "warning",
                  hideAllModals
                );
              });
            } else {
              alerts.ToastAlert("info", "No action taken");
              setIsLoading(false);
            }
          });
        }}
      >
        {isLoading ? (
          <i className="fas fa-spinner fa-pulse text-danger"></i>
        ) : (
          <i className="fas fa-times fs-4 text-danger"></i>
        )}
        <b>Delete sharing</b>
      </button>
    </OverlayTrigger>
  );
};

const LeftPanel = ({ callbackRefreshData, shareObj, isLoadingLeftPanel }) => {
  const validation_schema = Yup.object().shape({
    max_num_of_downloads: Yup.number().required("required"),
    is_sharing: Yup.bool(),
    passphrase: Yup.string(),
    email_text: Yup.string().required("required"),
  });

  function toDBDatestring(date) {
    let year = date.toISOString().split("-")[0];
    let month = date.toISOString().split("-")[1];
    let day = date.toISOString().split("-")[2].split("T")[0];
    let time = date.toISOString().split("T")[1].split(".")[0];
    let ret = `${year}-${month}-${day} ${time}`;
    return ret;
  }

  const [showExpireDate, setShowExpireDate] = useState(
    shareObj.expires ? true : false
  );
  const [startDate, setStartDate] = useState(
    shareObj.expires ? new Date(shareObj.expires) : new Date()
  );
  const [startDateStr, setStartDateStr] = useState(
    shareObj.expires ? shareObj.expires : toDBDatestring(new Date())
  );

  const ExampleCustomTimeInput = ({ date, value, onChange }) => (
    <input
      value={value}
      onChange={(e) => onChange(e.target.value)}
      style={{ border: "solid 1px pink" }}
    />
  );

  const initialValues = {
    max_num_of_downloads: shareObj.max_num_of_downloads,
    is_sharing: shareObj.is_sharing,
    passphrase: shareObj.passphrase,
    email_text: shareObj.text,
  };

  return (
    <div className="card shadow-sm " style={{ maxWidth: "1000px" }}>
      <div className="card-header">
        <h3 className="card-title">
          <i className="fas fa-cog" style={{ fontSize: "1.5rem" }}></i>
          &nbsp;Sharing configuration (ID={shareObj.id})
        </h3>
      </div>
      <div className="card-body">
        {showExpireDate ? (
          <div className="mb-5">
            <DatePicker
              selected={startDate}
              onChange={(date) => {
                console.log("DatePicker onChange");
                setStartDate(date);
                setStartDateStr(toDBDatestring(date));
                api
                  .actionSetExpireSharing(shareObj.id, toDBDatestring(date))
                  .then(({ data }) => {
                    console.log(data);
                    callbackRefreshData();
                  });
              }}
              timeFormat="HH:mm"
              dateFormat="yyyy/MM/dd HH:mm"
              showTimeSelect
              timeIntervals={10}
            />
            <a
              className="btn btn-link btn-color-danger btn-active-color-primary"
              onClick={(e) => {
                alerts
                  .ShowCustomTextDeletionConfirmationAlert(
                    "Are you sure you want to clear expiration date?",
                    "Clear expiration",
                    "Cancel"
                  )
                  .then((result) => {
                    console.log(result);
                    if (result.isConfirmed) {
                      api
                        .actionUnsetExpireSharing(shareObj.id)
                        .then(({ data }) => {
                          console.log(data);
                        });
                      alerts.ToastAlert("success", "Expiration date cleared");
                      setStartDate(new Date());
                      setStartDateStr(toDBDatestring(new Date()));
                      setShowExpireDate(false);
                      callbackRefreshData();
                    } else {
                      alerts.ToastAlert("info", "No action taken");
                    }
                  });
              }}
            >
              Clear expiration
            </a>
          </div>
        ) : (
          <div className="text-center">
            <button
              className="btn btn-secondary"
              type="submit"
              onClick={() => {
                setShowExpireDate(true);
                api
                  .actionSetExpireSharing(
                    shareObj.id,
                    toDBDatestring(new Date())
                  )
                  .then(({ data }) => {
                    console.log(data);
                  });
              }}
            >
              Set Expiration Date&nbsp;
              <i className="far fa-clock"></i>
            </button>
          </div>
        )}

        <div className="separator border-3 my-6"></div>

        <Formik
          validationSchema={validation_schema}
          initialValues={initialValues}
          validate={(values) => {}}
          onSubmit={(values, { setSubmitting }) => {
            const { is_sharing, max_num_of_downloads, passphrase, email_text } =
              values;
            console.log(is_sharing);
            console.log(max_num_of_downloads);
            console.log(passphrase);
            console.log(email_text);
            setTimeout(() => {
              api
                .updateSharing(
                  shareObj.id,
                  is_sharing,
                  max_num_of_downloads,
                  passphrase,
                  email_text
                )
                .then(({ data }) => {
                  setSubmitting(false);
                  callbackRefreshData();
                  alerts.ToastAlert("success", "Saved sharing configuration");
                });
            }, 700);
          }}
        >
          {({
            values,
            errors,
            touched,
            handleChange,
            handleBlur,
            handleSubmit,
            isSubmitting,
            /* and other goodies */
          }) => (
            <form onSubmit={handleSubmit}>
              <div className="mb-5">
                <label>
                  <Field type="checkbox" name="is_sharing" />
                  {`Global sharing for this object is: `}
                  {values.is_sharing ? (
                    <b className="fs-5 text-success">ON</b>
                  ) : (
                    <b className="fs-5 text-danger">OFF</b>
                  )}
                </label>
                {errors.is_sharing && touched.is_sharing && errors.is_sharing}
              </div>
              <div className="mb-5">
                <label htmlFor="max_num_of_downloads">
                  Number of downloads ( default: 0 = Infinite )
                </label>
                <Field
                  className="form-control"
                  type="number"
                  id="max_num_of_downloads"
                  name="max_num_of_downloads"
                />
                {errors.max_num_of_downloads &&
                  touched.max_num_of_downloads &&
                  errors.max_num_of_downloads}
              </div>
              <div className="mb-5">
                <label htmlFor="passphrase">
                  Passphrase&nbsp;<i className="fas fa-key"></i>
                </label>
                <Field
                  className="form-control"
                  type="text"
                  id="passphrase"
                  name="passphrase"
                />
                {errors.passphrase && touched.passphrase && errors.passphrase}
              </div>

              <div className="mb-5">
                <label htmlFor="email_text">Email Text</label>
                <Field
                  className="form-control"
                  type="text"
                  as="textarea"
                  id="email_text"
                  name="email_text"
                />
                {errors.email_text && touched.email_text && errors.email_text}
              </div>

              <div className="text-center">
                <button
                  className={`btn ${
                    isSubmitting ? "btn-secondary" : "btn-primary"
                  }`}
                  type="submit"
                  disabled={isSubmitting}
                >
                  {isSubmitting ? (
                    <i className="fas fa-spinner fa-pulse"></i>
                  ) : (
                    <i className="fas fa-save"></i>
                  )}
                  {isSubmitting ? "Saving ..." : "Save"}
                </button>
              </div>
            </form>
          )}
        </Formik>
      </div>
    </div>
  );
};

const UserItem = ({ callbackRefreshData, shareObj, user }) => {
  const { id, email, num_of_downloads, num_of_sended, is_sharing } = user;
  const { max_num_of_downloads } = shareObj;
  return (
    <tr>
      <td>
        <b>{email}</b>
      </td>
      <td>
        {num_of_sended == 0 ? (
          <i className="far fa-times-circle fs-4 text-danger"></i>
        ) : (
          <i className="far fa-check-circle fs-4 text-success"></i>
        )}
      </td>
      <td>
        {is_sharing ? (
          <i className="far fa-check-circle fs-4 text-success"></i>
        ) : (
          <i className="far fa-times-circle fs-4 text-danger"></i>
        )}
      </td>
      <td>
        <p className="m-0">
          {max_num_of_downloads == 0 ? (
            <>Downloads: unlimited</>
          ) : (
            <>
              Downloads: {num_of_downloads} / {max_num_of_downloads}
              <span className="text-danger">
                {" "}
                <small>
                  {num_of_downloads >= max_num_of_downloads
                    ? "(Exhausted)"
                    : ""}
                </small>
              </span>
            </>
          )}
        </p>
        <p className="m-0">Sended emails: {num_of_sended}</p>
      </td>
      <td>
        <ToggleSharingButton
          callbackRefreshData={callbackRefreshData}
          shareObj={shareObj}
          userObj={user}
        />
        <SendSingleEmailButton
          callbackRefreshData={callbackRefreshData}
          shareObj={shareObj}
          userObj={user}
        />
        <DeleteUserButton
          callbackRefreshData={callbackRefreshData}
          shareObj={shareObj}
          userObj={user}
        />
        <FollowSharingLinkButton
          callbackRefreshData={callbackRefreshData}
          shareObj={shareObj}
          userObj={user}
        />
      </td>
    </tr>
  );
};

const RightPanel = ({
  callbackRefreshData,
  shareObj,
  shareUsers,
  isLoadingRightPanel,
  hideAllModals,
}) => {
  return (
    <div className="card shadow-sm " style={{ maxWidth: "1000px" }}>
      <div className="card-header">
        <h3 className="card-title">
          <i className="fas fa-users" style={{ fontSize: "1.5rem" }}></i>
          &nbsp;Sharing users
        </h3>
        <div className="d-flex">
          <RefreshButton callbackRefreshData={callbackRefreshData} />
          {shareUsers.length !== 0 ? (
            <SendBulkEmailsButton
              callbackRefreshData={callbackRefreshData}
              shareObj={shareObj}
            />
          ) : (
            <></>
          )}
          <DeleteSharingButton
            callbackRefreshData={callbackRefreshData}
            shareObj={shareObj}
            hideAllModals={hideAllModals}
          />
        </div>
      </div>
      <div className="card-body">
        <h5 className="m-0">
          Users:&nbsp;
          <CreateUserButton
            callbackRefreshData={callbackRefreshData}
            shareObj={shareObj}
          />
        </h5>

        {shareUsers.length !== 0 ? (
          <div className="table-responsive">
            {!isLoadingRightPanel ? (
              <table className="table">
                <thead>
                  <tr className="fw-bolder fs-6 text-gray-800">
                    <th>E-mail</th>
                    <th>Sended</th>
                    <th>Sharing</th>
                    <th>Info</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  {shareUsers.map((user, idx) => (
                    <UserItem
                      key={`user-${idx}`}
                      callbackRefreshData={callbackRefreshData}
                      shareObj={shareObj}
                      user={user}
                    />
                  ))}
                </tbody>
              </table>
            ) : (
              <div className="text-center">
                <i className="fas fa-spinner fa-pulse fs-3x"></i>
              </div>
            )}
          </div>
        ) : (
          <p>No users</p>
        )}
      </div>
    </div>
  );
};

const ShareModal = ({ hideAllModals, fsObjectId }) => {
  const [shareObj, setShareObj] = useState(null);
  const [shareUsers, setShareUsers] = useState([]);
  const [isLoadingLeftPanel, setIsLoadingLeftPanel] = useState(false);
  const [isLoadingRightPanel, setIsLoadingRightPanel] = useState(false);

  const refreshData = () => {
    setIsLoadingLeftPanel(true);
    setIsLoadingRightPanel(true);
    api.getSharing(fsObjectId).then(({ data }) => {
      console.log(data);
      setShareObj(data);
      setIsLoadingLeftPanel(false);
    });
    api.listSharingUsers(fsObjectId).then(({ data }) => {
      console.log(data);
      setShareUsers(data);
      setIsLoadingRightPanel(false);
    });
  };

  const callbackRefreshData = useCallback(() => {
    refreshData();
  }, [refreshData]);

  useEffect(() => {
    refreshData();
  }, []);

  return (
    <div className="custom-mfsp-overlay bg-light">
      <div className="container mt-0">
        <div className="row">
          <div className="col-sm-12 col-md-12 text-center">
            <button
              className="btn btn-sm rounded-0 my-5"
              onClick={(e) => {
                hideAllModals();
              }}
            >
              <i className="fas fa-times"></i>
              &nbsp;Exit sharing panel
            </button>
          </div>
          <div className="col-md-12 col-lg-5">
            {shareObj ? (
              <LeftPanel
                callbackRefreshData={callbackRefreshData}
                shareObj={shareObj}
                isLoadingLeftPanel={isLoadingLeftPanel}
              />
            ) : (
              <></>
            )}
          </div>
          <div className="col-md-12 col-lg-7">
            {shareObj ? (
              <RightPanel
                callbackRefreshData={callbackRefreshData}
                shareObj={shareObj}
                shareUsers={shareUsers}
                isLoadingRightPanel={isLoadingRightPanel}
                hideAllModals={hideAllModals}
              />
            ) : (
              <></>
            )}
          </div>
        </div>
      </div>
    </div>
  );
};

export default ShareModal;
