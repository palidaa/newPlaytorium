@extends('layouts.app')

@section('title', 'Insert')

@section('content')
<div id="new">
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
                <option v-for="project in projects">@{{ project.prj_no }} - @{{ project.prj_name }}</option>
              </select>
            </div>
            <div class="col-md-4">
              <label class="control-label">Task name</label>
              <select class="form-control" v-model="task_name">
                <option value="Dev">Dev</option>
                <option value="Testing">Testing</option>
                <option value="Training">Training</option>
                <option value="Meeting">Meeting</option>
              </select>
            </div>
          </div>
        </div>

        <!-- date -->
        <div class="row">
          <div class="form-group col-md-3">
            <label for="startDate">Start date</label>
            <div class="input-group date">
              <input type="text" class="form-control" id="startDateInput" v-model="startDate">
              <div class="input-group-addon">
                <span class="glyphicon glyphicon-th"></span>
              </div>
            </div>
          </div>
          <div class="form-group col-md-3">
            <label for="endDate">End date</label>
            <div class="input-group date">
              <input type="text" class="form-control" id="endDateInput" v-model="endDate">
              <div class="input-group-addon">
                <span class="glyphicon glyphicon-th"></span>
              </div>
            </div>
          </div>
        </div>

        <hr>

        <div v-for="task in tasks">
          <div class="row">
            <div class="col-md-3">
              <input type="text" class="form-control" v-model="task.date" disabled>
            </div>
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
          <br>
        </div>

        <button type="button" class="btn btn-default" @click="submit">Submit</button>

        <br>
        <br>

      </div>
    </div>
  </div>
</div>

@endsection

@section('script')
<script src="{{ asset('js/new.js') }}"></script>
@endsection
