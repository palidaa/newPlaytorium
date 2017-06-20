@extends('layouts.app')

@section('title', 'Project Detail')

@section('content')

<div class="container">
  <div class="row">
    <div class="col-md-10 col-md-offset-1">

    <!-- Project Detail -->
    <div class="row">
      <div class="col-md-6">
        <p style="font-size:30px; font-weight:semibold;">Project Detail</p>
      </div>
    </div>
    <hr style="border-color:grey">
    <div class="row">
      <div class="col-md-3">
        <p style="font-size:18px; font-weight:bold;">Project Number</p>
      </div>
      <div class="col-md-9">
        <p style="font-size:18px;">PS170001</p>
      </div>
    </div>
    <div class="row">
      <div class="col-md-3">
        <p style="font-size:18px; font-weight:bold;">Project Name</p>
      </div>
      <div class="col-md-9">
        <p style="font-size:18px;">Function Lead Service for Project New Interaction Mgmt by Pega</p>
      </div>
    </div>
    <div class="row">
      <div class="col-md-3">
        <p style="font-size:18px; font-weight:bold;">Customer </p>
      </div>
      <div class="col-md-9">
        <p style="font-size:18px;">MFEC</p>
      </div>
    </div>
    <div class="row">
      <div class="col-md-3">
        <p style="font-size:18px; font-weight:bold;">Quotation number</p>
      </div>
      <div class="col-md-9">
        <p style="font-size:18px;">201701003</p>
      </div>
    </div>
    <div class="row">
      <div class="col-md-3">
        <p style="font-size:18px; font-weight:bold;">Description</p>
      </div>
    </div>
    <br>
    <hr style="border-color:grey">
    <!-- Project Members -->
    <div class="row">
      <div class="col-md-6">
      <p style="font-size:30px; font-weight:semibold;">Project Members</p>
      </div>
      <div class="col-md-6">
        <!-- add project button -->
        <button type="button" class="btn btn-default pull-right" data-toggle="modal" data-target="#myModal">
          <span class="glyphicon glyphicon-plus-sign"></span> Add member
        </button>

        <!-- Modal -->
        <div class="modal fade" id="myModal" role="dialog">
          <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add a member</h4>
              </div>
              <div class="modal-body">
                <div class="row">
                  <div class="col-md-4">
                    <label for="">Emp.No.</label>
                    <input type="text" class="form-control" name="" value="">
                  </div>
                  <div class="col-md-4 col-md-offset-1">
                    <label for="">Position</label>
                    <input type="text" class="form-control" name="" value="">
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal">Add</button>
              </div>
            </div>

          </div>
        </div>
      </div>
      </div>


          <table class="table table-hover table-striped">
            <tr style="font-size:20px;">
               <th>Emp.No.</th>
               <th>Name</th>
               <th>Position</th>
               <th>Role</th>
               <th></th>
             </tr>

             @for ($i = 0;$i<=4;$i++)
             <tr>
               <td>00001</td>
               <td>Noppawit  Thairungroj</td>
               <td>Tester</td>
               <td>Software Quality Control Engineer</td>
               <td><a href=#>x</a></td>
             </tr>
             @endfor
          </table>


      </div>
    </div>



@endsection
