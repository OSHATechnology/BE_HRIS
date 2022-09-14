<?php

use App\Http\Controllers\EmployeeController;
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

Route::get('',[EmployeeController::class,'index']);
Route::post('/add',[EmployeeController::class,'create']);
Route::post('/store',[EmployeeController::class,'store']);
Route::get('/show/{id}',[EmployeeController::class,'show']);
Route::get('/edit/{id}',[EmployeeController::class,'edit']);
Route::put('/update/{id}',[EmployeeController::class,'update']);
Route::delete('/destroy/{id}',[EmployeeController::class,'destroy']);
