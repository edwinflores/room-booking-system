<?php

use App\Http\Controllers\BookingsController;
use App\Http\Controllers\RoomsController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::shouldUse('auth0-api');

Route::group(['prefix' => 'bookings'], function ($router) {
    Route::get('rooms', [RoomsController::class, 'list']);

    // Route::group(['middleware' => 'auth'], function () {
    //     Route::get('protected', [MessagesController::class, 'showProtectedMessage']);
    //     Route::get('admin', [MessagesController::class, 'showAdminMessage'])->can('read:admin-messages');
    // });
});