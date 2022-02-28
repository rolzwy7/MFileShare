import axios from "axios";
import { getUrlParameters } from "../utils";

const consts = {
  API_BASE_URL: MfspApiConfig.api_base_url,
};

const axiosApi = axios.create({
  baseURL: consts.API_BASE_URL,
  //   timeout: 7000,
  //   xsrfCookieName: "csrftoken",
  //   xsrfHeaderName: "X-WP-Nonce",
  headers: { "X-WP-Nonce": MfspApiConfig.nonce },
  //   withCredentials: true,
  //   params: {
  //     format: "json",
  //   },
});

// Add a request interceptor
axiosApi.interceptors.request.use(
  function (config) {
    // Do something before request is sent
    let params = getUrlParameters();
    if(params.secret_explorer !== undefined) {
      config.params = { secret_explorer: params.secret_explorer };
    }
    return config;
  },
  function (error) {
    // Do something with request error
    return Promise.reject(error);
  }
);

export { axiosApi, consts };

