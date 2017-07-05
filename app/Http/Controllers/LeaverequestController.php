<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mail;
use DB;

class LeaverequestController extends Controller
{

  public function __construct()
  {
      $this->middleware('auth');
  }


  public function leave_request(Request $request)
  {
      return view('leave_request');
  }

  public function index(Request $request)
  {
      $leave_request_historys = DB::select('SELECT * FROM leaverequest_of_employee WHERE id = ? ', [Auth::id()]);
      return view('leave_request_history')->with('leave_request_history' ,$leave_request_historys);
  }

  public function addLeave(Request $request)
  {
    $this->validate($request,[
      'leave_type'=> 'required',
      'from'=>'required',
      'to'=>'required',
      'purpose'=>'required'
    ]);

    // DB::insert('insert into leaverequest_of_employee values (?, ?,?,?,?,?)',
    // [(Auth::id()),$request->input('from'),$request->input('to'),$request->input('leave_type'),'0',$request->input('purpose')]);

    $check_overlap = DB::select('
    SELECT * FROM leaverequest_of_employee l WHERE
    (l.from BETWEEN ? AND ? OR
    l.to BETWEEN ? AND ? OR
    ? BETWEEN l.from AND l.to) AND l.id = ?',[$request->input('from'),$request->input('to'),$request->input('from'),$request->input('to'),$request->input('from'),(Auth::id())]);


    if(strtotime($request->input('from')) > strtotime($request->input('to'))){
      \Session::flash('unsuccess_message','<strong>Unsuccess!</strong> There is something wrong on from and to field');
    }
    else if (!empty($check_overlap)){
      //throw new Exception('We have overlapping');
      \Session::flash('unsuccess_message','<strong>Unsuccess!</strong> You already have leave request on these day.');
    }
    else {
      DB::insert('insert into leaverequest_of_employee values (?, ?,?,?,?,?)',
      [(Auth::id()),$request->input('from'),$request->input('to'),$request->input('leave_type'),'0',$request->input('purpose')]);
      \Session::flash('success_message','<strong>Success!</strong> Leave request has been sent.');
    }

      // $leave_request_history = new leaverequest_of_employee;
      // $leave_request_history->id = Auth::id();
      // $leave_request_history->from = $request->input('from');
      // $leave_request_history->to = $request->input('to');
      // $leave_request_history->purpose = $request->input('purpose');
      // $leave_request_history->leave_type = $request->input('leave_type');
      // $leave_request_history->status = $request->input('0');
      // $leave_request_history->save();

      // $data = array('name'=>"Virat Gandhi") ;
      // Mail::send('mail', $data, function($message) {
      //    $message->to('miin2ht@gmail.com', 'Playtorium') ->subject
      //       ('Leave Request') ;
      //    $message->from('yudaqq@gmail.com','Palida') ;
      // });
      // echo "HTML Email Sent. Check your inbox.";


      //\Session::flash('flash_message','<strong>Success!</strong> Leave request has been sent.');

      return redirect()->route("leave_request");
  }


}
