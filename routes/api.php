<?php

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\StatusHireController;
use App\Models\StatusHire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::prefix('employee')->group(function () {
//     Route::controller(EmployeeController::class)->group(function () {
//         Route::get('asd', 'index')->name('employee.index');
//         Route::get('/add', 'create')->name('employee.create');
//         Route::get('/store', 'store')->name('employee.store');
//         Route::get('/show', 'show')->name('employee.show');
//         Route::get('/edit', 'edit')->name('employee.edit');
//         Route::get('/update', 'update')->name('employee.update');
//     });
// });

Route::get('/employee',[EmployeeController::class,'index']);
Route::get('/employee/add',[EmployeeController::class,'create']);
Route::post('/employee/store',[EmployeeController::class,'store']);
Route::get('/employee/show/{id}',[EmployeeController::class,'show']);
Route::get('/employee/edit/{id}',[EmployeeController::class,'edit']);
Route::put('/employee/update/{id}',[EmployeeController::class,'update']);
Route::delete('/employee/destroy/{id}',[EmployeeController::class,'destroy']);

Route::get('/status_hire',[StatusHireController::class,'index']);
Route::get('/status_hire/add',[StatusHireController::class,'add']);
Route::post('/status_hire/store',[StatusHireController::class,'store']);
Route::get('/status_hire/show/{id}',[StatusHireController::class,'show']);
Route::get('/status_hire/edit/{id}',[StatusHireController::class,'edit']);
Route::put('/status_hire/update/{id}',[StatusHireController::class,'update']);
Route::delete('status_hire/destroy/{id}',[StatusHireController::class,'destroy']);

Route::resource('notification', NotificationController::class);
