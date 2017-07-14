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
Route::get('/timesheet/new', 'TimesheetController@new')->name('new');

// fetch timesheet
Route::get('/timesheet/fetch', 'TimesheetController@fetch');

// insert timesheet
Route::post('/timesheet/insert', 'TimesheetController@insert');

// edit timesheet
Route::post('/timesheet/update', 'TimesheetController@update');

// delete timesheet
Route::delete('/timesheet/delete', 'TimesheetController@delete');

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

// insert project
Route::post('/project/insert', 'ProjectController@insert');

// insert Member
Route::post('/project/insertMember', 'ProjectController@insertMember');

// delete Member
Route::post('/project/deleteMember', 'ProjectController@deleteMember');

// view project by prj_no
Route::get('/project/{prj_no}', 'ProjectController@view');

Route::get('/report', 'ReportController@getdata');

Route::post('/report/export' , 'MessagesController@export');

Route::get('/export2' , 'MessagesController@export2');

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/sendbasicmail','MailController@html_email');

Route::get('/leave_request_history', 'LeaverequestController@index')->name('leave_request_history');

Route::post('/timesheet/addLeave', 'LeaverequestController@addLeave');

Route::get('/verify/accept/{code}' , 'LeaverequestController@accept');

Route::get('/verify/reject/{code}' , 'LeaverequestController@reject');

Route::get('/admin/holiday', 'AdminHolidayController@showHolidayList');

Route::post('/admin/holiday/deleteHoliday', 'AdminHolidayController@deleteHoliday');

Route::post('/admin/holiday/addHoliday', 'AdminHolidayController@addHoliday');

Route::get('/admin/new_user', 'AdminUserController@showForm');

Route::post('/admin/new_user/register', 'AdminUserController@register');
