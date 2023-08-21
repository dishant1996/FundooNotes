<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
   return view('auth.reset-password');})->name('password.reset');

    
//Route::get('/',[AuthController::class,'resetPassword'])->name('login');;

    //Route::get('/', function () {
        // return view('auth.reset-password');})->name('password.reset');

         //Route::get('resetpassword', 'AuthController@resetPassword')->name('login');

         