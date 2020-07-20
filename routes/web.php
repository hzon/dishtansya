<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::post('/register', 'AuthController@register')->middleware('guest');
Route::post('/login', 'AuthController@login')->middleware('guest');
Route::post('/order', 'OrderController@store')->middleware('auth:api');
Route::get('/logout', 'AuthController@logout');

