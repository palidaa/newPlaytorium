@extends('layouts.app')

@section('title', 'Report')

@section('content')

<div id="report" v-cloak>
  <div class="container">
    <div class="row">
      <div class="col-md-8 col-md-offset-2">
        <h2>Report</h2>
        <hr>
        <div class="container">
          <div class="row">
            <div class="col-md-3">
              <label>Report type</label>
              <select class="form-control" v-model="type">
                <option>Timesheet</option>
                <option>Summary Timesheet</option>
              </select>
            </div>
          </div>
          <div class="row">
            <div class="col-md-3">
              <label>Year</label>
              <select class="form-control" v-model="selectedYear">
                <option v-for="year in years">@{{ year }}</option>
              </select>
            </div>
            <div class="col-md-3" v-show="type=='Timesheet'">
              <label>Month</label>
              <select class="form-control" v-model="selectedMonth">
                <option v-for="month in months">@{{ month }}</option>
              </select>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6" v-show="type=='Timesheet'">
              <label>Project</label>
              <select class="form-control" v-model="selectedProject">
                <option v-for="project in projects">@{{ project.prj_no }} - @{{ project.prj_name }}</option>
              </select>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-3">
              <button type="button" class="btn btn-default" @click="download">Download</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('script')
<script src="{{ asset('js/report.js') }}"></script>
@endsection
