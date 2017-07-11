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

    public function index() {
      return view('project');
    }

    public function fetch() {
      if(Auth::user()->type == 'admin') {
        $projects = Project::all();
        return $projects;
      }
      else {
        $projects = DB::table('projects')
                      ->join('works', 'projects.prj_no', '=', 'works.prj_no')
                      ->where('works.id', Auth::id())
                      ->get();
        return $projects;
      }
    }

    public function insert(Request $request) {
        $project = new Project;
        $project->prj_no = $request->input('prj_no');
        $project->prj_name = $request->input('prj_name');
        $project->customer = $request->input('customer');
        $project->quo_no = $request->input('quo_no');
        $project->description = $request->input('description');
        $project->status = 'In Progress';
        $project->save();
        return redirect()->route('project');
    }

    public function view($prj_no) {
      $project = Project::find($prj_no);
      $members = DB::table('employees')
                    ->join('works', 'employees.id', '=', 'works.id')
                    ->where('works.prj_no', $prj_no)
                    ->get();
      return view('project_detail', compact('project', 'members'));
    }
}
