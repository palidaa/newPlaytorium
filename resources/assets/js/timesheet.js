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
  mounted: function() {
    this.fetch()
    // fetch projects
    axios.get('/project/fetchOwnProject')
      .then(response => {
        this.projects = response.data
      })
      .catch(error => {
        console.log(error);
      });

    // datepicker setup
    $('.input-group.date').datepicker({
      minViewMode: 1,
      maxViewMode: 2,
      format: 'yyyy-mm',
      orientation: 'bottom auto',
      autoclose: true
    }).on('changeDate', () => {
      this.date = $('#dateInput').val();
      this.fetch();
    });
  },
  methods: {
    fetch: function() {
      this.fetchHoliday()
      this.fetchLeaveDays()
      axios.get('/timesheet/fetch', {
        params: {
          date: this.date
        }
      })
        .then(response => {
          this.timesheets = response.data;
          this.timesheets.forEach(timesheet => {
            timesheet.isHoliday = false;
            timesheet.dayOfWeek = moment(timesheet.date).format('ddd');
            for(let i = 0; i < this.holidays.length; i++) {
              if(timesheet.date == this.holidays[i].holiday) {
                timesheet.isHoliday = true;
                timesheet.holidayName = '(' + this.holidays[i].date_name + ')';
                break;
              }
            }
            if(timesheet.dayOfWeek == 'Sat' || timesheet.dayOfWeek == 'Sun') {
              timesheet.isHoliday = true;
            }
          });
          //Wait until holiday is fetched
          setTimeout(() => {
            this.workingDay = this.getWorkingDayInMonth(this.date);
          }, 200)
          this.totalTimesheets = this.getTotalTimesheets();
        })
        .catch(error => {
          console.log(error);
        });
    },
    select: function(timesheet, key) {
      // prevent reference sharing
      let temp = Object.assign({}, timesheet)
      this.selectedTimesheet = temp;
      this.selectedKey = key;
    },
    remove: function(key) {
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
        callback: (confirm) => {
          if(confirm) {
            axios.delete('/timesheet/destroy', {
              params: {
                date: this.timesheets[key].date,
                prj_no: this.timesheets[key].prj_no
              }
            })
              .then(response => {
                Vue.delete(this.timesheets, key);
                this.totalTimesheets = this.getTotalTimesheets();
              })
              .catch(error => {
                console.log(error);
              });
          }
        }
      });
    },
    update: function() {
      axios.post('/timesheet/update', {
        date: this.timesheets[this.selectedKey].date,
        old_prj_no: this.timesheets[this.selectedKey].prj_no,
        new_prj_no: this.selectedTimesheet.prj_no,
        new_task_name: this.selectedTimesheet.task_name,
        new_time_in: this.selectedTimesheet.time_in,
        new_time_out: this.selectedTimesheet.time_out,
        new_description: this.selectedTimesheet.description
      })
        .then(response => {
          this.timesheets[this.selectedKey].prj_no = this.selectedTimesheet.prj_no
          this.timesheets[this.selectedKey].prj_name = this.projects.find(project => {
            return project.prj_no == this.selectedTimesheet.prj_no
          }).prj_name
          this.timesheets[this.selectedKey].task_name = this.selectedTimesheet.task_name
          this.timesheets[this.selectedKey].time_in = this.selectedTimesheet.time_in
          this.timesheets[this.selectedKey].time_out = this.selectedTimesheet.time_out
          this.timesheets[this.selectedKey].description = this.selectedTimesheet.description
        })
        .catch(error => {
          console.log(error);
        });
    },
    getTotalTimesheets: function() {
      let count = 0;
      for(let d = 1; d <= moment(this.date).daysInMonth(); d++) {
        if(this.timesheets.findIndex(timesheet => timesheet.date.substr(8, 2) == d) >= 0) {
          count++;
        }
      }
      return count;
    },
    getWorkingDayInMonth: function(date) {
      startDate = moment(date).format('YYYY-MM-01')
      endDate = moment(date).format('YYYY-MM-') + moment(date).daysInMonth()
      workingDay = moment(date).daysInMonth()
      for (let m = moment(startDate); m.diff(endDate, 'days') <= 0; m.add(1, 'days')) {
        for(let i = 0; i < this.holidays.length; i++) {
          if(moment(m).format('YYYY-MM-DD') == this.holidays[i].holiday || m.isoWeekday() == 6 || m.isoWeekday() == 7) {
            workingDay--
            break
          }
        }
      }
      workingDay -= this.leaveDays.length
      console.log(workingDay)
      return workingDay
    },
    fetchHoliday() {
      axios.get('/holiday/fetch', {
        params: {
          year: moment(this.date).format('YYYY'),
          month: moment(this.date).format('MM')
        }
      })
        .then(response => {
          console.log('holiday')
          this.holidays = response.data
        })
        .catch(error => {
          console.log(error)
        })
    },
    fetchLeaveDays() {
      axios.get('/leave_request/get-leaves-in-month', {
        params: {
          year: moment(this.date).format('YYYY'),
          month: moment(this.date).format('MM')
        }
      })
        .then(response => {
          console.log('leave')
          this.leaveDays = response.data
        })
        .catch(error => {
          console.log(error)
        })
    }
  }
});
