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

// fetch only own project in new page
Route::get('/project/fetchOwnProject', 'ProjectController@fetchOwnProject');

// insert project
Route::post('/project/store', 'ProjectController@store');

// delete project
Route::delete('/project/destroy', 'ProjectController@destroy');

Route::post('/project/changeStatus', 'ProjectController@changeStatus');

// insert Member
Route::post('/project/insertMember', 'ProjectController@insertMember');

// delete Member
Route::post('/project/deleteMember', 'ProjectController@deleteMember');

// view project by prj_no
Route::get('/project/{prj_no}', 'ProjectController@show');

Route::get('/report', 'ReportController@getdata');

Route::post('/report/export' , 'MessagesController@export');

Route::get('/export2' , 'MessagesController@export2');

Route::get('/report/trueexport', 'ReportController@export');

Route::get('/sendbasicmail','MailController@html_email');

Route::get('/leave_request_history', 'LeaverequestController@index')->name('leave_request_history');

Route::post('/timesheet/addLeave', 'LeaverequestController@addLeave');

Route::get('/verify/accept/{code}' , 'LeaverequestController@accept');

Route::get('/verify/reject/{code}' , 'LeaverequestController@reject');

Route::get('/holiday', 'HolidayController@index');

Route::get('/holiday/fetch', 'HolidayController@fetch');

Route::post('/holiday/store', 'HolidayController@store');

Route::delete('/holiday/destroy', 'HolidayController@destroy');

Route::get('/report/getYear', 'ReportController@getYear');

Route::get('/report/getMonth', 'ReportController@getMonth');

Route::get('/report/getProject', 'ReportController@getProject');

Route::get('/leave_request_history/fetch', 'LeaverequestController@fetch');

Route::get('/leave_request_history/getYear', 'LeaverequestController@getYear');

Route::get('/updatepassword', 'Auth\UpdatePasswordController@index');

Route::post('/updatepassword/update', 'Auth\UpdatePasswordController@update');