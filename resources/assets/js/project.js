let now = moment().format('YYYY-MM-DD')

new Vue({
  el: '#project',
  data: {
    projects: [],
    search: '',
    filtered: [],
    prj_no: '',
    prj_name: '',
    quo_no: '',
    prj_from: now,
    prj_to: now,
    customer: '',
    description: '',
    sortKey: '',
    sortOrders: {
      'prj_no': -1,
      'prj_name': -1,
      'customer': -1,
      'quo_no': -1,
      'prj_from': -1,
      'prj_to': -1,
      'status': -1
    }
  },
  mounted() {
    // datepicker setup
    $('.input-group.date').datepicker({
      maxViewMode: 2,
      format: 'yyyy-mm-dd',
      orientation: 'bottom auto',
      autoclose: true,
    }).on('changeDate', () => {
      this.prj_from = $('#prj_from').val();
      this.prj_to = $('#prj_to').val();
      if(moment(this.prj_to) < moment(this.prj_from)) {
        this.prj_to = this.prj_from
        $('#prj_to').val(this.prj_from)
      }
      console.log(this.prj_from)
      $('#to').datepicker('setStartDate', this.prj_from)
    });
    this.fetch()
    console.log(this.projects)
  },
  watch: {
    search(val) {
      this.filtered = []
      if(val.lenght < 2 ) {
        this.filtered = this.projects
      }
      else {
        var regexp = new RegExp(val, 'i')
        this.projects.forEach(project => {
          if(regexp.test(project.prj_no) || regexp.test(project.prj_name) || regexp.test(project.customer) || regexp.test(project.quo_no)) {
            this.filtered.push(project)
          }
        })
      }
    }
  },
  methods: {
    fetch() {
      axios.get('/project/fetch')
        .then(response => {
          console.log(response)
          this.projects = response.data
          this.projects.forEach(project => {
            axios.get('/project/hasMembers', {
              params: {
                prj_no: project.prj_no
              }
            }).then(response2 => {
              project.hasMembers = response2.data.hasMembers
            })
            .catch(error2 => {
              console.log(error2)
            })
          })
          this.filtered = this.projects
        })
        .catch(error => {
          console.log(error)
        })
    },
    show(project) {
      window.location.href = '/project/' + project.prj_no
    },
    store() {
      const project = {
        prj_no: this.prj_no,
        prj_name: this.prj_name,
        quo_no: this.quo_no,
        customer: this.customer,
        prj_from: this.prj_from,
        prj_to: this.prj_to,
        description: this.description,
        status: 'In Progress'
      }
      axios.post('/project/store', project)
        .then(response => {
          console.log(response)
          this.projects.unshift(project)
        })
        .catch(error => {
          console.log(error)
        })
    },
    destroy(index) {
      bootbox.confirm({
        title: 'Delete confirmation',
        message: 'Do you really want to cancel this project ?',
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
            axios.delete('/project/destroy', {
              params: {
                prj_no: this.projects[index].prj_no
              }
            })
            .then(response => {
              console.log(response)
              this.projects.splice(index, 1)
            })
            .catch(error => {
              console.log(error)
            })
          }
        }
      })
        .then(response => {
          console.log(response)
          this.projects.splice(index, 1)
        })
        .catch(error => {
          console.log(error)
        })
    },
    sortBy(key) {
      this.sortKey = key
      this.sortOrders[key] *= -1
      this.filtered.sort(this.sortFunction)
    },
    sortFunction(a, b) {
      if (a[this.sortKey] < b[this.sortKey]) {
        return -1 * this.sortOrders[this.sortKey]
      }
      else if (a[this.sortKey] > b[this.sortKey]) {
        return 1 * this.sortOrders[this.sortKey]
      }
      return 0
    }
  }
})