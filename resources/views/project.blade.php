@extends('layouts.app')

@section('title', 'Project')

@section('content')

<div id="project" v-cloak>
  <div class="container">
    <div class="row">
      <div class="col-md-10 col-md-offset-1">
        <h2>Project</h2>
        <hr>
          <div class="col-md-6">
            <input type="text" v-model="search">
            <br>
            <br>
          </div>
      @if(Auth::user()->user_type=="Admin")
          <button type="button" class="btn btn-default pull-right" data-toggle="modal" data-target="#addProject">
            <span class="glyphicon glyphicon-plus-sign"></span> Add project
          </button>
      @endif
        <table class="table table-hover table-striped">
          <tr style="font-size:20px;">
             <th>Prj.No.</th>
             <th>Name</th>
             <th>Customer</th>
             <th>Quo.No.</th>
             <th>status</th>
           </tr>
           <tr class="click-table" v-for="project in filtered" @click="view(project)">
             <td>@{{ project.prj_no }}</td>
             <td>@{{ project.prj_name }}</td>
             <td>@{{ project.customer }}</td>
             <td>@{{ project.quo_no }}</td>
             <td style="color:#C4B20F;">@{{ project.status }}</td>
           </tr>
        </table>
      @if(Auth::user()->user_type=="Admin")
        <!-- Modal -->
        <div class="modal fade" id="addProject" role="dialog">
          <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add a project</h4>
              </div>
              <div class="modal-body">
                <form class="" id="form" action="/project/insert" method="post">
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
          @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@section('script')
<script src="{{ asset('js/project.js') }}"></script>
@endsection
