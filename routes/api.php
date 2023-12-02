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

Route::group(['prefix'=>'v1','namespace'=>'Api'],function(){

    Route::post('register','AuthController@register');
    Route::post('login','AuthController@login');
    Route::post('forget-password','AuthController@forgetPassword');
    Route::post('reset-password','AuthController@resetPassword');
    Route::post('submit-token','AuthController@submitToken')->middleware('auth:sanctum');

    Route::group(['namespace'=>'Patient'],function(){

         Route::get('departments','HomeController@departments');
         Route::get('doctors','HomeController@doctors');

         Route::group(['middleware'=>['auth:sanctum','role:patient']],function(){

            Route::get('doctor-appointments','ReservationController@appointments');
            Route::post('book-appointment/{id}','ReservationController@bookAppointment');
            Route::get('patient-reservations','ReservationController@reservations');
            Route::get('patient-notifications','ReservationController@notifications');

         });
    });

    Route::group(['namespace'=>'Doctor'],function(){

        Route::group(['middleware'=>['auth:sanctum','role:doctor']],function(){
              
           Route::post('accept-reservation/{id}','ReservationController@acceptReservation');
           Route::post('reject-reservation/{id}','ReservationController@rejectReservation');
           Route::get('doctor-notifications','ReservationController@notifications');
           Route::get('doctor-reservations','HomeController@reservations');

        });
   });

});