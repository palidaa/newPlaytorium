new Vue({
  el: '#project',
  data: {
    projects: [],
    search: '',
    filtered: [],
    prj_no: '',
    prj_name: '',
    quo_no: '',
    customer: '',
    description: ''
  },
  mounted() {
    this.fetch()
    console.log(this.filtered)
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
