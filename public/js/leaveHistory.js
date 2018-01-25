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
/******/ 	return __webpack_require__(__webpack_require__.s = 153);
/******/ })
/************************************************************************/
/******/ ({

/***/ 120:
/***/ (function(module, exports) {

new Vue({
  el: '#leaveHistory',
  data: {
    leaveHistorys: [],
    leave_type: '',
    year: '',
    years: []
  },
  mounted: function mounted() {
    var _this = this;

    this.fetch();
    this.getYear();
  },

  watch: {
    year: function year() {
      this.fetch();
    },
    leave_type: function leave_type() {
      this.fetch();
      this.getYear();
    }
  },
  methods: {
    fetch: function fetch() {
      var _this2 = this;

      axios.get('/leave_request_history/fetch', {
        params: {
          year: this.year,
          leave_type: this.leave_type
        }
      }).then(function (response) {
        _this2.leaveHistorys = response.data;
      }).catch(function (error) {
        console.log(error);
      });
    },
    getYear: function getYear() {
      var _this2 = this;

      axios.get('/leave_request_history/getYear', {
        params: {
          leave_type: this.leave_type
        }
      }).then(function (response) {
        _this2.years = response.data;
      }).catch(function (error) {
        console.log(error);
      });
    },
    destroy(index) {
      axios.delete('/leave_request_history/destroy', {
        params: {
          id: this.leaveHistorys[index].id,
          leave_date: this.leaveHistorys[index].leave_date
        }
      })
        .then(response => {
          console.log(response)
          this.leaveHistorys.splice(index, 1)
        })
        .catch(error => {
          console.log(error)
        })
    }
  }
});

/***/ }),

/***/ 153:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(120);


/***/ })

/******/ });