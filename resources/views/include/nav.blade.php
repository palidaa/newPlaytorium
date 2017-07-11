<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <div class="navbar-brand">
        <img src="{{URL::asset('/images/logo.png')}}" style="max-width:120px; margin-top: -7px;">
      </div>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav">
        <li><a href="/timesheet">Timesheet</a></li>
        <li><a href="/leave_request">Leave Request</a></li>
        <li><a href="/project">Project</a></li>
		    <li><a href="/report">Report</a></li>

      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">
          <span class="glyphicon glyphicon-user"></span> {{ Auth::user()->name }}
          <span class="caret"></span></a>
          <ul class="dropdown-menu">
            @if(Auth::user()->user_type=="Admin")
              <li><a href="/admin/holiday">Add Holiday</a></li>
              <li><a href="/admin/new_user">Add New user</a></li>
            @endif
            <li><a href="{{ route('logout') }}"
                onclick="event.preventDefault();
                         document.getElementById('logout-form').submit();">
                         Logout
                </a>
            </li>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>
