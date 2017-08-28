<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;

class ReportController extends Controller
{
    //
	public function getdata(){
		$datas = DB::select('select w.prj_no,p.prj_name from works w join projects p on w.prj_no=p.prj_no where w.id=? group by w.prj_no,p.prj_name',[Auth::id()]);
		return view('report',['data'=>$datas]);
	}

 public function getProject(Request $request)
  {
		$datas = DB::select('select p.prj_no,p.prj_name from timesheets t join projects p on p.prj_no=t.prj_no where t.id= ? and month(t.date)= ? and year(t.date)= ? and p.prj_no!="PS00000"  group by p.prj_no,p.prj_name;',[Auth::id(),$request->input('month'),$request->input('year')]);

		return $datas;
  }

   public function getYear(Request $request)
  {
  	if($request->input('type')=='Timesheet'){
		$year = DB::select('select year(t.date) as year from timesheets t where t.id=? and t.prj_no!="PS00000" group by year(t.date)',[Auth::id()]);
  	}else{
		$year = DB::select('select year(t.date) as year from timesheets t group by year(t.date)');
  	}
	return $year;
  }

  public function getMonth(Request $request)
  {
	$month = DB::select('select monthname(t.date) as monthname ,month(t.date) as month from timesheets t where t.id = ? and year(t.date) = ? and t.prj_no!="PS00000" group by monthname,month order by month(t.date);'
			,[Auth::id(),$request->input('year')]);
	return $month;
  }

}
