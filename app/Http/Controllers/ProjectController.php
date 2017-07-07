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

      $userid = DB::select('SELECT e.id,u.user_type FROM users u join employees e on e.email=u.email where u.id= ?' , [Auth::id()]  );

      if($userid[0]->user_type=='Admin'){
              $users = DB::table('projects')
              ->select(DB::raw("*"))
              ->orderBy('status','desc')
              ->orderBy('prj_no','desc')
              ->get();
      }else{
        $users = DB::select('select p.* from projects p join works w on p.prj_no=w.prj_no where w.id= ? order by status desc,prj_no desc',
        [$userid[0]->id]);
      }

      return view('project')->with('projects',$users)
      ->with('type',$userid[0]->user_type)
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

    public function deleteProject(Request $request){
    $works = DB::delete('delete from projects where prj_no=?' ,[$request->input('prj_no')]);
        return redirect()->back();
    }

    public function showProjectDetailList(String $id){
      $userid = DB::select('SELECT e.id,u.user_type FROM users u join employees e on e.email=u.email where u.id= ?' , [Auth::id()]  );
      $works = DB::select('select * from works w where w.id = ? and w.prj_no = ?',[$userid[0]->id,$id]);

      if($userid[0]->user_type=='Admin' or sizeof($works)!=0){
        $members = DB::select('select * from employees e inner join works w on e.id = w.id where w.prj_no = ?',[$id]);
        $project = DB::select('select * from projects where prj_no=?',[$id]);
      }

      return view('project_detail', compact('members','project'))
        ->with('type',$userid[0]->user_type)
        ->with('works',$works);
    }

    public function search(Request $request) {
      $no = $request->input('prj_no');
      $name = $request->input('prj_name');
/*
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

*/

$userid = DB::select('SELECT e.id,u.user_type FROM users u join employees e on e.email=u.email where u.id= ?' , [Auth::id()]  );

      if($userid[0]->user_type=='Admin'){
        $result = DB::table('projects')
             ->select(DB::raw("*"))
             ->where('prj_no',$no)
             ->orwhere('prj_name', 'like','%' . $name . '%'  )
             ->orderBy('status','desc')
             ->orderBy('prj_no','desc')
             ->get();
      }else{
        $result = DB::select('select p.* from projects p join works w on p.prj_no=w.prj_no where w.id= ? and (p.prj_no= ? or p.prj_name like ?) order by status desc,prj_no desc',
        [$userid[0]->id,$no,'%'.$name.'%']);
      }

      $type = DB::table('users')
         ->select('user_type')
         ->where('id',Auth::id())
         ->get();

       return view('project')->with('projects',$result)
       ->with('num',$no)
       ->with('name',$name)
        ->with('type',$type[0]->user_type);
   }



}
