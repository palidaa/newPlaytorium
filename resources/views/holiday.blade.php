@extends('layouts.app')

@section('title', 'Manage Holiday')

@section('content')

<div id="holiday" v-cloak>
  <div class="container">
    <div class="row">
      <div class="col-md-8 col-md-offset-2">
        <h2>Holiday</h2>
        <hr>
        <div class="row">
          <div class="col-md-3">
            <select class="form-control" v-model="selectedYear">
              <option v-for="year in years">@{{ year }}</option>
            </select>
          </div>
          @if(Auth::user()->isAdmin())
            <div class="col-md-9">
              <button type="button" class="btn btn-default pull-right" data-toggle="modal" data-target="#addHoliday">Add holiday</button>
            </div>
          @endif
        </div>
        <br>

        <!-- Modal -->
          <div class="modal fade" id="addHoliday" role="dialog">
            <div class="modal-dialog">

              <!-- Modal content-->
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Add holiday</h4>
                </div>
                <div class="modal-body">
                  <div class="row">
                    <div class="col-md-5">
                      <label>Date</label>
                      <div class="input-group date">
                        <input type="text" class="form-control" id="datepicker"><span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                      </div>
                    </div>
                    <div class="col-md-7">
                      <label>Date name</label>
                      <input type="text" class="form-control" v-model="date_name">
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn" @click="store">Add</button>
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
              </div>

            </div>
          </div>

        <table class="table table-striped">
          <tr>
            <th>Date</th>
            <th>Date name</th>
            <th></th>
          </tr>
          <tr v-for="(holiday, key) in holidays">
            <td>@{{ holiday.holiday }}</td>
            <td>@{{ holiday.date_name }}</td>
            @if(Auth::user()->isAdmin())
              <td><a href="#" @click.prevent="destroy(key)">Delete</a></td>
            @endif
          </tr>
        </table>
      </div>
    </div>
  </div>
</div>

@endsection

@section('script')
<script src="{{ asset('js/holiday.js') }}"></script>
@endsection
