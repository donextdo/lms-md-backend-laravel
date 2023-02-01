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


Route::get('classes', [TutorController::class, 'tutorClasses'])->name('tutor.class.tutorClasses');
Route::get('students', [TutorController::class, 'tutorStudents'])->name('tutor.class.tutorStudents');



Route::get('sessionClass/{id}', [ClassController::class, 'sessionClass'])->name('tutor.class.sessionClass');
Route::get('studentClass/{id}', [ClassController::class, 'studentClass'])->name('tutor.class.studentClass');

Route::put('blockStudent/{id}', [StudentController::class, 'block'])->name('tutor.student.block');
Route::get('student/{id}', [StudentController::class, 'get'])->name('tutor.student.get');


Route::put('postpone/{id}', [SessionController::class, 'postpone'])->name('tutor.session.postpone');
Route::post('session', [SessionController::class, 'create'])->name('tutor.session.create');
Route::get('session/{id}', [SessionController::class, 'get'])->name('tutor.session.get');
Route::post('session/{id}', [SessionController::class, 'update'])->name('tutor.session.update');
Route::delete('session/{id}', [SessionController::class, 'delete'])->name('tutor.session.delete');
Route::put('recording/{id}', [SessionController::class, 'recording'])->name('tutor.session.recording');

Route::get('dashboard', [DashboardController::class, 'tutorDashboard'])->name('tutor.dashboard');
