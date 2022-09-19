<?php

use App\Http\Controllers\AttendanceStatusController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\StatusHireController;
use App\Models\StatusHire;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\API\AuthenticatedController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\FurloughController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\TeamMemberController;
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

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/auth/login', [AuthenticatedController::class, 'store']);
Route::post('/auth/logout', [AuthenticatedController::class, 'destroy'])->middleware('auth:sanctum');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::resource('roles', RoleController::class)->except(['create', 'edit']);
    Route::post('roles-permissions/detach', [RolePermissionController::class, 'detachPermissionFromRole']);
    Route::resource('role-permissions', RolePermissionController::class)->only(['index', 'store']);
    Route::resource('permissions', PermissionController::class)->except(['create', 'edit']);
    Route::apiResource('furlough', FurloughController::class)->except(['create', 'edit']);
    Route::post('employee/import', [EmployeeController::class, 'import']);
    Route::apiResource('employee', EmployeeController::class);
    Route::resource('notification', NotificationController::class);
    Route::resource('status_hire', StatusHireController::class);
    Route::resource('attendance_status', AttendanceStatusController::class);
    Route::apiResource('attendance', AttendanceController::class);
    Route::resource('team', TeamController::class);
    Route::resource('team_member', TeamMemberController::class);
    Route::apiResource('partners', PartnerController::class)->except(['create', 'edit']);
});
