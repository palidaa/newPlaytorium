<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use App\Project;
use App\Work;
use App\Employee;

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
        $projects = Project::where('projects.prj_no', '<>', 'PS00000')
                    ->orderBy('projects.status','desc')
                    ->orderBy('prj_no','desc')
                    ->get();
        return $projects;
      }
      else {
        $projects = Project::join('works', 'projects.prj_no', '=', 'works.prj_no')
                      ->where('works.id', Auth::id())
                      ->where('projects.prj_no', '<>', 'PS00000')
                      ->orderBy('projects.status', 'desc')
                      ->orderBy('projects.prj_no', 'desc')
                      ->get();
        return $projects;
      }
    }

    public function fetchOwnProject() {
      $projects = Project::join('works', 'projects.prj_no', '=', 'works.prj_no')
                      ->where('works.id', Auth::id())
                      ->where('projects.prj_no', '<>', 'PS00000')
                      ->orderBy('projects.status', 'desc')
                      ->orderBy('projects.prj_no', 'desc')
                      ->get();
      return $projects;
    }

    public function store(Request $request) {
        $project = new Project;
        $project->prj_no = $request->input('prj_no');
        $project->prj_name = $request->input('prj_name');
        $project->customer = $request->input('customer');
        $project->quo_no = $request->input('quo_no');
        $project->prj_from = $request->input('prj_from');
        $project->prj_to = $request->input('prj_to');
        $project->description = $request->input('description');
        $project->status = 'In Progress';
        $project->save();
    }

    public function updateDuration(Request $request) {
      $project = Project::find($request->input('prj_no'));
      $project->prj_from = $request->input('prj_from');
      $project->prj_to = $request->input('prj_to');
      $project->save();
      return redirect()->back();
    }

    public function destroy(Request $request) {
      Project::destroy($request->input('prj_no'));
    }

    public function insertMember(Request $request) {
        $member = Employee::find($request->input('id'));
        if($member != NULL) {
          $work = new Work;
          $work->id = $request->input('id');
          $work->prj_no = $request->input('prj_no');
          $work->position = $request->input('position');
          $work->save();
          return redirect()->back();
        }else {
          return redirect()->back()->withErrors(['No member found!']);
        }
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

    public function changeStatus(Request $request) {
      $project = Project::find($request->input('prj_no'));
      if($project->status == "In Progress") {
        $project->status = "Done";
      }
      else {
        $project->status = "In Progress";
      }
      $project->save();
      return redirect()->back();
    }

}
