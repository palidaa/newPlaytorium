<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use DB;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();

    
        $schedule->call(function () {
            $employees = DB:: select('select * from employees');
            $annualDay = 0;

            foreach($employees as $employee){
                if($employee->type <= 1){
                    $annualDay = 6;
                }
                else if($employee->type > 1 &&  $employee->type < 8){
                    $annualDay = 12;
                }
                else if($employee->type >= 8 ){
                    $annualDay = 15;
                }
            $leave = DB::select('select SUM(l.totalhours)*0.125 as leave_used 
            from leaverequest_of_employee l 
            where l.id= ? and leave_type= ? and year(l.leave_date)= ? and l.status !=?' 
            , [$employee->id,'Annual Leave', date('Y',strtotime(' -1 year')), 'Rejected' ] );

            $sum_leave = $annualDay  - $leave[0]->leave_used;
           if($sum_leave>=5) { $sum_leave=5; }
           else if($sum_leave<=0) { $sum_leave=0; }

            DB::update('update employees set carry_annual_leave = ? where employees.id =?', [$sum_leave,$employee->id]);
            }
           // DB::update('update timesheet.employees set carry_annual_leave = ? where timesheet.employees.id =10002', [rand(1,100)]);

        });
    
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
