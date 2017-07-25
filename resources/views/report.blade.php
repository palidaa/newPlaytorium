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
				<select class="form-control" name="type" onchange="showDiv(this)" id="type">
				 <option value="Timesheet">Timesheet</option>
				 <option value="Summary Timesheet">Summary Timesheet</option>
				</select>
			  </div>
			</div>


			<p id="selYM">Select year and month to export a report.</p>
			<div class="row">
			  <div class="form-group col-md-3">
				  <select class="form-control" name="year" id="year" v-model="year" onchange="showProject(this,document.getElementById('month'))">
				   <option value="">Select Year</option>
				   
					@for($i=0;$i<20;$i++)
						<option value=<?php echo date("Y")-$i; ?>><?php echo date("Y")-$i; ?></option>
					@endfor
					
				  </select>
			  </div>

				<div class="form-group col-md-3">
				  <select class="form-control" name="month" id="month" v-model="month" onchange="showProject(document.getElementById('year'),this)">
					<option value="">Select Month</option>
					<option value="01">January</option>
					<option value="02">Febuary</option>
					<option value="03">March</option>
					<option value="04">April</option>
					<option value="05">Mar</option>
					<option value="06">June</option>
					<option value="07">July</option>
					<option value="08">August</option>
					<option value="09">September</option>
					<option value="10">October</option>
					<option value="11">November</option>
					<option value="12">December</option>
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
<script type="text/javascript">
  showDiv(document.getElementById('type'));
</script>
</div>
</div>
@endsection


<script type="text/javascript">
function showDiv(elem){
	if(elem.value == "Timesheet"){
		document.getElementById('month').style.display = "block";
		document.getElementById('project').style.display = "none";
		document.getElementById('selYM').innerHTML = "Select year and month to export a report.";
		document.getElementById('p').style.display = "none";
	}
	else if(elem.value == "Summary Timesheet"){
		document.getElementById('month').style.display = "none";
		document.getElementById('project').style.display = "none";
		document.getElementById('selYM').innerHTML = "Select year to export a report.";
		document.getElementById('p').style.display = "none";
	}
}
function showProject(elem1,elem2){
	if(elem1.value == "" || elem2.value == "" ){
		document.getElementById('month').style.display = "block";
		document.getElementById('project').style.display = "none";
		document.getElementById('selYM').innerHTML = "Select year and month to export a report.";
		document.getElementById('p').style.display = "none";
	}
	else{
		document.getElementById('month').style.display = "block";
		document.getElementById('project').style.display = "block";
		document.getElementById('selYM').innerHTML = "Select year and month to export a report.";
		document.getElementById('p').style.display = "block";
	}
}
</script>

@section('script')
<script src="{{ asset('js/report.js') }}"></script>
@endsection