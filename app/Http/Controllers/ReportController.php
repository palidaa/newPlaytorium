<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;

class ReportController extends Controller
{
    //
	public function getdata(){

		$userid = DB::select('SELECT e.id,e.first_name,e.last_name,u.user_type FROM users u join employees e on e.email=u.email where u.id= ?' , [Auth::id()]  );

		$datas = DB::select('select w.prj_no,p.prj_name from works w join projects p on w.prj_no=p.prj_no where w.id=? group by w.prj_no',[$userid[0]->id]);

		return view('report',['data'=>$datas,'id'=>$userid[0]->id]);
	}
}
