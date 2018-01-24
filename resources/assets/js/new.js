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
    errors: false
  },
  mounted: function() {
    pace.start()

    axios.get('/project/fetchNew')
      .then(response => {
        this.projects = response.data
      })
      .catch(error => {
        console.log(error)
      })

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
      for (var m = startDate; m.diff(endDate, 'days') <= 0; m.add(1, 'days')) {
        var task = {
          date: moment(m).format('YYYY-MM-DD'),
          dayOfWeek: moment(m).format('ddd'),
          time_in: '09:00',
          time_out: '18:00',
          description: ''
        }
        this.tasks.push(task)
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
    isWeekend: function(task) {
      if(task.dayOfWeek == 'Sat' || task.dayOfWeek == 'Sun') {
        return true;
      }
      return false;
    }
  }
})
