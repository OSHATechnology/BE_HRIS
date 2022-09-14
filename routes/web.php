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
    return ['Laravel' => app()->version()];
});

Route::prefix('employee')->group(function () {
    Route::controller(EmployeeController::class)->group(function () {
        Route::get('/', 'index')->name('employee.index');
        Route::post('/add', 'create')->name('employee.create');
        Route::post('/store', 'store')->name('employee.store');
        Route::get('/show/{id}', 'show')->name('employee.show');
        Route::get('/edit/{id}', 'edit')->name('employee.edit');
        Route::put('/update/{id}', 'update')->name('employee.update');
        Route::delete('/destroy/{id}', 'destroy')->name('employee.destroy');
    });
});

require __DIR__ . '/auth.php';
