<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use App\Holidays;

class AdminHolidayController extends Controller
{
    public function __construct()
    {
      $this->middleware('auth');
    }

    public function showHolidayList()
    {
      $users = DB::select('select * from holidays');
      return view('admin_holiday')->with('holidays',$users);
    }

    public function addHoliday(Request $request){
        $holiday = new Holidays;
        $holiday->holiday = $request->input('holiday');
        $holiday->date_name = $request->input('date_name');
        $holiday->save();
        return redirect()->back();
    }

    public function deleteHoliday(Request $request){
      $works = DB::delete('delete from holidays where holiday=? and date_name=?' ,[$request->input('holiday'),$request->input('date_name')]);
        return redirect()->back();
    }

}
