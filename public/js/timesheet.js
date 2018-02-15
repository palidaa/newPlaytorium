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
/******/ 	return __webpack_require__(__webpack_require__.s = 171);
/******/ })
/************************************************************************/
/******/ ({

/***/ 136:
/***/ (function(module, exports) {

new Vue({
  el: '#timesheet',
  data: {
    date: moment().format('YYYY-MM'),
    workingDay: 30,
    totalTimesheets: 0,
    timesheets: [],
    selectedTimesheet: {
      prj_no: '',
      prj_name: '',
      task_name: '',
      time_in: '',
      time_out: '',
      description: ''
    },
    selectedKey: 0,
    projects: [],
    holidays: [],
    leaveDays: []
  },
  mounted: function mounted() {
    var _this = this;

    this.fetch();
    // fetch projects
    axios.get('/project/fetchOwnProject').then(function (response) {
      _this.projects = response.data;
    }).catch(function (error) {
      console.log(error);
    });

    // datepicker setup
    $('.input-group.date').datepicker({
      minViewMode: 1,
      maxViewMode: 2,
      format: 'yyyy-mm',
      orientation: 'bottom auto',
      autoclose: true
    }).on('changeDate', function () {
      _this.date = $('#dateInput').val();
      _this.fetch();
    });
  },
  methods: {
    fetch: function fetch() {
      var _this2 = this;

      this.fetchHoliday();
      this.fetchLeaveDays();
      axios.get('/timesheet/fetch', {
        params: {
          date: this.date
        }
      }).then(function (response) {
        _this2.timesheets = response.data;
        _this2.timesheets.forEach(function (timesheet) {
          timesheet.isHoliday = false;
          timesheet.dayOfWeek = moment(timesheet.date).format('ddd');
          for (var i = 0; i < _this2.holidays.length; i++) {
            if (timesheet.date == _this2.holidays[i].holiday) {
              timesheet.isHoliday = true;
              timesheet.holidayName = '(' + _this2.holidays[i].date_name + ')';
              break;
            }
          }
          if (timesheet.dayOfWeek == 'Sat' || timesheet.dayOfWeek == 'Sun') {
            timesheet.isHoliday = true;
          }
        });
        //Wait until holiday is fetched
        setTimeout(function () {
          _this2.workingDay = _this2.getWorkingDayInMonth(_this2.date);
        }, 200);
        _this2.totalTimesheets = _this2.getTotalTimesheets();
      }).catch(function (error) {
        console.log(error);
      });
    },
    select: function select(timesheet, key) {
      // prevent reference sharing
      var temp = Object.assign({}, timesheet);
      this.selectedTimesheet = temp;
      this.selectedKey = key;
    },
    remove: function remove(key) {
      var _this3 = this;

      bootbox.confirm({
        title: 'Delete confirmation',
        message: 'Do you really want to delete a task ?',
        buttons: {
          cancel: {
            label: 'No'
          },
          confirm: {
            label: 'Yes'
          }
        },
        callback: function callback(confirm) {
          if (confirm) {
            axios.delete('/timesheet/destroy', {
              params: {
                date: _this3.timesheets[key].date,
                prj_no: _this3.timesheets[key].prj_no
              }
            }).then(function (response) {
              Vue.delete(_this3.timesheets, key);
              _this3.totalTimesheets = _this3.getTotalTimesheets();
            }).catch(function (error) {
              console.log(error);
            });
          }
        }
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
        _this4.timesheets[_this4.selectedKey].prj_no = _this4.selectedTimesheet.prj_no;
        _this4.timesheets[_this4.selectedKey].task_name = _this4.selectedTimesheet.task_name;
        _this4.timesheets[_this4.selectedKey].time_in = _this4.selectedTimesheet.time_in;
        _this4.timesheets[_this4.selectedKey].time_out = _this4.selectedTimesheet.time_out;
        _this4.timesheets[_this4.selectedKey].description = _this4.selectedTimesheet.description;
      }).catch(function (error) {
        console.log(error);
      });
    },
    getTotalTimesheets: function getTotalTimesheets() {
      var _this5 = this;

      var count = 0;

      var _loop = function _loop(d) {
        if (_this5.timesheets.findIndex(function (timesheet) {
          return timesheet.date.substr(8, 2) == d;
        }) >= 0) {
          count++;
        }
      };

      for (var d = 1; d <= moment(this.date).daysInMonth(); d++) {
        _loop(d);
      }
      return count;
    },
    getWorkingDayInMonth: function getWorkingDayInMonth(date) {
      startDate = moment(date).format('YYYY-MM-01');
      endDate = moment(date).format('YYYY-MM-') + moment(date).daysInMonth();
      workingDay = moment(date).daysInMonth();
      for (var m = moment(startDate); m.diff(endDate, 'days') <= 0; m.add(1, 'days')) {
        for (var i = 0; i < this.holidays.length; i++) {
          if (moment(m).format('YYYY-MM-DD') == this.holidays[i].holiday || m.isoWeekday() == 6 || m.isoWeekday() == 7) {
            workingDay--;
            break;
          }
        }
      }
      workingDay -= this.leaveDays.length;
      console.log(workingDay);
      return workingDay;
    },
    fetchHoliday: function fetchHoliday() {
      var _this6 = this;

      axios.get('/holiday/fetch', {
        params: {
          year: moment(this.date).format('YYYY'),
          month: moment(this.date).format('MM')
        }
      }).then(function (response) {
        console.log('holiday');
        _this6.holidays = response.data;
      }).catch(function (error) {
        console.log(error);
      });
    },
    fetchLeaveDays: function fetchLeaveDays() {
      var _this7 = this;

      axios.get('/leave_request/get-leaves-in-month', {
        params: {
          year: moment(this.date).format('YYYY'),
          month: moment(this.date).format('MM')
        }
      }).then(function (response) {
        console.log('leave');
        _this7.leaveDays = response.data;
      }).catch(function (error) {
        console.log(error);
      });
    }
  }
});

/***/ }),

/***/ 171:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(136);


/***/ })

/******/ });