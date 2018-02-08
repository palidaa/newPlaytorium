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
  mounted() {
    this.getYear()
  },
  watch: {
    selectedYear() {
      this.getMonth()
    },
    selectedMonth() {
      this.getProject()
    }
  },
  methods: {
    getYear() {
      axios.get('/report/getyear', {
        params: {
          type: this.type
        }
      })
        .then(response => {
          this.years = response.data.map(x => x.year)
          this.selectedYear = this.years[0]
        })
        .catch(error => {
          console.log(error)
        })
    },
    getMonth() {
      axios.get('/report/getmonth', {
        params: {
          year: this.selectedYear
        }
      })
        .then(response => {
          this.months = response.data.map(x => x.month)
          this.selectedMonth = this.months[0]
        })
        .catch(error => {
          console.log(error)
        })
    },
    getProject() {
      axios.get('/report/getproject', {
        params: {
          year: this.selectedYear,
          month: this.selectedMonth
        }
      })
        .then(response => {
          this.projects = response.data
          this.selectedProject = this.projects[0].prj_no + ' - ' + this.projects[0].prj_name
        })
        .catch(error => {
          console.log(error)
        })
    },
    download() {
      if(this.type == 'Timesheet') {
        window.location = '/report/export-timesheet?year=' + this.selectedYear + '&month=' + this.selectedMonth + '&project=' + this.selectedProject.substr(0, 8)
      }
      else if(this.type == 'Summary Timesheet') {
        window.location = '/report/export-summary-timesheet?year=' + this.selectedYear + '&type=Summary Timesheet'
      }
    }
  }
})