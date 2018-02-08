new Vue({
  el: '#holiday',
  data: {
    holidays: [],
    selectedYear: moment().format('YYYY'),
    years: [],
    month: '01',
    date: '',
    date_name: ''
  },
  mounted() {
    this.fetch()
    this.getYear()
    $('.input-group.date').datepicker({
      format: "yyyy/mm/dd",
      startView: 0,
      maxViewMode: 2,
      orientation: "bottom auto",
      autoclose: true
    })
    .on(
      'changeDate', () => {
        this.date = $('#datepicker').val()
      }
    )
  },
  watch: {
    selectedYear() {
      this.fetch()
    },
    month() {
      this.fetch()
    }
  },
  methods: {
    fetch() {
      axios.get('/holiday/fetch',{
        params: {
          year: this.selectedYear,
          month: this.month
        }
      })
        .then(response => {
          this.holidays = response.data
        })
        .catch(error => {
          console.log(error)
        })
    },
    store() {
      axios.post('/holiday/store', {
        date: this.date,
        date_name: this.date_name
      })
        .then(response => {
          console.log(response)
          window.location.href = '/holiday'
        })
        .catch(error => {
          console.log(error)
        })
    },
    destroy(key) {
      bootbox.confirm({
        title: 'Delete confirmation',
        message: 'Do you really want to delete ?',
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
            axios.delete('/holiday/destroy', {
              params: {
                date: this.holidays[key].holiday
              }
            })
              .then(response => {
                console.log(response)
                Vue.delete(this.holidays, key)
              })
              .catch(error => {
                console.log(error)
              })
          }
        }
      })
    },
    getYear() {
      axios.get('/holiday/get-year')
        .then(response => {
          this.years = response.data.map(x => x.year)
        })
        .catch(error => {
          console.log(error)
        })
    }
  }
})
