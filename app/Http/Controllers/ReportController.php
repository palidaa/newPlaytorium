<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;

class ReportController extends Controller
{
    //
	public function getdata(){
		$datas = DB::select('select w.prj_no,p.prj_name from works w join projects p on w.prj_no=p.prj_no where w.id=? group by w.prj_no',[Auth::id()]);
		return view('report',['data'=>$datas]);
	}
}
