<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/private', function () {
    return response('Welcome! You are logged in.');
})->middleware('auth');
