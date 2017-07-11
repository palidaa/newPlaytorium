let now = moment().format('YYYY-MM-DD');

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
  mounted: function() {
    $('.input-group.date').datepicker({
      format: 'yyyy-mm-dd',
      autoclose: true
    }).on('changeDate', () => {
      this.startDate = $('#startDateInput').val();
      this.endDate = $('#endDateInput').val();
      this.tasks = [];
      this.appendTask(this.startDate, this.endDate);
    });
  },
  methods: {
    appendTask: function(startDate, endDate) {
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
    submit: function() {
      this.tasks.forEach(task => {
        axios.post('/timesheet/insert', {
            date: task.date,
            time_in: task.time_in,
            time_out: task.time_out,
            prj_no: this.prj_no,
            task_name: this.task_name,
            description: task.description
          })
          .then(response => {
            console.log(response);
          })
          .catch(error => {
            console.log(error);
          });
        });
      //window.location.href = '/timesheet';
    }
  }
});
