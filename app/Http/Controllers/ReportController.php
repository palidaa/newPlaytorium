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
use App\Holiday;
use App\Project;
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
	  	//Get number of days in month
		$daysInMonth = cal_days_in_month(CAL_GREGORIAN, $request->input('month'), $request->input('year'));
		//Get holidays
		$holidays = Holiday::selectRaw('DATE_FORMAT(holiday, "%e") AS date, date_name')
							->whereMonth('holiday', $request->input('month'))
							->get();
		//Get leaves days
		$sick_leave = $this->get_total_leave_days('Sick Leave', $request->input('year'), $request->input('month'));
		$annual_leave = $this->get_total_leave_days('Annual Leave', $request->input('year'), $request->input('month'));
		$personal_leave = $this->get_total_leave_days('Personal Leave', $request->input('year'), $request->input('month'));
		$timesheets = [];
		//Generate a timesheet template of the month
		for($date = 1; $date <= $daysInMonth; $date++) {
			$dayOfWeek = date('w', strtotime($request->input('year') . '-' . $request->input('month') . '-' . $date));
			$timesheet = (object)[
				'date' => $date . '/' . $request->input('month') . '/' . substr($request->input('year'), 2, 2),
				'task_name' => '',
				'description' => '',
				'is_holiday' => false
			];
			if($dayOfWeek == 0 || $dayOfWeek == 6) {
				$timesheet->is_holiday = true;
				$timesheet->task_name = 'Holiday';
				$timesheet->description = 'Weekend';
			}
			foreach($holidays as $holiday) {
				if($date == $holiday->date) {
					$timesheet->is_holiday = true;
					$timesheet->task_name = 'Holiday';
					$timesheet->description = $holiday->date_name;
				}
			}
			array_push($timesheets, $timesheet);
		}
		//Load a template file from the server storage
		$spreadsheet = IOFactory::load('storage/Playtorium_Timesheet_template.xlsx');
		//Select a sheet
		$sheet = $spreadsheet->getActiveSheet();
		//Fill data into a cell
		$sheet->setCellValue('B2', Auth::user()->name);
		$employee = Employee::where('id', Auth::id())->get();
		$sheet->setCellValue('B3', $employee[0]->role);
		$sheet->setCellValue('C4', 'MFEC');
		foreach($timesheets as $index => $timesheet) {
			$row = $index + 8;
			$spreadsheet->getActiveSheet()
    					->setCellValue('A' . $row, $timesheet->date)
						->setCellValue('B' . $row, $timesheet->task_name)
						->setCellValue('C' . $row, $timesheet->description)
						->setCellValue('L' . $row, $timesheet->is_holiday);
			if($timesheet->is_holiday) {
				$spreadsheet->getActiveSheet()
							->getStyle('A' . $row . ':' . 'J' . $row)->getFill()
							->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
							->getStartColor()->setARGB('B8CCE4');
			}
		}
		//Get timesheets from database
		$db_timesheets = Timesheet::selectRaw('*, DAY(date) - 1 AS idx')
								->where('id', Auth::id())
								->whereYear('date', $request->input('year'))
								->whereMonth('date', $request->input('month'))
								->where('prj_no', $request->input('project'))
								->orderBy('date', 'asc')
								->get();
		//Set excel header and footer cell
		$project = Project::where('prj_no', $db_timesheets[0]->prj_no)->get();
		$spreadsheet->getActiveSheet()
					->setCellValue('C4', $project[0]->customer)
					->setCellValue('E3', 1 . ' ' . date('M', strtotime($db_timesheets[0]->date)) . ' - ' . $daysInMonth . ' ' . date('M', strtotime($db_timesheets[0]->date)) . ' ' . $request->input('year'))
					->setCellValue('H2', Date::PHPToExcel(strtotime('19:00:00')))
					->setCellValue('D44', $sick_leave)
					->setCellValue('D45', $annual_leave)
					->setCellValue('D46', $personal_leave)
					->setCellValue('D47', count($holidays));
		//Replace timesheets to excel
		foreach($db_timesheets as $timesheet) {
			$row = $timesheet->idx + 8;
			$spreadsheet->getActiveSheet()
						->setCellValue('B' . $row, $timesheet->task_name)
						->setCellValue('C' . $row, $timesheet->description)
						->setCellValue('D' . $row, Date::PHPToExcel(strtotime($timesheet->time_in)))
						->setCellValue('E' . $row, Date::PHPToExcel(strtotime($timesheet->time_out)));
		}
		//Export
		$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="Timesheet.xlsx"');
    	$writer->save('php://output');
  }
  
  private function get_total_leave_days($type, $year, $month)
  {
		$days = DB::table('leaverequest_of_employee')
				->whereYear('leave_date', $year)
				->whereMonth('leave_date', $month)
				->where('leave_type', $type)
				->where('status', 'Accepted')
				->count();
	  	return $days;
  }
}