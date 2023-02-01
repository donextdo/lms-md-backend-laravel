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

Route::get('subjects', [SubjectController::class, 'index'])->name('admin.subject.index');
Route::post('subject', [SubjectController::class, 'create'])->name('admin.subject.create');
Route::get('subject/{id}', [SubjectController::class, 'get'])->name('admin.subject.get');
Route::put('subject/{id}', [SubjectController::class, 'update'])->name('admin.subject.update');
Route::delete('subject/{id}', [SubjectController::class, 'delete'])->name('admin.subject.delete');

Route::get('grades', [GradeController::class, 'index'])->name('admin.grade.index');
Route::post('grade', [GradeController::class, 'create'])->name('admin.grade.create');
Route::get('grade/{id}', [GradeController::class, 'get'])->name('admin.grade.get');
Route::put('grade/{id}', [GradeController::class, 'update'])->name('admin.grade.update');
Route::delete('grade/{id}', [GradeController::class, 'delete'])->name('admin.grade.delete');

Route::get('tutors', [TutorController::class, 'index'])->name('admin.tutor.index');
Route::post('tutor', [TutorController::class, 'create'])->name('admin.tutor.create');
Route::get('tutor/{id}', [TutorController::class, 'get'])->name('admin.tutor.get');
Route::put('tutor/{id}', [TutorController::class, 'update'])->name('admin.tutor.update');
Route::delete('tutor/{id}', [TutorController::class, 'delete'])->name('admin.tutor.delete');

Route::get('classes', [ClassController::class, 'index'])->name('admin.class.index');
Route::get('classes/{subject}', [ClassController::class, 'classesPerSubject'])->name('admin.class.classesPerSubject');
Route::post('class', [ClassController::class, 'create'])->name('admin.class.create');
Route::get('class/{id}', [ClassController::class, 'get'])->name('admin.class.get');
Route::put('class/{id}', [ClassController::class, 'update'])->name('admin.class.update');
Route::delete('class/{id}', [ClassController::class, 'delete'])->name('admin.class.delete');
Route::get('sessionClass/{id}', [ClassController::class, 'sessionClass'])->name('admin.class.sessionClass');
Route::get('studentClass/{id}', [ClassController::class, 'studentClass'])->name('admin.class.studentClass');


Route::get('studentCount', [StudentController::class, 'studentCount'])->name('admin.student.studentCount');
Route::get('students', [StudentController::class, 'index'])->name('admin.student.index');
Route::get('studentRequestIndetail/{id}', [DashboardController::class, 'studentRequestIndetail'])->name('admin.student.studentRequestIndetail');
Route::post('student', [StudentController::class, 'create'])->name('admin.student.create');
Route::get('student/{id}', [StudentController::class, 'get'])->name('admin.student.get');
Route::put('student/{id}', [StudentController::class, 'update'])->name('admin.student.update');
Route::delete('student/{id}', [StudentController::class, 'delete'])->name('admin.student.delete');
Route::put('blockStudent/{id}', [StudentController::class, 'block'])->name('admin.student.block');
Route::put('activateStudent/{id}', [StudentController::class, 'activate'])->name('admin.student.activate');
Route::put('approveStudent/{id}', [StudentController::class, 'approveStudent'])->name('admin.student.approveStudent');
Route::put('shiftSession/{class}/{student}', [StudentController::class, 'shiftSession'])->name('admin.student.shift');
Route::get('sessionStatus/{class}/{student}', [StudentController::class, 'sessionStatus'])->name('admin.session.sessionStatus');

Route::get('sessions', [SessionController::class, 'index'])->name('admin.session.index');
Route::post('session', [SessionController::class, 'create'])->name('admin.session.create');
Route::get('session/{id}', [SessionController::class, 'get'])->name('admin.session.get');
Route::post('session/{id}', [SessionController::class, 'update'])->name('admin.session.update');
Route::put('recording/{id}', [SessionController::class, 'recording'])->name('admin.session.recording');
Route::delete('session/{id}', [SessionController::class, 'delete'])->name('admin.session.delete');


Route::get('session_emails', [Session_emailController::class, 'index'])->name('admin.session_email.index');
Route::post('session_email', [Session_emailController::class, 'create'])->name('admin.session_email.create');
Route::get('session_email/{id}', [Session_emailController::class, 'get'])->name('admin.session_email.get');
Route::put('session_email/{id}', [Session_emailController::class, 'update'])->name('admin.session_email.update');
Route::delete('session_email/{id}', [Session_emailController::class, 'delete'])->name('admin.session_email.delete');

Route::get('dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');
Route::get('refreshRequests', [DashboardController::class, 'refreshRequests'])->name('admin.refreshRequests');
