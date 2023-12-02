<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['prefix'=>'v1','namespace'=>'Api\Admin','middleware'=>['auth:sanctum','role:admin']],function(){

         Route::get('doctors','DoctorController@index')->name('doctors.index');
         Route::post('doctors','DoctorController@store')->name('doctors.store');
         Route::put('doctors/{doctor}','DoctorController@update')->name('doctors.update');
         Route::delete('doctors/{doctor}','DoctorController@destroy')->name('doctors.destroy');
         
         Route::get('appointments','AppointmentController@index')->name('appointments.index');
         Route::post('appointments','AppointmentController@store')->name('appointments.store');
         Route::put('appointments/{appointment}','AppointmentController@update')->name('appointments.update');
         Route::delete('appointments/{appointment}','AppointmentController@destroy')->name('appointments.destroy');

});