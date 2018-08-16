<?php

// Step 1
Route::get('/ynab/auth', 'YnabController@auth');
Route::get('/ynab/redirect', 'YnabController@redirect');

// Step 2
Route::get('/ynab/choose-budget', 'YnabController@chooseBudget');
Route::get('/ynab/choose-budget/{budget_id}', 'YnabController@chosenBudget');

// Step 3
Route::get('/ynab/choose-account', 'YnabController@chooseAccount');
Route::get('/ynab/choose-account/{account_id}', 'YnabController@chosenAccount');


// Start over
Route::get('/ynab/reset', 'YnabController@reset');
Route::get('/ynab/reset-budget', 'YnabController@resetBudget');
Route::get('/ynab/reset-account', 'YnabController@resetAccount');
