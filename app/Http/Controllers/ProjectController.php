<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use App\Project;
use App\Work;

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
      if(Auth::user()->isAdmin()) {
        $projects = Project::orderBy('projects.status','desc')
                    ->orderBy('prj_no','desc')
                    ->get();
        return $projects;
      }
      else {
        $projects = Project::join('works', 'projects.prj_no', '=', 'works.prj_no')
                      ->where('works.id', Auth::id())
                      ->orderBy('projects.status','desc')
                      ->orderBy('projects.prj_no','desc')
                      ->get();
        return $projects;
      }
    }

    public function fetchNew() {
        $projects = Project::join('works', 'projects.prj_no', '=', 'works.prj_no')
                      ->where('works.id', Auth::id())
                      ->where('status','In Progress')
                      ->orderBy('projects.status','desc')
                      ->orderBy('projects.prj_no','desc')
                      ->get();
        return $projects;
    }

    public function store(Request $request) {
        $project = new Project;
        $project->prj_no = $request->input('prj_no');
        $project->prj_name = $request->input('prj_name');
        $project->customer = $request->input('customer');
        $project->quo_no = $request->input('quo_no');
        $project->description = $request->input('description');
        $project->status = 'In Progress';
        $project->save();
    }

    public function destroy(Request $request) {
      Project::destroy($request->input('prj_no'));
    }

    public function insertMember(Request $request) {
        $work = new Work;
        $work->id = Auth::id();
        $work->prj_no = $request->input('prj_no');
        $work->position = $request->input('position');
        $work->save();
        return redirect()->back();
    }

    public function show($prj_no) {
      $project = Project::find($prj_no);
      $members = DB::table('employees')
                    ->join('works', 'employees.id', '=', 'works.id')
                    ->where('works.prj_no', $prj_no)
                    ->get();
      return view('project_detail', compact('project', 'members'));
    }

     public function deleteMember(Request $request){
      $works = DB::delete('delete from works where id=? and prj_no=?' ,[$request->input('id'),$request->input('prj_no')]);
      return redirect()->back();
    }

}
