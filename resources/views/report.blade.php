@extends('layouts.app')

@section('title', 'Report')

@section('content')

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
				  <select class="form-control" name="year" id="year">
				   <option value="">Select Year</option>
				   
					@for($i=0;$i<20;$i++)
						<option value=<?php echo date("Y")-$i; ?>><?php echo date("Y")-$i; ?></option>
					@endfor
					
				  </select>
			  </div>

				<div class="form-group col-md-3">
				  <select class="form-control" name="month" id="month">
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
			
			<!--   @foreach($data as $eachdata)
				<option value={{ $eachdata->prj_no }}>{{ $eachdata->prj_name }}</option>
			   @endforeach -->
			  
			  <?php
			  $datas = DB::select('select distinct w.prj_no,p.prj_name from works w join projects p join timesheets t on w.prj_no=p.prj_no and t.id=w.id and t.prj_no=p.prj_no where w.id= ? and year(t.date)= ? and month(t.date)= ? order by w.prj_no',[$id,2017,02]);
			  	foreach($data as $eachdata){
			  		echo "<option value=".$eachdata->prj_no.">".$eachdata->prj_name."</option>";
			  	}

			  ?>
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
@endsection


<script type="text/javascript">
function showDiv(elem){
	if(elem.value == "Timesheet"){
		document.getElementById('month').style.display = "block";
		document.getElementById('project').style.display = "block";
		document.getElementById('selYM').innerHTML = "Select year and month to export a report.";
		document.getElementById('p').style.display = "block";
	}
	else if(elem.value == "Summary Timesheet"){
		document.getElementById('month').style.display = "none";
		document.getElementById('project').style.display = "none";
		document.getElementById('selYM').innerHTML = "Select year to export a report.";
		document.getElementById('p').style.display = "none";
	}
}
</script>