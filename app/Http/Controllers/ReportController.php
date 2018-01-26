<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use App\Employee;
use App\Timesheet;
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

  public function export(Request $request)
  {
	  	//Load template file from the server storage
		$spreadsheet = IOFactory::load('storage/Playtorium_Timesheet_V2.xlsx');
		//Select a sheet
		$sheet = $spreadsheet->getActiveSheet();
		//Fill data into a cell
		$sheet->setCellValue('B2', Auth::user()->name);
		$employee = Employee::where('id', Auth::id())->get();
		$sheet->setCellValue('B3', $employee[0]->role);
		$sheet->setCellValue('C4', 'MFEC');
		$timesheets = Timesheet::where('id', Auth::id())
								->whereYear('date', '2017')
								->whereMonth('date', '01')
								->where('prj_no', 'PS170001')
								->orderBy('date', 'asc')
								->get();
		$sheet->setCellValue('A8', $timesheets[0]->prj_no);
		foreach($timesheets as $index => $timesheet) {
			$row = $index + 8;
			$spreadsheet->getActiveSheet()
    					->setCellValue('A' . $row, Date::PHPToExcel($timesheet->date))
						->setCellValue('B' . $row, $timesheet->task_name)
						->setCellValue('C' . $row, $timesheet->description)
						->setCellValue('D' . $row, Date::PHPToExcel($timesheet->date . ' ' . $timesheet->time_in))
						->setCellValue('E' . $row, Date::PHPToExcel($timesheet->date . ' ' . $timesheet->time_out));
		}
		//Export
		$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
    	header('Content-Type: application/vnd.ms-excel');
    	header('Content-Disposition: attachment; filename="Timesheet.xlsx"');
    	$writer->save("php://output");
  }
}
