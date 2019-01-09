@extends('layouts.app')

@section('title', 'Leave request history')

@section('content')

<div id="leaveHistory" v-cloak>
<div class="container">
  <div class="row">
    <div class="col-md-10 col-md-offset-1">
    <div class="row">
        <div class="col-md-11">
          <h2>Leave request history</h2>
        </div>
        <div class="col-md-1 ">
          <button style="margin-top:50%;" type="buttom" class="btn btn-default " onclick="location.href = '/leave_request';" >Back</button>
        </div>
        </div>
    <hr>
    <div class="row">
      <div class="col-md-3">
        <select class="form-control" v-model="leave_type">
        <option value="">Leave Type</option>
        <option value="Annual leave">Annual leave</option>
          <option value="Personal leave">Personal leave</option>
          <option value="Sick leave">Sick leave</option>
        </select>
      </div>
      <div class="col-md-3">
          <select class="form-control" v-model="year">
             <option value="">Year</option>
             <option v-for="year in years" v-bind:value="year.year">
              @{{ year.year }}
            </option>
        </select>
      </div>
    </div> 
    <br>
          <table class="table table-hover table-striped">
            <tr style="font-size:20px;">
               <th>Leave Type</th>
               <th>From</th>
               <th>To</th>
               <th>Purpose</th>
               <th>Status</th>
               <th></th>
             </tr>

             <!-- @for ($i = 0;$i<sizeof($leave_request_history);$i++)
              <tr>
                <td>{{ $leave_request_history[$i]->leave_type}}</td>
                <td>{{ $leave_request_history[$i]->leave_from }}</td>
                <td>{{ $leave_request_history[$i]->leave_to }}</td>
                <td>{{ $leave_request_history[$i]->purpose }}</td>
                <td style="color:#C4B20F;">{{ $leave_request_history[$i]->status }}</td>

              </tr>
             @endfor -->

              <tr v-for="(leaveHistory, index) in leaveHistorys">
                <td>@{{ leaveHistory.leave_type }}</td>
                <td>@{{ leaveHistory.leave_from }}</td>
                <td>@{{ leaveHistory.leave_to }}</td>
                <td>@{{ leaveHistory.purpose }}</td>
                <td style="color:#C4B20F;">@{{ leaveHistory.status }}</td>
                <td v-if="leaveHistory.status=='Pending' && moment(leaveHistory.leave_to, 'YYYY-MM-DD').diff(moment().format('YYYY-MM-DD'), 'days') >= 0"><a href="#" @click.prevent="remove(index)">Cancel</td>                <td v-else><a href =""></td>
              </tr>

          </table>
    </div>
  </div>
</div>
</div>
@endsection

@section('script')
<script src="{{ asset('js/leaveHistory.js') }}"></script>
@endsection