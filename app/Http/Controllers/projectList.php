<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class projectList extends Controller
{
    public function showProjectList(){
      $users = DB::select('select * from projects');
      return view('project')->with('project',$users);

    }
}
