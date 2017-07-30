<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PHPExcel_Worksheet_Drawing;
use mergeCells;
use PHPExcel_Style_Border;
use PHPExcel_Style_Font;
use PHPExcel_Style_Protection;
use PHPExcel_IOFactory;
use PHPExcel_Shared_Date;
use PHPExcel_Style_NumberFormat;
use Illuminate\Support\Facades\Auth;
use DatePeriod;
use DateTime;
use DateInterval;

class MessagesController extends Controller
{	
	public function __construct()
  {
      $this->middleware('auth');
  }
  
    public function submit(Request $request){
      $this->validate($request,[
        'email' => 'required' ,
        'pwd' => 'required'
        ] );

      $users= DB::select('select password from id where username = ? ' , [$request->input('email')]  );
      if( count($users) == 0 || $users[0]->password != $request->input('pwd') )
        echo 'incorrect';
		else return view('/timesheet');
    }
    
 public function export(Request $request){
    if($request->input('type')=="Timesheet"){
		if($request->input('year')=="" or $request->input('month')=="" or $request->input('project')=="") return redirect('report');
      Excel::create('timesheet' , function ($excel)use ($request) {
        $excel -> sheet('sheet' , function($sheet)use ($request){
			
          $employee = Auth::id();
          $project = $request->input('project');
          $year = $request->input('year');
          $month = $request->input('month');
         
          $month = date("m",strtotime($month."/01/2017"));

            $sheet->setFreeze('A8');
            $sheet->setFontFamily('Arial');
            $sheet->setFontSize(10);

            $objDrawing = new PHPExcel_Worksheet_Drawing();
            $objDrawing->setPath('images/Logo_2.png');
            $objDrawing->setResizeProportional(true);
            $objDrawing->setWidth(90);
            $objDrawing->setHeight(52);
            $objDrawing->setCoordinates('A1');
            $objDrawing->setWorksheet($sheet);

            $sheet->getStyle("A6:J7")->applyFromArray(
            array(
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                        'color' => array('argb' => '000')
                      )
                  )
              )
          );
          $sheet->getStyle("A1:J4")->applyFromArray(
          array(
              'borders' => array(
                  'allborders' => array(
                      'style' => PHPExcel_Style_Border::BORDER_THIN,
                      'color' => array('argb' => '000')
                    )
                )
            )
        );
        $sheet ->setcolumnFormat(array(
                      'F' => '0.00',
                      'G' => '0.00',
                      'H' => '0.00',
                      'I' => '0.00'
                      ));

        $sheet->cells('A2:A4' , function($cells){
          $cells->setFontweight('bold');
        });
        $sheet->cells('D3' , function($cells){
          $cells->setFontweight('bold');
        });
        $sheet->cells('A1:J1' , function($cells){
          $cells->setFontWeight('bold');
          $cells->setFontSize(30);
        });
        $sheet->cells('A6:J7' , function($cells){
          $cells->setFontWeight('bold');
        });



            $sheet->setHeight(1, 40);
            $sheet->setHeight(6, 20);
            $sheet->setHeight(7, 20);

            $sheet ->mergeCells('C1:J1');
            $sheet ->mergeCells('B2:C2');
            $sheet ->mergeCells('B3:C3');
            $sheet ->mergeCells('A4:B4');
            $sheet ->mergeCells('A6:A7');
            $sheet ->mergeCells('B6:B7');
            $sheet ->mergeCells('C6:C7');
            $sheet ->mergeCells('D6:D7');
            $sheet ->mergeCells('E6:E7');
            $sheet ->mergeCells('F6:F7');
            $sheet ->mergeCells('G6:I6');
            $sheet ->mergeCells('J6:J7');

            $sheet->cells('C1:J1' , function($cells){
              $cells->setAlignment('center');
              $cells->setValignment('center');
            });
            $sheet->cells('A6:J6' , function($cells){
              $cells->setBackground('#00B0F0');
            });
            $sheet->cells('G7:I7' , function($cells){
              $cells->setBackground('#00B0F0');
            });
            $sheet->cells('A6:A7' , function($cells){
              $cells->setAlignment('center');
              $cells->setValignment('center');
            });
            $sheet->cells('B6:B7' , function($cells){
              $cells->setAlignment('center');
              $cells->setValignment('center');
            });
            $sheet->cells('C6:C7' , function($cells){
              $cells->setAlignment('center');
              $cells->setValignment('center');
            });
            $sheet->cells('D6:D7' , function($cells){
              $cells->setAlignment('center');
              $cells->setValignment('center');
            });
            $sheet->cells('E6:E7' , function($cells){
              $cells->setAlignment('center');
              $cells->setValignment('center');
            });
            $sheet->cells('F6:F7' , function($cells){
              $cells->setAlignment('center');
              $cells->setValignment('center');
            });
            $sheet->cells('G6:I7' , function($cells){
              $cells->setAlignment('center');
              $cells->setValignment('center');
            });
            $sheet->cells('J6:J7' , function($cells){
              $cells->setAlignment('center');
              $cells->setValignment('center');
            });

                $users= DB::select('select first_name,last_name,role from employees where id = ? ' , [$employee]  );
                $users2 = DB::select( 'select date_format(t.date, ? ) as date ,
                  t.task_name,t.description,
                  date_format(t.time_in,?) as time_in ,
                  date_format(t.time_out,?) as time_out,
                  TIME_to_sec(cal_works(t.date,t.time_in,t.time_out))/(60*60) as cal_works,
                  TIME_to_sec(cal_ot_wk(t.date,t.time_in,time_out))/(60*60) as cal_ot_1,
                  TIME_to_sec(cal_ot_holiday_wk(t.date,t.time_in,time_out))/(60*60) as cal_ot_2,
                  TIME_to_sec(cal_ot_holiday_non_wk(t.date,t.time_in,time_out))/(60*60) as cal_ot_3
                  from timesheets t where t.id= ? and date_format(t.date, ? )=? and t.prj_no=? ' ,
                  ['%m/%d/%Y','%H:%i','%H:%i',$employee , '%Y-%m' ,$year.'-'.$month,$project  ]);
                $users3 = DB::select('select p.customer from projects p where p.prj_no=?' , [$project]);
                $users4 = DB::select('select SEC_TO_TIME(sum(TIME_TO_SEC(cal_works(t.time_in,t.time_out)))) as sum_wk,
                SEC_TO_TIME(sum(TIME_TO_SEC(cal_ot_wk(t.date,t.time_in,time_out)))) as sum_ot_wk,
                SEC_TO_TIME(sum(TIME_TO_SEC(cal_ot_holiday_wk(t.date,t.time_in,time_out)))) as sum_ot_hwk,
                SEC_TO_TIME(sum(TIME_TO_SEC(cal_ot_holiday_non_wk(t.date,t.time_in,time_out)))) as sum_ot_hnwk,
                SEC_TO_TIME(sum(TIME_TO_SEC(cal_ot_wk(t.date,t.time_in,time_out)))+sum(TIME_TO_SEC(cal_ot_holiday_wk(t.date,t.time_in,time_out)))+sum(TIME_TO_SEC(cal_ot_holiday_non_wk(t.date,t.time_in,time_out)))) as sum_ot from timesheets t
                where t.id=? and date_format(t.time_in,?)=? and t.prj_no=?' , 
                [$employee,'%Y-%m',$year.'-'.$month,$project]);
                $total_work_day_per_month = DB::select('select sum(TIME_TO_SEC(cal_works(t.time_in,t.time_out)))/(8*60*60) as sum_wk
                    from timesheets t
                    where t.id=? and date_format(t.time_in,?)=? and t.prj_no=?' , 
                    [$employee,'%Y-%m',$year.'-'.$month,$project]);
                $sick_leave = DB::select('select ifnull(count(l.leave_date),0) as sick_leave from leaverequest_of_employee l where l.id= ? and month(l.leave_date)= ? and leave_type= ? 
                  and year(l.leave_date)= ? ' , 
                    [$employee ,$month,'Sick leave',$year ]);
                $private_leave = DB::select('select ifnull(count(l.leave_date),0) as private_leave from leaverequest_of_employee l where l.id= ? and month(l.leave_date)= ? and leave_type= ? and year(l.leave_date)= ? ' , 
                    [$employee ,$month,'Personal leave',$year ]);
                $public_holiday = DB::select('select count(*) as public_holiday
                    from holidays h
                    where year(h.holiday)=? and month(h.holiday)= ? ' , 
                    [$year , $month ]);
                $annual_leave = DB::select('select ifnull(count(l.leave_date),0) as annual_leave from leaverequest_of_employee l where l.id= ? and month(l.leave_date)= ? and leave_type= ? and year(l.leave_date)= ? ;' , 
                    [$employee ,$month,'Annual leave',$year]);
                $holiday = DB::select('select date_format(h.holiday,?) as holiday,h.date_name 
                  from holidays h 
                  where year(h.holiday)=? and month(h.holiday)= ?',['%m/%d/%Y',$year,$month]);
                $leave = DB::select('select date_format(l.leave_date, ? ) as leave_date,l.leave_type from leaverequest_of_employee l where id= ? and year(l.leave_date)= ? and month(l.leave_date)= ?',
                  ['%m/%d/%Y',$employee,$year,$month]);

                $strStartDate = $month."/01/2017";
                $strEndDate = date ("m/d/Y", strtotime("-1 day", strtotime(($month+1)."/01/2017")));

                $sheet ->fromArray(array(
                 array (null , null,'TIMESHEET'),
                 array ('Name:' , $users[0]->first_name." ".$users[0]->last_name),
                 array ('Role:' , $users[0]->role , null,'Duration', date("j M",strtotime($strStartDate))." -".date("j M Y",strtotime($strEndDate))),
                 array ('Customer Site:',null , $users3[0]->customer),
                 array (),
                 array ('Date','Task Name' , 'Description' , 'Time In','Time Out',"Work\n(hours)" , "OT (hours)" , null , null , 'Remark'),
                 array (null,null,null,null,null,null,'Working Day','Holiday Working','Holiday Non-Working' ),
                ) , NULL , 'A1',false,false );

           $sheet -> getStyle('F6') -> getAlignment() -> setWrapText(true);

            $strStartDate = $month."/01/2017";
            $strEndDate = date ("m/d/Y", strtotime("-1 day", strtotime(($month+1)."/01/2017")));

           $intWorkDay = 0;
           $intHoliday = 0;
           $intTotalDay = ((strtotime($strEndDate) - strtotime($strStartDate))/  ( 60 * 60 * 24 )) + 1;

           $rowCount = 8 ;
           $eiei2 = 0;
           $eiei = count($users2);
           $countholiday = 0;
           $countleave = 0;

           while ($intTotalDay-- > 0) {
            $DayOfWeek = date("w", strtotime($strStartDate));
            $sheet -> SetCellValue('A'.$rowCount, $strStartDate);

            if($DayOfWeek == 0 or $DayOfWeek ==6)  // 0 = Sunday, 6 = Saturday;
            {
             $intHoliday++;
             $sheet->cells('A'.$rowCount.':'.'J'.$rowCount, function($cells){
               $cells->setBackground('b8cce4');
               $cells->setFontColor('8080a3');
             });
             //$sheet -> SetCellValue('A'.$rowCount, $strStartDate)
              $sheet-> SetCellValue('B'.$rowCount, 'Holiday' )
                    -> SetCellValue('C'.$rowCount, 'Weekend');
             }else if(count($holiday)>0 and $countholiday<count($holiday) and  $holiday[$countholiday]->holiday==$strStartDate){
              $sheet->cells('A'.$rowCount.':'.'J'.$rowCount, function($cells){
               $cells->setBackground('b8cce4');
               $cells->setFontColor('8080a3');
              });
             //$sheet -> SetCellValue('A'.$rowCount, $strStartDate)
              $sheet-> SetCellValue('B'.$rowCount, 'Holiday' )
                    -> SetCellValue('C'.$rowCount, $holiday[$countholiday]->date_name);
              $countholiday++;
             }else if(count($leave)>0 and $countleave<count($leave) and $strStartDate==$leave[$countleave]->leave_date){
                $sheet->SetCellValue('C'.$rowCount,$leave[$countleave]->leave_type);
                $countleave++;
             }

            if(count($users2)>0 and $eiei2<count($users2) and $users2[$eiei2]->date==$strStartDate){
              //$sheet -> SetCellValue('A'.$rowCount, $users2[$eiei2]->date);
              $sheet -> SetCellValue('B'.$rowCount, $users2[$eiei2]->task_name)
                  -> SetCellValue('C'.$rowCount, $users2[$eiei2]->description)
                  -> SetCellValue('D'.$rowCount, $users2[$eiei2]->time_in)
                  -> SetCellValue('E'.$rowCount, $users2[$eiei2]->time_out);
              if($users2[$eiei2]->cal_works != 0) $sheet -> SetCellValue('F'.$rowCount, $users2[$eiei2]->cal_works);
              if($users2[$eiei2]->cal_ot_1!=0) $sheet -> SetCellValue('G'.$rowCount, $users2[$eiei2]->cal_ot_1);
              if($users2[$eiei2]->cal_ot_2!=0) $sheet -> SetCellValue('H'.$rowCount, $users2[$eiei2]->cal_ot_2);
              if($users2[$eiei2]->cal_ot_3!=0) $sheet -> SetCellValue('I'.$rowCount, $users2[$eiei2]->cal_ot_3);
              $eiei2++;
              //if(  $eiei2 >= (count($users2))  ) break;
            }
            $rowCount++;
           //$DayOfWeek = date("l", strtotime($strStartDate)); // return Sunday, Monday,Tuesday....
           $strStartDate = date ("m/d/Y", strtotime("+1 day", strtotime($strStartDate)));
           }

             $sheet->getStyle('A8'.':'.'J'.$rowCount)->applyFromArray(
             array(
                 'borders' => array(
                     'allborders' => array(
                         'style' => PHPExcel_Style_Border::BORDER_THIN,
                         'color' => array('argb' => '000')
                       )
                   )
               )
           );
           $sheet->getStyle('C'.($rowCount+1).':'.'C'.($rowCount+3))->applyFromArray(
           array(
               'borders' => array(
                   'allborders' => array(
                       'style' => PHPExcel_Style_Border::BORDER_THIN,
                       'color' => array('argb' => '000')
                     )
                 )
             )
         );
         $sheet->getStyle('F'.($rowCount+1).':'.'F'.($rowCount+3))->applyFromArray(
         array(
             'borders' => array(
                 'allborders' => array(
                     'style' => PHPExcel_Style_Border::BORDER_THIN,
                     'color' => array('argb' => '000')
                       )
                   )
               )
           );
           $sheet->getStyle('G'.($rowCount+1).':'.'I'.($rowCount+1))->applyFromArray(
           array(
                   'borders' => array(
                       'allborders' => array(
                           'style' => PHPExcel_Style_Border::BORDER_THIN,
                           'color' => array('argb' => '000')
                         )
                     )
                 )
             );
             $rowCount++;

             $sheet -> SetCellValue('F'.$rowCount, '=SUM(F8:F'.($rowCount-2).')')
                    -> SetCellValue('G'.$rowCount, '=SUM(G8:G'.($rowCount-2).')')
                    -> SetCellValue('H'.$rowCount, '=SUM(H8:H'.($rowCount-2).')')
                    -> SetCellValue('I'.$rowCount, '=SUM(I8:I'.($rowCount-2).')')
                    -> SetCellValue('F'.($rowCount+1), '=G'.$rowCount.'+H'.$rowCount.'+I'.$rowCount)
                    -> SetCellValue('F'.($rowCount+2), '=F'.$rowCount.'/8');

             $sheet -> SetCellValue('C'.$rowCount, 'Total work hour/month');
             $sheet->cells('C'.$rowCount , function($cells){
               $cells->setFontWeight('bold');
             });
             $rowCount++;
             $sheet -> SetCellValue('C'.$rowCount, 'Total OT hour/month');
             $sheet->cells('C'.$rowCount , function($cells){
               $cells->setFontWeight('bold');
             });
             $rowCount++;
             $sheet -> SetCellValue('C'.$rowCount, 'Totlal work day/month');
             $sheet->cells('C'.$rowCount , function($cells){
               $cells->setFontWeight('bold');
             });
             $rowCount++;
             $rowCount++;

             $sheet ->mergeCells('A'.$rowCount.':'.'C'.$rowCount);
             $sheet -> SetCellValue('A'.$rowCount, 'Sick Leave');
             $sheet -> SetCellValue('D'.$rowCount, $sick_leave[0]->sick_leave );
             $sheet->cells('A'.$rowCount.':'.'C'.$rowCount , function($cells){
               $cells->setAlignment('center');
               $cells->setValignment('center');
               $cells->setFontWeight('bold');
             });
			 $sheet->getStyle('A'.($rowCount).':'.'D'.($rowCount+3))->applyFromArray(
				 array(
					 'borders' => array(
						 'allborders' => array(
							 'style' => PHPExcel_Style_Border::BORDER_THIN,
							 'color' => array('argb' => '000')
						   )
					   )
				   )
				);
				  $rowCount++;

				 $sheet ->mergeCells('A'.$rowCount.':'.'C'.$rowCount);
				 $sheet -> SetCellValue('A'.$rowCount, 'Annual Leave');
				 $sheet -> SetCellValue('D'.$rowCount, $annual_leave[0]->annual_leave );
				 $sheet->cells('A'.$rowCount.':'.'C'.$rowCount , function($cells){
				   $cells->setAlignment('center');
				   $cells->setValignment('center');
					$cells->setFontWeight('bold');
				 });
				 
				 $rowCount++;

				 $sheet ->mergeCells('A'.$rowCount.':'.'C'.$rowCount);
				 $sheet -> SetCellValue('A'.$rowCount, 'Private Leave');
				 $sheet -> SetCellValue('D'.$rowCount, $private_leave[0]->private_leave );
				 $sheet->cells('A'.$rowCount.':'.'C'.$rowCount , function($cells){
				   $cells->setAlignment('center');
				   $cells->setValignment('center');
					$cells->setFontWeight('bold');
				 });
				 $rowCount++;

				 $sheet ->mergeCells('A'.$rowCount.':'.'C'.$rowCount);
				 $sheet -> SetCellValue('A'.$rowCount, 'Public Holiday');
				 $sheet -> SetCellValue('D'.$rowCount, $public_holiday[0]->public_holiday );
				 $sheet->cells('A'.$rowCount.':'.'C'.$rowCount , function($cells){
				   $cells->setAlignment('center');
				   $cells->setValignment('center');
					$cells->setFontWeight('bold');
				 });

				 $rowCount++;
				 $rowCount++;

				 $sheet ->mergeCells('A'.$rowCount.':'.'G'.$rowCount);
				 $sheet -> SetCellValue('A'.$rowCount, 'OT Working Day = work on working day ( Mon-Fri )  after normal working hour ( 9:00-18:00 ), after 1 hour break');
				 $rowCount++;
				 $sheet ->mergeCells('A'.$rowCount.':'.'G'.$rowCount);
				 $sheet -> SetCellValue('A'.$rowCount, 'OT Holiday Working Hour = work on weekend and holiday in normal working hour ( 9:00-18:00 )');
				 $rowCount++;
				 $sheet ->mergeCells('A'.$rowCount.':'.'G'.$rowCount);
				 $sheet -> SetCellValue('A'.$rowCount, 'OT Holiday Non Working Hour = work on weekend and holiday after normal working hour ( 9:00-18:00 ) after 1 hour break');

				 $rowCount++;
				 $rowCount++;

				 $sheet ->mergeCells('A'.$rowCount.':'.'B'.($rowCount+1));
				 $sheet -> SetCellValue('A'.$rowCount, 'Prepared by');
				 $sheet->cells('A'.$rowCount.':'.'B'.($rowCount+1) , function($cells){
				   $cells->setAlignment('center');
				   $cells->setValignment('center');
					$cells->setFontWeight('bold');
				 });

				 $sheet ->mergeCells('C'.$rowCount.':'.'D'.($rowCount+1));
				 $sheet -> SetCellValue('C'.$rowCount, '______________________________');
				 $sheet->cells('C'.$rowCount.':'.'D'.($rowCount+1) , function($cells){
				   $cells->setAlignment('center');
				   $cells->setValignment('center');
					$cells->setFontWeight('bold');
				 });

				 $sheet ->mergeCells('E'.$rowCount.':'.'G'.($rowCount+1));
				 $sheet -> SetCellValue('E'.$rowCount, '____________________');
				 $sheet->cells('E'.$rowCount.':'.'G'.($rowCount+1) , function($cells){
				   $cells->setAlignment('center');
				   $cells->setValignment('center');
					$cells->setFontWeight('bold');
				 });

				 $sheet->cells('C'.$rowCount.':'.'D'.($rowCount+1) , function($cells){
				   $cells->setAlignment('center');
				   $cells->setValignment('center');
				 });
				 $rowCount++;
				 $rowCount++;

				  $sheet ->mergeCells('C'.$rowCount.':'.'D'.$rowCount);
				  $sheet -> SetCellValue('C'.$rowCount, $users[0]->first_name." ".$users[0]->last_name);
				  $sheet->cells('C'.$rowCount.':'.'D'.$rowCount , function($cells){
					$cells->setAlignment('center');
					$cells->setValignment('center');
				  });

				  $sheet ->mergeCells('E'.$rowCount.':'.'G'.$rowCount);
				  $sheet -> SetCellValue('E'.$rowCount, '(Date)');
				  $sheet->cells('E'.$rowCount.':'.'G'.$rowCount , function($cells){
					$cells->setAlignment('center');
					$cells->setValignment('center');
				  });
				  $rowCount++;
				  $rowCount++;

				  $sheet ->mergeCells('A'.$rowCount.':'.'B'.($rowCount+1));
				  $sheet -> SetCellValue('A'.$rowCount, 'Approved by');
				  $sheet->cells('A'.$rowCount.':'.'B'.($rowCount+1) , function($cells){
					$cells->setAlignment('center');
					$cells->setValignment('center');
					 $cells->setFontWeight('bold');
				  });

				  $sheet ->mergeCells('C'.$rowCount.':'.'D'.($rowCount+1));
				  $sheet -> SetCellValue('C'.$rowCount, '______________________________');
				  $sheet->cells('C'.$rowCount.':'.'D'.($rowCount+1) , function($cells){
					$cells->setAlignment('center');
					$cells->setValignment('center');
					 $cells->setFontWeight('bold');
				  });

				  $sheet ->mergeCells('E'.$rowCount.':'.'G'.($rowCount+1));
				  $sheet -> SetCellValue('E'.$rowCount, '____________________');
				  $sheet->cells('E'.$rowCount.':'.'G'.($rowCount+1) , function($cells){
					$cells->setAlignment('center');
					$cells->setValignment('center');
					 $cells->setFontWeight('bold');
				  });
				  $rowCount++;
				  $rowCount++;

				  $sheet ->mergeCells('C'.$rowCount.':'.'D'.$rowCount);
				  $sheet -> SetCellValue('C'.$rowCount, '(PM)');
				  $sheet->cells('C'.$rowCount.':'.'D'.$rowCount , function($cells){
					$cells->setAlignment('center');
					$cells->setValignment('center');
				  });

				  $sheet ->mergeCells('E'.$rowCount.':'.'G'.$rowCount);
				  $sheet -> SetCellValue('E'.$rowCount, '(Date)');
				  $sheet->cells('E'.$rowCount.':'.'G'.$rowCount , function($cells){
					$cells->setAlignment('center');
					$cells->setValignment('center');
				  });


				  $value =  $sheet->getCell('A4')->getValue();
				 $width = mb_strwidth ($value);
				 $sheet->setWidth('A' , $width + 5);
				 $value =  $sheet->getCell('B6')->getValue();
				 $width = mb_strwidth ($value);
				 $sheet->setWidth('B' , $width + 5);
				 //$value =  $sheet->getCell('C6')->getValue();
				 //$width = mb_strwidth ($value);

				 $rowCountdes = 0;
				   $maxlenC=$width + 15;
				 while($rowCountdes < $eiei ){
					$currlen = mb_strwidth ($sheet->getCell('C'.($rowCountdes+8))->getValue());
				   if($maxlenC<$currlen)$maxlenC=$currlen;
					$rowCountdes++;
				 }


				 $sheet->setWidth('C' , $maxlenC);
				 $value =  $sheet->getCell('D6')->getValue();
				 $width = mb_strwidth ($value);
				 $sheet->setWidth('D' , $width + 5);
				 $value =  $sheet->getCell('E6')->getValue();
				 $width = mb_strwidth ($value);
				 $sheet->setWidth('E' , $width + 5);
				 $value =  $sheet->getCell('F6')->getValue();
				 $width = mb_strwidth ($value);
				 $sheet->setWidth('F' , $width + 5);
				 $value =  $sheet->getCell('G7')->getValue();
				 $width = mb_strwidth ($value);
				 $sheet->setWidth('G' , $width + 5);
				 $value =  $sheet->getCell('H7')->getValue();
				 $width = mb_strwidth ($value);
				 $sheet->setWidth('H' , $width + 5);
				 $value =  $sheet->getCell('I7')->getValue();
				 $width = mb_strwidth ($value);
				 $sheet->setWidth('I' , $width + 5);



			  //   $sheet->getProtection()->setSheet(true);
				 $sheet->getProtection()->setObjects(true);
			  //   $sheet->getStyle('A8:J'.$rowCount)
			  //           ->getProtection()->setLocked(
			  //             PHPExcel_Style_Protection::PROTECTION_UNPROTECTED
			  //           );
			   });


			}) -> export('xlsx');
		}else if($request->input('type')=="Summary Timesheet"){
			if($request->input('year')=="") return redirect('report');
				Excel::create('summary_timesheet' , function ($excel) use ($request){

				$excel -> sheet('Timesheet' , function($sheet)use ($request){
					$year = $request->input('year');
					$sheet->setHeight(array(
						2     =>  15,
						3     =>  48
					));
					$sheet ->setcolumnFormat(array(
						 'B' => '@',
						 'F' => '0.00',
						 'G' => '0.00',
						 'H' => '0.00',
						 'I' => '0.00',
						 'J' => '0.00',
						 'K' => '0.00',
						 'L' => '0.00',
						 'M' => '0.00',
						 'N' => '0.00',
						 'O' => '0.00',
						 'P' => '0.00',
						 'Q' => '0.00'
					));
					$sheet ->mergeCells('F2:Q2');
					$sheet->cell('F2',function($cell){
						$cell->setFontFamily('Arial');
						$cell->setFontSize(9);
						$cell->setFontColor('#FFFFFF');
						$cell->setBackground('#00B050');
						$cell->setAlignment('center');
						$cell->setValignment('center');
						$cell->setFontWeight('bold');
					});
					$sheet->cells('B3:Q3',function($cells){
						$cells->setFontFamily('Arial');
						$cells->setFontSize(9);
						$cells->setFontColor('#FFFFFF');
						$cells->setAlignment('center');
						$cells->setValignment('center');
						$cells->setBackground('#00B050');
						$cells->setFontWeight('bold');
					});
					$sheet->cells('B:B',function($cells){
						$cells->setAlignment('center');
						$cells->setValignment('center');
					});
					$query0= DB::select('select e.id,e.first_name,e.last_name
						from employees e join works w on e.id=w.id join timesheets t on e.id= t.id and t.prj_no=w.prj_no
						where year(date)= ?
						group by e.id,e.first_name,e.last_name
						order by e.id',[$year]);

					$sheet ->fromArray(array(
					array (null , null, null , null ,null, 'Effort (MD)'),
					array(null,'รหัสพนักงาน','Name-Surname','Project','Project Name','Jan-'.$year,'Feb-'.$year,'Mar-'.$year,'Apr-'.$year,'May-'.$year,'Jun-'.$year,'Jul-'.$year,'Aug-'.$year,'Sep-'.$year,'Oct-'.$year,'Nov-'.$year,'Dec-'.$year)
					) , NULL , 'A2',false,false );
					$currentRow = 4;
					foreach($query0 as $query0_v){
						$sheet->SetCellValue('B'.$currentRow,$query0_v->id)
							->SetCellValue('C'.$currentRow,$query0_v->first_name.' '.$query0_v->last_name);
						$currentRow++;
						$query1 = DB::select('select w.prj_no,p.prj_name
							from (employees e join works w on e.id=w.id join timesheets t on e.id= t.id and t.prj_no=w.prj_no) join projects p on p.prj_no=w.prj_no
							where year(date)= ? and e.id = ?
							group by w.prj_no,p.prj_name',[$year,$query0_v->id]);
						$test = 30;
						foreach($query1 as $query1_v){

							$sheet->SetCellValue('D'.$currentRow,$query1_v->prj_no)
									->SetCellValue('E'.$currentRow,$query1_v->prj_name);
							$query2 = DB::select('select month(date) as month,sum(TIME_TO_SEC(cal_works(t.date,t.time_in,t.time_out)))/(8*60*60) as effort
								from employees e join works w on e.id=w.id join timesheets t on e.id= t.id and t.prj_no=w.prj_no
								where year(date) = ? and e.id = ? and w.prj_no = ?
								group by e.id,w.prj_no,month(date)
								order by e.id',[$year,$query0_v->id,$query1_v->prj_no]);
							foreach($query2 as $query2_v){
								$sheet -> SetCellValue(chr(69+$query2_v->month).$currentRow,$query2_v->effort);
							}
							$currentRow++;
							
						}
						$sheet ->mergeCells('D'.$currentRow.':'.'E'.$currentRow);
						$sheet->SetCellValue('D'.$currentRow,"Non project code");
						$query3 = DB::select('SELECT loe.leave_date FROM leaverequest_of_employee loe join employees e on loe.id=e.id where loe.id= ? and year(loe.leave_date)= ? and loe.status="Accepted"',[$query0_v->id,$year]);

						for($i =1;$i<13;$i++){
							$month[$i] = 0;
						}
						foreach($query3 as $query3_v){
								$month[(int)date('m', strtotime($query3_v->leave_date))]++;
						}
						for($i =1;$i<13;$i++){
							if($month[$i]>0){
								$sheet -> SetCellValue(chr(69+$i).$currentRow,$month[$i]);
							}
						}
						$currentRow++;


					}
					$currentRow++;
					$sheet ->mergeCells('B'.$currentRow.':'.'E'.$currentRow);
					$sheet->cell('B'.$currentRow,function($cell){
						$cell->setAlignment('center');
						$cell->setValignment('center');
					});
					$sheet ->SetCellValue('B'.$currentRow,'summary');
					for($i = 70;$i<82;$i++){
						$sheet->SetCellValue(chr($i).$currentRow,
						"=SUM(".chr($i)."4:".chr($i).($currentRow-1).")"
						);
					}
					$sheet->getStyle('F2')->applyFromArray(
						array(
						'borders' => array(
							'allborders' => array(
								'style' => PHPExcel_Style_Border::BORDER_THIN,
								'color' => array('argb' => '000')
							)
						)
						)
					);
					$sheet->getStyle('B3:Q'.($currentRow))->applyFromArray(
						array(
						'borders' => array(
							'allborders' => array(
								'style' => PHPExcel_Style_Border::BORDER_THIN,
								'color' => array('argb' => '000')
							)
						)
						)
					);
					$sheet->getStyle('B3:Q3')->applyFromArray(
						array(
						'borders' => array(
							'bottom' => array(
								'style' => PHPExcel_Style_Border::BORDER_NONE
							)
						)
						)
					);
					$sheet->getStyle('B4:Q4')->applyFromArray(
						array(
						'borders' => array(
							'top' => array(
								'style' => PHPExcel_Style_Border::BORDER_NONE
							)
						)
						)
					);
					$sheet->setFreeze('A4');
					//$sheet ->fromArray()
					//$sheet->setAllBorders('thin');

				  });

			}) -> export('xlsx');
		}else {
			return redirect('report');
		}
    }

    public function import(){
      /*$inputFileName = 'C:/Users/ice_2/OneDrive/Work/internship/Project/Timsheet/timesheet_Feb/Playtorium_Timesheet_Anuchit_CPM.xlsx'; 
      $sheetname = 'Feb 17'; 

      $id = '10005';
      $project = 'PS170006';

      $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
      $objReader = PHPExcel_IOFactory::createReader($inputFileType);

      $objReader->setLoadSheetsOnly($sheetname); 

      $objPHPExcel = $objReader->load($inputFileName); 
      
      $sheet = $objPHPExcel->getSheet(0); 
      $highestRow = $sheet->getHighestRow(); 
      $highestColumn = $sheet->getHighestColumn();

      //  Loop through each row of the worksheet in turn
      for ($row = 8; $row <= $highestRow; $row++){ 
          //  Read a row of data into an array
          $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                                          NULL,
                                          TRUE,
                                          FALSE);
          //  Insert row data array into your database of choice here
          if($rowData[0][1]!=NULL and $rowData[0][1] != 'Holiday' and strrpos($rowData[0][2],"Leave")==false and $rowData[0][1] != 'ลาพักร้อน'){
            DB::insert('insert into timesheets  values ( ? , ?, ? , ? , ? , ? , ? )', 
            [$id,$project,date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($rowData[0][0]))
            ,$rowData[0][1],$rowData[0][2],PHPExcel_Style_NumberFormat::toFormattedString($rowData[0][3],'hh:mm:ss')
            ,PHPExcel_Style_NumberFormat::toFormattedString($rowData[0][4]
              ,'hh:mm:ss')] );
          }
          
      }*/
    }

}
