<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\User;

class UpdatePasswordController extends Controller
{
    public function __construct()
    {
      $this->middleware('auth');
    }

    public function index()
    {
      return view('auth/passwords/update');
    }

    public function update(Request $request)
    {
      $validator = $this->validate($request, [
        'current_password' => 'required',
        'password' => 'required|string|min:6|confirmed'
      ]);

      $user = User::find(Auth::id());
      $user->password = Hash::make($request->input('password'));
      $user->save();
      return redirect('/timesheet');
    }
}
