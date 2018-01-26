@extends('layouts.app')

@section('title', 'Timesheet')

@section('content')

<div id="timesheet" v-cloak>
  <div class="container">
    <div class="row">
      <div class="col-md-8 col-md-offset-2">
        <h2>Timesheet</h2>
        <hr>

        <div class="progress">
          <div class="progress-bar" role="progressbar" :style="{width: totalTimesheets/workingDay*100 + '%'}">
            @{{ parseInt(totalTimesheets/workingDay*100) }}%
          </div>
        </div>
        <!-- date -->
        <label>Date</label>
        <div class="row">
          <div class="form-group col-md-3">
            <div class="input-group date">
              <input type="text" class="form-control" id="dateInput" v-model="date" readonly>
              <div class="input-group-addon">
                <span class="glyphicon glyphicon-th"></span>
              </div>
            </div>
          </div>
          <div class="col-md-9">
            <a href="/timesheet/new" class="btn btn-default pull-right">New task</a>
          </div>
        </div>

        <!-- timesheet -->
        <div class="panel panel-default" :class="{ 'panel-danger': isWeekend(timesheet) }" v-for="(timesheet, key) in timesheets">
          <div class="panel-heading">@{{ timesheet.prj_no }} - @{{ timesheet.prj_name }}
            <div class="btn-group pull-right">
              <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" style="-webkit-box-shadow: none;">
                <span class="caret"></span>
              </a>
              <ul class="dropdown-menu dropdown-menu-right">
                <li>
                  <a href="#" @click.prevent="select(timesheet, key)" data-toggle="modal" data-target="#edit">Edit</a>
                </li>
                <li>
                  <a href="#" @click.prevent="remove(key)">Delete</a>
                </li>
              </ul>
            </div>
          </div>
          <div class="panel-body">
            <p>Date: @{{ timesheet.date }}, @{{ timesheet.dayOfWeek }}</p>
            <p>Time: @{{ timesheet.time_in }} - @{{ timesheet.time_out }}</p>
            <p>Task: @{{ timesheet.task_name }}</p>
            <p>Description: @{{ timesheet.description }}</p>
          </div>
        </div>
        <p v-if="timesheets.length == 0">No task has been added.</p>

        <!-- Modal -->
        <div class="modal fade" id="edit" role="dialog">
          <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit</h4>
              </div>
              <div class="modal-body">
                <form>
                  <div class="row">
                    <div class="col-md-6">
                      <label>Project</label>
                      <select class="form-control">
                        <option v-for="project in projects">@{{ project.prj_no }} - @{{ project.prj_name }}</option>
                      </select>
                    </div>
                    <div class="col-md-6">
                      <label>Task name</label>
                      <select class="form-control" v-model="selectedTimesheet.task_name">
                        <option>Dev</option>
                        <option>Testing</option>
                        <option>Training</option>
                        <option>Meeting</option>
                      </select>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <label>Time in</label>
                      <input type="time" class="form-control" v-model="selectedTimesheet.time_in">
                    </div>
                    <div class="col-md-6">
                      <label>Time out</label>
                      <input type="time" class="form-control" v-model="selectedTimesheet.time_out">
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <label>Description</label>
                      <textarea class="form-control" rows="4" v-model="selectedTimesheet.description"></textarea>
                    </div>
                  </div>
                </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal" @click="update">Save</button>
              </div>
            </div>

          </div>
        </div>

      </div>
    </div>
  </div>
</div>
@endsection

@section('script')
<script src="{{ asset('js/timesheet.js') }}"></script>
@endsection
