<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use DB;
use App\Project;
use App\Work;
use App\Employee;
use App\File;
use App\Mail\ProjectWarning;

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
        foreach($projects as $project) {
          if($this->hasMembers($project->prj_no)) {
            $project->hasMembers = true;
          }
          else {
            $project->hasMembers = false;
          }
        }
        return $projects;
      }
      else {
        $projects = Project::join('works', 'projects.prj_no', '=', 'works.prj_no')
                      ->where('works.id', Auth::id())
                      ->where('projects.prj_no', '<>', 'PS00000')
                      ->orderBy('projects.status', 'desc')
                      ->orderBy('projects.prj_no', 'desc')
                      ->get();
        foreach($projects as $project) {
          if($this->hasMembers($project->prj_no)) {
            $project->hasMembers = true;
          }
          else {
            $project->hasMembers = false;
          }
        }
        return $projects;
      }
    }

    public function fetchOwnProject() {
      $projects = Project::join('works', 'projects.prj_no', '=', 'works.prj_no')
                      ->where('works.id', Auth::id())
                      ->where('projects.prj_no', '<>', 'PS00000')
                      ->where('projects.status', 'In Progress')
                      ->orderBy('projects.status', 'desc')
                      ->orderBy('projects.prj_no', 'desc')
                      ->get();
       foreach($projects as $project) {
          if($this->hasMembers($project->prj_no)) {
            $project->hasMembers = true;
          }
          else {
            $project->hasMembers = false;
          }
        }
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
      if(Auth::user()->isAdmin()) {
        $files = File::where('prj_no', $prj_no)->get();
        return view('project_detail', compact('project', 'members', 'files')); 
      }
      else {
        return view('project_detail', compact('project', 'members'));
      }
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
    private function hasMembers($prj_no) {
      $members = Work::where('prj_no', $prj_no)->first();
      if($members == NULL)  return false;
      else return true;
    }

    public function deadlineWarning() {
      $projects = Project::all();
      foreach($projects as $project) {
        $now = strtotime(date('Y-m-d'));
        $date = strtotime($project->prj_to);
        $diff = $date - $now;
        $diff = floor($diff / (60*60*24));
        if($diff == 40) {
          Mail::to('j_pcr@hotmail.com')->send(new ProjectWarning($project));
        }
      }
    }
}
