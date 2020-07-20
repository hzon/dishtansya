<?php

use Illuminate\Support\Facades\Route;

Route::get('/login', function () {
    return view('welcome');
});

Route::middleware('auth:api')->post('/order', 'OrderController@store');
Route::post('/register', 'AuthController@register');
Route::post('/login', 'AuthController@login');

