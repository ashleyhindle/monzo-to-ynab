<?php

Route::get('/', function () {
    return view('welcome');
});

Route::get('/monzo/auth', function() {
   return view('monzo/auth');
});
