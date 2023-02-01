<?php

use App\Http\Controllers\GradeController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TutorController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\Session_emailController;
use App\Http\Controllers\DashboardController;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
});


Route::get('classes', [StudentController::class, 'studentClasses'])->name('student.studentClasses');
Route::get('pastClasses', [StudentController::class, 'studentPastClasses'])->name('student.studentPastClasses');
Route::get('dashboard', [DashboardController::class, 'studentDashboard'])->name('student.dashboard');




