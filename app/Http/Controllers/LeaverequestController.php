<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mail;
use DB;
use DateInterval;
use DatePeriod;
use DateTime;

class LeaverequestController extends Controller
{

  public function __construct()
  {
      $this->middleware('auth');
  }


  public function leave_request(Request $request)
  {
    $user = DB::select('SELECT e.id,e.type,e.department,e.carry_annual_leave,u.email FROM users u join employees e on e.email=u.email where u.id= ?' , [Auth::id()]  );

    $leave_annual = DB::select('select SUM(l.totalhours)*0.125 as leave_annual_used from leaverequest_of_employee l
    where l.id= ? and leave_type= ? and year(l.leave_date)= ? and l.status !=?' ,
    [$user[0]->id,'Annual Leave', date("Y"), 'Rejected' ] );

    $leave_personal = DB::select('select SUM(l.totalhours)*0.125 as leave_personal_used from leaverequest_of_employee l
    where l.id= ? and leave_type= ? and year(l.leave_date)= ? and l.status !=?' ,
    [$user[0]->id,'Personal Leave', date("Y"), 'Rejected' ] );

    $leave_sick =DB::select('select SUM(l.totalhours)*0.125 as leave_sick_used from leaverequest_of_employee l
    where l.id= ? and leave_type= ? and year(l.leave_date)= ? and l.status !=?' ,
    [$user[0]->id,'Sick Leave', date("Y"), 'Rejected' ] );


    $remain_leave_annual = 0;
    $remain_leave_personal = 6;
    $remain_leave_sick = 30;

    if($user[0]->type==1){
      $remain_leave_annual=6+$user[0]->carry_annual_leave;
    }else if($user[0]->type>=2 && $user[0]->type<=7){
      $remain_leave_annual=12+$user[0]->carry_annual_leave;
    }else if($user[0]->type>=8){
      $remain_leave_annual=15+$user[0]->carry_annual_leave;
    }

    $remain_leave_annual=$remain_leave_annual-($leave_annual[0]->leave_annual_used);
    $remain_leave_personal=$remain_leave_personal-($leave_personal[0]->leave_personal_used);
    $remain_leave_sick=$remain_leave_sick-($leave_sick[0]->leave_sick_used);

      return view('leave_request')
        ->with('remain_annual',$remain_leave_annual)
        ->with('remain_personal',$remain_leave_personal)
        ->with('remain_sick',$remain_leave_sick);
  }

  public function index(Request $request)
  {
    $leave_request_historys = DB::select('SELECT * FROM leaverequest_of_employee WHERE id = ? and year(leave_date) = ? and leave_date=date(leave_from) order by leave_from', [Auth::id(),date("Y")]);

      return view('leave_request_history')->with('leave_request_history' ,$leave_request_historys);
  }

 public function fetch(Request $request)
  {
    $leave_request_historys = DB::select('SELECT * FROM leaverequest_of_employee WHERE id = ? and year(leave_date) like ? and leave_type like ? and leave_date=date(leave_from) order by status desc,leave_from desc', [Auth::id(),"%".$request->input('year')."%","%".$request->input('leave_type')."%"]);

      return $leave_request_historys;
  }

  public function getYear(Request $request)
  {
    $leave_request_historys = DB::select('SELECT DISTINCT year(leave_date) as year FROM leaverequest_of_employee WHERE id = ? ', [Auth::id()]);

      return $leave_request_historys;
  }

  public function accept($code)
  {
      $check_status = DB::select( 'select l.status from leaverequest_of_employee l WHERE l.code = ?' , [$code] );
      if ($check_status == NULL){
          return view('cancelled_mail');
      }
      else if($check_status[0]->status == 'Pending'){
        DB::update( 'UPDATE leaverequest_of_employee l SET l.status=? WHERE l.code = ? and l.status = ? ' , ['Accepted',$code,'Pending'] );
        return view('accepted_mail');
      }
      else
        return view('already_mail');
  }
  public function reject($code)
  {
      $check_status = DB::select( 'select l.status from leaverequest_of_employee l WHERE l.code = ?' , [$code] );
      if ($check_status == NULL){
          return view('cancelled_mail');
      }
      else if($check_status[0]->status == 'Pending'){
        DB::update( 'UPDATE leaverequest_of_employee l SET l.status=? WHERE l.code = ? and l.status = ? ' , ['Rejected',$code,'Pending'] );
        return view('denied_mail');
      }
      else
        return view('already_mail');
  }

  public function destroy(Request $request) {
    DB::delete('delete from leaverequest_of_employee where id=? AND leave_from=? AND leave_to=?', [Auth::id(),$request->input('leave_from'),$request->input('leave_to')]);
  }

  public function addLeave(Request $request)
  {

    $code=substr(md5(mt_rand()),0,15);

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
    (l.leave_date BETWEEN ? AND ?) AND l.id = ?',[$request->input('from'),$request->input('to'),(Auth::id())]);



      $data = DB::select('SELECT * FROM employees WHERE id = ? ', [Auth::id()] );
      $user = DB::select('SELECT e.id,e.type,e.department,e.carry_annual_leave,u.email FROM users u join employees e on e.email=u.email where u.id= ?' , [Auth::id()]  );
      $leave = DB::select('select SUM(l.totalhours)*0.125 as leave_used from leaverequest_of_employee l where l.id= ? and leave_type= ? and year(l.leave_date)= year(?) and l.status !=?' , [$user[0]->id,$request->input('leave_type'), $request->input('to'), 'Rejected' ] );
      $leave_days= DB::select('select cal_days(?,?) as leave_days ' , [$request->input('from'),$request->input('to')]  );
      $subtractor = 0;
      if ($this->includeBreakTime($request->input('startHour'), $request->input('endHour'))) {
        $subtractor = 1;
      }
      $leave_times= $request->input('endHour') - $request->input('startHour') - $subtractor;
      $year_leave = 0;
      $from = explode('-' ,$request->input('from') );
      $to = explode('-' ,$request->input('to') );
      $accept_path = 'http://localhost:8000/verify/accept/'.$code ;
      $reject_path = 'http://localhost:8000/verify/reject/'.$code ;
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

      switch ($to[1]){
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

      if(($year_leave - $leave[0]->leave_used)*8 < ($leave_days[0]->leave_days)*$leave_times){
        \Session::flash('unsuccess_message','<strong>Unsuccess!</strong> Your remain leave day is not enough.');
      }
      else if($leave_days[0]->leave_days==0){
        \Session::flash('unsuccess_message','<strong>Unsuccess!</strong> Please select working day');
      }
      else if(strtotime($request->input('from')) > strtotime($request->input('to'))){
        \Session::flash('unsuccess_message','<strong>Unsuccess!</strong> There is something wrong on from and to field');
      }
      else if (!empty($check_overlap)){
        //throw new Exception('We have overlapping');
        \Session::flash('unsuccess_message','<strong>Unsuccess!</strong> You already have leave request on these day.');
      }
      else {
        $mail = array('date_from'=>$from[2],'month_from'=>$month_from, 'year_from'=> ($from[0]+543) ,
         'leave_type' => $leave_type ,'purpose'=> $request->input('purpose'),
         'date_to'=>$to[2],'month_to'=>$month_to, 'year_to'=> ($to[0]+543)
         ,'data' => $data ,'line1'=> $year_leave , 'line2'=>$leave[0]->leave_used, 'line3'=>( $year_leave -$leave[0]->leave_used)
         ,'leave_day'=>$leave_days[0]->leave_days , 'accept_path' => $accept_path , 'reject_path' => $reject_path,
         'leave_times'=>$leave_times, 'type'=>$request->input('type'),'endHour'=>$request->input('endHour'),
         'startHour'=>$request->input('startHour')
        );

        $modmail = DB::select('SELECT email FROM users WHERE user_type="Admin"');
		foreach($modmail as $eachmail){
			Mail::send('mail',
			 $mail, function($message) use ($data,$eachmail) {
			   $message->to($eachmail->email, 'Leave Moderator') ->subject
				  ('Leave Request from '.$data[0]->first_name.' '.$data[0]->last_name) ;
			   $message->from($data[0]->email,$data[0]->first_name.' '.$data[0]->last_name) ;
			});
        }
		$begin = new DateTime($request->input('from'));
		$interval = new DateInterval( "P1D" );
		$end = new DateTime($request->input('to'));
		$end->add( $interval );
		$period = new DatePeriod($begin, $interval, $end);
		$holidays = DB::select('select holiday from holidays');
		foreach($period as $period_v){
			$notinholiday = true;
			foreach($holidays as $holiday){
				if($holiday->holiday==$period_v->format('Y-m-d'))$notinholiday = false;
			}
			if(date('N', $period_v->getTimestamp())<6 and $notinholiday){
        $subtractor = 0;
        if ($this->includeBreakTime($request->input('startHour'), $request->input('endHour'))) {
          $subtractor = 1;
        }
				DB::insert('insert into leaverequest_of_employee values (?,?,?,?,?,?,?,?,?)',
        [$user[0]->id,$period_v,$request->input('from').' '.$request->input('startHour'),
          $request->input('to').' '.$request->input('endHour'),$request->input('leave_type'),
          'Pending',
          $request->input('purpose'),
          $code,
          $request->input('endHour') - $request->input('startHour') - $subtractor
        ]);
			}
		}

        //return view('mail')->with('mail' , $mail);
        \Session::flash('success_message','<strong>Success!</strong> Leave request has been sent.');
      }





    return redirect()->route('leave_request');

  }

  private function includeBreakTime($startHour, $endHour) {
    if ($startHour <= 12 && $endHour >= 13) {
      return true;
    }
  }

  public function get_leaves_in_month(Request $request) {
    $leave_days = DB::table('leaverequest_of_employee')
                    ->where('id', Auth::id())
                    ->whereYear('leave_date', $request->input('year'))
                    ->whereMonth('leave_date', $request->input('month'))
                    ->whereIn('status', ['Accepted', 'Pending'])
                    ->get();
    return $leave_days;
  }
}
