<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\NotesController;
use App\Http\Controllers\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ApiMid;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
  
// });

Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);
Route::post('/forgotpassword',[AuthController::class,'forgotPassword']);

Route::get('/notes/getnotes',[NotesController::class,'getNotes']);

Route::group(['middleware' => 'auth:api'], function () {
Route::post('/logout',[AuthController::class,'logout']);
Route::post('/verifyEmail',[AuthController::class,'verifyEmail']);
Route::get('/userdetails',[AuthController::class,'userDetails']);
Route::post('/resetpassword',[AuthController::class,'resetPassword']);
Route::post('/note/create',[NotesController::class,'create']);
Route::put('/note/update{id}',[NotesController::class,'updateNote']); //id manually





});



