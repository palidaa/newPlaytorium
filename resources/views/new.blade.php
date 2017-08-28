@extends('layouts.app')

@section('title', 'Insert')

@section('content')
<div id="new" v-cloak>
  <div v-if="errors" class="alert alert-danger">
    <div class="container" style="padding-bottom: 0px">
      <div class="row">
        <div class="col-md-8 col-md-offset-2">
          <strong>Please select a project!</strong>
        </div>
      </div>
    </div>
  </div>
  <div class="container">
    <div class="row">
      <div class="col-md-8 col-md-offset-2">
        <h2>New task</h2>
        <hr>

        <!-- head form -->
        <div class="form-group">
          <div class="row">
            <div class="col-md-4">
              <label class="control-label">Project</label>
              <select class="form-control" v-model="selectedProject">
                <option disabled hidden value="">Please select a prject</option>
                <option v-for="project in projects">@{{ project.prj_no }} - @{{ project.prj_name }}</option>
              </select>
            </div>
            <div class="col-md-4">
              <label class="control-label">Task name</label>
              <select class="form-control" v-model="task_name">
                <option>Dev</option>
                <option>Testing</option>
                <option>Design</option>
                <option>Training</option>
                <option>Meeting</option>
              </select>
            </div>
          </div>
        </div>

        <!-- date -->
        <div class="row">
          <div class="form-group col-md-3">
            <label for="startDate">Start date</label>
            <div class="input-group date" id="fromDatepicker">
              <input type="text" class="form-control" id="startDateInput" v-model="startDate" readonly>
              <div class="input-group-addon">
                <span class="glyphicon glyphicon-th"></span>
              </div>
            </div>
          </div>
          <div class="form-group col-md-3">
            <label for="endDate">End date</label>
            <div class="input-group date" id="toDatepicker">
              <input type="text" class="form-control" id="endDateInput" v-model="endDate" readonly>
              <div class="input-group-addon">
                <span class="glyphicon glyphicon-th"></span>
              </div>
            </div>
          </div>
        </div>

        <hr>

        <div v-for="(task, index) in tasks">
          <div class="panel panel-default">
            <div class="panel-heading">
              @{{ task.date }}
              <span class="close" @click="removeTask(task, index)">&times;</span>
            </div>
            <div class="panel-body">
              <div class="row">
                <div class="col-md-3">
                  <input type="time" class="form-control" v-model="task.time_in">
                </div>
                <div class="col-md-3">
                  <input type="time" class="form-control" v-model="task.time_out">
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-md-12">
                  <textarea name="name" class="form-control" rows="3" cols="80" placeholder="Description" v-model="task.description"></textarea>
                </div>
              </div>
            </div>
          </div>
        </div>

        <button type="button" class="btn btn-default" @click="submit">Submit</button>

      </div>
    </div>
  </div>
</div>

@endsection

@section('script')
<script src="{{ asset('js/new.js') }}"></script>
@endsection
