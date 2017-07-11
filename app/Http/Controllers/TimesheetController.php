<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $date = $request->input('date');
        $timesheets = Timesheet::where(['id' => $id, 'date' => $date])->get();
        return $timesheets;
    }

    public function insert(Request $request)
    {
        $timesheet = Timesheet::firstOrNew([
          'id' => Auth::id(),
          'date' => $request->input('date'),
          'prj_no' => $request->input('prj_no')
        ]);
        $timesheet->time_in = $request->input('time_in');
        $timesheet->time_out = $request->input('time_out');
        $timesheet->task_name = $request->input('task_name');
        $timesheet->description = $request->input('description');
        $timesheet->save();
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

    public function delete(Request $request)
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
