/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId])
/******/ 			return installedModules[moduleId].exports;
/******/
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// identity function for calling harmony imports with the correct context
/******/ 	__webpack_require__.i = function(value) { return value; };
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 152);
/******/ })
/************************************************************************/
/******/ ({

/***/ 122:
/***/ (function(module, exports) {

new Vue({
  el: '#timesheet',
  data: {
    date: moment().format('YYYY-MM-DD'),
    timesheets: [],
    selectedTimesheet: {
      prj_no: '',
      task_name: '',
      time_in: '',
      time_out: '',
      description: ''
    },
    selectedKey: 0
  },
  mounted: function mounted() {
    var _this = this;

    this.fetch();
    $('.input-group.date').datepicker({
      format: 'yyyy-mm-dd',
      autoclose: true
    }).on('changeDate', function () {
      _this.date = $('#dateInput').val();
      _this.fetch();
    });
  },
  methods: {
    fetch: function fetch() {
      var _this2 = this;

      axios.get('/timesheet/fetch', {
        params: {
          date: this.date
        }
      }).then(function (response) {
        console.log(response);
        _this2.timesheets = response.data;
      }).catch(function (error) {
        console.log(error);
      });
    },
    select: function select(timesheet, key) {
      this.selectedTimesheet = timesheet;
      this.selectedKey = key;
    },
    remove: function remove(key) {
      var _this3 = this;

      axios.delete('/timesheet/delete', {
        params: {
          date: this.timesheets[key].date,
          prj_no: this.timesheets[key].prj_no
        }
      }).then(function (response) {
        console.log(response);
        Vue.delete(_this3.timesheets, key);
      }).catch(function (error) {
        console.log(error);
      });
    },
    update: function update() {
      var _this4 = this;

      axios.post('/timesheet/update', {
        date: this.timesheets[this.selectedKey].date,
        old_prj_no: this.timesheets[this.selectedKey].prj_no,
        new_prj_no: this.selectedTimesheet.prj_no,
        new_task_name: this.selectedTimesheet.task_name,
        new_time_in: this.selectedTimesheet.time_in,
        new_time_out: this.selectedTimesheet.time_out,
        new_description: this.selectedTimesheet.description
      }).then(function (response) {
        console.log(response);
        _this4.timesheets[_this4.selectedKey].prj_no = _this4.selectedTimesheet.prj_no;
        _this4.timesheets[_this4.selectedKey].task_name = _this4.selectedTimesheet.task_name;
        _this4.timesheets[_this4.selectedKey].time_in = _this4.selectedTimesheet.time_in;
        _this4.timesheets[_this4.selectedKey].time_out = _this4.selectedTimesheet.time_out;
        _this4.timesheets[_this4.selectedKey].description = _this4.selectedTimesheet.description;
      }).catch(function (error) {
        console.log(error);
      });
    }
  }
});

/***/ }),

/***/ 152:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(122);


/***/ })

/******/ });