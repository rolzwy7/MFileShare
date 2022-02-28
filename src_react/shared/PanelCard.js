import React from "react";

const PanelCard = ({ children, icon_classname, title }) => {
  return (
    <div
      className="card bg-secondary shadow-sm w-100 m-0 mb-2 py-0"
      style={{ maxWidth: "100%" }}
    >
      <div className="card-header">
        <h3 className="card-title">
          <i className={icon_classname} style={{ fontSize: "1.2rem" }}></i>
          &nbsp;{title}
        </h3>
      </div>
      <div className="card-body py-4">{children}</div>
    </div>
  );
};

export default PanelCard;
