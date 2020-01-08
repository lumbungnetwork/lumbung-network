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
        Route::post('/adm/tron', 'Admin\MasterAdminController@postTronPerusahaan')->middleware('auth');
        Route::get('/adm/add/tron', 'Admin\MasterAdminController@getAddTronPerusahaan')->name('adm_addTronPerusahaan')->middleware('auth');
        Route::post('/adm/add/tron', 'Admin\MasterAdminController@postAddTronPerusahaan')->middleware('auth');
        Route::get('/adm/bonus-start', 'Admin\MasterAdminController@getBonusStart')->name('adm_bonusStart')->middleware('auth');
        Route::post('/adm/bonus-start', 'Admin\MasterAdminController@postBonusStart')->middleware('auth');
        Route::get('/adm/add/bonus-reward', 'Admin\MasterAdminController@getNewBonusReward')->name('adm_newReward')->middleware('auth');
        Route::post('/adm/add/bonus-reward', 'Admin\MasterAdminController@postNewBonusReward')->middleware('auth');
        Route::get('/adm/bonus-reward', 'Admin\MasterAdminController@getBonusReward')->name('adm_Rewards')->middleware('auth');
        Route::post('/adm/bonus-reward', 'Admin\MasterAdminController@postBonusReward')->middleware('auth');
        
        //Pin & Transaction
        Route::get('/adm/list/transactions', 'Admin\MasterAdminController@getListTransactions')->name('adm_listTransaction')->middleware('auth');
        Route::post('/adm/confirm/transaction', 'Admin\MasterAdminController@postConfirmTransaction')->middleware('auth');
        Route::post('/adm/reject/transaction', 'Admin\MasterAdminController@postRejectTransaction')->middleware('auth');
        Route::get('/adm/list/kirim-paket', 'Admin\MasterAdminController@getListKirimPaket')->name('adm_listKirimPaket')->middleware('auth');
        Route::get('/adm/kirim-paket/{id}/{user_id}', 'Admin\MasterAdminController@getKirimPaketByID')->name('adm_KirimPaketID')->middleware('auth');
        Route::post('/adm/kirim-paket', 'Admin\MasterAdminController@postConfirmKirimPaket')->middleware('auth');
        Route::get('/adm/history/transactions', 'Admin\MasterAdminController@getListHistoryTransactions')->name('adm_listHistoryTransaction')->middleware('auth');
        
        //Member
        Route::get('/adm/list/member', 'Admin\MasterAdminController@getAllMember')->name('adm_listMember')->middleware('auth');
        Route::get('/adm/list/bonus-sp', 'Admin\MasterAdminController@getAllBonusSponsor')->name('adm_listBonusSP')->middleware('auth');
        Route::get('/adm/list/wd', 'Admin\MasterAdminController@getAllWD')->name('adm_listWD')->middleware('auth');
        Route::get('/adm/list/wd-eidr', 'Admin\MasterAdminController@getAllWDeIDR')->name('adm_listWDeIDR')->middleware('auth');
        Route::get('/adm/list/wd-royalti', 'Admin\MasterAdminController@getAllWDRoyalti')->name('adm_listWDRoyalti')->middleware('auth');
        Route::post('/adm/check/wd', 'Admin\MasterAdminController@postCheckWD')->middleware('auth');
        Route::post('/adm/check/wd-eidr', 'Admin\MasterAdminController@postCheckWDeIDR')->middleware('auth');
        Route::post('/adm/check/wd-royalti', 'Admin\MasterAdminController@postCheckWDRoyalti')->middleware('auth');
        Route::post('/adm/reject/wd', 'Admin\MasterAdminController@postRejectWD')->middleware('auth');
        Route::post('/adm/reject/wd-royalti', 'Admin\MasterAdminController@postRejectWDRoyalti')->middleware('auth');
        Route::get('/adm/history/wd', 'Admin\MasterAdminController@getAllHistoryWD')->name('adm_listHistoryWD')->middleware('auth');
        Route::get('/adm/history/wd-eidr', 'Admin\MasterAdminController@getAllHistoryWDeIDR')->name('adm_listHistoryWDeIDR')->middleware('auth');
        Route::get('/adm/history/wd-royalti', 'Admin\MasterAdminController@getAllHistoryWDRoyalti')->name('adm_listHistoryWDRoyalti')->middleware('auth');
        Route::get('/adm/list/req-stockist', 'Admin\MasterAdminController@getAllRequestMemberStockist')->name('adm_listReqStockist')->middleware('auth');
        Route::get('/adm/list/stockist', 'Admin\MasterAdminController@getAllMemberStockists')->name('adm_listMemberStockist')->middleware('auth');
        Route::post('/adm/req-stockist', 'Admin\MasterAdminController@postRequestMemberStockist')->middleware('auth');
        Route::post('/adm/reject-stockist', 'Admin\MasterAdminController@postRejectMemberStockist')->middleware('auth');
        Route::get('/adm/list/purchases', 'Admin\MasterAdminController@getAllPurchase')->name('adm_listPurchases')->middleware('auth');
        Route::get('/adm/add/purchase', 'Admin\MasterAdminController@getAddPurchase')->name('adm_addPurchase')->middleware('auth');
        Route::post('/adm/add/purchase', 'Admin\MasterAdminController@postAddPurchase')->middleware('auth');
        Route::get('/adm/list/claim-reward', 'Admin\MasterAdminController@getAllClaimReward')->name('adm_listClaimReward')->middleware('auth');
        Route::post('/adm/check/claim-reward', 'Admin\MasterAdminController@postCheckClaimReward')->middleware('auth');
        Route::post('/adm/reject/claim-reward', 'Admin\MasterAdminController@postRejectClaimReward')->middleware('auth');
        Route::get('/adm/history/claim-reward', 'Admin\MasterAdminController@getHistoryClaimReward')->name('adm_historyClaimReward')->middleware('auth');
        Route::get('/adm/list/req-input-stock', 'Admin\MasterAdminController@getAllRequestMemberInputStock')->name('adm_listReqInputStock')->middleware('auth');
        Route::post('/adm/req-input-stock', 'Admin\MasterAdminController@postRequestMemberInputStock')->middleware('auth');
        Route::post('/adm/reject-input-stock', 'Admin\MasterAdminController@postRejectMemberInputStock')->middleware('auth');
        Route::get('/adm/list/confirm-belanja', 'Admin\MasterAdminController@getAllConfirmBelanjaStockist')->name('adm_listConfirmBelanjaStockist')->middleware('auth');
        Route::post('/adm/confirm-belanja', 'Admin\MasterAdminController@postConfirmBelanjaStockist')->middleware('auth');
        Route::get('/adm/list/verification-royalti', 'Admin\MasterAdminController@getAllVerificationRoyalti')->name('adm_listVerificationRoyalti')->middleware('auth');
        Route::post('/adm/verification-royalti', 'Admin\MasterAdminController@postVerificationRoyalti')->middleware('auth');
        Route::get('/adm/list/belanja-reward', 'Admin\MasterAdminController@getAllBelanjaReward')->name('adm_listBelanjaReward')->middleware('auth');
        Route::post('/adm/check/belanja-reward', 'Admin\MasterAdminController@postCheckBelanjaReward')->middleware('auth');
        Route::post('/adm/reject/belanja-reward', 'Admin\MasterAdminController@postRejectBelanjaReward')->middleware('auth');
        Route::get('/adm/history/belanja-reward', 'Admin\MasterAdminController@getHistoryBelanjaReward')->name('adm_historyBelanjaReward')->middleware('auth');
        Route::get('/adm/edit/purchase/{id}', 'Admin\MasterAdminController@getEditPurchase')->name('adm_editPurchase')->middleware('auth');
        Route::post('/adm/edit/purchase', 'Admin\MasterAdminController@postEditPurchase')->middleware('auth');
        Route::post('/adm/rm/purchase', 'Admin\MasterAdminController@postRemovePurchase')->middleware('auth');
        Route::get('/adm/list/penjualan-reward', 'Admin\MasterAdminController@getAllPenjualanReward')->name('adm_listPenjualanReward')->middleware('auth');
        Route::post('/adm/check/penjualan-reward', 'Admin\MasterAdminController@postCheckPenjualanReward')->middleware('auth');
        Route::post('/adm/reject/penjualan-reward', 'Admin\MasterAdminController@postRejectPenjualanReward')->middleware('auth');
        Route::get('/adm/history/penjualan-reward', 'Admin\MasterAdminController@getHistoryPenjualanReward')->name('adm_historyPenjualanReward')->middleware('auth');
        Route::post('/adm/change/data/member', 'Admin\MasterAdminController@postAdminChangeDataMember')->middleware('auth');
        Route::post('/adm/change/passwd/member', 'Admin\MasterAdminController@postAdminChangePasswordMember')->middleware('auth');
        Route::post('/adm/change/block/member', 'Admin\MasterAdminController@postAdminChangeBlockMember')->middleware('auth');
        Route::post('/adm/search-list/member', 'Admin\MasterAdminController@postSearchMember')->middleware('auth');
        Route::post('/adm/change/tron/member', 'Admin\MasterAdminController@postAdminChangeTronMember')->middleware('auth');
        Route::post('/adm/search-list/member-stockist', 'Admin\MasterAdminController@postSearchMemberStockist')->middleware('auth');
        

        //Ajax
        Route::get('/ajax/adm/admin/{type}/{id}', 'Admin\AjaxController@getAdminById')->middleware('auth');
        Route::get('/ajax/adm/package/{id}', 'Admin\AjaxController@getPackageById')->middleware('auth');
        Route::get('/ajax/adm/cek/transaction/{id}/{user_id}/{is_tron}', 'Admin\AjaxController@getCekTransactionById')->middleware('auth');
        Route::get('/ajax/adm/reject/transaction/{id}/{user_id}/{is_tron}', 'Admin\AjaxController@getRejectTransactionById')->middleware('auth');
        Route::get('/ajax/adm/bank/{id}', 'Admin\AjaxController@getBankPerusahaan')->middleware('auth');
        Route::get('/ajax/adm/tron/{id}', 'Admin\AjaxController@getTronPerusahaan')->middleware('auth');
        Route::get('/ajax/adm/kirim-paket/{id}/{user_id}', 'Admin\AjaxController@getKirimPaket')->middleware('auth');
        Route::get('/ajax/adm/cek/kirim-paket', 'Admin\AjaxController@getCekKirimPaket')->middleware('auth');
        Route::get('/ajax/adm/cek/reject-wd/{id}', 'Admin\AjaxController@getCekRejectWD')->middleware('auth');
        Route::get('/ajax/adm/cek/detail-wd/{id}', 'Admin\AjaxController@getCekDetailWD')->middleware('auth');
        Route::get('/ajax/adm/cek/reject-wd-eidr/{id}', 'Admin\AjaxController@getCekRejectWDeIDR')->middleware('auth');
        Route::get('/ajax/adm/cek/detail-wd-eidr/{id}', 'Admin\AjaxController@getCekDetailWDeIDR')->middleware('auth');
        Route::get('/ajax/adm/cek/reject-wd-royalti/{id}', 'Admin\AjaxController@getCekRejectWDRoyalti')->middleware('auth');
        Route::get('/ajax/adm/cek/detail-wd-royalti/{id}', 'Admin\AjaxController@getCekDetailWDRoyalti')->middleware('auth');
        Route::get('/ajax/adm/cek/req-stockist/{id}', 'Admin\AjaxController@getCekRequestMemberStockist')->middleware('auth');
        Route::get('/ajax/adm/reject/req-stockist/{id}', 'Admin\AjaxController@getCekRejectMemberStockist')->middleware('auth');
        Route::get('/ajax/adm/edit/bonus-reward/{id}', 'Admin\AjaxController@getEditBonusReward')->middleware('auth');
        Route::get('/ajax/adm/cek/reject-claim-reward/{id}', 'Admin\AjaxController@getCekRejectClaimReward')->middleware('auth');
        Route::get('/ajax/adm/cek/detail-claim-reward/{id}', 'Admin\AjaxController@getCekDetailClaimReward')->middleware('auth');
        Route::get('/ajax/adm/cek/req-input-stock/{id}/{user_id}', 'Admin\AjaxController@getCekRequestMemberInputStock')->middleware('auth');
        Route::get('/ajax/adm/reject/req-input-stock/{id}/{user_id}', 'Admin\AjaxController@getCekRejectMemberInputStock')->middleware('auth');
        Route::get('/ajax/adm/cek/confirm-belanja/{id}', 'Admin\AjaxController@getCekConfirmBelanjaStockist')->middleware('auth');
        Route::get('/ajax/adm/cek/verivication-royalti/{id}', 'Admin\AjaxController@getCekVerificationRoyalti')->middleware('auth');
        Route::get('/ajax/adm/cek/reject-belanja-reward/{id}', 'Admin\AjaxController@getCekRejectBelanjaReward')->middleware('auth');
        Route::get('/ajax/adm/cek/detail-belanja-reward/{id}', 'Admin\AjaxController@getCekDetailBelanjaReward')->middleware('auth');
        Route::get('/ajax/rm/purchase/{id}', 'Admin\AjaxController@getRemovePurchaseId')->middleware('auth');
        Route::get('/ajax/adm/cek/reject-penjualan-reward/{id}', 'Admin\AjaxController@getCekRejectPenjualanReward')->middleware('auth');
        Route::get('/ajax/adm/cek/detail-penjualan-reward/{id}', 'Admin\AjaxController@getCekDetailPenjualanReward')->middleware('auth');
        Route::get('/ajax/adm/change-data/member/{id}', 'Admin\AjaxController@getAdminChangeDataMember')->middleware('auth');
        Route::get('/ajax/adm/change-passwd/member/{id}', 'Admin\AjaxController@getAdminChangePasswordMember')->middleware('auth');
        Route::get('/ajax/adm/change-block/member/{id}', 'Admin\AjaxController@getAdminChangeBlockMember')->middleware('auth');
        Route::get('/ajax/adm/change-tron/member/{id}', 'Admin\AjaxController@getAdminChangeTronMember')->middleware('auth');
        Route::get('/ajax/adm/get-page', 'Admin\AjaxController@getAdminGetCurrentPage')->middleware('auth');
        
//        Route::get('/adm/daerah', 'Admin\MasterAdminController@getAllDaerah')->middleware('auth');
        ////////////////////////////////////////////////////////////////////////
        //##########################
        ////////////////////////////////////////////////////////////////////////
        //#########################
        
        
    //Wilayah Member
        Route::get('/m/dashboard', 'Admin\DashboardController@getMemberDashboard')->name('mainDashboard')->middleware('auth');
        
        //profile
        Route::get('/m/profile', 'Admin\MemberController@getMyProfile')->name('m_myProfile')->middleware('auth');
        Route::get('/m/add/profile', 'Admin\MemberController@getAddMyProfile')->name('m_newProfile')->middleware('auth');
        Route::post('/m/add/profile', 'Admin\MemberController@postAddMyProfile')->middleware('auth');
        Route::get('/m/edit/address', 'Admin\MemberController@getEditAddress')->name('m_editAddress')->middleware('auth');
        Route::post('/m/edit/address', 'Admin\MemberController@postEditAddress')->middleware('auth');
        
        Route::get('/m/tron', 'Admin\MemberController@getMyTron')->name('m_myTron')->middleware('auth');
        Route::get('/m/add/tron', 'Admin\MemberController@getAddMyTron')->name('m_newTron')->middleware('auth');
        Route::post('/m/add/tron', 'Admin\MemberController@postAddMyTron')->middleware('auth');
        
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
        Route::get('/m/my/sponsor-tree', 'Admin\MemberController@getMySponsorTree')->name('m_mySponsorTree')->middleware('auth');
        
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
        Route::get('/m/req/wd-royalti', 'Admin\BonusmemberController@getRequestWithdrawalRoyalti')->name('m_requestWDRoyalti')->middleware('auth');
        Route::post('/m/request/wd-royalti', 'Admin\BonusmemberController@postRequestWithdrawRoyalti')->middleware('auth');
        Route::get('/m/req/wd-eidr', 'Admin\BonusmemberController@getRequestWithdrawaleIDR')->name('m_requestWDeIDR')->middleware('auth');
        Route::post('/m/request/wd-eidr', 'Admin\BonusmemberController@postRequestWithdraweIDR')->middleware('auth');
        Route::get('/m/req/claim-reward', 'Admin\BonusmemberController@getRequestClaimReward')->name('m_requestClaimReward')->middleware('auth');
        Route::post('/m/request/claim-reward', 'Admin\BonusmemberController@postRequestClaimReward')->middleware('auth');
        Route::get('/m/history/reward', 'Admin\BonusmemberController@getHistoryReward')->name('m_historyReward')->middleware('auth');
        Route::get('/m/belanja-reward', 'Admin\BonusmemberController@getBelanjaReward')->name('m_BelanjaReward')->middleware('auth');
        Route::post('/m/request/belanja-reward', 'Admin\BonusmemberController@postRequestBelanjaReward')->middleware('auth');
        Route::get('/m/stockist/penjualan-reward', 'Admin\BonusmemberController@getPenjualanReward')->name('m_PenjualanReward')->middleware('auth');
        Route::post('/m/request/penjualan-reward', 'Admin\BonusmemberController@postRequestPenjualanReward')->middleware('auth');
        
        //Belanja
        Route::get('/m/req/stockist', 'Admin\MemberController@getRequestMemberStockist')->name('m_reqMemberStockist')->middleware('auth');
        Route::post('/m/req/stockist', 'Admin\MemberController@postRequestMemberStockist')->middleware('auth');
        Route::get('/m/search/stockist', 'Admin\MemberController@getSearchStockist')->name('m_SearchStockist')->middleware('auth');
        Route::post('/m/s/stockist', 'Admin\MemberController@postSearchStockist')->middleware('auth');
        Route::get('/m/shoping/{stokist_id}', 'Admin\MemberController@getMemberShoping')->name('m_MemberShoping')->middleware('auth');
        Route::get('/m/detail/purchase/{stokist_id}/{id}', 'Admin\MemberController@getDetailPurchase')->name('m_DetailPurchase')->middleware('auth');
        Route::post('/m/shoping', 'Admin\MemberController@postMemberShoping')->middleware('auth');
        Route::get('/m/history/shoping', 'Admin\MemberController@getHistoryShoping')->name('m_historyShoping')->middleware('auth');
        Route::get('/m/pembayaran/{id}', 'Admin\MemberController@getMemberPembayaran')->name('m_MemberPembayaran')->middleware('auth');
        Route::post('/m/pembayaran', 'Admin\MemberController@postMemberPembayaran')->middleware('auth');
        
        Route::get('/m/stockist-report', 'Admin\MemberController@getMemberStockistReport')->name('m_MemberStockistReport')->middleware('auth');
        Route::get('/m/detail/stockist-report/{id}', 'Admin\MemberController@getMemberDetailStockistReport')->name('m_MemberDetailStockistReport')->middleware('auth');
        Route::get('/m/purchase/input-stock', 'Admin\MemberController@getStockistInputPurchase')->name('m_StockistInputPruchase')->middleware('auth');
        Route::post('/m/purchase/input-stock', 'Admin\MemberController@postStockistInputPurchase')->middleware('auth');
        Route::get('/m/purchase/list-stock', 'Admin\MemberController@getStockistListPurchase')->name('m_StockistListPruchase')->middleware('auth');
        Route::get('/m/purchase/detail-stock/{id}', 'Admin\MemberController@getStockistDetailRequestStock')->name('m_StockistDetailPruchase')->middleware('auth');
        Route::post('/m/add/req-stock', 'Admin\MemberController@postAddRequestStock')->middleware('auth');
        Route::post('/m/reject/req-stock', 'Admin\MemberController@postRejectRequestStock')->middleware('auth');
        Route::post('/m/add/transfer-royalti', 'Admin\MemberController@postAddTransferRoyalti')->middleware('auth');
        Route::post('/m/add/confirm-pembelian', 'Admin\MemberController@postAddConfirmPembelian')->middleware('auth');
        Route::post('/m/add/reject-pembelian', 'Admin\MemberController@postAddRejectPembelian')->middleware('auth');
        Route::get('/m/purchase/my-stock', 'Admin\MemberController@getStockistMyStockPurchaseSisa')->name('m_StockistMyPruchaseSisa')->middleware('auth');
        
        Route::get('/m/explorer/statistic', 'Admin\MemberController@getExplorerStatistic')->name('m_ExplorerStatistic')->middleware('auth');
        
        //Ajax
        Route::get('/m/cek/add-sponsor', 'Admin\AjaxmemberController@postCekAddSponsor')->middleware('auth');
        Route::get('/m/cek/add-package/{id_paket}/{setuju}', 'Admin\AjaxmemberController@getCekAddPackage')->middleware('auth');
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
        Route::get('/m/cek/confirm-wd-royalti', 'Admin\AjaxmemberController@getCekConfirmWDRoyalti')->middleware('auth');
        Route::get('/m/cek/add-tron', 'Admin\AjaxmemberController@getCekAddTron')->middleware('auth');
        Route::get('/m/cek/confirm-wd-eidr', 'Admin\AjaxmemberController@getCekConfirmWDeIDR')->middleware('auth');
        Route::get('/m/cek/req-stockist', 'Admin\AjaxmemberController@getCekRequestMemberStockist')->middleware('auth');
        Route::get('/m/stockist/cek/shoping', 'Admin\AjaxmemberController@getStockistCekSoping')->middleware('auth');
        Route::get('/m/cek/shoping', 'Admin\AjaxmemberController@getCekSoping')->middleware('auth');
        Route::get('/m/cek/edit-address', 'Admin\AjaxmemberController@getCekEditAddress')->middleware('auth');
        Route::get('/m/cek/confirm-claim-reward', 'Admin\AjaxmemberController@getCekConfirmClaimReward')->middleware('auth');
        Route::get('/m/cek/member-pembayaran', 'Admin\AjaxmemberController@getCekMemberPembayaran')->middleware('auth');
        Route::get('/m/cek/add-stock', 'Admin\AjaxmemberController@postCekAddRequestStock')->middleware('auth');
        Route::get('/m/cek/reject-stock', 'Admin\AjaxmemberController@postCekRejectRequestStock')->middleware('auth');
        Route::get('/m/cek/add-royalti', 'Admin\AjaxmemberController@postCekAddRoyalti')->middleware('auth');
        Route::get('/m/cek/confirm-pembelian', 'Admin\AjaxmemberController@postCekConfirmPembelian')->middleware('auth');
        Route::get('/m/cek/reject-pembelian', 'Admin\AjaxmemberController@postCekRejectPembelian')->middleware('auth');
        Route::get('/m/cek/confirm-belanja-reward', 'Admin\AjaxmemberController@getCekConfirmBelanjaReward')->middleware('auth');
        Route::get('/m/cek/confirm-penjualan-reward', 'Admin\AjaxmemberController@getCekConfirmPenjualanReward')->middleware('auth');
        Route::get('/m/cek/usercode-stockist', 'Admin\AjaxmemberController@getSearchUserCodeStockist')->middleware('auth');
        
        Route::get('/m/search/{type}', 'Admin\AjaxmemberController@getSearchByType')->middleware('auth');
});
