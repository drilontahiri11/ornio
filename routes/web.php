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


Route::get('auth/login','Auth\AuthenticateController@login');


Route::group(['prefix'=>'api/v1/','middleware' => ['jwt.auth']], function() {
    Route::get('logout','Auth\AuthenticateController@logout');

    Route::get('dashboard','DashboardController@index');

    Route::get('vacations','VacationController@index');

    Route::group(['middleware'=>['admin']],function(){
        Route::post('vacation/{id}/accept','VacationController@acceptRequest');
        Route::post('vacation/{id}/deny','VacationController@denyRequest');
    });

    Route::post('vacation/new', 'VacationController@newVacationRequest');
    Route::get('vacation/used-days', 'VacationController@getEmployeeUsedVacationDays');
});
