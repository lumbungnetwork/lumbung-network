<?php

Route::group(['prefix' => '2fa'], function () {
    Route::get('/', 'LoginSecurityController@show2faForm')->name('2fa')->middleware('auth');
    Route::post('/generateSecret', 'LoginSecurityController@generate2faSecret')->name('generate2faSecret')->middleware('auth');
    Route::post('/enable2fa', 'LoginSecurityController@enable2fa')->name('enable2fa')->middleware('auth');
    Route::post('/disable2fa', 'LoginSecurityController@disable2fa')->name('disable2fa')->middleware('auth');

    // 2fa middleware
    Route::post('/2faVerify', function () {
        return redirect(URL()->previous());
    })->name('2faVerify')->middleware(['auth', '2fa']);
});

// test middleware
Route::get('/test_middleware', function () {
    return "2FA middleware work!";
})->middleware(['auth', '2fa']);

// Dashboard
Route::get('/dashboard', 'Finance\AppController@getFinanceDashboard')->name('dashboard')->middleware('auth');
Route::get('/platform-liquidity', 'Finance\AppController@getPlatformLiquidityDetails')->name('platformLiquidityDetails')->middleware('auth');



//account
Route::get('/account', 'Finance\AppController@getAccountPage')->name('account')->middleware('auth');
Route::get('/account/credentials', 'Finance\AppController@getAccountCredentialsPage')->name('account.credentials')->middleware(['auth', '2fa']);
Route::get('/account/addresses', 'Finance\AppController@getAccountAddressesPage')->name('account.addresses')->middleware(['auth', '2fa']);
Route::get('/account/referrals-list', 'Finance\AppController@getAccountReferralsList')->name('account.refferals')->middleware(['auth', '2fa']);
Route::get('/account/activate', 'Finance\AppController@getAccountActivate')->name('account.activate')->middleware('auth');
Route::post('/account/activate/trc10', 'Finance\AppController@postAccountActivateTRC10')->name('account.activate.postTRC10')->middleware('auth');
Route::post('/account/set-tron', 'Finance\AppController@postAccountSetTron')->name('account.set-tron')->middleware('auth');
Route::post('/account/reset-tron', 'Finance\AppController@postAccountResetTron')->name('account.reset-tron')->middleware('auth');

//contract
Route::get('/contracts', 'Finance\ContractController@getContractsPage')->name('contracts')->middleware('auth');
Route::get('/new-contract', 'Finance\ContractController@getNewContractPage')->name('contracts.new')->middleware('auth');
Route::get('/contracts/{contract_id}', 'Finance\ContractController@getContractDetailPage')->name('contracts.detail')->middleware('auth');
Route::post('/contracts/{contract_id}/compound', 'Finance\ContractController@postContractCompound')->name('contracts.post.compound')->middleware('auth');
Route::post('/contracts/{contract_id}/withdraw', 'Finance\ContractController@postContractWithdraw')->name('contracts.post.withdraw')->middleware('auth');
Route::post('/contracts/{contract_id}/upgrade', 'Finance\ContractController@postContractUpgrade')->name('contracts.post.upgrade')->middleware('auth');
Route::post('/contracts/{contract_id}/break', 'Finance\ContractController@postContractBreak')->name('contracts.post.break')->middleware('auth');
Route::post('/new-contract', 'Finance\ContractController@postNewContract')->name('contracts.post.new')->middleware('auth');

//wallet
Route::get('/wallet', 'Finance\AppController@getWallet')->name('wallet')->middleware(['auth', '2fa']);
Route::get('/wallet/deposit-usdt', 'Finance\AppController@getWalletDepositUSDT')->name('wallet.deposit-usdt')->middleware('auth');
Route::get('/wallet/test-decode', 'Finance\AppController@getTest')->name('wallet.test')->middleware('auth');

// Credit
Route::post('/credit/convert-from-usdt', 'Finance\CreditController@postConvertFromUSDT')->name('credit.postConvertFromUSDT')->middleware('auth');
Route::post('/credit/convert-to-usdt', 'Finance\CreditController@postConvertToUSDT')->name('credit.postConvertToUSDT')->middleware('auth');
Route::post('/credit/transfer', 'Finance\CreditController@postCreditTransfer')->name('credit.postCreditTransfer')->middleware('auth');

//AJAX
Route::group(['prefix' => 'ajax'], function () {
    Route::post('/verify-usdt-deposit', 'Finance\AjaxController@postVerifyUSDTDeposit')->name('ajax.postVerifyUSDTDeposit')->middleware('auth');
    Route::post('/validate-usdt-deposit', 'Finance\AjaxController@postValidateUSDTDeposit')->name('ajax.postValidateUSDTDeposit')->middleware('auth');
    Route::get('/convert-from-usdt', 'Finance\AjaxController@getConvertFromUSDT')->name('ajax.getConvertFromUSDT')->middleware('auth');
    Route::get('/convert-to-usdt', 'Finance\AjaxController@getConvertToUSDT')->name('ajax.getConvertToUSDT')->middleware('auth');
    Route::get('/credit/transfer', 'Finance\AjaxController@getCreditTransfer')->name('ajax.getCreditTransfer')->middleware('auth');
    Route::get('/usdt/withdraw', 'Finance\AjaxController@getUSDTWithdraw')->name('ajax.getUSDTWithdraw')->middleware('auth');
    Route::post('/usdt/withdraw', 'Finance\AppController@postUSDTWithdraw')->name('ajax.postUSDTWithdraw')->middleware('auth');

    Route::get('/wallet/history', 'Finance\AjaxController@getWalletHistory')->name('ajax.getWalletHistory')->middleware('auth');
    Route::get('/account/telegram', 'Finance\AjaxController@getAccountTelegram')->name('ajax.getAccountTelegram')->middleware('auth');
    Route::get('/dashboard/platform-liquidity', 'Finance\AjaxController@getPlatformLiquidity')->name('ajax.getPlatformLiquidity')->middleware('auth');
});
