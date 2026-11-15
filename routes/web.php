<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\StudentController;
use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $todayCounts = Attendance::query()
        ->whereDate('attendance_date', today())
        ->selectRaw('status, count(*) as total')
        ->groupBy('status')
        ->pluck('total', 'status');
    $totalStudents = Student::count();
    $recentAttendances = Attendance::query()
        ->with('student')
        ->latest('attendance_date')
        ->latest('id')
        ->limit(8)
        ->get();

    return view('dashboard', compact('todayCounts', 'totalStudents', 'recentAttendances'));
})->name('dashboard');

Route::resource('students', StudentController::class)->except('show');
Route::resource('attendances', AttendanceController::class)->except('show');
