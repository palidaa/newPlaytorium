@extends('layouts.app')

@section('title', 'Leave request history')

@section('content')

<div class="container">
  <div class="row">
    <div class="col-md-10 col-md-offset-1">
    <h2>Leave request history</h2>
    <br>
          <table class="table table-hover table-striped">
            <tr style="font-size:20px;">
               <th>Leave Type</th>
               <th>From</th>
               <th>To</th>
               <th>Purpose</th>
               <th>Status</th>
             </tr>

             @for ($i = 0;$i<sizeof($leave_request_history);$i++)
              <tr>
                <td>{{ $leave_request_history[$i]->leave_type}}</td>
                <td>{{ $leave_request_history[$i]->leave_from }}</td>
                <td>{{ $leave_request_history[$i]->leave_to }}</td>
                <td>{{ $leave_request_history[$i]->purpose }}</td>
                <td style="color:#C4B20F;">{{ $leave_request_history[$i]->status }}</td>

              </tr>
             @endfor
          </table>
    </div>
  </div>
</div>
@endsection
