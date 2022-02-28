const axiosApi = axios.create({
  baseURL: `${MfspApiConfig.api_base_url}myplugin/v1/`,
  headers: { "X-WP-Nonce": MfspApiConfig.nonce },
});

const msfpUploadFile = () => {
  return new Promise((resolve, reject) => {
    axiosApi.post(`import/csv`).then(resolve, reject);
  });
};

// function mfspUploadFiles() {}
