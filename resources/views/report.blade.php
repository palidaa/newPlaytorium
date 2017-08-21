@extends('layouts.app')

@section('title', 'Report')

@section('content')

<div id="report" v-cloak>
<div class="container">
  <div class="row">
    <div class="col-md-8 col-md-offset-2">
      <h2>Report</h2>
      <hr>

      <!-- Detail -->
	  <form action="/report/export" method="post">
      {{ csrf_field() }}
		  <div class="container-fluid">
			<div class="row">
			  <div class="form-group col-md-4">
				<label>Type</label>
				<br>
				<select class="form-control" name="type" onchange="showDiv(this)" id="type" v-model="type">
				<option value="">Select Type</option>
				 <option value="Timesheet">Timesheet</option>
				 <option value="Summary Timesheet">Summary Timesheet</option>
				</select>
			  </div>
			</div>


			<p id="selYM">Select year and month to export a report.</p>
			<div class="row">
			  <div class="form-group col-md-3">
				  <select class="form-control" name="year" id="year" v-model="year">
				   <option value="">Select Year</option>
					   <option v-for="year in years" v-bind:value="year.year">
					    @{{ year.year }}
					  </option>
				  </select>
			  </div>

				<div class="form-group col-md-3">
				  <select class="form-control" name="month" id="month" v-model="month">
					<option value="">Select Month</option>
					<option v-for="month in months" v-bind:value="month.month">
					    @{{ month.monthname }}
					  </option>
				  </select>
			  </div>

			</div>
			  <p id="p">Project</p>
			  <select class="form-control" name="project" id="project" >
			   <option value="">Select Project</option>
			    <option v-for="project in projects" v-bind:value="project.prj_no">
				    @{{ project.prj_no }} - @{{ project.prj_name }}
				  </option>

				</select>

		  <br>
		  <br>

		  <!-- Button -->
		  <button type="submit" class="btn btn-default" data-toggle="modal" data-target="#myModal">View</button>
		  </div>
		</form>
    </div>
  </div>
</div>
</div>
</div>
@endsection

@section('script')
<script src="{{ asset('js/report.js') }}"></script>


<script type="text/javascript">
function showDiv(elem){
	if(elem.value == ""){
		document.getElementById('month').style.display = "none";
		document.getElementById('project').style.display = "none";
		document.getElementById('selYM').style.display = "none";
		document.getElementById('p').style.display = "none";
		document.getElementById('year').style.display = "none";
	}else if(elem.value == "Timesheet"){
		document.getElementById('year').style.display = "block";
		document.getElementById('month').style.display = "block";
		document.getElementById('project').style.display = "block";
		document.getElementById('selYM').style.display = "block";
		document.getElementById('selYM').innerHTML = "Select year and month to export a report.";
		document.getElementById('p').style.display = "block";
	}
	else {
		document.getElementById('year').style.display = "block";
		document.getElementById('month').style.display = "none";
		document.getElementById('project').style.display = "none";
		document.getElementById('selYM').style.display = "block";
		document.getElementById('selYM').innerHTML = "Select year to export a report.";
		document.getElementById('p').style.display = "none";
	}
}
</script>

@endsection
