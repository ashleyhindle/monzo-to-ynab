<?php

// Step 1
Route::get('/monzo/auth', 'MonzoController@auth');
Route::get('/monzo/redirect', 'MonzoController@redirect');

// Step 2
Route::get('/monzo/choose-account', 'MonzoController@choose');
Route::get('/monzo/choose-account/{account_id}', 'MonzoController@chosen');


Route::get('/monzo/reset', 'MonzoController@reset');
Route::get('/monzo/reset-account', 'MonzoController@resetAccount');

// Final step
Route::get('/monzo/setup-webhook', 'MonzoController@setupWebhook');
Route::get('/monzo/cancel', 'MonzoController@cancelMessage');
Route::get('/monzo/cancel/{account_id}', 'MonzoController@cancel');

// Parse incoming transaction, find YNAB OauthToken, refresh it if needed, add YNAB transaction
Route::post('/monzo/webhook', 'MonzoWebhookController@webhook');
