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
/******/ 	return __webpack_require__(__webpack_require__.s = 151);
/******/ })
/************************************************************************/
/******/ ({

/***/ 120:
/***/ (function(module, exports) {

var now = moment().format('YYYY-MM-DD');

new Vue({
  el: '#new',
  data: {
    prj_no: '',
    task_name: 'Dev',
    startDate: now,
    endDate: now,
    tasks: [{
      date: now,
      time_in: '09:00',
      time_out: '18:00',
      description: ''
    }]
  },
  mounted: function mounted() {
    var _this = this;

    $('.input-group.date').datepicker({
      format: 'yyyy-mm-dd',
      autoclose: true
    }).on('changeDate', function () {
      _this.startDate = $('#startDateInput').val();
      _this.endDate = $('#endDateInput').val();
      _this.tasks = [];
      _this.appendTask(_this.startDate, _this.endDate);
    });
  },
  methods: {
    appendTask: function appendTask(startDate, endDate) {
      startDate = moment(startDate);
      endDate = moment(endDate);
      for (var m = startDate; m.diff(endDate, 'days') <= 0; m.add(1, 'days')) {
        var task = {
          date: moment(m).format('YYYY-MM-DD'),
          time_in: '09:00',
          time_out: '18:00',
          description: ''
        };
        this.tasks.push(task);
      }
    },
    submit: function submit() {
      var _this2 = this;

      this.tasks.forEach(function (task) {
        axios.post('/timesheet/insert', {
          date: task.date,
          time_in: task.time_in,
          time_out: task.time_out,
          prj_no: _this2.prj_no,
          task_name: _this2.task_name,
          description: task.description
        }).then(function (response) {
          console.log(response);
        }).catch(function (error) {
          console.log(error);
        });
      });
      //window.location.href = '/timesheet';
    }
  }
});

/***/ }),

/***/ 151:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(120);


/***/ })

/******/ });