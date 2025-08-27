<?php

use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VisitorInformationController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/', [LandingPageController::class, 'index'])->name('home');

Auth::routes(['register' => false]);

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/age-demographics', [HomeController::class, 'ageDemographics'])->name('age-demographics');
Route::get('/gender-demographics', [HomeController::class, 'genderDemographics'])->name('gender-demographics');
Route::get('/most-visited', [HomeController::class, 'mostVisited'])->name('most-visited');
Route::get('/most-visited-today', [HomeController::class, 'visitToday'])->name('most-visited-today');
Route::get('/most-visited-month', [HomeController::class, 'visitThisMonth'])->name('most-visited-month');
Route::get('/student-demographics', [HomeController::class, 'studentDemographics'])->name('student-demographics');

Route::get('/guest-survey', [GuestController::class, 'create'])->name('guest.survey.create');
Route::post('/guest-survey', [GuestController::class, 'store'])->name('guest.survey.store');


Route::get('/visitor/index', [VisitorInformationController::class, 'index'])->name('visitor.index');
Route::get('/visitor-information/data-table', [VisitorInformationController::class, 'dataTable'])->name('visitor-information.data-table');
Route::get('/visitor-information', [VisitorInformationController::class, 'getVisitorData'])->name('visitor.info');
Route::get('/visitor/export', [VisitorInformationController::class, 'export'])->name('visitor.export');

Route::get('/visitor/demographics/{entryId}', [VisitorInformationController::class, 'view'])->name('visitor.demographics');


Route::get('/feedback-survey', [FeedbackController::class, 'create'])->name('feedback.survey.create');
Route::post('/feedback-survey', [FeedbackController::class, 'store'])->name('feedback.survey.store');

Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');


