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

    public function showProjectList(){
    
      $users = DB::table('projects')
              ->select(DB::raw("*"))
              ->orderBy('status','desc')
              ->orderBy('prj_no','desc')
              ->get();
        
      return view('project')->with('projects',$users)
      ->with('num',"")
      ->with('name',"");

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

    public function addProjectMember(Request $request){
        $member = new Work;
        $member->id = $request->input('id');
        $member->prj_no = $request->input('prj_no');
        $member->position = $request->input('position');
        $member->save();
        return redirect()->back();
    }

    public function deleteMember(Request $request){
    $works = DB::delete('delete from works where id=? and prj_no=?' ,[$request->input('id'),$request->input('prj_no')]);
        return redirect()->back();
    }

    public function showProjectDetailList(String $id){
    $members = DB::select('select * from employees e inner join works w on e.id = w.id where w.prj_no = ?',[$id]);
    $project = DB::select('select * from projects where prj_no=?',[$id]);

      return view('project_detail', compact('members','project'));
    }

    public function search(Request $request) {
      $no = $request->input('prj_no');
      $name = $request->input('prj_name');

      if($no!=""){
        $result = DB::table('projects')
           ->select(DB::raw("*"))
           ->where('prj_no',$no)
           ->where('prj_name', 'like','%' . $name . '%'  )
           ->orderBy('status','desc')
           ->orderBy('prj_no','desc')
           ->get();
         }
      else {
        $result = DB::table('projects')
             ->select(DB::raw("*"))
             ->where('prj_no',$no)
             ->orwhere('prj_name', 'like','%' . $name . '%'  )
             ->orderBy('status','desc')
             ->orderBy('prj_no','desc')
             ->get();
      }

       return view('project')->with('projects',$result)
       ->with('num',$no)
       ->with('name',$name);
   }
}
