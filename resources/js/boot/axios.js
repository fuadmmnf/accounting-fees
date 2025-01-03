import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'

const axiosContext = axios.create({
    baseURL: process.env.NODE_ENV === 'production' ? 'https://sroregister.com' : 'http://127.0.0.1',
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content
    },
    withCredentials: true
});

axiosContext.interceptors.request.use(config => {
    config.headers.Authorization = `Bearer ${localStorage.getItem('token')? localStorage.getItem('token') : ''}`;
    return config;
});

// TODO: Change conditions according to your response structure
axiosContext.interceptors.response.use(
    response => {
      if (response.data.code === 400) {
        localStorage.removeItem('token');
        window.location = "/login";
        return;
      }
      return response;
    },
    error => {
      if (error.response && error.response.status === 401) {
        localStorage.removeItem('token');
        window.location = "/login";
        return;
      }
      return Promise.reject(error);
    }
  );

Vue.prototype.$axios = axiosContext;
Vuex.Store.prototype.$axios = axiosContext;
