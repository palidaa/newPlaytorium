new Vue({
  el: '#project',
  data: {
    projects: [],
    search: '',
    filtered: []
  },
  mounted: function() {
    pace.start();
    this.fetch();
    console.log(this.filtered);
  },
  watch: {
    search: function(val) {
      this.filtered = [];
      if(val.lenght < 2 ) {
        this.filtered = this.projects;
      }
      else {
        var regexp = new RegExp(val, 'i');
        this.projects.forEach(project => {
          if(regexp.test(project.prj_no) || regexp.test(project.prj_name)) {
            this.filtered.push(project);
          }
        });
      }
    }
  },
  methods: {
    fetch: function() {
      axios.get('/project/fetch')
        .then(response => {
          console.log(response);
          this.projects = response.data;
          this.filtered = this.projects;
        })
        .catch(error => {
          console.log(error);
        });
    },
    view: function(project) {
      window.location.href = '/project/' + project.prj_no;
    }
  }
});
