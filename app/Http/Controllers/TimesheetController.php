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

    public function index()
    {
        return view('timesheet');
    }

    public function fetch(Request $request)
    {
        // date format yyyy-mm
        $timesheets = Timesheet::join('projects', 'timesheets.prj_no' ,'=', 'projects.prj_no')
                        ->where('id', Auth::id())
                        ->whereYear('date', substr($request->input('date'), 0, 4))
                        ->whereMonth('date', substr($request->input('date'), 5, 2))
                        ->latest('date')
                        ->select('timesheets.*', 'projects.prj_name')
                        ->get();
        return $timesheets;
    }

    public function store(Request $request)
    {
        $validator = $this->validate($request, [
          'prj_no' => 'required',
          'task_name' => 'required',
          'time_in' => 'required',
          'time_out' => 'required'
        ]);
        $timesheet = Timesheet::firstOrNew(
          [
            'id' => Auth::id(),
            'date' => $request->input('date'),
            'prj_no' => $request->input('prj_no')
          ],
          [
            'time_in' => $request->input('time_in'),
            'time_out' => $request->input('time_out'),
            'task_name' => $request->input('task_name'),
            'description' => $request->input('description')
          ]
        );
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

    public function destroy(Request $request)
    {
        $id = Auth::id();
        $prj_no = $request->input('prj_no');
        $date = $request->input('date');
        $timesheet = Timesheet::where(['id' => $id, 'prj_no' => $prj_no, 'date' => $date])->delete();
    }

    // new page
    public function create()
    {
      return view('new');
    }

}
