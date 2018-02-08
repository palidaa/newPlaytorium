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
/******/ 	return __webpack_require__(__webpack_require__.s = 168);
/******/ })
/************************************************************************/
/******/ ({

/***/ 133:
/***/ (function(module, exports) {

var now = moment().format('YYYY-MM-DD');

new Vue({
  el: '#new',
  data: {
    // selectedProject format = 'prj_no - prj_name'
    selectedProject: '',
    projects: [],
    task_name: 'Dev',
    startDate: now,
    endDate: now,
    tasks: [{
      date: now,
      dayOfWeek: moment(now).format('ddd'),
      time_in: '09:00',
      time_out: '18:00',
      description: ''
    }],
    holidays: [],
    leaveDays: [],
    errors: false
  },
  mounted: function mounted() {
    var _this = this;

    pace.start();

    axios.get('/project/fetchOwnProject').then(function (response) {
      _this.projects = response.data;
    }).catch(function (error) {
      console.log(error);
    });

    this.fetchHoliday();
    this.fetchLeaveDays();

    //setup datepicker
    $('.input-group.date').datepicker({
      maxViewMode: 2,
      format: 'yyyy-mm-dd',
      orientation: 'bottom auto',
      autoclose: true
    }).on('changeDate', function () {
      _this.startDate = $('#startDateInput').val();
      _this.endDate = $('#endDateInput').val();
      if (moment(_this.endDate) < moment(_this.startDate)) {
        _this.endDate = _this.startDate;
        $('#endDateInput').val(_this.startDate);
      }
      $('#toDatepicker').datepicker('setStartDate', _this.startDate);
      _this.tasks = [];
      _this.appendTask(_this.startDate, _this.endDate);
    });
  },
  methods: {
    appendTask: function appendTask(startDate, endDate) {
      startDate = moment(startDate);
      endDate = moment(endDate);
      this.fetchHoliday();
      this.fetchLeaveDays();
      for (var m = startDate; m.diff(endDate, 'days') <= 0; m.add(1, 'days')) {
        var task = {
          date: moment(m).format('YYYY-MM-DD'),
          dayOfWeek: moment(m).format('ddd'),
          time_in: '09:00',
          time_out: '18:00',
          description: '',
          isHoliday: false,
          isLeaveDay: false,
          isFullDayLeave: false
        };
        for (var i = 0; i < this.holidays.length; i++) {
          if (task.date == this.holidays[i].holiday) {
            task.isHoliday = true;
            //task.holidayName = '(' + this.holidays[i].date_name + ')'
            task.description = this.holidays[i].date_name;
            break;
          }
        }
        for (var _i = 0; _i < this.leaveDays.length; _i++) {
          if (task.date == this.leaveDays[_i].leave_date) {
            task.leaveDay = true;
            task.description = this.leaveDays[_i].leave_type + ': You have left this day from ' + this.leaveDays[_i].leave_from.substr(11, 5) + ' to ' + this.leaveDays[_i].leave_to.substr(11, 5);
            if (this.leaveDays[_i].totalhours == 8) {
              task.isFullDayLeave = true;
            }
            break;
          }
        }
        if (task.dayOfWeek == 'Sat' || task.dayOfWeek == 'Sun') {
          task.isHoliday = true;
        }
        if (!task.isFullDayLeave) {
          this.tasks.push(task);
        }
      }
    },
    removeTask: function removeTask(task, index) {
      this.tasks.splice(index, 1);
    },
    submit: function submit() {
      if (this.selectedProject == '') {
        this.errors = true;
        scroll(0, 0);
      }
      var promises = [];
      for (var i = 0; i < this.tasks.length; i++) {
        promises.push(axios.post('/timesheet/store', {
          date: this.tasks[i].date,
          time_in: this.tasks[i].time_in,
          time_out: this.tasks[i].time_out,
          prj_no: this.selectedProject.substr(0, this.selectedProject.indexOf(' ')),
          task_name: this.task_name,
          description: this.tasks[i].description
        }));
      }
      axios.all(promises).then(axios.spread(function () {
        for (var _len = arguments.length, responses = Array(_len), _key = 0; _key < _len; _key++) {
          responses[_key] = arguments[_key];
        }

        for (var _i2 = 0; _i2 < responses.length; _i2++) {
          console.log(responses[_i2]);
        }
        window.location.href = '/timesheet';
      })).catch(function (error) {
        console.log(error);
      });
    },
    fetchHoliday: function fetchHoliday() {
      var _this2 = this;

      axios.get('/holiday/fetch', {
        params: {
          year: moment(this.startDate).format('YYYY'),
          month: moment(this.startDate).format('MM')
        }
      }).then(function (response) {
        _this2.holidays = response.data;
      }).catch(function (error) {
        console.log(error);
      });
    },
    fetchLeaveDays: function fetchLeaveDays() {
      var _this3 = this;

      axios.get('/leave_request/get-leaves-in-month', {
        params: {
          year: moment(this.startDate).format('YYYY'),
          month: moment(this.startDate).format('MM')
        }
      }).then(function (response) {
        _this3.leaveDays = response.data;
      }).catch(function (error) {
        console.log(error);
      });
    }
  }
});

/***/ }),

/***/ 168:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(133);


/***/ })

/******/ });