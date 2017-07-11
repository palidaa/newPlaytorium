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

Route::get('/report', function () {
    return view('report');
});

// project route

// route to project page
Route::get('/project', 'ProjectController@index')->name('project');

// fetch project
Route::get('/project/fetch', 'ProjectController@fetch');

// insert project
Route::post('/project/insert', 'ProjectController@insert');

// view project by prj_no
Route::get('/project/{prj_no}', 'ProjectController@view');

Route::post('/submit', 'MessagesController@submit');

Route::get('/export', 'MessagesController@export');

Route::get('/export2', 'MessagesController@export2');

Route::get('/report', function () {
    return view('report');
});

Route::get('/home', 'HomeController@index')->name('home');
