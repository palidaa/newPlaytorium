@extends('layouts.app')

@section('title', 'Project')

@section('content')

<div id="project" v-cloak>
  <div class="container">
    <div class="row">
      <div class="col-md-10 col-md-offset-1">
        <h2>Project</h2>
        <hr>

        <div class="row">
          <div class="col-md-6">
            <div class="input-group">
              <input type="text" class="form-control" v-model="search" placeholder="Search project">
              <span class="input-group-addon" aria-hidden="true">
                <span class="glyphicon glyphicon-search"></span>
              </span>
            </div>
          </div>
          @if(Auth::user()->isAdmin())
            <div class="col-md-6">
              <button type="button" class="btn btn-default pull-right" data-toggle="modal" data-target="#addProject">
                <span class="glyphicon glyphicon-plus-sign"></span> Add project
              </button>
            </div>
          @endif
        </div>
        <br>

        <table class="table table-hover table-striped">
          <tr style="font-size:20px;">
             <th>Prj.No.</th>
             <th>Name</th>
             <th>Customer</th>
             <th>Quo.No.</th>
             <th>From</th>
             <th>To</th>
             <th>Status</th>
             <th><th>
           </tr>
           <tr class="click-table" v-for="(project, index) in filtered" @click="show(project)">
             <td>@{{ project.prj_no }}</td>
             <td>@{{ project.prj_name }}</td>
             <td>@{{ project.customer }}</td>
             <td>@{{ project.quo_no }}</td>
             <td>@{{ project.prj_from }}</td>
             <td>@{{ project.prj_to }}</td>
             <td style="color: #C4B20F;">@{{ project.status }}</td>
             @if(Auth::user()->isAdmin())
              <td><a href="#" @click.prevent.stop="destroy(index)">Delete</a></td>
             @endif
           </tr>
        </table>

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
                <div class="row">
                  <div class="col-md-6">
                    <label for="">Prj.No.</label>
                    <input type="text" class="form-control" name="prj_no" v-model="prj_no">
                  </div>
                  <div class="col-md-6">
                    <label for="">Name</label>
                    <input type="text" class="form-control" name="prj_name" v-model="prj_name">
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <label for="">Quo.No</label>
                    <input type="text" class="form-control" name="quo_no" v-model="quo_no">
                  </div>
                  <div class="col-md-6">
                    <label for="">Customer</label>
                    <input type="text" class="form-control" name="customer" v-model="customer">
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <label for="">From</label>
                    <div class="input-group date">
                      <input type="text" class="form-control" id="prj_from" v-model="prj_from" readonly>
                      <div class="input-group-addon">
                        <span class="glyphicon glyphicon-th"></span>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <label for="">To</label>
                    <div class="input-group date" id="to">
                      <input type="text" class="form-control" id="prj_to" v-model="prj_to" readonly>
                      <div class="input-group-addon">
                        <span class="glyphicon glyphicon-th"></span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <label for="">Description</label>
                    <textarea class='form-control' style="resize: none" name="description" v-model="description" rows="4"></textarea>
                  </div>
                </div>
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal" @click="store()">Add</button>
              </div>
            </div>

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
