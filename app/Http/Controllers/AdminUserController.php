<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;

class AdminUserController extends Controller
{
    public function __construct()
    {
      $this->middleware('auth');
    }

    public function showForm()
    {
      return view('admin_new_user');
    }

    public function register(Request $request){
        $holiday = new Holidays;
        $holiday->holiday = $request->input('holiday');
        $holiday->date_name = $request->input('date_name');
        $holiday->save();
        return redirect()->back();
    }
}
