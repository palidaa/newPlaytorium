<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

// timesheet route

// redirect to timesheet page
Route::get('/', function () {
    return redirect()->route('timesheet');
});

// route to timesheet page
Route::get('/timesheet', 'TimesheetController@index')->name('timesheet');

// route to insert page
Route::get('/timesheet/new', 'TimesheetController@create')->name('new');

// fetch timesheet
Route::get('/timesheet/fetch', 'TimesheetController@fetch');

// insert timesheet
Route::post('/timesheet/store', 'TimesheetController@store');

// edit timesheet
Route::post('/timesheet/update', 'TimesheetController@update');

// delete timesheet
Route::delete('/timesheet/destroy', 'TimesheetController@destroy');

// leave request route
Route::get('/leave_request', function () {
    return view('leave_request');
});

Route::get('/leave_request', 'LeaverequestController@leave_request')->name('leave_request');

// project route

// route to project page
Route::get('/project', 'ProjectController@index')->name('project');

// fetch project
Route::get('/project/fetch', 'ProjectController@fetch');

//add hasmember in fatch
Route::get('/project/hasMembers', 'ProjectController@hasMembers');

// fetch only own project in new page
Route::get('/project/fetchOwnProject', 'ProjectController@fetchOwnProject');

// insert project
Route::post('/project/store', 'ProjectController@store');

// change project date
Route::post('/project/updateDuration', 'ProjectController@updateDuration');

// delete project
Route::delete('/project/destroy', 'ProjectController@destroy');

Route::post('/project/changeStatus', 'ProjectController@changeStatus');


// insert Member
Route::post('/project/insertMember', 'ProjectController@insertMember');

// delete Member
Route::post('/project/deleteMember', 'ProjectController@deleteMember');

// view project by prj_no
Route::get('/project/{prj_no}', 'ProjectController@show');

Route::get('/report', 'ReportController@index')->name('report');

Route::post('/report/export' , 'MessagesController@export');

Route::get('/export2' , 'MessagesController@export2');

Route::get('/report/export-timesheet', 'ReportController@export');

Route::get('/report/export-summary-timesheet', 'MessagesController@export');

Route::get('/sendbasicmail','MailController@html_email');

Route::get('/leave_request_history', 'LeaverequestController@index')->name('leave_request_history');

// delete leaverequest
Route::delete('/leave_request_history/destroy', 'LeaverequestController@destroy');

Route::get('/leave_request/get-leaves-in-month', 'LeaverequestController@get_leaves_in_month');

Route::post('/timesheet/addLeave', 'LeaverequestController@addLeave');

Route::get('/verify/accept/{code}' , 'LeaverequestController@accept');

Route::get('/verify/reject/{code}' , 'LeaverequestController@reject');

Route::get('/holiday', 'HolidayController@index');

Route::get('/holiday/fetch', 'HolidayController@fetch');

Route::post('/holiday/store', 'HolidayController@store');

Route::get('/holiday/get-year', 'HolidayController@get_year');

Route::delete('/holiday/destroy', 'HolidayController@destroy');

Route::get('/report/getyear', 'ReportController@getYear');

Route::get('/report/getmonth', 'ReportController@getMonth');

Route::get('/report/getproject', 'ReportController@getProject');

Route::get('/leave_request_history/fetch', 'LeaverequestController@fetch');

Route::get('/leave_request_history/getYear', 'LeaverequestController@getYear');

Route::get('/updatepassword', 'Auth\UpdatePasswordController@index');

Route::post('/updatepassword/update', 'Auth\UpdatePasswordController@update');

Route::post('/upload', 'FileController@upload');

Route::get('/download', 'FileController@download');

Route::delete('/delete', 'FileController@delete');