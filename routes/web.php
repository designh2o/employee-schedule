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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/schedule', ['as' => 'schedule.get.work', 'uses' => 'ScheduleController@getWorkSchedule']);
Route::get('/schedule-free', ['as' => 'schedule.get.free', 'uses' => 'ScheduleController@getFreeSchedule']);