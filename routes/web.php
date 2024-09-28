<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Menu;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\LeaveRequestController;


Route::redirect('/', '/dashboard');
Auth::routes();

// Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::middleware('auth')->group(function () {

    Route::resource('menu', Menu::class);
    Route::get('/menu/delete/{id}', [Menu::class, 'destroy']);

    // Set the default route to the index method of AdminController
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.index');

    // Resource routes for Employees
    Route::resource('employees', EmployeeController::class);

    // Resource routes for Users
    Route::resource('users', UserController::class);
    Route::get('users/edit/{user}', [UserController::class, 'edit'])->name('users.edit');
    Route::get('users/create', [UserController::class, 'create'])->name('users.create');



    Route::get('export-users', [UserController::class, 'export']);

    // Resource routes for Attendances
    Route::resource('attendances', AttendanceController::class);
    // Additional attendance routes
    Route::get('attendances/pdf', [AttendanceController::class, 'generatePDF'])->name('attendances.pdf');

    Route::get('attendances/export/', [AttendanceController::class, 'export'])->name('attendances.export');

    Route::get('export-users', [UserController::class, 'export'])->name('users.export');


Route::resource('leave_requests', LeaveRequestController::class);
    Route::get('/test', function () {
        return 'Test route is working!';
    });



    Route::get('attendance-image/{date}/{type}/{filename}', function ($date, $type, $filename) {
        $path = "E:/Python App/output/$date/$type/$filename";

        if (!File::exists($path)) {
            abort(404);
        }

        $file = File::get($path);
        $type = File::mimeType($path);

        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);

        return $response;
    });

    Route::get('/report/department', [ReportController::class, 'departmentReportForm'])->name('report.departmentForm');
    Route::post('/report/department', [ReportController::class, 'generateDepartmentReport']);
    Route::post('/report/download-department', [ReportController::class, 'downloadExcel']);// Route to show individual report form

    Route::get('/report/individual', [ReportController::class, 'individualReportForm'])->name('report.individualForm');
    Route::post('/report/individual', [ReportController::class, 'generateIndividualReport']);
    Route::post('/report/download-individual-excel', [ReportController::class, 'downloadIndividualExcel']);
    Route::put('/leave-requests/{leaveRequest}/update-status', [LeaveRequestController::class, 'updateStatus'])->name('leave_requests.updateStatus');







});

