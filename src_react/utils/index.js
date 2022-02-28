/**
 * Parse GET variables to change initial settings
 * @returns {object} GET parameters (key=value) object
 */
export function getUrlParameters() {
  const $_GET = {};
  window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, (a, name, value) => {
    $_GET[name] = value;
  });
  return $_GET;
}
