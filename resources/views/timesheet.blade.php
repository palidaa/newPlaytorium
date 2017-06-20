@extends('layouts.app')

@section('title', 'Timesheet')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-8 col-md-offset-2">
      <h2>Timesheet</h2>
      <hr>

      <!-- date -->
      <div class="row">
        <div class="form-group col-md-3">
          <label for="date">Date</label>
          <div class="input-group date" data-provide="datepicker" data-date-format="yyyy-mm-dd" data-date-autoclose="true">
            <input type="text" class="form-control" id="dateInput">
            <div class="input-group-addon">
              <span class="glyphicon glyphicon-th"></span>
            </div>
          </div>
        </div>
      </div>

      <!-- timesheet -->
      <div id="timesheets">
        @foreach($timesheets as $timesheet)
        <div class="panel panel-default">
          <div class="panel-heading">{{ $timesheet->prj_no }} - {{ $timesheet->task_name }}
            <div class="btn-group pull-right">
              <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                <span class="caret"></span>
              </a>
              <ul class="dropdown-menu dropdown-menu-right">
                <li>
                  <a href="#">Edit</a>
                </li>
                <li>
                  <a href="#">Delete</a>
                </li>
              </ul>
            </div>
          </div>
          <div class="panel-body">
              Time: {{ $timesheet->time_in }} - {{ $timesheet->time_out }}
              <br>
              Description: {{ $timesheet->description }}
          </div>
        </div>
        @endforeach
      </div>
      <button type="button" class="btn btn-default" data-toggle="modal" data-target="#myModal">Add task</button>


      <!-- Modal -->
      <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">

          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Add a task</h4>
            </div>
            <div class="modal-body">
              <form class="form-horizontal" id="form" action="/timesheet/addTask" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="date" id="date">

                <div class="form-group">
                  <div class="col-md-3">
                    <label class="control-label" for="timeIn">Time in</label>
                    <input type="time" class="form-control" name="time_in" id="timeIn" value="9.00">
                  </div>
                  <div class="col-md-3">
                    <label class="control-label" for="timeOut">Time out</label>
                    <input type="time" class="form-control" name="time_out" id="timeOut" value="18.00">
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-md-6">
                    <label class="control-label" for="projectName">Project No.</label>
                    <input type="text" class="form-control" name="prj_no" id="projectName">
                  </div>
                  <div class="col-md-6">
                    <label class="control-label" for="taskName">Task name</label>
                    <select class="form-control" name="task_name" id="taskName">
                      <option value="Dev">Dev</option>
                      <option value="Testing">Testing</option>
                      <option value="Training">Training</option>
                      <option value="Meeting">Meeting</option>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-md-12">
                    <label class="control-label" for="description">Description</label>
                    <textarea class="form-control" style="resize: none" name="description" id="description" rows="4"></textarea>
                  </div>
                </div>
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
              <button type="button" class="btn btn-primary" onclick="submit()">Add</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('script')
<script type="text/javascript">
$(document).ready(function(){
  $('#dateInput').change(function(){
    var date = $('#dateInput').val();
    $('#timesheets').load('timesheet?date=' + date + ' #timesheets');
  });
});
</script>
@endsection
