<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class projectDetailList extends Controller
{
    public function showProjectDetailList(){
      $users = DB::select('select * from employees e inner join works w on e.id = w.id where w.prj_no = "PS170001"');
      return view('project_detail')->with('tables',$users);

    }
}
