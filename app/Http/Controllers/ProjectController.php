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
      if(Auth::user()->user_type == 'Admin') {
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
