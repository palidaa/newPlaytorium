@extends('layouts.app')

@section('title', 'Project Detail')

@section('content')

<div class="container">
  <div class="row">
    <div class="col-md-10 col-md-offset-1">

    <!-- Project Detail -->
    <h2>Project Detail</h2>
    <hr>
    <div class="row">
      <div class="col-md-3">
        <p style="font-size:18px; font-weight:bold;">Project Number</p>
      </div>
      <div class="col-md-9">
        <p style="font-size:18px;">{{ $project->prj_no}}</p>
      </div>
    </div>
    <div class="row">
      <div class="col-md-3">
        <p style="font-size:18px; font-weight:bold;">Project Name</p>
      </div>
      <div class="col-md-9">
        <p style="font-size:18px;">{{ $project->prj_name}}</p>
      </div>
    </div>
    <div class="row">
      <div class="col-md-3">
        <p style="font-size:18px; font-weight:bold;">Customer </p>
      </div>
      <div class="col-md-9">
        <p style="font-size:18px;">{{ $project->customer}}</p>
      </div>
    </div>
    <div class="row">
      <div class="col-md-3">
        <p style="font-size:18px; font-weight:bold;">Quotation number</p>
      </div>
      <div class="col-md-9">
        <p style="font-size:18px;">{{ $project->quo_no}}</p>
      </div>
    </div>
    <div class="row">
      <div class="col-md-3">
        <p style="font-size:18px; font-weight:bold;">Description</p>
      </div>
      <div class="col-md-9">
        <p style="font-size:18px;">{{ $project->description}}</p>
      </div>
    </div>
    <br>
    <hr>

    <!-- Project Members -->
    <div class="row">
      <div class="col-md-6">
        <p style="font-size:30px; font-weight:semibold;">Project Members</p>
      </div>

      <div class="col-md-6">
      @if(Auth::user()->user_type=="Admin")
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
                <form id="form" action="/project/insertMember" method="post">
                  {{ csrf_field() }}
                  <input type="hidden" name="prj_no" value="{{ $project->prj_no}}">
                <div class="row">
                  <div class="col-md-4">
                    <label for="">Emp.No.</label>
                    <input type="text" class="form-control" name="id" value="">
                  </div>
                  <div class="col-md-4 col-md-offset-1">
                    <label for="">Position</label>
                    <input type="text" class="form-control" name="position" value="">
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
        @endif
      </div>
    </div>

    <table class="table table-hover table-striped">
      <tr style="font-size:20px;">
         <th>Emp.No.</th>
         <th>Name</th>
         <th>Position</th>
         <th>Role</th>
         @if(Auth::user()->user_type=="Admin")
          <th></th>
         @endif
       </tr>

       @foreach($members as $member)
       <tr>
         <td>{{ $member->id }}</td>
         <td>{{ $member->first_name." ".$member->last_name }}</td>
         <td>{{ $member->position }}</td>
         <td>{{ $member->role }}</td>
         @if(Auth::user()->user_type=="Admin")
          <form action="/project/deleteMember" method="post">
             {{ csrf_field() }}
           <input type="hidden" name="id" value="{{$member->id}}">
           <input type="hidden" name="prj_no" value="{{ $project->prj_no}}">
            <td><button type="submit" class="btn btn-primary" >x</a></td>
          </form>
         @endif
       </tr>
       @endforeach
    </table>

  </div>
</div>



@endsection
