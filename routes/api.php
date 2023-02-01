<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\StudentController;
use App\Http\Middleware\Admin;
use App\Http\Middleware\Tutor;
use App\Http\Middleware\Student;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\Auth\AccountController;
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



Route::middleware('guest')->group(function () {
    Route::post('register', [StudentController::class, 'create'])->middleware('cors')->name('create');
    Route::post('login', [LoginController::class, 'login'])->name('login');
    Route::get('countries', [CountryController::class, 'index'])->name('country.index');
    Route::get('subjects', [SubjectController::class, 'index'])->name('admin.subject.index');
});

Route::middleware('auth:api')->group(function () {
    Route::post('resetPassword', [AccountController::class, 'resetPassword'])->name('resetPassword');
    Route::post('resetInfo', [AccountController::class, 'resetInfo'])->name('resetInfo');
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('getUser', [AccountController::class, 'view'])->name('view');
    Route::get('country/{id}', [CountryController::class, 'get'])->name('country.get');
    Route::get('getSessionNotes/{id}', [SessionController::class, 'getSessionNotes'])->name('getSessionNotes');


});

Route::middleware([Admin::class, 'auth:api'])->prefix('admin')->group(__DIR__ . '/admin-portal.php');
Route::middleware([Tutor::class, 'auth:api'])->prefix('tutor')->group(__DIR__ . '/tutor-portal.php');
Route::middleware([Student::class, 'auth:api'])->prefix('student')->group(__DIR__ . '/student-portal.php');
