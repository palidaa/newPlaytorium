@extends('layouts.app')

@section('title', 'Timesheet')

@section('content')

<div id="timesheet">
  <div class="container">
    <div class="row">
      <div class="col-md-8 col-md-offset-2">
        <h2>Timesheet</h2>
        <hr>

        <!-- date -->
        <div class="row">
          <div class="form-group col-md-3">
            <label>Date</label>
            <div class="input-group date">
              <input type="text" class="form-control" id="dateInput" v-model="date">
              <div class="input-group-addon">
                <span class="glyphicon glyphicon-th"></span>
              </div>
            </div>
          </div>
        </div>

        <!-- timesheet -->
        <div class="panel panel-default" v-for="(timesheet, key) in timesheets">
          <div class="panel-heading">@{{ timesheet.prj_no }} - @{{ timesheet.task_name }}
            <div class="btn-group pull-right">
              <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
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
              Time: @{{ timesheet.time_in }} - @{{ timesheet.time_out }}
              <br>
              Description: @{{ timesheet.description }}
          </div>
        </div>

        <a href="/timesheet/new" class="btn btn-default">New task</button></a>

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
                      <label for="">Prj.No.</label>
                      <input type="text" class="form-control" v-model="selectedTimesheet.prj_no">
                    </div>
                    <div class="col-md-6">
                      <label for="">Task name</label>
                      <input type="text" class="form-control" v-model="selectedTimesheet.task_name">
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <label for="">Time in</label>
                      <input type="time" class="form-control" v-model="selectedTimesheet.time_in">
                    </div>
                    <div class="col-md-6">
                      <label for="">Time out</label>
                      <input type="time" class="form-control" v-model="selectedTimesheet.time_out">
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <label for="">Description</label>
                      <textarea class="form-control" rows="4" v-model="selectedTimesheet.description"></textarea>
                    </div>
                  </div>
                </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" @click="update">Save</button>
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
