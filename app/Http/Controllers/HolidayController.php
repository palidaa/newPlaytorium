<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Holiday;

class HolidayController extends Controller
{

    public function __construct() {
      $this->middleware('auth');
      $this->middleware('admin');
    }

    public function index() {
      return view('holiday');
    }

    public function fetch(Request $request) {
      if($request->input('month') != NULL) {
        $holiday = Holiday::whereMonth('holiday', $request->input('month'))->get();
      }
      else {
        $holiday = Holiday::selectRaw('DATE_FORMAT(holiday, "%m-%d") AS date, date_name')->get();
      }
      return $holiday;
    }

    public function store(Request $request) {
      $holiday = new Holiday;
      $holiday->holiday = $request->input('date');
      $holiday->date_name = $request->input('date_name');
      $holiday->save();
    }

    public function destroy(Request $request) {
      Holiday::destroy($request->input('date'));
    }
}
