<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use App\Timesheet;

class TimesheetController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        return view('timesheet');
    }

    public function fetch(Request $request)
    {
        $id = Auth::id();
        // date format yyyy-mm
        $date = $request->input('date');
        $timesheets = DB::table('timesheets')
                        ->join('projects', 'timesheets.prj_no' ,'=', 'projects.prj_no')
                        ->where('id', $id)
                        ->whereYear('date', substr($date, 0, 4))
                        ->whereMonth('date', substr($date, 5, 2))
                        ->latest('date')
                        ->select('timesheets.*', 'projects.prj_name')
                        ->get();
        return $timesheets;
    }

    public function store(Request $request)
    {
        $timesheet = Timesheet::where([
          'id' => Auth::id(),
          'date' => $request->input('date'),
          'prj_no' => $request->input('prj_no')
        ])->first();
        if(empty($timesheet)) {
          $timesheet = new Timesheet;
          $timesheet->id = Auth::id();
          $timesheet->date = $request->input('date');
          $timesheet->prj_no = $request->input('prj_no');
          $timesheet->time_in = $request->input('time_in');
          $timesheet->time_out = $request->input('time_out');
          $timesheet->task_name = $request->input('task_name');
          $timesheet->description = $request->input('description');
          $timesheet->save();
        }
        else {
          $timesheet = Timesheet::where(['id' => Auth::id(), 'prj_no' => $request->input('prj_no'), 'date' => $request->input('date')])
                                ->update([
                                  'time_in' => $request->input('time_in'),
                                  'time_out' => $request->input('time_out'),
                                  'prj_no' => $request->input('prj_no'),
                                  'task_name' => $request->input('task_name'),
                                  'description' => $request->input('description')
                                ]);
        }
    }

    public function update(Request $request)
    {
        $id = Auth::id();
        $prj_no = $request->input('old_prj_no');
        $date = $request->input('date');
        $timesheet = Timesheet::where(['id' => $id, 'prj_no' => $prj_no, 'date' => $date])
                              ->update([
                                'time_in' => $request->input('new_time_in'),
                                'time_out' => $request->input('new_time_out'),
                                'prj_no' => $request->input('new_prj_no'),
                                'task_name' => $request->input('new_task_name'),
                                'description' => $request->input('new_description')
                              ]);
    }

    public function destroy(Request $request)
    {
        $id = Auth::id();
        $prj_no = $request->input('prj_no');
        $date = $request->input('date');
        $timesheet = Timesheet::where(['id' => $id, 'prj_no' => $prj_no, 'date' => $date])->delete();
    }

    // new page
    public function new()
    {
      return view('new');
    }
}
