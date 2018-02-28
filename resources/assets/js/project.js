let now = moment().format('YYYY-MM-DD')

new Vue({
  el: '#project',
  data: {
    projects: [],
    search: '',
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
    },
    currentPage: 1
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
      $('#to').datepicker('setStartDate', this.prj_from)
    });
    this.fetch()
  },
  computed: {
    filteredProject() {
      let filteredProject = []
      let regexp = new RegExp(this.search, 'i')
      // Filter
      this.projects.forEach(project => {
        if(regexp.test(project.prj_no) || regexp.test(project.prj_name) || regexp.test(project.customer) || regexp.test(project.quo_no)) {
          filteredProject.push(project)
        }
      })
      // Sort
      filteredProject.sort(this.sortFunction)
      // Paging
      startIndex = (this.currentPage -1) * 10
      endIndex = startIndex + 10
      filteredProject = filteredProject.slice(startIndex, endIndex)
      return filteredProject
    }
  },
  methods: {
    fetch() {
      axios.get('/project/fetch')
        .then(response => {
          this.projects = response.data
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
    },
    sortFunction(a, b) {
      if (a[this.sortKey] < b[this.sortKey]) {
        return -1 * this.sortOrders[this.sortKey]
      }
      else if (a[this.sortKey] > b[this.sortKey]) {
        return 1 * this.sortOrders[this.sortKey]
      }
      return 0
    },
    changePage(page) {
      this.currentPage = page
      if(this.currentPage < 1) {
        this.currentPage = 1
      }
      if(this.currentPage > this.projects.length / 10 + 1) {
        this.currentPage = parseInt(this.projects.length / 10 + 1)
      }
    }
  }
})