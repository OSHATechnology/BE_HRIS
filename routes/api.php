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
use App\Http\Controllers\API\PasswordResetLinkController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\BasicSalaryByEmployeeController;
use App\Http\Controllers\BasicSalaryByRoleController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\FurloughController;
use App\Http\Controllers\FurloughTypeController;
use App\Http\Controllers\OvertimeController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\SalaryCutDetailController;
use App\Http\Controllers\TeamMemberController;
use App\Http\Controllers\TodayAttendanceController;
use App\Http\Controllers\WorkPermitController;
use App\Models\BasicSalaryByEmployee;
use App\Models\Employee;
use App\Models\WorkPermit;
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
Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->middleware('guest');
Route::post('/logout', [AuthenticatedController::class, 'destroy'])->middleware('auth:sanctum');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('roles', RoleController::class);
    Route::post('roles-permissions/detach', [RolePermissionController::class, 'detachPermissionFromRole']);
    Route::apiResource('role-permissions', RolePermissionController::class)->only(['index', 'store']);
    Route::apiResource('permissions', PermissionController::class);
    Route::put('furlough/attendance_accepted/{id}', [FurloughController::class, 'attendance_accepted']);
    Route::put('furlough/attendance_declined/{id}', [FurloughController::class, 'attendance_declined']);
    Route::apiResource('furlough', FurloughController::class);
    Route::apiResource('furlough_type', FurloughTypeController::class);
    Route::post('employee/import', [EmployeeController::class, 'import']);
    Route::post('employee/update_password/{id}', [EmployeeController::class, 'update_password']);
    Route::get('employee/trash', [EmployeeController::class, 'trash']);
    Route::get('employee/restore/{id}', [EmployeeController::class, 'restore']);
    Route::apiResource('employee', EmployeeController::class);
    Route::apiResource('notification', NotificationController::class);
    Route::apiResource('status_hire', StatusHireController::class);
    Route::apiResource('attendance_status', AttendanceStatusController::class);
    Route::get('attendance/today', [AttendanceController::class, 'today']);
    Route::apiResource('attendance', AttendanceController::class);
    Route::apiResource('overtime', OvertimeController::class);
    Route::apiResource('work_permit', WorkPermitController::class);
    Route::apiResource('team', TeamController::class);
    Route::apiResource('team_member', TeamMemberController::class);
    Route::apiResource('partners', PartnerController::class)->except(['create', 'edit']);
    Route::apiResource('basic_salary_by_role', BasicSalaryByRoleController::class);
    Route::apiResource('basic_salary_by_employee', BasicSalaryByEmployeeController::class);
    Route::get('salary/auto/{id}', [SalaryController::class, 'automatic_data']);
    Route::apiResource('salary', SalaryController::class);
    Route::get('salary_cut/att_cut/{id}', [SalaryCutDetailController::class, 'attendanceCutFee']);
    Route::apiResource('salary_cut', SalaryCutDetailController::class);
});
