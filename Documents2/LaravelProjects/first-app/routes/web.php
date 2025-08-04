<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/aboutme', function () {
    return view('aboutme');
});

Route::get('/contact', function () {
    return view('contact');
});

Route::get('/physiology', function () {
    return view('physiology');
});

Route::get('/intervalPaces', function () {
    return view('intervalPaces');
});