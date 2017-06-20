<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PHPExcel_Worksheet_Drawing;
use mergeCells;
use PHPExcel_Style_Border;

class MessagesController extends Controller
{
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

    public function export(){
        Excel::create('timesheet' , function ($excel) {

            $excel -> sheet('sheet' , function($sheet){
            $objDrawing = new PHPExcel_Worksheet_Drawing();
            $objDrawing->setPath('images/Logo_1.png');
            $objDrawing->setResizeProportional(true);
            $objDrawing->setWidth(40);
            $objDrawing->setCoordinates('A1');
            $objDrawing->setWorksheet($sheet);

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

            $sheet->cells('A6:J6' , function($cells){
              $cells->setBackground('#3399FF');
            });
            $sheet->cells('G7:I7' , function($cells){
              $cells->setBackground('#3399FF');
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

                $users= DB::select('select first_name,last_name,role from employees where id = ? ' , ['00000']  );
                $users2 = DB::select( 'select date_format(t.date, ? ) as date ,t.task_name,t.description,date_format(t.time_in,? ) as time_in,date_format(t.time_out,? ) as time_out,cal_works(t.time_in,t.time_out) as work_hours,cal_ot_wk(t.date,t.time_in,time_out),
                cal_ot_holiday_wk(t.date,t.time_in,time_out) , cal_ot_holiday_non_wk(t.date,t.time_in,time_out) from timesheets t where t.id= ? and date_format(t.time_in, ? )=? and t.prj_no=? ' , ['%Y/%m/%d','%H:%i%:%s','%H:%i%:%s','00000' , '%Y-%m' ,'2017-01','PS170001'  ]);
                $users3 = DB::select('select p.customer from projects p where p.prj_no=?' , ['PS170001']);
                $users4 = DB::select('select SEC_TO_TIME(sum(TIME_TO_SEC(cal_works(t.time_in,t.time_out)))) as sum_wk,
                SEC_TO_TIME(sum(TIME_TO_SEC(cal_ot_wk(t.date,t.time_in,time_out)))) as sum_ot_wk,
                SEC_TO_TIME(sum(TIME_TO_SEC(cal_ot_holiday_wk(t.date,t.time_in,time_out)))) as sum_ot_hwk,
                SEC_TO_TIME(sum(TIME_TO_SEC(cal_ot_holiday_non_wk(t.date,t.time_in,time_out)))) as sum_ot_hnwk,
                SEC_TO_TIME(sum(TIME_TO_SEC(cal_ot_wk(t.date,t.time_in,time_out)))+sum(TIME_TO_SEC(cal_ot_holiday_wk(t.date,t.time_in,time_out)))+sum(TIME_TO_SEC(cal_ot_holiday_non_wk(t.date,t.time_in,time_out)))) as sum_ot from timesheets t
                where t.id=? and date_format(t.time_in,?)=? and t.prj_no=?' , ['00000','%Y-%m','2017-01','PS170001']);

                $sheet ->fromArray(array(
                 array ('Name:' , $users[0]->first_name." ".$users[0]->last_name),
                 array ('Role' , $users[0]->role , null,'Duration', 'xxx'),
                 array ('Customer Site:',null , $users3[0]->customer),
                 array (),
                 array ('Date','Task Name' , 'Description' , 'Time In','Time Out',"Work(hours)" , "OT (hours)" , null , null , 'Remark'),
                 array (null,null,null,null,null,null,'Working Day','Holiday Working','Holiday Non-Working' ),

           ) , NULL , 'A2',false,false  );



           $rowCount = 8 ;
           $column = 'A';
           $eiei = count($users2);
             while($rowCount < (8+$eiei) ){
               $sheet -> SetCellValue('A'.$rowCount, $users2[$rowCount-8]->date);
               $sheet -> SetCellValue('B'.$rowCount, $users2[$rowCount-8]->task_name);
               $sheet -> SetCellValue('C'.$rowCount, $users2[$rowCount-8]->description);
               $sheet -> SetCellValue('D'.$rowCount, $users2[$rowCount-8]->time_in);
               $sheet -> SetCellValue('E'.$rowCount, $users2[$rowCount-8]->time_out);
               $sheet -> SetCellValue('F'.$rowCount, $users2[$rowCount-8]->work_hours);
               $rowCount++;
             }

             $sheet -> SetCellValue('C'.$rowCount, 'Total work hour/month');
             $sheet -> SetCellValue('F'.$rowCount, $users4[0]->sum_wk);
             $sheet -> SetCellValue('G'.$rowCount, $users4[0]->sum_ot_wk);
             $sheet -> SetCellValue('H'.$rowCount, $users4[0]->sum_ot_hwk);
             $sheet -> SetCellValue('I'.$rowCount, $users4[0]->sum_ot_hnwk);
             $rowCount++;
             $sheet -> SetCellValue('C'.$rowCount, 'Total OT hour/month');
             $sheet -> SetCellValue('F'.$rowCount, $users4[0]->sum_ot);
             $rowCount++;
             $sheet -> SetCellValue('C'.$rowCount, 'Totlal work day/month');
             $rowCount++;
             $rowCount++;

             $sheet ->mergeCells('A'.$rowCount.':'.'C'.$rowCount);
             $sheet -> SetCellValue('A'.$rowCount, 'Sick Leave');
             $sheet->cells('A'.$rowCount.':'.'C'.$rowCount , function($cells){
               $cells->setAlignment('center');
               $cells->setValignment('center');
             });
              $rowCount++;

             $sheet ->mergeCells('A'.$rowCount.':'.'C'.$rowCount);
             $sheet -> SetCellValue('A'.$rowCount, 'Annual Leave');
             $sheet->cells('A'.$rowCount.':'.'C'.$rowCount , function($cells){
               $cells->setAlignment('center');
               $cells->setValignment('center');
             });
             $rowCount++;

             $sheet ->mergeCells('A'.$rowCount.':'.'C'.$rowCount);
             $sheet -> SetCellValue('A'.$rowCount, 'Private Leave');
             $sheet->cells('A'.$rowCount.':'.'C'.$rowCount , function($cells){
               $cells->setAlignment('center');
               $cells->setValignment('center');
             });
             $rowCount++;

             $sheet ->mergeCells('A'.$rowCount.':'.'C'.$rowCount);
             $sheet -> SetCellValue('A'.$rowCount, 'Public Leave');
             $sheet->cells('A'.$rowCount.':'.'C'.$rowCount , function($cells){
               $cells->setAlignment('center');
               $cells->setValignment('center');
             });

             $rowCount++;
             $rowCount++;

             $sheet ->mergeCells('A'.$rowCount.':'.'G'.$rowCount);
             $sheet -> SetCellValue('A'.$rowCount, 'OT Working Day = work on working day ( Mon-Fri)  after normal working hour ( 9:00-18:00 ), after 30 minute break');
             $rowCount++;
             $sheet ->mergeCells('A'.$rowCount.':'.'G'.$rowCount);
             $sheet -> SetCellValue('A'.$rowCount, 'OT Holiday Working Hour = work on weekend and holiday in normal working hour ( 9:00-18:00 )');
             $rowCount++;
             $sheet ->mergeCells('A'.$rowCount.':'.'G'.$rowCount);
             $sheet -> SetCellValue('A'.$rowCount, 'OT Holiday Non Working Hour = work on weekend and holiday after normal working hour ( 9:00-18:00 ) after 30 minute break');

             $rowCount++;
             $rowCount++;

             $sheet ->mergeCells('A'.$rowCount.':'.'B'.($rowCount+1));
             $sheet -> SetCellValue('A'.$rowCount, 'Prepared by');
             $sheet->cells('A'.$rowCount.':'.'B'.($rowCount+1) , function($cells){
               $cells->setAlignment('center');
               $cells->setValignment('center');
             });

             $sheet ->mergeCells('C'.$rowCount.':'.'D'.($rowCount+1));
             $sheet -> SetCellValue('C'.$rowCount, '___________________________');
             $sheet->cells('C'.$rowCount.':'.'D'.($rowCount+1) , function($cells){
               $cells->setAlignment('center');
               $cells->setValignment('center');
             });

             $sheet ->mergeCells('E'.$rowCount.':'.'G'.($rowCount+1));
             $sheet -> SetCellValue('E'.$rowCount, '_________________');
             $sheet->cells('E'.$rowCount.':'.'G'.($rowCount+1) , function($cells){
               $cells->setAlignment('center');
               $cells->setValignment('center');
             });

             $sheet->cells('C'.$rowCount.':'.'D'.($rowCount+1) , function($cells){
               $cells->setAlignment('center');
               $cells->setValignment('center');
             });
             $rowCount++;
             $rowCount++;

              $sheet ->mergeCells('C'.$rowCount.':'.'D'.$rowCount);
              $sheet -> SetCellValue('C'.$rowCount, '(your name)');
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
              });

              $sheet ->mergeCells('C'.$rowCount.':'.'D'.($rowCount+1));
              $sheet -> SetCellValue('C'.$rowCount, '___________________________');
              $sheet->cells('C'.$rowCount.':'.'D'.($rowCount+1) , function($cells){
                $cells->setAlignment('center');
                $cells->setValignment('center');
              });

              $sheet ->mergeCells('E'.$rowCount.':'.'G'.($rowCount+1));
              $sheet -> SetCellValue('E'.$rowCount, '_________________');
              $sheet->cells('E'.$rowCount.':'.'G'.($rowCount+1) , function($cells){
                $cells->setAlignment('center');
                $cells->setValignment('center');
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
             $value =  $sheet->getCell('C6')->getValue();
             $width = mb_strwidth ($value);
             $sheet->setWidth('C' , $width + 15);
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



          });

        }) -> export('xlsx');
    }

}
