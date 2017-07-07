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

//timesheet route
Route::get('/', function () {
    return redirect()->route('timesheet');
});

Route::get('/timesheet', 'TimesheetController@index')->name('timesheet');

Route::post('/timesheet/addTask', 'TimesheetController@addTask');

//leave request route
// Route::get('/leave_request', function () {
//     return view('leave_request');
// });

Route::get('/leave_request', 'LeaverequestController@leave_request')->name('leave_request');

Route::get('/report', 'ReportController@getdata');

Route::get('/project' , 'ProjectController@showProjectList')->name('project');
Route::get('/project/search','ProjectController@search');

Route::post('/project/addProject' , 'ProjectController@addProject');

Route::post('/submit' , 'MessagesController@submit');

Route::post('/report/export' , 'MessagesController@export');


Route::get('/project_detail', 'projectDetailList@showProjectDetailList');

Route::get('/project/{id}', 'ProjectController@showProjectDetailList');
Route::post('/project/addProjectMember' , 'ProjectController@addProjectMember');

Route::post('/project/deleteMember' , 'ProjectController@deleteMember');

Route::post('/project/deleteProject' , 'ProjectController@deleteProject');

Route::get('/export2' , 'MessagesController@export2');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/sendbasicmail','MailController@html_email');

Route::get('/leave_request_history', 'LeaverequestController@index')->name('leave_request_history');

Route::post('/timesheet/addLeave', 'LeaverequestController@addLeave');
