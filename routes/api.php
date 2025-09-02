<?php

use App\Http\Controllers\Api\VisitController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('visits/{id}/mark-visited', [VisitController::class, 'markVisited'])->name('api.visits.mark-visited');
