<?php

Route::get('/', 'Admin\HomeController@getFront')->name('frontLogin');
Route::get('/area/login', 'Admin\HomeController@getAreaLogin')->name('areaLogin');
Route::post('/area/login', 'Admin\HomeController@postAreaLogin');
//referal link
Route::get('/ref/{code_referal}', 'FrontEnd\ReferalController@getAddReferalLink')->name('referalLink');
Route::post('/ref', 'FrontEnd\ReferalController@postAddReferalLink');
Route::get('/forgot/passwd', 'FrontEnd\FrontEndController@getForgotPassword')->name('forgotPasswd');
Route::post('/forgot/passwd', 'FrontEnd\FrontEndController@postForgotPassword');
Route::get('/auth/passwd/{code}/{email}', 'FrontEnd\FrontEndController@getAuthPassword')->name('passwdauth');
Route::post('/auth/passwd', 'FrontEnd\FrontEndController@postAuthPassword');

Auth::routes();
Route::prefix('/')->group(function () {
    
    Route::get('/adm/dashboard', 'Admin\DashboardController@getDashboard')->name('admDashboard')->middleware('auth');
    Route::get('/user_logout', 'Admin\HomeController@getUserLogout')->middleware('auth');
    
    //Wilayah Admin
        //admin
        Route::get('/adm/add-admin', 'Admin\MasterAdminController@getAddAdmin')->name('addCrew')->middleware('auth');
        Route::post('/adm/new-admin', 'Admin\MasterAdminController@postAddAdmin')->middleware('auth');

        //setting
        Route::get('/adm/add/pin-setting', 'Admin\MasterAdminController@getAddPinSetting')->name('addSettingPin')->middleware('auth');
        Route::post('/adm/add/pin-setting', 'Admin\MasterAdminController@postPinSetting')->middleware('auth');
        Route::get('/adm/packages', 'Admin\MasterAdminController@getAllPackage')->name('allPackage')->middleware('auth');
        Route::post('/adm/package', 'Admin\MasterAdminController@postUpdatePackage')->middleware('auth');
        Route::get('/adm/bank', 'Admin\MasterAdminController@getBankPerusahaan')->name('adm_bankPerusahaan')->middleware('auth');
        Route::post('/adm/bank', 'Admin\MasterAdminController@postBankPerusahaan')->middleware('auth');
        Route::get('/adm/add/bank', 'Admin\MasterAdminController@getAddBankPerusahaan')->name('adm_addBankPerusahaan')->middleware('auth');
        Route::post('/adm/add/bank', 'Admin\MasterAdminController@postAddBankPerusahaan')->middleware('auth');
        Route::get('/adm/bonus-start', 'Admin\MasterAdminController@getBonusStart')->name('adm_bonusStart')->middleware('auth');
        Route::post('/adm/bonus-start', 'Admin\MasterAdminController@postBonusStart')->middleware('auth');
        
        //Pin & Transaction
        Route::get('/adm/list/transactions', 'Admin\MasterAdminController@getListTransactions')->name('adm_listTransaction')->middleware('auth');
        Route::post('/adm/confirm/transaction', 'Admin\MasterAdminController@postConfirmTransaction')->middleware('auth');
        Route::get('/adm/list/kirim-paket', 'Admin\MasterAdminController@getListKirimPaket')->name('adm_listKirimPaket')->middleware('auth');
        Route::get('/adm/kirim-paket/{id}/{user_id}', 'Admin\MasterAdminController@getKirimPaketByID')->name('adm_KirimPaketID')->middleware('auth');
        Route::post('/adm/kirim-paket', 'Admin\MasterAdminController@postConfirmKirimPaket')->middleware('auth');
        
        //Member
        Route::get('/adm/list/member', 'Admin\MasterAdminController@getAllMember')->name('adm_listMember')->middleware('auth');
        Route::get('/adm/list/bonus-sp', 'Admin\MasterAdminController@getAllBonusSponsor')->name('adm_listBonusSP')->middleware('auth');
        Route::get('/adm/list/wd', 'Admin\MasterAdminController@getAllWD')->name('adm_listWD')->middleware('auth');
        Route::post('/adm/check/wd', 'Admin\MasterAdminController@postCheckWD')->middleware('auth');
        Route::post('/adm/reject/wd', 'Admin\MasterAdminController@postRejectWD')->middleware('auth');
        Route::get('/adm/history/wd', 'Admin\MasterAdminController@getAllHistoryWD')->name('adm_listHistoryWD')->middleware('auth');

        //Ajax
        Route::get('/ajax/adm/admin/{type}/{id}', 'Admin\AjaxController@getAdminById')->middleware('auth');
        Route::get('/ajax/adm/package/{id}', 'Admin\AjaxController@getPackageById')->middleware('auth');
        Route::get('/ajax/adm/cek/transaction/{id}/{user_id}', 'Admin\AjaxController@getCekTransactionById')->middleware('auth');
        Route::get('/ajax/adm/bank/{id}', 'Admin\AjaxController@getBankPerusahaan')->middleware('auth');
        Route::get('/ajax/adm/kirim-paket/{id}/{user_id}', 'Admin\AjaxController@getKirimPaket')->middleware('auth');
        Route::get('/ajax/adm/cek/kirim-paket', 'Admin\AjaxController@getCekKirimPaket')->middleware('auth');
        Route::get('/ajax/adm/cek/reject-wd/{id}', 'Admin\AjaxController@getCekRejectWD')->middleware('auth');
        Route::get('/ajax/adm/cek/detail-wd/{id}', 'Admin\AjaxController@getCekDetailWD')->middleware('auth');
        
        
        ////////////////////////////////////////////////////////////////////////
        //##########################
        ////////////////////////////////////////////////////////////////////////
        //#########################
        
        
    //Wilayah Member
        Route::get('/m/test/email', 'Admin\MemberController@getTestEmail')->middleware('auth');
        Route::get('/m/dashboard', 'Admin\DashboardController@getMemberDashboard')->name('mainDashboard')->middleware('auth');
        
        //profile
        Route::get('/m/profile', 'Admin\MemberController@getMyProfile')->name('m_myProfile')->middleware('auth');
        Route::get('/m/add/profile', 'Admin\MemberController@getAddMyProfile')->name('m_newProfile')->middleware('auth');
        Route::post('/m/add/profile', 'Admin\MemberController@postAddMyProfile')->middleware('auth');
        
        //Bank
        Route::get('/m/bank', 'Admin\MemberController@getMyBank')->name('m_myBank')->middleware('auth');
        Route::post('/m/add/bank', 'Admin\MemberController@postAddBank')->middleware('auth');
        Route::post('/m/activate/bank', 'Admin\MemberController@postActivateBank')->middleware('auth');
        
        //Sponsor
        Route::get('/m/add/sponsor', 'Admin\MemberController@getAddSponsor')->name('m_newSponsor')->middleware('auth');
        Route::post('/m/add/sponsor', 'Admin\MemberController@postAddSponsor')->middleware('auth');
        Route::get('/m/status/sponsor', 'Admin\MemberController@getStatusSponsor')->name('m_statusSponsor')->middleware('auth');
        Route::get('/m/add/placement', 'Admin\MemberController@getAddPlacement')->name('m_addPlacement')->middleware('auth');
        Route::post('/m/add/placement', 'Admin\MemberController@postAddPlacement')->middleware('auth');
        Route::get('/m/my/sponsor', 'Admin\MemberController@getMySponsor')->name('m_mySponsor')->middleware('auth');
        Route::get('/m/my/binary', 'Admin\MemberController@getMyBinary')->name('m_myBinary')->middleware('auth');
        Route::get('/m/status/member', 'Admin\MemberController@getStatusMember')->name('m_statusMember')->middleware('auth');
        
        //Package
        Route::get('/m/add/package', 'Admin\MemberController@getAddPackage')->name('m_newPackage')->middleware('auth');
        Route::post('/m/add/package', 'Admin\MemberController@postAddPackage')->middleware('auth');
        Route::get('/m/list/order-package', 'Admin\MemberController@getListOrderPackage')->name('m_listOrderPackage')->middleware('auth');
        Route::get('/m/detail/order-package/{paket_id}', 'Admin\MemberController@getDetailOrderPackage')->name('m_detailOrderPackage')->middleware('auth');
        Route::post('/m/confirm/package', 'Admin\MemberController@postActivatePackage')->middleware('auth');
        Route::get('/m/add/upgrade', 'Admin\MemberController@getAddUpgrade')->name('m_newUpgrade')->middleware('auth');
        Route::post('/m/add/upgrade', 'Admin\MemberController@postAddUpgrade')->middleware('auth');
        Route::get('/m/add/repeat-order', 'Admin\MemberController@getAddRO')->name('m_newRO')->middleware('auth');
        Route::post('/m/add/repeat-order', 'Admin\MemberController@postAddRO')->middleware('auth');
        
        //Pin & Transaction
        Route::get('/m/add/pin', 'Admin\MemberController@getAddPin')->name('m_newPin')->middleware('auth');
        Route::post('/m/add/pin', 'Admin\MemberController@postAddPin')->middleware('auth');
        Route::get('/m/list/transactions', 'Admin\MemberController@getListTransactions')->name('m_listTransactions')->middleware('auth');
        Route::get('/m/add/transaction/{id}', 'Admin\MemberController@getAddTransaction')->name('m_addTransaction')->middleware('auth');
        Route::post('/m/add/transaction', 'Admin\MemberController@postAddTransaction')->middleware('auth');
        Route::post('/m/reject/transaction', 'Admin\MemberController@postRejectTransaction')->middleware('auth');
        Route::get('/m/pin/stock', 'Admin\MemberController@getMyPinStock')->name('m_myPinStock')->middleware('auth');
        Route::get('/m/pin/history', 'Admin\MemberController@getMyPinHistory')->name('m_myPinHistory')->middleware('auth');
        Route::get('/m/add/transfer-pin', 'Admin\MemberController@getTransferPin')->name('m_addTransferPin')->middleware('auth');
        Route::post('/m/add/transfer-pin', 'Admin\MemberController@postAddTransferPin')->middleware('auth');
        
        //Menu Bonus
        Route::get('/m/summary/bonus', 'Admin\BonusmemberController@getMySummaryBonus')->name('m_myBonusSummary')->middleware('auth');
        Route::get('/m/sponsor/bonus', 'Admin\BonusmemberController@getMySponsorBonus')->name('m_myBonusSponsor')->middleware('auth');
        Route::get('/m/binary/bonus', 'Admin\BonusmemberController@getMyBinaryBonus')->name('m_myBonusBinary')->middleware('auth');
        Route::get('/m/level/bonus', 'Admin\BonusmemberController@getMyLevelBonus')->name('m_myBonusLevel')->middleware('auth');
        Route::get('/m/ro/bonus', 'Admin\BonusmemberController@getMyROBonus')->name('m_myBonusRO')->middleware('auth');
        Route::get('/m/saldo/bonus', 'Admin\BonusmemberController@getMySaldoBonus')->name('m_myBonusSaldo')->middleware('auth');
        Route::post('/m/request/wd', 'Admin\BonusmemberController@postRequestWithdraw')->middleware('auth');
        Route::get('/m/history/wd', 'Admin\BonusmemberController@getHistoryWithdrawal')->name('m_historyWD')->middleware('auth');
        Route::get('/m/req/wd', 'Admin\BonusmemberController@getRequestWithdrawal')->name('m_requestWD')->middleware('auth');
        
        //Ajax
        Route::get('/m/cek/add-sponsor', 'Admin\AjaxmemberController@postCekAddSponsor')->middleware('auth');
        Route::get('/m/cek/add-package/{id_paket}', 'Admin\AjaxmemberController@getCekAddPackage')->middleware('auth');
        Route::get('/m/cek/add-pin', 'Admin\AjaxmemberController@postCekAddPin')->middleware('auth');
        Route::get('/m/cek/add-profile', 'Admin\AjaxmemberController@postCekAddProfile')->middleware('auth');
        Route::get('/m/cek/add-transaction', 'Admin\AjaxmemberController@postCekAddTransaction')->middleware('auth');
        Route::get('/m/cek/reject-transaction', 'Admin\AjaxmemberController@postCekRejectTransaction')->middleware('auth');
        Route::get('/m/cek/confirm-order', 'Admin\AjaxmemberController@getCekConfirmOrderPackage')->middleware('auth');
        Route::get('/m/cek/add-bank', 'Admin\AjaxmemberController@getCekAddBank')->middleware('auth');
        Route::get('/m/activate/bank/{id}', 'Admin\AjaxmemberController@getActivateBank')->middleware('auth');
        Route::get('/m/cek/kirim-paket', 'Admin\AjaxmemberController@getCekConfirmKirimPaket')->middleware('auth');
        Route::get('/m/cek/transfer-pin', 'Admin\AjaxmemberController@getCekTransferPin')->middleware('auth');
        Route::get('/m/cek/upgrade-package/{id_paket}', 'Admin\AjaxmemberController@getCekUpgrade')->middleware('auth');
        Route::get('/m/cek/placement/{id}/{type}', 'Admin\AjaxmemberController@getCekPlacementKiriKanan')->middleware('auth');
        Route::get('/m/cek/usercode', 'Admin\AjaxmemberController@getSearchUserCode')->middleware('auth');
        Route::get('/m/cek/repeat-order', 'Admin\AjaxmemberController@getCekRO')->middleware('auth');
        Route::get('/m/cek/confirm-wd', 'Admin\AjaxmemberController@getCekConfirmWD')->middleware('auth');
});
