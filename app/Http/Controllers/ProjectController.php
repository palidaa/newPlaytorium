<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use App\Project;

class ProjectController extends Controller
{
    public function __construct()
    {
      $this->middleware('auth');
    }

    public function showProjectList(){
      $users = DB::select('select * from projects');
      return view('project')->with('projects',$users);

    }
    public function addProject(Request $request)
    {
        $project = new Project;
        $project->prj_no = $request->input('prj_no');
        $project->prj_name = $request->input('prj_name');
        $project->customer = $request->input('customer');
        $project->quo_no = $request->input('quo_no');
        $project->description =  $request->input('description');
        $project->status = 'In Progress';
        $project->save();
        return redirect()->route('project');
    }

    public function showProjectDetailList(String $id){
//    $members = DB::select('select * from employees e inner join works w on e.id = w.id where w.prj_no = ?',Auth::id());
      $members = DB::select('select * from employees e inner join works w on e.id = w.id where w.prj_no = ?',[$id]);
      $project = DB::select('select * from projects where prj_no=?',[$id]);

      return view('project_detail', compact('members','project'));
    }
}
