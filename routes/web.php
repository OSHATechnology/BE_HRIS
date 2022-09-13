<?php

use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Route;

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

Route::prefix('employee')->group(function () {
    Route::controller(EmployeeController::class)->group(function () {
        Route::get('/', 'index')->name('employee.index');
        Route::get('/add', 'create')->name('employee.create');
        Route::get('/store', 'store')->name('employee.store');
        Route::get('/show/{id}', 'show')->name('employee.show');
        Route::get('/edit', 'edit')->name('employee.edit');
        Route::get('/update', 'update')->name('employee.update');
    });
});
