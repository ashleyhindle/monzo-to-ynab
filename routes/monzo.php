<?php
Route::get('/monzo/auth', 'MonzoController@auth');
Route::get('/monzo/choose-account', 'MonzoController@choose');
Route::get('/monzo/choose-account/{account_id}', 'MonzoController@chosen');
Route::get('/monzo/reset', 'MonzoController@reset');
Route::get('/monzo/reset-account', 'MonzoController@resetAccount');

// Exchange authorization code for an access token and refresh token
// Hit the 'who am i' API and get the account_id and store these securely against that?
Route::get('/monzo/redirect', 'MonzoController@redirect');

// Parse incoming transaction, find YNAB OauthToken, refresh it if needed, add YNAB transaction
Route::get('/monzo/webhook', 'MonzoController@webhook');
