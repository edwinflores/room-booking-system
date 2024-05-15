<?php

use App\Http\Controllers\BookingsController;
use App\Http\Controllers\RoomsController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::shouldUse('auth0-api');

Route::group(['prefix' => 'bookings'], function ($router) {
    // Route::get('rooms', [RoomsController::class, 'list']);

    Route::group(['middleware' => 'auth'], function () {
        // Rooms
        Route::get('rooms', [RoomsController::class, 'list']); // List all rooms
        Route::post('rooms', [RoomsController::class, 'listByAvailability']); // List all rooms available between two datetimes

        // Bookings
        Route::get('my-bookings', [BookingsController::class, 'listByUser']);
        Route::post('add', [BookingsController::class, 'add']);
    });
});
