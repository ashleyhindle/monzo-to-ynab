<?php

Route::get('/', function () {
    return view('home');
});

Route::get('/done', function () {
    session()->forget('monzo');
    session()->forget('ynab');

    return view('done');
});

require_once __DIR__ . '/monzo.php';
require_once __DIR__ . '/ynab.php';

Route::get('/about', function () {
    return view('about');
});

Route::get('/privacy', function () {
    return view('privacy');
});
