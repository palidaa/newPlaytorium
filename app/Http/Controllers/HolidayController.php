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
      $holiday = Holiday::whereYear('holiday', $request->input('year'))
                        ->orderBy('holiday')
                        ->get();
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

    public function get_year() {
      $years = Holiday::selectRaw('YEAR(holiday) AS year')->distinct()->get();
      return $years;
    }
}
