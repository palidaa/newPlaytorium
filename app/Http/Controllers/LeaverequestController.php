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

      $data = DB::select('SELECT * FROM employees WHERE id = ? ', [Auth::id()] );
      $user = DB::select('SELECT e.id,e.type,e.department,e.carry_annual_leave,u.email FROM users u join employees e on e.email=u.email where u.id= ?' , [Auth::id()]  );
      DB::insert('insert into leaverequest_of_employee values (?,?,?,?,?,?)', [$user[0]->id,$request->input('from'),$request->input('to'),$request->input('leave_type'),'0',$request->input('purpose')]);
      $leave = DB::select('select ifnull(sum(cal_days(l.from,l.to)),0) as leave_used from leaverequest_of_employee l where l.id= ? and leave_type= ? and year(l.from)= year(?)' , [$user[0]->id,$request->input('leave_type'), $request->input('to') ] );
      $leave_days= DB::select('select cal_days( ? , ? ) as leave_days ' , [$request->input('from'),$request->input('to')]  );
      $year_leave = 0;

      $from = explode('-' ,$request->input('from') );
      $to = explode('-' ,$request->input('to') );

      $month_from = "";
      $month_to = "";
      $leave_type = "";

      switch ($request->input('leave_type')) {
      case "Annual Leave":
          $leave_type = "ลาพักร้อน";
          break;
      case "Personal Leave":
          $leave_type = "ลากิจ";
          break;
      case "Sick Leave":
          $leave_type = "ลาป่วย";
          break;
      }

      switch ($from[1]) {
      case 1:
          $month_from = "มกราคม";
          break;
      case 2:
          $month_from = "กุมภาพันธ์";
          break;
      case 3:
          $month_from = "มีนาคม";
          break;
      case 4:
          $month_from = "เมษายน";
          break;
      case 5:
          $month_from = "พฤษภาคม";
          break;
      case 6:
          $month_from = "มิถุนายน";
          break;
      case 7:
          $month_from = "กรกฎาคม";
          break;
      case 8:
          $month_from = "สิงหาคม";
          break;
      case 9:
          $month_from = "กันยายน";
          break;
      case 10:
          $month_from = "ตุลาคม";
          break;
      case 11:
          $month_from = "พฤศจิกายน";
          break;
      case 12:
          $month_from = "ธันวาคม";
          break;
      }

      switch ($to[1]) {
      case 1:
          $month_to = "มกราคม";
          break;
      case 2:
          $month_to = "กุมภาพันธ์";
          break;
      case 3:
          $month_to = "มีนาคม";
          break;
      case 4:
          $month_to = "เมษายน";
          break;
      case 5:
          $month_to = "พฤษภาคม";
          break;
      case 6:
          $month_to = "มิถุนายน";
          break;
      case 7:
          $month_to = "กรกฎาคม";
          break;
      case 8:
          $month_to = "สิงหาคม";
          break;
      case 9:
          $month_to = "กันยายน";
          break;
      case 10:
          $month_to = "ตุลาคม";
          break;
      case 11:
          $month_to = "พฤศจิกายน";
          break;
      case 12:
          $month_to = "ธันวาคม";
          break;
      }

      switch ($request->input('leave_type')) {
        case 'Annual Leave':
         if($user[0]->type==1){
            $year_leave=6+$user[0]->carry_annual_leave;
          }else if($user[0]->type>=2 && $user[0]->type<=7){
            $year_leave=12+$user[0]->carry_annual_leave;
          }else if($user[0]->type>=8){
            $year_leave=15+$user[0]->carry_annual_leave;
          }
          break;
        case 'Personal Leave':
          $year_leave=6;
          break;
        case 'Sick Leave':
          $year_leave=30;
          break;
      }

      $mail = array('date_from'=>$from[2],'month_from'=>$month_from, 'year_from'=> ($from[0]+543) ,
       'leave_type' => $leave_type ,'purpose'=> $request->input('purpose'),
       'date_to'=>$to[2],'month_to'=>$month_to, 'year_to'=> ($to[0]+543)
       ,'data' => $data ,'line1'=> $year_leave , 'line2'=>$leave[0]->leave_used, 'line3'=>( $year_leave -$leave[0]->leave_used)
       ,'leave_day'=>$leave_days[0]->leave_days
      );

      //return view('mail')->with('mail' , $mail);

      // $leave_request_history = new leaverequest_of_employee;
      // $leave_request_history->id = Auth::id();
      // $leave_request_history->from = $request->input('from');
      // $leave_request_history->to = $request->input('to');
      // $leave_request_history->purpose = $request->input('purpose');
      // $leave_request_history->leave_type = $request->input('leave_type');
      // $leave_request_history->status = $request->input('0');
      // $leave_request_history->save();



      Mail::send('mail', $mail, function($message) {
         $message->to('miin2ht@gmail.com', 'Playtorium') ->subject
            ('Leave Request') ;
         $message->from('yudaqq@gmail.com','Kimmintra') ;
      });
    return redirect()->route('leave_request');

  }


}
