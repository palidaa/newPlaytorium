new Vue({
  el: '#timesheet',
  data: {
    date: moment().format('YYYY-MM-DD'),
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
    projects: []
  },
  mounted: function() {
    pace.start();
    this.fetch();

    // fetch project
    axios.get('/project/fetch')
      .then(response => {
        this.projects = response.data;
      })
      .catch(error => {
        console.log(error);
      });

    // datepicker setup
    $('.input-group.date').datepicker({
      format: 'yyyy-mm-dd',
      autoclose: true
    }).on('changeDate', () => {
      this.date = $('#dateInput').val();
      this.fetch();
    });
  },
  methods: {
    fetch: function() {
      pace.start();
      axios.get('/timesheet/fetch', {
        params: {
          date: this.date
        }
      })
        .then(response => {
          console.log(response);
          this.timesheets = response.data;
          pace.stop();
        })
        .catch(error => {
          console.log(error);
        });
    },
    select: function(timesheet, key) {
      this.selectedTimesheet = timesheet;
      this.selectedKey = key;
    },
    remove: function(key) {
      axios.delete('/timesheet/delete', {
        params: {
          date: this.timesheets[key].date,
          prj_no: this.timesheets[key].prj_no
        }
      })
        .then(response => {
          console.log(response);
          Vue.delete(this.timesheets, key);
        })
        .catch(error => {
          console.log(error);
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
          console.log(response);
          this.timesheets[this.selectedKey].prj_no = this.selectedTimesheet.prj_no;
          this.timesheets[this.selectedKey].task_name = this.selectedTimesheet.task_name;
          this.timesheets[this.selectedKey].time_in = this.selectedTimesheet.time_in;
          this.timesheets[this.selectedKey].time_out = this.selectedTimesheet.time_out;
          this.timesheets[this.selectedKey].description = this.selectedTimesheet.description;
        })
        .catch(error => {
          console.log(error);
        });
    }
  }
});
