import axios from 'axios';
import store from '../../store';

const TOKEN = localStorage.getItem('token');

const getClient = () => {
  const config = {
    baseURL: process.env.MIX_API_BASE_URL,
    timeout: process.env.MIX_APP_REQUEST_TIMEOUT || 5000
  };

  config.headers = {
    Authorization: `Bearer ${TOKEN}`
  };

  const client = axios.create(config);
  initInterceptors(client);
  return client;
};

export default class ApiClient {
  constructor() {
    this.client = getClient();
  }

  setHeaders(headers) {
    this.client.defaults.headers = headers;
    return this;
  }

  getClient() {
    return this.client;
  }

  get(path, params) {
    return this.client.get(path, params);
  }

  post(path, params) {
    return this.client.post(path, params);
  }

  put(path, params) {
    return this.client.put(path, params);
  }

  delete(path, params) {
    return this.client.delete(path, params);
  }
}

function initInterceptors(client) {
  client.interceptors.response.use(response => {
    let res = response.data;
    res.status = response.status;
    return res;  
    
  }, error => {
    if (error.response.status === 401) {
      store.dispatch('auth/clearSession', null);
     // Return user to login page
     let loc = window.location.href
     let isInLoginPage = loc.includes('/login')
     !isInLoginPage && (window.location.href = '/login');
    }
    return Promise.reject(error);
  });
}
