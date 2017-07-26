new Vue({
  el: '#timesheet',
  data: {
    date: moment().format('YYYY-MM'),
    daysInMonth: moment().daysInMonth(),
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
    projects: []
  },
  mounted: function() {
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
      minViewMode: 1,
      maxViewMode: 2,
      format: 'yyyy-mm',
      orientation: 'bottom auto',
      autoclose: true
    }).on('changeDate', () => {
      this.date = $('#dateInput').val();
      this.daysInMonth = moment(this.date).daysInMonth();
      this.fetch();
    });
  },
  methods: {
    fetch: function() {
      axios.get('/timesheet/fetch', {
        params: {
          date: this.date
        }
      })
        .then(response => {
          console.log(response);
          this.timesheets = response.data;
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
                console.log(response);
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
    },
    getTotalTimesheets: function() {
      let count = 0;
      for(let d = 1; d <= moment(this.date).daysInMonth(); d++) {
        if(this.timesheets.findIndex(timesheet => timesheet.date.substr(8, 2) == d) >= 0) {
          count++;
        }
      }
      return count;
    }
  }
});
