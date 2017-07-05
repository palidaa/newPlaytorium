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
				<select class="form-control" name="type">
				 <option value=""></option>
				 <option value="Timesheet">Timesheet</option>
				 <option value="Summary Timesheet">Summary Timesheet</option>
				</select>
			  </div>
			</div>

			<p>Select year and month to export a report.</p>
			<div class="row">
			  <div class="form-group col-md-3">
				  <select class="form-control" name="year">
				   <option value="">Select Year</option>
				   <option value="2018">2018</option>
				   <option value="2017">2017</option>
				   <option value="2016">2016</option>
				  </select>
			  </div>

				<div class="form-group col-md-3">
				  <select class="form-control" name="month">
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
			  <p>Project</p>

			  <select class="form-control" name="project">
			   <option value="">Select Project</option>
			   @foreach($data as $eachdata)
				<option value={{ $eachdata->prj_no }}>{{ $eachdata->prj_name }}</option>
			   @endforeach
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
@endsection
