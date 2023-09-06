<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\StudentsController;
use App\Http\Controllers\AttendancesController;
use App\Http\Controllers\ViolationsController;
use App\Http\Controllers\ReportsController;

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
    if(session()->has('email')){
        return redirect('dashboard');
    }
    return view('index');
});
Route::post('register',[UsersController::class,'register']);
Route::post('login',[UsersController::class,'login']);
Route::get('/logout', function () {
    if(session()->has('email')){
        session()->pull('email',null);
    }
    return redirect('/');
});

// --WebGaurd-- is a Middleware which protect pages to open without having session
// add --WebGaurd-- path as 'sessionGaurd' of middleware App\Http\Kernel.php
Route::get('dashboard',[DashboardController::class,'dashboard'])->Middleware('sessionGaurd');
// students 
Route::match(['get', 'post'], 'students', [StudentsController::class, 'view'])->name('students')->middleware('sessionGaurd');
Route::post('addStudent',[StudentsController::class,'register'])->Middleware('sessionGaurd');
Route::get('fetch_student_by_id/{id}',[StudentsController::class,'fetch'])->Middleware('sessionGaurd');
Route::post('updateStudent',[StudentsController::class,'update'])->Middleware('sessionGaurd');
Route::get('student/delete/{id}',[StudentsController::class,'delete'])->Middleware('sessionGaurd');

// Attendance 
Route::get('attendance',[AttendancesController::class,'viewClasses'])->Middleware('sessionGaurd');
Route::get('take-attendance/{class}/{section}',[AttendancesController::class,'viewNewAttendanceTable'])->name('take-attendance')->Middleware('sessionGaurd');
Route::get('update-attendance/{class}/{section}', function ($class, $section) {
    return view('update-attendance', compact('class', 'section'));
})->name('update-attendance')->middleware('sessionGaurd');
Route::match(['get', 'post'], 'view-attendance-register/{class}/{section}', [AttendancesController::class, 'viewAttendanceRegister'])->name('view-attendance-register')->middleware('sessionGaurd');
Route::post('fetchAttendance',[AttendancesController::class,'fetchOldAttendance'])->Middleware('sessionGaurd');
Route::post('saveAttendance',[AttendancesController::class,'save'])->Middleware('sessionGaurd');
Route::post('updateAttendance',[AttendancesController::class,'update'])->Middleware('sessionGaurd');
Route::match(['get', 'post'], 'search-student-attendance', [AttendancesController::class, 'searchStudentsAttendance'])->name('search-student-attendance')->middleware('sessionGaurd');

// Rule Violations 
Route::get('rule-violations',[ViolationsController::class,'fetchClassSection'])->Middleware('sessionGaurd');
Route::post('saveViolations',[ViolationsController::class,'save'])->Middleware('sessionGaurd');
Route::get('violations/delete/{id}',[ViolationsController::class,'delete'])->Middleware('sessionGaurd');

// Reports 
Route::match(['get', 'post'], 'report', [ReportsController::class, 'searchStudentsAttendanceReports'])->name('reports')->middleware('sessionGaurd');