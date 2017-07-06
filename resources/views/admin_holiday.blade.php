@extends('layouts.app')

@section('title', 'Holiday')

@section('content')

<div class="container">
  <div class="row">
    <div class="col-md-10 col-md-offset-1">

    <!-- Holiday list -->
    <div class="row">
      <div class="col-md-6">
      <p style="font-size:30px; font-weight:semibold;">Holiday</p>
      </div>
      <div class="col-md-6">
        <button type="button" class="btn btn-default pull-right" data-toggle="modal" data-target="#myModal">
          <span class="glyphicon glyphicon-plus-sign"></span> Add holiday
        </button>

        <!-- Modal -->
        <div class="modal fade" id="myModal" role="dialog">
          <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add a holiday</h4>
              </div>
              <div class="modal-body">
                <form id="form" action="/admin/holiday/addHoliday" method="post">
                  {{ csrf_field() }}

                <div class="row">
                  <div class="col-md-4">
                    <label for="">Date</label>
                    <div class="input-group date" data-provide="datepicker" data-date-format="yyyy-mm-dd" data-date-autoclose="true">
                      <input type="text" class="form-control" name="holiday" id="date">
                      <div class="input-group-addon">
                        <span class="glyphicon glyphicon-th"></span>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4 col-md-offset-1">
                    <label for="">Name</label>
                    <input type="text" class="form-control" name="date_name" value="">
                  </div>
                </div>
                  </form>
              </div>



              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary" form="form">Add</button>
              </div>

            </div>

          </div>
        </div>

      </div>
      </div>


          <table class="table table-hover table-striped">
            <tr style="font-size:20px;">
               <th>No.</th>
               <th>Date</th>
               <th>Name</th>
             </tr>
             <?php $i = 1; ?>
             @foreach($holidays as $holiday)
            <tr>
               <td>{{ $i }}</td>
               <td>{{ $holiday->holiday }}</td>
               <td>{{ $holiday->date_name }}</td>
               <form action="/admin/holiday/deleteHoliday" method="post">
                 {{ csrf_field() }}
               <input type="hidden" name="holiday" value="{{$holiday->holiday}}">
               <input type="hidden" name="date_name" value="{{$holiday->date_name}}">
               <td><button type="submit" class="btn btn-primary" >x</a></td>
              </form>
             </tr>
             <?php $i = $i + 1; ?>
             @endforeach
          </table>

      </div>
    </div>



@endsection
