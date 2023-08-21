<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Labelscontroller;
use App\Http\Controllers\NoteController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
  
// });

Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);
Route::post('/forgotpassword',[AuthController::class,'forgotPassword']);
Route::post('/resetpassword',[AuthController::class,'resetPassword']);
Route::get('/notes/getnotes',[NoteController::class,'getNotes']);

//middleware used and grouped
Route::group(['middleware' => 'auth:api'], function () {
Route::post('/logout',[AuthController::class,'logout']);
Route::post('/verifyEmail',[AuthController::class,'verifyEmail']);
Route::get('/userdetails',[AuthController::class,'userDetails']);
//Route::post('/resetpassword',[AuthController::class,'resetPassword']);


Route::post('/note/create',[NoteController::class,'create']);
Route::put('/note/update/{id}',[NoteController::class,'updateNote']); //id manually
Route::delete('/note/delete/{title}',[NoteController::class,'deleteNotes']); 


Route::post('/label/make',[Labelscontroller::class,'makeLabel']); 
Route::post('/addNoteTolabel/{labelsId}/notes/{noteId}',[Labelscontroller::class,'addNoteToLabel']); //add note from label 
Route::delete('/delNoteFromLabel/{labelsId}/notes/{noteId}',[Labelscontroller::class,'delNoteFromLabel']); //del note from label
Route::delete('/label/delLabel/{id}',[Labelscontroller::class,'delLabel']);
Route::put('/label/update/{id}',[Labelscontroller::class,'updateLabel']);
    
});



