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
/******/ 	return __webpack_require__(__webpack_require__.s = 170);
/******/ })
/************************************************************************/
/******/ ({

/***/ 135:
/***/ (function(module, exports) {

new Vue({
  el: '#report',
  data: {
    type: 'Timesheet',
    years: [],
    months: [],
    projects: [],
    selectedYear: '',
    selectedMonth: '',
    selectedProject: ''
  },
  mounted: function mounted() {
    this.getYear();
  },

  watch: {
    selectedYear: function selectedYear() {
      this.getMonth();
    },
    selectedMonth: function selectedMonth() {
      this.getProject();
    }
  },
  methods: {
    getYear: function getYear() {
      var _this = this;

      axios.get('/report/getyear', {
        params: {
          type: this.type
        }
      }).then(function (response) {
        _this.years = response.data.map(function (x) {
          return x.year;
        });
        _this.selectedYear = _this.years[0];
      }).catch(function (error) {
        console.log(error);
      });
    },
    getMonth: function getMonth() {
      var _this2 = this;

      axios.get('/report/getmonth', {
        params: {
          year: this.selectedYear
        }
      }).then(function (response) {
        _this2.months = response.data.map(function (x) {
          return x.month;
        });
        _this2.selectedMonth = _this2.months[0];
      }).catch(function (error) {
        console.log(error);
      });
    },
    getProject: function getProject() {
      var _this3 = this;

      axios.get('/report/getproject', {
        params: {
          year: this.selectedYear,
          month: this.selectedMonth
        }
      }).then(function (response) {
        _this3.projects = response.data;
        _this3.selectedProject = _this3.projects[0].prj_no + ' - ' + _this3.projects[0].prj_name;
      }).catch(function (error) {
        console.log(error);
      });
    },
    download: function download() {
      if (this.type == 'Timesheet') {
        window.location = '/report/export-timesheet?year=' + this.selectedYear + '&month=' + this.selectedMonth + '&project=' + this.selectedProject.substr(0, 8) + '&type=Timesheet';
      } else if (this.type == 'Timesheet (Special)') {
        window.location = '/report/export-timesheet?year=' + this.selectedYear + '&month=' + this.selectedMonth + '&project=' + this.selectedProject.substr(0, 8) + '&type=Timesheet(Special)';
      } else if (this.type == 'Summary Timesheet') {
        window.location = '/report/export-summary-timesheet?year=' + this.selectedYear + '&type=Summary Timesheet';
      }
    }
  }
});

/***/ }),

/***/ 170:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(135);


/***/ })

/******/ });