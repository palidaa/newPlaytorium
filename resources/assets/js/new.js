let now = moment().format('YYYY-MM-DD')

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
  mounted: function() {
    pace.start()

    axios.get('/project/fetchOwnProject')
      .then(response => {
        this.projects = response.data
      })
      .catch(error => {
        console.log(error)
      })

    this.fetchHoliday();
    this.fetchLeaveDays();

    //setup datepicker
    $('.input-group.date').datepicker({
      maxViewMode: 2,
      format: 'yyyy-mm-dd',
      orientation: 'bottom auto',
      autoclose: true
    }).on('changeDate', () => {
      this.startDate = $('#startDateInput').val()
      this.endDate = $('#endDateInput').val()
      if(moment(this.endDate) < moment(this.startDate)) {
        this.endDate = this.startDate
        $('#endDateInput').val(this.startDate)
      }
      $('#toDatepicker').datepicker('setStartDate', this.startDate)
      this.tasks = []
      this.appendTask(this.startDate, this.endDate)
    })
  },
  methods: {
    appendTask: function(startDate, endDate) {
      startDate = moment(startDate)
      endDate = moment(endDate)
      this.fetchHoliday()
      this.fetchLeaveDays()
      for (let m = startDate; m.diff(endDate, 'days') <= 0; m.add(1, 'days')) {
        let task = {
          date: moment(m).format('YYYY-MM-DD'),
          dayOfWeek: moment(m).format('ddd'),
          time_in: '09:00',
          time_out: '18:00',
          description: '',
          isHoliday: false,
          isLeaveDay: false,
          isFullDayLeave: false
        }
        for(let i = 0; i < this.holidays.length; i++) {
          if(task.date == this.holidays[i].holiday) {
            task.isHoliday = true
            //task.holidayName = '(' + this.holidays[i].date_name + ')'
            task.description = this.holidays[i].date_name
            break
          }
        }
        for(let i = 0; i < this.leaveDays.length; i++) {
          if(task.date == this.leaveDays[i].leave_date) {
            task.leaveDay = true
            task.description = this.leaveDays[i].leave_type + ': You have left this day from ' + this.leaveDays[i].leave_from.substr(11, 5) + ' to ' + this.leaveDays[i].leave_to.substr(11, 5)
            if(this.leaveDays[i].totalhours == 8) {
              task.isFullDayLeave = true
            }
            break
          }
        }
        if(task.dayOfWeek == 'Sat' || task.dayOfWeek == 'Sun') {
          task.isHoliday = true
        }
        if(!task.isFullDayLeave) {
          this.tasks.push(task)
        }
      }
    },
    removeTask: function(task, index) {
      this.tasks.splice(index, 1)
    },
    submit: function() {
      if(this.selectedProject == '') {
        this.errors = true
        scroll(0, 0)
      }
      let promises = []
      for(let i = 0; i < this.tasks.length; i++) {
        promises.push(axios.post('/timesheet/store', {
          date: this.tasks[i].date,
          time_in: this.tasks[i].time_in,
          time_out: this.tasks[i].time_out,
          prj_no: this.selectedProject.substr(0, this.selectedProject.indexOf(' ')),
          task_name: this.task_name,
          description: this.tasks[i].description
        }))
      }
      axios.all(promises)
        .then(axios.spread((...responses) => {
          for(let i = 0; i < responses.length; i++) {
            console.log(responses[i])
          }
          window.location.href = '/timesheet'
        }))
        .catch(error => {
          console.log(error)
        })
    },
    fetchHoliday() {
      axios.get('/holiday/fetch', {
        params: {
          year: moment(this.startDate).format('YYYY'),
          month: moment(this.startDate).format('MM')
        }
      })
        .then(response => {
          this.holidays = response.data
        })
        .catch(error => {
          console.log(error)
        })
    },
    fetchLeaveDays() {
      axios.get('/leave_request/get-leaves-in-month', {
        params: {
          year: moment(this.startDate).format('YYYY'),
          month: moment(this.startDate).format('MM')
        }
      })
        .then(response => {
          this.leaveDays = response.data
        })
        .catch(error => {
          console.log(error)
        })
    }
  }
})
