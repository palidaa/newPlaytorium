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
        $timesheets = DB::select('SELECT * FROM timesheets WHERE id = ? AND date = ?', [Auth::id(), $request->input('date')]);
        return view('timesheet')->with('timesheets' ,$timesheets);
    }

    public function addTask(Request $request)
    {
        $timesheet = new Timesheet;
        $timesheet->id = Auth::id();
        $timesheet->date = $request->input('date');
        $timesheet->time_in = $request->input('time_in');
        $timesheet->time_out = $request->input('time_out');
        $timesheet->prj_no = $request->input('prj_no');
        $timesheet->task_name = $request->input('task_name');
        $timesheet->description = $request->input('description');
        $timesheet->save();
        return redirect()->route('timesheet');
    }
}
