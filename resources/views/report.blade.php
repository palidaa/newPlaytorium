@extends('layouts.app')

@section('title', 'Report')

@section('content')

<div class="container">
  <div class="row">
    <div class="col-md-8 col-md-offset-2">
      <h2>Report</h2>
      <hr>

      <!-- Detail -->
      <div class="container-fluid">
        <div class="row">
          <div class="form-group col-md-3">
            <label>Type</label>
            <br>
            <select style="">
             <option value=""></option>
             <option value="Timesheet">Timesheet</option>
             <option value="Summary Timesheet">Summary Timesheet</option>
            </select>
          </div>
        </div>

        <p>Select month and year to export a report</p>
        <div class="row">
          <div class="form-group col-md-2">
              <select>
               <option value="">YYYY</option>
               <option value="2018">2018</option>
               <option value="2017">2017</option>
               <option value="2016">2016</option>
              </select>
          </div>

            <div class="form-group col-md-2">
              <select>
                <option value="">MM</option>
                <option value="01">01</option>
                <option value="02">02</option>
                <option value="03">03</option>
                <option value="04">04</option>
                <option value="05">05</option>
                <option value="06">06</option>
                <option value="07">07</option>
                <option value="08">08</option>
                <option value="09">09</option>
                <option value="10">10</option>
                <option value="11">11</option>
                <option value="12">12</option>
              </select>
          </div>

        </div>
          <p>Project</p>

          <select>
           <option value=""></option>
           <option value="Function Lead Service for Project New Interaction Mgmt by Pega">Function Lead Service for Project New Interaction Mgmt by Pega</option>
           <option value="Function Lead Service for Project New Interaction Mgmt by Pega">Function Lead Service for Project New Interaction Mgmt by Pega</option>
           <option value="Function Lead Service for Project New Interaction Mgmt by Pega">Function Lead Service for Project New Interaction Mgmt by Pega</option>
          </select>
      <br>
      <br>

      <!-- Button -->
      <button type="button" class="btn btn-default" data-toggle="modal" data-target="#myModal">View</button>

      </div>

    </div>
  </div>
</div>

</div>
@endsection
