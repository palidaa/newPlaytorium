@extends('layouts.app')

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
        @if ($type=='Admin')
          <button type="button" class="btn btn-default pull-right" data-toggle="modal" data-target="#myModal">
            <span class="glyphicon glyphicon-plus-sign"></span> Add project
          </button>
        @endif

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
                <form class="" id="form" action="/project/addProject" method="post">
                  {{ csrf_field() }}

                  <div class="row">
                    <div class="col-md-3">
                      <label for="">Prj.No.</label>
                      <input type="text" class="form-control" name="prj_no" value="">
                    </div>
                    <div class="col-md-8">
                      <label for="">Name</label>
                      <input type="text" class="form-control" name="prj_name" value="">
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-3">
                      <label for="">Quo.No</label>
                      <input type="text" class="form-control" name="quo_no" value="">
                    </div>
                    <div class="col-md-8">
                      <label for="">Customer</label>
                      <input type="text" class="form-control" name="customer" value="">
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-11">
                      <label for="">Description</label>
                      <textarea class='form-control' style="resize: none" name="description" rows="4"></textarea>
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

      <form method="GET" action="/project/search">

        <label>Project number </label>
        <input type="text" name ="prj_no" value = "{{ $num }}">

        <label>Name</label>
        <input type="text" name="prj_name" value = "{{ $name }}">

        <button type="submit" class="btn btn-primary" >Search</button>
      </form>

          <table class="table table-hover table-striped">
            <tr style="font-size:20px;">
               <th>Prj.No.</th>
               <th>Name</th>
               <th>Customer</th>
               <th>Quo.No.</th>
               <th>status</th>
             </tr>

             @if ( sizeof($projects) ==0)
                <td> No result were found !!</td>

             @else

             @foreach($projects as $project)
              <tr class="project" value="{{ $project->prj_no }}">
                <td>{{ $project->prj_no }}</td>
                <td>{{ $project->prj_name  }}</td>
                <td>{{ $project->customer }}</td>
                <td>{{ $project->quo_no }}</td>
                <td style="color:#C4B20F;">{{ $project->status }}</td>
              </tr>
             @endforeach

             @endif

<!--
             @for ($i = 0;$i<=2;$i++)
             <tr>
               <td>PS170001</td>
               <td>Function Lead Service for Project New Interaction Mgmt by Pega</td>
               <td>MFEC</td>
               <td>201701003</td>
               <td style="color:#0FC40F;">Done</td>
             </tr>
             @endfor
-->
          </table>


      </div>
    </div>
@endsection

@section('script')
<script type="text/javascript">
  $(document).ready(function(){
    $('tr.project').each(function(){
      $(this)
      .hover(function(){
        $(this).css( 'cursor', 'pointer' );
      })
      .click(function(){
        window.location.href = '/project/' + $(this).attr('value');
      });
    });
  });
</script>
@endsection
