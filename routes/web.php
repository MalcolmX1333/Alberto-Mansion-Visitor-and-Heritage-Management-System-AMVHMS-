<?php

use App\Http\Controllers\Admin\AdminReservationController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Guest\ReservationController;
use App\Http\Controllers\VisitorInformationController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingPageController::class, 'index'])->name('landing.page');
Auth::routes();

// Instead of /create/{id}/qr-code, try:
Route::get('/reservation/{id}/qr-code', [App\Http\Controllers\QRController::class, 'generateQrCode'])->name('generate.qr');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/age-demographics', [HomeController::class, 'ageDemographics'])->name('age-demographics');
    Route::get('/gender-demographics', [HomeController::class, 'genderDemographics'])->name('gender-demographics');
    Route::get('/most-visited', [HomeController::class, 'mostVisited'])->name('most-visited');
    Route::get('/most-visited-today', [HomeController::class, 'visitToday'])->name('most-visited-today');
    Route::get('/most-visited-month', [HomeController::class, 'visitThisMonth'])->name('most-visited-month');
    Route::get('/student-demographics', [HomeController::class, 'studentDemographics'])->name('student-demographics');

    Route::get('/visitor/index', [VisitorInformationController::class, 'index'])->name('visitor.index');
    Route::get('/visitor-information/data-table', [VisitorInformationController::class, 'dataTable'])->name('visitor-information.data-table');
    Route::get('/visitor-information', [VisitorInformationController::class, 'getVisitorData'])->name('visitor.info');
    Route::get('/visitor/export', [VisitorInformationController::class, 'export'])->name('visitor.export');

    Route::get('/visitor/demographics/{entryId}', [VisitorInformationController::class, 'view'])->name('visitor.demographics');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/reservations', [AdminReservationController::class, 'index'])->name('reservation.index');
        Route::get('/reservations/{id}/details', [AdminReservationController::class, 'details'])->name('reservation.details');
        Route::put('/reservations/{id}/status', [AdminReservationController::class, 'updateStatus'])->name('reservation.update-status');
        Route::delete('/reservations/{id}', [AdminReservationController::class, 'destroy'])->name('reservation.destroy');
        Route::get('/reservations/{id}/qr', [AdminReservationController::class, 'generateQR'])->name('reservation.qr');
        Route::get('/reservations/search', [AdminReservationController::class, 'search'])->name('reservation.search');
        Route::get('/mark-visited/{id}', [AdminReservationController::class, 'markVisited'])->name('reservation.mark-visited');
    });
});

Route::middleware(['guest.survey.auth'])->group(function () {
    Route::get('/guest-survey', [GuestController::class, 'create'])->name('guest.survey.create');
    Route::post('/guest-survey', [GuestController::class, 'store'])->name('guest.survey.store');


//Route::get('/feedback-survey', [FeedbackController::class, 'create'])->name('feedback.survey.create');
//Route::post('/feedback-survey', [FeedbackController::class, 'store'])->name('feedback.survey.store');
});



Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');


Route::get('/guest/reservations', [ReservationController::class, 'index'])->name('guest.reservation.index');
Route::get('/guest/reservation/{id}/details', [ReservationController::class, 'details'])->name('guest.reservation.details');
Route::get('/guest/reservation/{id}/edit', [ReservationController::class, 'edit'])->name('guest.reservation.edit');

Route::get('/registration-type-demographics', [HomeController::class, 'registrationTypeDemographics'])->name('registration.type.demographics');


Route::get('/visit/unauthorized', function () {
    return view('layouts.partials.unauthorizedVisit');
})->name('visit.unauthorized');


Route::get('api/visits/{id}/mark-visited', [App\Http\Controllers\Api\VisitController::class, 'markVisited'])
    ->name('api.visits.mark-visited')
    ->middleware(['auth', 'role:Admin']);
