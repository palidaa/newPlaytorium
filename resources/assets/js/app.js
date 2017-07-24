
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('./bootstrap-datepicker');

window.Vue = require('vue');
window.moment = require('moment');
window.pace = require('./pace.min');
window.bootbox = require('bootbox');

// Add a request interceptor
axios.interceptors.request.use(function (config) {
    // Do something before request is sent
    pace.start();
    return config;
  }, function (error) {
    // Do something with request error
    return Promise.reject(error);
  });

// Add a response interceptor
axios.interceptors.response.use(function (response) {
    // Do something with response data
    pace.stop();
    return response;
  }, function (error) {
    // Do something with response error
    return Promise.reject(error);
  });

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

//Vue.component('example', require('./components/Example.vue'));
