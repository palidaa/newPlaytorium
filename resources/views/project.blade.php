@extends('layout.app')

@section('title', 'Project')


@section('content')

<div class="container">
  <div class="row">
    <div class="col-md-10 col-md-offset-1">

    <div class="row">
      <div class="col-md-6">
      <p style="font-size:30px; font-weight:semibold;">Project</p>
      </div>
      <div class="col-md-6">
        <!-- add project button -->
        <button type="button" class="btn btn-default pull-right" data-toggle="modal" data-target="#myModal">
          <span class="glyphicon glyphicon-plus-sign"></span> Add project
        </button>

        <!-- Modal -->
        <div class="modal fade" id="myModal" role="dialog">
          <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add a project</h4>
              </div>
              <div class="modal-body">
                <p>Some text in the modal.</p>
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
               <th>Prj.No.</th>
               <th>Name</th>
               <th>Customer</th>
               <th>Quo.No.</th>
               <th>status</th>
             </tr>

             @for ($i = 0;$i<=4;$i++)
             <tr>
               <td>PS170001</td>
               <td>Function Lead Service for Project New Interaction Mgmt by Pega</td>
               <td>MFEC</td>
               <td>201701003</td>
               <td style="color:#C4B20F;">In Progress</td>
             </tr>
             @endfor

             @for ($i = 0;$i<=2;$i++)
             <tr>
               <td>PS170001</td>
               <td>Function Lead Service for Project New Interaction Mgmt by Pega</td>
               <td>MFEC</td>
               <td>201701003</td>
               <td style="color:#0FC40F;">Done</td>
             </tr>
             @endfor
          </table>


      </div>
    </div>



@endsection

