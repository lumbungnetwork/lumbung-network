<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::domain('member.' . config('services.app.domain'))->group(function () {
    Route::get('/', function () {
        return view('member')->with('title', 'Join Lumbung Network');
    });
    Route::get('/login', 'Auth\LoginController@showLoginForm')->name('member.login');
    Route::post('/login', 'Auth\LoginController@postLogin')->name('member.postLogin');
    Route::post('/logout', 'Auth\LoginController@logout')->name('member.postLogout');
    Route::get('/area/login', 'Admin\HomeController@getAreaLogin')->name('areaLogin');
    Route::post('/area/login', 'Admin\HomeController@postAreaLogin');

    // Register
    Route::get('/register', 'Auth\RegisterController@showRegistrationForm')->name('member.register');
    Route::post('/register', 'Auth\RegisterController@postRegister')->name('member.postRegister');
    Route::get('/ref/{ref}', 'Auth\RegisterController@getRegisterRef')->where(['ref' => '^[\w-]+$'])->name('member.registerRef');

    Route::get('/forgot/passwd', 'FrontEnd\FrontEndController@getForgotPassword')->name('forgotPasswd');
    Route::post('/forgot/passwd', 'FrontEnd\FrontEndController@postForgotPassword');
    Route::get('/auth/passwd/{code}/{email}', 'FrontEnd\FrontEndController@getAuthPassword')->name('passwdauth');
    Route::post('/auth/passwd', 'FrontEnd\FrontEndController@postAuthPassword');

    //telegram bot webhook
    Route::post('/' . config('services.telegram.webhook') . '/webhook', 'TelegramBotController@handleRequest');
    // Digiflazz webhook
    Route::post('/digiflazz/webhook', 'Member\DigiflazzController@handleRequest');


    // New Member Routes
    Route::get('/home', 'Member\AppController@getHome')->name('member.home')->middleware('auth');
    Route::get('/stake', 'Member\AppController@getStake')->name('member.stake')->middleware('auth');
    Route::get('/wallet', 'Member\AppController@getWallet')->name('member.wallet')->middleware('auth');
    Route::get('/wallet/deposit', 'Member\AppController@getWalletDeposit')->name('member.wallet.deposit')->middleware('auth');
    Route::post('/wallet/deposit', 'Member\AppController@postWalletDeposit')->name('member.postWalletDeposit')->middleware('auth');
    Route::get('/wallet/deposit-payment/{transaction_id}', 'Member\AppController@getDepositPayment')->whereNumber('transaction_id')->name('member.depositPayment')->middleware('auth');
    Route::post('/wallet/deposit-payment', 'Member\AppController@postDepositPayment')->name('member.postDepositPayment')->middleware('auth');
    Route::post('/wallet/deposit-payment-tron', 'Member\AppController@postDepositPaymentTron')->name('member.postDepositPaymentTron')->middleware('auth');
    Route::get('/wallet/withdraw', 'Member\AppController@getWalletWithdraw')->name('member.wallet.withdraw')->middleware('auth');
    Route::post('/wallet/withdraw', 'Member\AppController@postWalletWithdraw')->name('member.postWalletWithdraw')->middleware('auth');


    // Account
    Route::get('/account', 'Member\AppController@getAccount')->name('member.account')->middleware('auth');
    Route::get('/account/profile', 'Member\AppController@getProfile')->name('member.profile')->middleware('auth');
    Route::get('/account/edit-profile', 'Member\AppController@getEditProfile')->name('member.editProfile')->middleware('auth');
    Route::get('/account/security', 'Member\AppController@getSecurity')->name('member.security')->middleware('auth');
    Route::post('/account/security/create-2fa', 'Member\SecurityController@postCreate2FA')->name('member.security.postCreate2FA')->middleware('auth');
    Route::post('/account/security/edit-2fa', 'Member\SecurityController@postEdit2FA')->name('member.security.postEdit2FA')->middleware('auth');
    Route::post('/account/security/change-password', 'Member\SecurityController@postChangePassword')->name('member.security.postChangePassword')->middleware('auth');
    Route::get('/account/tron', 'Member\AppController@getAccountTron')->name('member.tron')->middleware('auth');
    Route::post('/account/tron/set-tron', 'Member\TronController@postSetTron')->name('member.tron.postSetTron')->middleware('auth');
    Route::post('/account/tron/reset-tron', 'Member\TronController@postResetTron')->name('member.tron.postResetTron')->middleware('auth');
    Route::post('/account/bank/set-bank', 'Member\TronController@postSetBank')->name('member.tron.postSetBank')->middleware('auth');
    Route::post('/account/bank/reset-bank', 'Member\TronController@postResetBank')->name('member.tron.postResetBank')->middleware('auth');
    Route::get('/account/bank', 'Member\AppController@getBank')->name('member.account.bank')->middleware('auth');
    Route::get('/account/premium-membership', 'Member\MembershipController@getUpgradeMembership')->name('member.account.membership')->middleware('auth');
    Route::post('/account/premium-membership', 'Member\MembershipController@postUpgradeMembership')->name('member.account.postMembership')->middleware('auth');

    // Store
    Route::get('/store/info', 'Member\StoreController@getStoreInfo')->name('member.store.info')->middleware('auth');
    Route::get('/store/edit-info', 'Member\StoreController@getStoreEditInfo')->name('member.store.editInfo')->middleware('auth');
    Route::post('/store/add-info', 'Member\StoreController@postStoreAddInfo')->name('member.store.postStoreAddInfo')->middleware('auth');
    Route::post('/store/edit-info', 'Member\StoreController@postStoreEditInfo')->name('member.store.postStoreEditInfo')->middleware('auth');
    Route::get('/store/inventory', 'Member\StoreController@getStoreInventory')->name('member.store.inventory')->middleware('auth');
    Route::get('/store/add-product', 'Member\StoreController@getStoreAddProduct')->name('member.store.addProduct')->middleware('auth');
    Route::post('/store/add-product', 'Member\StoreController@postStoreAddProduct')->name('member.store.postAddProduct')->middleware('auth');
    Route::get('/store/edit-product/{product_id}', 'Member\StoreController@getStoreEditProduct')->whereNumber('product_id')->name('member.store.editProduct')->middleware('auth');
    Route::post('/store/edit-product/{product_id}', 'Member\StoreController@postStoreEditProduct')->whereNumber('product_id')->name('member.store.postEditProduct')->middleware('auth');
    Route::post('/store/delete-product/{product_id}', 'Member\StoreController@postStoreDeleteProduct')->whereNumber('product_id')->name('member.store.postDeleteProduct')->middleware('auth');
    Route::get('/store/transactions', 'Member\StoreController@getStoreTransactions')->name('member.store.transactions')->middleware('auth');
    Route::get('/store/confirm-physical-order/{masterSalesID}', 'Member\StoreController@getStoreConfirmPhysicalOrder')->whereNumber('masterSalesID')->name('member.store.confirmPhysicalOrder')->middleware('auth');
    Route::get('/store/confirm-digital-order/{id}', 'Member\StoreController@getStoreConfirmDigitalOrder')->whereNumber('id')->name('member.store.confirmDigitalOrder')->middleware('auth');
    Route::post('/store/confirm-digital-order', 'Member\StoreController@postStoreConfirmDigitalOrder')->name('member.store.postConfirmDigitalOrder')->middleware('auth');

    // Staking
    Route::get('/stake/history', 'Member\AppController@getStakeHistory')->name('member.stakeHistory')->middleware('auth');
    Route::get('/stake/claimed-div-history', 'Member\AppController@getStakeClaimedDivHistory')->name('member.stakeClaimedDivHistory')->middleware('auth');
    Route::get('/stake/div-history', 'Member\AppController@getStakeDivHistory')->name('member.stakeDivHistory')->middleware('auth');
    Route::get('/stake/leaderboard', 'Member\AppController@getStakeLeaderboard')->name('member.stakeLeaderboard')->middleware('auth');

    // Shopping
    Route::get('/shopping', 'Member\AppController@getShopping')->name('member.shopping')->middleware('auth');
    Route::get('/shop/{id}', 'Member\ShoppingController@getShop')->whereNumber('id')->name('member.shop')->middleware('auth');
    Route::get('/shopping/payment/{masterSalesID}', 'Member\ShoppingController@getShoppingPayment')->whereNumber('masterSalesID')->name('member.shopping.payment')->middleware('auth');
    Route::get('/shopping/select-operators/{type}', 'Member\ShoppingController@getPhoneOperatorList')->where('type', '(1|2)')->name('member.shopping.operatorList')->middleware('auth');
    Route::get('/shopping/pricelist/{operator_id}/{type_id}', 'Member\ShoppingController@getPrepaidPhoneCreditPricelist')->where('operator_id', '(1|2|3|4|5|6)')->where('type_id', '(1|2)')->name('member.shopping.prepaidPhonePricelist')->middleware('auth');
    Route::get('/shopping/pln-prepaid', 'Member\ShoppingController@getPLNPrepaidPricelist')->name('member.shopping.plnPrepaid')->middleware('auth');
    Route::get('/shopping/emoney', 'Member\ShoppingController@getEmoneyOperatorList')->name('member.shopping.emoney')->middleware('auth');
    Route::get('/shopping/emoney/{operator_id}', 'Member\ShoppingController@getEmoneyPricelist')->where('operator_id', '(21|22|23|24|25|26|27|28)')->name('member.shopping.emoneyPricelist')->middleware('auth');
    Route::get('/shopping/digital-payment/{sale_id}', 'Member\ShoppingController@getDigitalOrderPayment')->whereNumber('sale_id')->name('member.shopping.digitalPayment')->middleware('auth');
    Route::get('/shopping/receipt/{sale_id}', 'Member\ShoppingController@getShoppingReceipt')->whereNumber('sale_id')->name('member.shopping.receipt')->middleware('auth');
    Route::get('/shopping/transactions', 'Member\ShoppingController@getShoppingTransactions')->name('member.shopping.transactions')->middleware('auth');
    Route::get('/shopping/postpaid', 'Member\ShoppingController@getPostpaidList')->name('member.shopping.postpaid')->middleware('auth');
    Route::get('/shopping/postpaid/{type}', 'Member\ShoppingController@getPostpaidCheckCustomerNo')->where('type', '(4|5|6|7|8|9)')->name('member.shopping.postpaidCheck')->middleware('auth');
    Route::get('/shopping/hp-postpaid', 'Member\ShoppingController@getHPPostpaidList')->name('member.shopping.hpPostpaid')->middleware('auth');
    Route::get('/shopping/hp-postpaid/{buyer_sku_code}', 'Member\ShoppingController@getHPPostpaidCheckCustomerNo')->where('buyer_sku_code', '(HALO|MATRIX|SMARTPOST|TRIPOST|XLPOST)')->name('member.shopping.hpPostpaidCheck')->middleware('auth');
    Route::get('/shopping/pdam', 'Member\ShoppingController@getPDAMCheckCustomerNo')->name('member.shopping.pdam')->middleware('auth');
    Route::get('/shopping/multifinance', 'Member\ShoppingController@getMultifinanceCheckCustomerNo')->name('member.shopping.multifinance')->middleware('auth');

    Route::post('/shopping/checkout', 'Member\ShoppingController@postCheckout')->name('member.shopping.postCheckout')->middleware('auth');
    Route::post('/shopping/digital-order', 'Member\ShoppingController@postShoppingDigitalOrder')->name('member.shopping.postShoppingDigitalOrder')->middleware('auth');
    Route::post('/shopping/digital-order-quickbuy', 'Member\ShoppingController@postShoppingStoreQuickbuy')->name('member.shopping.postShoppingStoreQuickbuy')->middleware('auth');
    Route::post('/shopping/confirm-digital-order', 'Member\ShoppingController@postShoppingConfirmDigitalOrderByEidr')->name('member.shopping.postShoppingConfirmDigitalOrderByEidr')->middleware('auth');


    // Claims
    Route::get('/claim/shopping-reward', 'Member\AppController@getClaimShoppingReward')->name('member.claim.shoppingReward')->middleware('auth');

    // AJAX
    Route::group(['prefix' => 'ajax'], function () {
        // Reward Claims
        Route::post('/claim/shopping-reward', 'Member\AjaxController@postClaimShoppingReward')->name('ajax.claim.shoppingReward')->middleware('auth');
        Route::post('/claim/staking-dividend', 'Member\AjaxController@postClaimStakingDividend')->name('ajax.claim.stakingDividend')->middleware('auth');
        Route::post('/stake/shopping-reward', 'Member\AjaxController@postStakeShoppingReward')->name('ajax.stake.shoppingReward')->middleware('auth');

        // Staking
        Route::get('/stake/add', 'Member\AjaxController@getStakeAdd')->name('ajax.stake.add')->middleware('auth');
        Route::get('/stake/substract', 'Member\AjaxController@getStakeSubstract')->name('ajax.stake.substract')->middleware('auth');
        Route::post('/stake/confirm', 'Member\AjaxController@postStakeConfirm')->name('ajax.stake.confirm')->middleware('auth');
        Route::post('/stake/unstake', 'Member\AjaxController@postUnstake')->name('ajax.unstake')->middleware('auth');

        // Store
        Route::get('/shopping/get-product-image', 'Member\AjaxController@getSearchProductImage')->name('ajax.store.getSearchProductImage')->middleware('auth');
        Route::post('/store/cancel-physical-order', 'Member\AjaxController@postStoreCancelPhysicalOrder')->name('ajax.store.postStoreCancelPhysicalOrder')->middleware('auth');
        Route::post('/store/cancel-digital-order', 'Member\AjaxController@postStoreCancelDigitalOrder')->name('ajax.store.postStoreCancelDigitalOrder')->middleware('auth');
        Route::post('/store/confirm-physical-order', 'Member\AjaxController@postStoreConfirmPhysicalOrder')->name('ajax.store.postStoreConfirmPhysicalOrder')->middleware('auth');
        Route::post('/store/change-buyer-quickbuy', 'Member\AjaxController@postChangeBuyerQuickbuy')->name('ajax.store.postChangeBuyerQuickbuy')->middleware('auth');

        // Shopping
        Route::get('/shopping/get-product-by-id', 'Member\AjaxController@getProductById')->name('ajax.shopping.getProductById')->middleware('auth');
        Route::get('/shopping/get-product-by-category', 'Member\AjaxController@getProductByCategory')->name('ajax.shopping.getProductByCategory')->middleware('auth');
        Route::get('/shopping/get-shop-name', 'Member\AjaxController@getShopName')->name('ajax.shopping.getShopName')->middleware('auth');
        Route::get('/shopping/get-username', 'Member\AjaxController@getUserName')->name('ajax.shopping.getUsername')->middleware('auth');
        Route::get('/shopping/get-cart-total', 'Member\AjaxController@getCartTotal')->name('ajax.shopping.getCartTotal')->middleware('auth');
        Route::get('/shopping/get-cart-contents', 'Member\AjaxController@getCartContents')->name('ajax.shopping.getCartContents')->middleware('auth');
        Route::get('/shopping/get-delete-cart-item', 'Member\AjaxController@getDeleteCartItem')->name('ajax.shopping.getDeleteCartItem')->middleware('auth');
        Route::get('/shopping/get-cart-checkout', 'Member\AjaxController@getCartCheckout')->name('ajax.shopping.getCartCheckout')->middleware('auth');
        Route::get('/shopping/check-digital-order-status', 'Member\AjaxController@getCheckDigitalOrderStatus')->name('ajax.shopping.getCheckDigitalOrderStatus')->middleware('auth');
        Route::get('/shopping/postpaid-check', 'Member\AjaxController@getCheckPostpaidCustomerNo')->name('ajax.shopping.getCheckPostpaidCustomerNo')->middleware('auth');
        Route::get('/shopping/pln-prepaid-inquiry', 'Member\DigiflazzController@prepaidPLNInquiry')->name('ajax.shopping.prepaidPLNInquiry')->middleware('auth');

        Route::post('/shopping/post-add-to-cart', 'Member\AjaxController@postAddToCart')->name('ajax.shopping.postAddToCart')->middleware('auth');
        Route::post('/shopping/post-cancel-payment-buyer', 'Member\AjaxController@postCancelShoppingPaymentBuyer')->name('ajax.shopping.postCancelShoppingPaymentBuyer')->middleware('auth');
        Route::post('/shopping/post-cancel-digital-payment-buyer', 'Member\AjaxController@postCancelDigitalShoppingPaymentBuyer')->name('ajax.shopping.postCancelDigitalShoppingPaymentBuyer')->middleware('auth');
        Route::post('/shopping/post-shopping-payment-cash', 'Member\AjaxController@postShoppingPaymentCash')->name('ajax.shopping.postShoppingPaymentCash')->middleware('auth');
        Route::post('/shopping/post-digital-shopping-payment-cash', 'Member\AjaxController@postDigitalShoppingPaymentCash')->name('ajax.shopping.postDigitalShoppingPaymentCash')->middleware('auth');
        Route::post('/shopping/post-shopping-payment-int-eidr', 'Member\AjaxController@postShoppingPaymentInternaleIDR')->name('ajax.shopping.postShoppingPaymentInternaleIDR')->middleware('auth');
        Route::post('/shopping/post-shopping-payment-ext-eidr', 'Member\AjaxController@postShoppingPaymentExternaleIDR')->name('ajax.shopping.postShoppingPaymentExternaleIDR')->middleware('auth');

        // Regions
        Route::get('/region/search-by-type/{type}', 'Member\AjaxController@getSearchAddressRegionByType')->where('type', '(kota|kecamatan|kelurahan)')->name('ajax.region.getSearchAddressRegionByType')->middleware('auth');
        Route::post('/region/add-user-profile', 'Member\AjaxController@postAddUserProfile')->name('ajax.region.postAddUserProfile')->middleware('auth');
        Route::post('/region/edit-user-profile', 'Member\AjaxController@postEditUserProfile')->name('ajax.region.postEditUserProfile')->middleware('auth');

        // Internal eIDR
        Route::post('/wallet/cancel-deposit', 'Member\AjaxController@postCancelDepositTransaction')->name('ajax.postCancelDepositTransaction')->middleware('auth');

        // Telegram
        Route::get('/telegram/link', 'Member\AjaxController@getCreateTelegramLink')->name('ajax.telegram.link')->middleware('auth');
        Route::get('/telegram/unlink', 'Member\AjaxController@getRemoveTelegramLink')->name('ajax.telegram.unlink')->middleware('auth');
    });



    ////////////////////////

    Route::prefix('/')->group(function () {

        Route::get('/adm/dashboard', 'Admin\DashboardController@getDashboard')->name('admDashboard')->middleware('auth');
        Route::get('/user_logout', 'Admin\HomeController@getUserLogout')->middleware('auth');

        //Wilayah Admin
        //admin
        Route::get('/adm/add-admin', 'Admin\MasterAdminController@getAddAdmin')->name('addCrew')->middleware('auth');
        Route::post('/adm/new-admin', 'Admin\MasterAdminController@postAddAdmin')->middleware('auth');
        Route::post('/adm/admin', 'Admin\MasterAdminController@postEditAdmin')->middleware('auth');

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
        Route::get('/adm/testing', 'Admin\MasterAdminController@getTesting')->name('adm_Testing')->middleware('auth');
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
        Route::post('/adm/remove-stockist', 'Admin\MasterAdminController@postRemoveMemberStockist')->middleware('auth');
        Route::post('/adm/edit-stockist', 'Admin\MasterAdminController@postEditMemberStockist')->middleware('auth');
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
        Route::post('/adm/change/2fa/member', 'Admin\MasterAdminController@postAdminChange2faMember')->middleware('auth');
        Route::post('/adm/change/block/member', 'Admin\MasterAdminController@postAdminChangeBlockMember')->middleware('auth');
        Route::post('/adm/search-list/member', 'Admin\MasterAdminController@postSearchMember')->middleware('auth');
        Route::post('/adm/search-list/member-by-month', 'Admin\MasterAdminController@postSearchMemberByMonth')->middleware('auth');
        Route::post('/adm/change/tron/member', 'Admin\MasterAdminController@postAdminChangeTronMember')->middleware('auth');
        Route::post('/adm/search-list/member-stockist', 'Admin\MasterAdminController@postSearchMemberStockist')->middleware('auth');
        Route::post('/adm/search-list/member-vendor', 'Admin\MasterAdminController@postSearchMemberVendor')->middleware('auth');
        Route::get('/adm/history/req-stockist', 'Admin\MasterAdminController@getHistoryRequestMemberStockist')->name('adm_historyReqStockist')->middleware('auth');
        Route::get('/adm/history/req-input-stock', 'Admin\MasterAdminController@getHistoryRequestInputStock')->name('adm_historyReqInputStock')->middleware('auth');
        Route::get('/adm/stockist/stock/{id}', 'Admin\MasterAdminController@getMemberStockistStock')->name('adm_memberStockistStock')->middleware('auth');
        Route::post('/adm/edit-stock', 'Admin\MasterAdminController@postEditStock')->middleware('auth');
        Route::post('/adm/rm-stock', 'Admin\MasterAdminController@postRemoveStock')->middleware('auth');
        Route::get('/adm/list/topup', 'Admin\MasterAdminController@getAllTopup')->name('adm_listTopup')->middleware('auth');
        Route::post('/adm/check/topup', 'Admin\MasterAdminController@postCheckTopup')->middleware('auth');
        Route::post('/adm/reject/topup', 'Admin\MasterAdminController@postRejectTopup')->middleware('auth');
        Route::get('/adm/history/topup', 'Admin\MasterAdminController@getAllHistoryTopup')->name('adm_listHistoryTopup')->middleware('auth');
        Route::get('/adm/list/vpenjualan-reward', 'Admin\MasterAdminController@getAllVPenjualanReward')->name('adm_listVPenjualanReward')->middleware('auth');
        Route::post('/adm/check/vpenjualan-reward', 'Admin\MasterAdminController@postCheckVPenjualanReward')->middleware('auth');
        Route::post('/adm/reject/vpenjualan-reward', 'Admin\MasterAdminController@postRejectVPenjualanReward')->middleware('auth');
        Route::get('/adm/history/vpenjualan-reward', 'Admin\MasterAdminController@getHistoryVPenjualanReward')->name('adm_historyVPenjualanReward')->middleware('auth');
        Route::get('/adm/list/vbelanja-reward', 'Admin\MasterAdminController@getAllVBelanjaReward')->name('adm_listVBelanjaReward')->middleware('auth');
        Route::post('/adm/check/vbelanja-reward', 'Admin\MasterAdminController@postCheckVBelanjaReward')->middleware('auth');
        Route::post('/adm/reject/vbelanja-reward', 'Admin\MasterAdminController@postRejectVBelanjaReward')->middleware('auth');
        Route::get('/adm/history/vbelanja-reward', 'Admin\MasterAdminController@getHistoryVBelanjaReward')->name('adm_historyVBelanjaReward')->middleware('auth');


        Route::get('/adm/list/vpurchases', 'Admin\MasterAdminController@getAllVendorPurchase')->name('adm_listVendorPurchases')->middleware('auth');
        Route::get('/adm/add/vpurchase', 'Admin\MasterAdminController@getAddVendorPurchase')->name('adm_addVendorPurchase')->middleware('auth');
        Route::post('/adm/add/vpurchase', 'Admin\MasterAdminController@postAddVendorPurchase')->middleware('auth');
        Route::get('/adm/edit/vpurchase/{id}', 'Admin\MasterAdminController@getEditVendorPurchase')->name('adm_editVendorPurchase')->middleware('auth');
        Route::post('/adm/edit/vpurchase', 'Admin\MasterAdminController@postEditVendorPurchase')->middleware('auth');
        Route::post('/adm/rm/vpurchase', 'Admin\MasterAdminController@postRemoveVendorPurchase')->middleware('auth');
        Route::get('/adm/list/req-vendor', 'Admin\MasterAdminController@getAllRequestMemberVendor')->name('adm_listReqVendor')->middleware('auth');
        Route::get('/adm/list/vendor', 'Admin\MasterAdminController@getAllMemberVendor')->name('adm_listMemberVendor')->middleware('auth');
        Route::post('/adm/req-vendor', 'Admin\MasterAdminController@postRequestMemberVendor')->middleware('auth');
        Route::post('/adm/reject-vendor', 'Admin\MasterAdminController@postRejectMemberVendor')->middleware('auth');
        Route::get('/adm/history/req-vendor', 'Admin\MasterAdminController@getHistoryRequestMemberVendor')->name('adm_historyReqVendor')->middleware('auth');
        Route::get('/adm/history/req-input-vstock', 'Admin\MasterAdminController@getHistoryRequestInputVStock')->name('adm_historyReqInputVStock')->middleware('auth');
        Route::get('/adm/list/req-input-vstock', 'Admin\MasterAdminController@getAllRequestMemberInputVStock')->name('adm_listReqInputVStock')->middleware('auth');
        Route::post('/adm/req-input-vstock', 'Admin\MasterAdminController@postRequestMemberInputVStock')->middleware('auth');
        Route::post('/adm/reject-input-vstock', 'Admin\MasterAdminController@postRejectMemberInputVStock')->middleware('auth');
        Route::get('/adm/vendor/stock/{id}', 'Admin\MasterAdminController@getMemberVendorStock')->name('adm_memberVendorStock')->middleware('auth');
        Route::post('/adm/remove-vendor', 'Admin\MasterAdminController@postRemoveMemberVendor')->middleware('auth');
        Route::get('/adm/list/isi-deposit', 'Admin\MasterAdminController@getAllRequestIsiDeposit')->name('adm_listIsiDeposit')->middleware('auth');
        Route::post('/adm/confirm/isi-deposit', 'Admin\MasterAdminController@postConfirmTransactionIsiDeposit')->middleware('auth');
        Route::post('/adm/reject/isi-deposit', 'Admin\MasterAdminController@postRejectTransactionIsiDeposit')->middleware('auth');
        Route::get('/adm/list/tarik-deposit', 'Admin\MasterAdminController@getAllRequestTarikDeposit')->name('adm_listTarikDeposit')->middleware('auth');
        Route::post('/adm/confirm/tarik-deposit', 'Admin\MasterAdminController@postConfirmTransactionTarikDeposit')->middleware('auth');
        Route::get('/adm/transfer/system-deposit', 'Admin\MasterAdminController@getTransferSystemDeposit')->name('adm_listTransferDeposit')->middleware('auth');
        Route::post('/adm/transfer/system-deposit', 'Admin\MasterAdminController@postTransferSystemDeposit')->middleware('auth');
        Route::get('/adm/history/deposit', 'Admin\MasterAdminController@getAllTransactionDeposit')->name('adm_historyDeposit')->middleware('auth');

        Route::get('/adm/list/ppob-transaction/eidr', 'Admin\MasterAdminController@getListVendorPPOBTransactionsEiDR')->name('adm_listVendotPPOBTransactionsEiDR')->middleware('auth');
        Route::get('/adm/ppob-transaction/eidr/{id}', 'Admin\MasterAdminController@geDetailVendorPPOBTransactionsEiDR')->middleware('auth');
        Route::post('/adm/approve/ppob-transaction/eidr', 'Admin\MasterAdminController@postApprovePPOBTransactionsEiDR')->middleware('auth');
        Route::post('/adm/reject/ppob-transaction/eidr', 'Admin\MasterAdminController@postRejectPPOBTransactionsEiDR')->middleware('auth');
        Route::get('/adm/cek-status/ppob-transaction/{id}', 'Admin\MasterAdminController@getPPOBTransactionsAPI')->middleware('auth');

        //Test Api Digiflazz
        Route::get('/adm/test-digiflazz/saldo', 'Admin\MasterAdminController@getMemberTestingCheckSaldo')->middleware('auth');
        Route::get('/adm/test-digiflazz/daftar-pulsa/{cmd}', 'Admin\MasterAdminController@getMemberTestingCheckDaftarPulsa')->middleware('auth');
        Route::get('/adm/test-digiflazz/top-pulsa/{buyer}/{hp}', 'Admin\MasterAdminController@getMemberTestingCheckTopupPulsa')->middleware('auth');
        Route::get('/adm/test-digiflazz/cek-status/{id}/{cek}', 'Admin\MasterAdminController@getMemberTestingCheckStatus')->middleware('auth');
        Route::get('/adm/test-digiflazz/status', 'Admin\MasterAdminController@getMemberNewTestingCheckStatus')->middleware('auth');
        Route::get('/adm/update-transaction/{id}/{lihat}', 'Admin\MasterAdminController@getUpdateTransaction')->middleware('auth');





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
        Route::get('/ajax/adm/remove/stockist/{id}', 'Admin\AjaxController@getCekRemoveMemberStockist')->middleware('auth');
        Route::get('/ajax/adm/edit/stockist/{id}', 'Admin\AjaxController@getCekEditMemberStockist')->middleware('auth');
        Route::get('/ajax/adm/edit/bonus-reward/{id}', 'Admin\AjaxController@getEditBonusReward')->middleware('auth');
        Route::get('/ajax/adm/cek/reject-claim-reward/{id}', 'Admin\AjaxController@getCekRejectClaimReward')->middleware('auth');
        Route::get('/ajax/adm/cek/detail-claim-reward/{id}', 'Admin\AjaxController@getCekDetailClaimReward')->middleware('auth');
        Route::get('/ajax/adm/cek/req-input-stock/{id}/{user_id}', 'Admin\AjaxController@getCekRequestMemberInputStock')->middleware('auth');
        Route::get('/ajax/adm/reject/req-input-stock/{id}/{user_id}', 'Admin\AjaxController@getCekRejectMemberInputStock')->middleware('auth');
        Route::get('/ajax/adm/cek/confirm-belanja/{id}', 'Admin\AjaxController@getCekConfirmBelanjaStockist')->middleware('auth');
        Route::get('/ajax/adm/cek/verivication-royalti/{id}', 'Admin\AjaxController@getCekVerificationRoyalti')->middleware('auth');
        Route::get('/ajax/adm/cek/reject-belanja-reward/{id}', 'Admin\AjaxController@getCekRejectBelanjaReward')->middleware('auth');
        Route::get('/ajax/adm/cek/detail-belanja-reward/{id}', 'Admin\AjaxController@getCekDetailBelanjaReward')->middleware('auth');
        Route::get('/ajax/adm/cek/reject-vbelanja-reward/{id}', 'Admin\AjaxController@getCekRejectVBelanjaReward')->middleware('auth');
        Route::get('/ajax/adm/cek/detail-vbelanja-reward/{id}', 'Admin\AjaxController@getCekDetailVBelanjaReward')->middleware('auth');
        Route::get('/ajax/rm/purchase/{id}', 'Admin\AjaxController@getRemovePurchaseId')->middleware('auth');
        Route::get('/ajax/adm/cek/reject-penjualan-reward/{id}', 'Admin\AjaxController@getCekRejectPenjualanReward')->middleware('auth');
        Route::get('/ajax/adm/cek/reject-vpenjualan-reward/{id}', 'Admin\AjaxController@getCekRejectVPenjualanReward')->middleware('auth');
        Route::get('/ajax/adm/cek/detail-penjualan-reward/{id}', 'Admin\AjaxController@getCekDetailPenjualanReward')->middleware('auth');
        Route::get('/ajax/adm/cek/detail-vpenjualan-reward/{id}', 'Admin\AjaxController@getCekDetailVPenjualanReward')->middleware('auth');
        Route::get('/ajax/adm/change-data/member/{id}', 'Admin\AjaxController@getAdminChangeDataMember')->middleware('auth');
        Route::get('/ajax/adm/change-passwd/member/{id}', 'Admin\AjaxController@getAdminChangePasswordMember')->middleware('auth');
        Route::get('/ajax/adm/change-2fa/member/{id}', 'Admin\AjaxController@getAdminChange2faMember')->middleware('auth');
        Route::get('/ajax/adm/change-block/member/{id}', 'Admin\AjaxController@getAdminChangeBlockMember')->middleware('auth');
        Route::get('/ajax/adm/change-tron/member/{id}', 'Admin\AjaxController@getAdminChangeTronMember')->middleware('auth');
        Route::get('/ajax/adm/get-page', 'Admin\AjaxController@getAdminGetCurrentPage')->middleware('auth');
        Route::get('/ajax/adm/edit-stock/{stockist_id}/{purchase_id}', 'Admin\AjaxController@getAdminEditStock')->middleware('auth');
        Route::get('/ajax/adm/rm-stock/{stockist_id}/{purchase_id}', 'Admin\AjaxController@getAdminRemoveStock')->middleware('auth');
        Route::get('/ajax/adm/cek/reject-topup/{id}/{user_id}', 'Admin\AjaxController@getCekRejectTopup')->middleware('auth');
        Route::get('/ajax/rm/vpurchase/{id}', 'Admin\AjaxController@getRemoveVendorPurchaseId')->middleware('auth');
        Route::get('/ajax/adm/cek/req-vendor/{id}', 'Admin\AjaxController@getCekRequestMemberVendor')->middleware('auth');
        Route::get('/ajax/adm/reject/req-vendor/{id}', 'Admin\AjaxController@getCekRejectMemberVendor')->middleware('auth');
        Route::get('/ajax/adm/remove/vendor/{id}', 'Admin\AjaxController@getCekRemoveMemberVendor')->middleware('auth');
        //        Route::get('/ajax/adm/edit/stockist/{id}', 'Admin\AjaxController@getCekEditMemberStockist')->middleware('auth');
        Route::get('/ajax/adm/cek/req-input-vstock/{id}/{user_id}', 'Admin\AjaxController@getCekRequestMemberInputVStock')->middleware('auth');
        Route::get('/ajax/adm/reject/req-input-vstock/{id}/{user_id}', 'Admin\AjaxController@getCekRejectMemberInputVStock')->middleware('auth');
        Route::get('/ajax/adm/cek/isi-deposit/{id}/{user_id}/{is_tron}', 'Admin\AjaxController@getCekIsiDepositTransactionById')->middleware('auth');
        Route::get('/ajax/adm/reject/isi-deposit/{id}/{user_id}/{is_tron}', 'Admin\AjaxController@getRejectIsiDepositTransactionById')->middleware('auth');
        Route::get('/ajax/adm/cek/tarik-deposit/{id}/{user_id}/{is_tron}', 'Admin\AjaxController@getCekTarikDepositTransactionById')->middleware('auth');
        Route::get('/ajax/adm/cek/ppob-transaction/{id}/{type}', 'Admin\AjaxController@getCekPPOBTransactionById')->middleware('auth');
        Route::get('/ajax/adm/cek/test-hash', 'Admin\AjaxController@getCekTestHash')->middleware('auth');
        Route::get('/ajax/adm/cek/test-send', 'Admin\AjaxController@getCekTestSend')->middleware('auth');
        Route::get('/ajax/adm/cek/test-check-mutation', 'Admin\AjaxController@getCekTestCheckMutation')->middleware('auth');
        Route::get('/ajax/adm/cek/test-check-balance', 'Admin\AjaxController@getCekTestCheckBalance')->middleware('auth');

        //        Route::get('/adm/daerah', 'Admin\MasterAdminController@getAllDaerah')->middleware('auth');
        ////////////////////////////////////////////////////////////////////////
        //##########################
        ////////////////////////////////////////////////////////////////////////
        //#########################


        //Wilayah Member
        Route::get('/m/dashboard', 'Admin\DashboardController@getMemberDashboard')->name('mainDashboard')->middleware('auth');
        Route::get('/m/networking', 'Admin\DashboardController@getMemberNetworking')->name('mainNetworking')->middleware('auth');
        Route::get('/m/wallet', 'Admin\DashboardController@getMemberWallet')->name('mainWallet')->middleware('auth');
        Route::get('/m/explorers', 'Admin\DashboardController@getMemberExplorers')->name('mainExplorer')->middleware('auth');
        Route::get('/m/staking', 'Admin\DashboardController@getMemberStaking')->name('mainStaking')->middleware('auth');
        Route::get('/m/staking-leaderboard', 'Admin\DashboardController@getMemberStakingLeaderboard')->name('mainStakingLeaderboard')->middleware('auth');
        Route::get('/m/claimed-dividend-history', 'Admin\DashboardController@getClaimedDividendHistory')->name('mainClaimedDividendHistory')->middleware('auth');
        Route::get('/m/dividend-history', 'Admin\DashboardController@getDividendHistory')->name('mainDividendHistory')->middleware('auth');
        Route::get('/m/staking-history', 'Admin\DashboardController@getStakingHistory')->name('mainStakingHistory')->middleware('auth');
        Route::get('/m/my-account', 'Admin\DashboardController@getMemberMyAccount')->name('mainMyAccount')->middleware('auth');
        Route::get('/m/notification', 'Admin\DashboardController@getMemberNotification')->name('mainNotification')->middleware('auth');

        //profile
        Route::get('/m/profile', 'Admin\MemberController@getMyProfile')->name('m_myProfile')->middleware('auth');
        Route::get('/m/add/profile', 'Admin\MemberController@getAddMyProfile')->name('m_newProfile')->middleware('auth');
        Route::post('/m/add/profile', 'Admin\MemberController@postAddMyProfile')->middleware('auth');
        Route::get('/m/edit/address', 'Admin\MemberController@getEditAddress')->name('m_editAddress')->middleware('auth');
        Route::post('/m/edit/address', 'Admin\MemberController@postEditAddress')->middleware('auth');
        Route::get('/m/edit/2fa', 'Admin\MemberController@getEdit2FA')->name('m_edit2FA')->middleware('auth');
        Route::get('/m/edit/password', 'Admin\MemberController@getEditPassword')->name('m_editPassword')->middleware('auth');
        Route::post('/m/edit/password', 'Admin\MemberController@postEditPassword')->middleware('auth');
        Route::post('/m/edit/2fa', 'Admin\MemberController@postEdit2FA')->middleware('auth');

        Route::get('/m/tron', 'Admin\MemberController@getMyTron')->name('m_myTron')->middleware('auth');
        Route::get('/m/add/tron', 'Admin\MemberController@getAddMyTron')->name('m_newTron')->middleware('auth');
        Route::post('/m/add/tron', 'Admin\MemberController@postAddMyTron')->middleware('auth');
        Route::post('/m/reset/tron', 'Admin\MemberController@postResetTron')->middleware('auth');

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
        Route::post('/m/reject/package', 'Admin\MemberController@postRejectPackage')->middleware('auth');
        Route::get('/m/add/upgrade', 'Admin\MemberController@getAddUpgrade')->name('m_newUpgrade')->middleware('auth');
        Route::get('/m/add/repeat-order', 'Admin\MemberController@getAddRO')->name('m_newRO')->middleware('auth');
        Route::post('/m/add/repeat-order', 'Admin\MemberController@postAddRO')->middleware('auth');

        //Pin & Transaction
        Route::get('/m/add/pin', 'Admin\MemberController@getAddPin')->name('m_newPin')->middleware('auth');
        Route::post('/m/add/pin', 'Admin\MemberController@postAddPin')->middleware('auth');
        Route::get('/m/list/transactions', 'Admin\MemberController@getListTransactions')->name('m_listTransactions')->middleware('auth');
        Route::get('/m/add/transaction/{id}', 'Admin\MemberController@getAddTransaction')->name('m_addTransaction')->middleware('auth');
        Route::post('/m/add/transaction', 'Admin\MemberController@postAddTransaction')->middleware('auth');
        Route::post('/m/add/transaction-tron', 'Admin\MemberController@postAddTransactionTron')->middleware('auth');
        Route::post('/m/reject/transaction', 'Admin\MemberController@postRejectTransaction')->middleware('auth');
        Route::get('/m/pin/stock', 'Admin\MemberController@getMyPinStock')->name('m_myPinStock')->middleware('auth');
        Route::get('/m/pin/history', 'Admin\MemberController@getMyPinHistory')->name('m_myPinHistory')->middleware('auth');
        Route::get('/m/add/transfer-pin', 'Admin\MemberController@getTransferPin')->name('m_addTransferPin')->middleware('auth');
        Route::post('/m/add/transfer-pin', 'Admin\MemberController@postAddTransferPin')->middleware('auth');

        //Menu Bonus
        Route::get('/m/summary/bonus', 'Admin\BonusmemberController@getMySummaryBonus')->name('m_myBonusSummary')->middleware('auth');
        Route::get('/m/sponsor/bonus', 'Admin\BonusmemberController@getMySponsorBonus')->name('m_myBonusSponsor')->middleware('auth');
        Route::get('/m/binary/bonus', 'Admin\BonusmemberController@getMyBinaryBonus')->name('m_myBonusBinary')->middleware('auth');
        Route::get('/m/royalti-calculation', 'Admin\BonusmemberController@getRoyaltiCalculation')->name('m_myBonusRoyaltiCalculation')->middleware('auth');
        Route::post('/m/royalti-calculation', 'Admin\BonusmemberController@postRoyaltiCalculation')->middleware('auth');
        Route::get('/m/level/bonus', 'Admin\BonusmemberController@getMyLevelBonus')->name('m_myBonusLevel')->middleware('auth');
        Route::get('/m/ro/bonus', 'Admin\BonusmemberController@getMyROBonus')->name('m_myBonusRO')->middleware('auth');
        Route::get('/m/saldo/bonus', 'Admin\BonusmemberController@getMySaldoBonus')->name('m_myBonusSaldo')->middleware('auth');
        Route::post('/m/request/wd', 'Admin\BonusmemberController@postRequestWithdraw')->middleware('auth');
        Route::get('/m/history/wd', 'Admin\BonusmemberController@getHistoryWithdrawal')->name('m_historyWD')->middleware('auth');
        Route::get('/m/req/wd', 'Admin\BonusmemberController@getRequestWithdrawal')->name('m_requestWD')->middleware('auth');
        Route::get('/m/req/wd-royalti', 'Admin\BonusmemberController@getRequestWithdrawalRoyalti')->name('m_requestWDRoyalti')->middleware('auth');
        Route::post('/m/request/wd-royalti', 'Admin\BonusmemberController@postRequestWithdrawRoyalti')->middleware('auth');
        Route::post('/m/request/wd-royalti-eidr', 'Admin\BonusmemberController@postRequestWithdrawRoyaltieIDR')->middleware('auth');
        Route::get('/m/req/wd-eidr', 'Admin\BonusmemberController@getRequestWithdrawaleIDR')->name('m_requestWDeIDR')->middleware('auth');
        Route::post('/m/request/wd-eidr', 'Admin\BonusmemberController@postRequestWithdraweIDR')->middleware('auth');
        // Route::get('/m/req/claim-reward', 'Admin\BonusmemberController@getRequestClaimReward')->name('m_requestClaimReward')->middleware('auth');
        // Route::post('/m/request/claim-reward', 'Admin\BonusmemberController@postRequestClaimReward')->middleware('auth');
        // Route::get('/m/history/reward', 'Admin\BonusmemberController@getHistoryReward')->name('m_historyReward')->middleware('auth');
        // Route::get('/m/belanja-reward', 'Admin\BonusmemberController@getBelanjaReward')->name('m_BelanjaReward')->middleware('auth');
        // Route::post('/m/request/belanja-reward', 'Admin\BonusmemberController@postRequestBelanjaReward')->middleware('auth');
        // Route::get('/m/stockist/penjualan-reward', 'Admin\BonusmemberController@getPenjualanReward')->name('m_PenjualanReward')->middleware('auth');
        // Route::post('/m/request/penjualan-reward', 'Admin\BonusmemberController@postRequestPenjualanReward')->middleware('auth');
        Route::post('/m/request/topup-saldo', 'Admin\BonusmemberController@postRequestTopupSaldo')->middleware('auth');
        Route::get('/m/history/topup-saldo', 'Admin\BonusmemberController@getHistoryTopupSaldo')->name('m_historyTopupSaldo')->middleware('auth');
        Route::get('/m/topup/pembayaran/{id}', 'Admin\BonusmemberController@getMemberTopupPembayaran')->name('m_MemberTopupPembayaran')->middleware('auth');
        Route::post('/m/topup/pembayaran', 'Admin\BonusmemberController@postMemberTopupPembayaran')->middleware('auth');
        Route::post('/m/reject/topup', 'Admin\BonusmemberController@postRejectTopup')->middleware('auth');
        Route::get('/m/history/wd-eidr', 'Admin\BonusmemberController@getHistoryWithdrawaleIDR')->name('m_historyWDeIDR')->middleware('auth');
        // Route::get('/m/vbelanja-reward', 'Admin\BonusmemberController@getVBelanjaReward')->name('m_VBelanjaReward')->middleware('auth');
        // Route::post('/m/request/vbelanja-reward', 'Admin\BonusmemberController@postRequestVBelanjaReward')->middleware('auth');
        // Route::get('/m/vendor/penjualan-reward', 'Admin\BonusmemberController@getVendorPenjualanReward')->name('m_VPenjualanReward')->middleware('auth');
        // Route::post('/m/vendor/penjualan-reward', 'Admin\BonusmemberController@postVendorPenjualanReward')->middleware('auth');

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
        Route::get('/m/print-shopping-receipt/{id}', 'Admin\MemberController@printShoppingReceipt')->name('m_SellerPrintShoppingReceipt')->middleware('auth');
        Route::get('/m/purchase/input-stock', 'Admin\MemberController@getStockistInputPurchase')->name('m_StockistInputPruchase')->middleware('auth');
        Route::post('/m/purchase/input-stock', 'Admin\MemberController@postStockistInputPurchase')->middleware('auth');
        Route::get('/m/purchase/list-stock', 'Admin\MemberController@getStockistListPurchase')->name('m_StockistListPruchase')->middleware('auth');
        Route::get('/m/purchase/detail-stock/{id}', 'Admin\MemberController@getStockistDetailRequestStock')->name('m_StockistDetailPruchase')->middleware('auth');
        Route::post('/m/add/req-stock', 'Admin\MemberController@postAddRequestStock')->middleware('auth');
        Route::post('/m/add/req-stock-tron', 'Admin\MemberController@postAddRequestStockTron')->middleware('auth');
        Route::post('/m/reject/req-stock', 'Admin\MemberController@postRejectRequestStock')->middleware('auth');
        Route::post('/m/add/transfer-royalti', 'Admin\MemberController@postAddTransferRoyalti')->middleware('auth');
        Route::post('/m/add/confirm-pembelian', 'Admin\MemberController@postAddConfirmPembelian')->middleware('auth');
        Route::post('/m/add/reject-pembelian', 'Admin\MemberController@postAddRejectPembelian')->middleware('auth');
        Route::get('/m/purchase/my-stock', 'Admin\MemberController@getStockistMyStockPurchaseSisa')->name('m_StockistMyPruchaseSisa')->middleware('auth');

        //New Stockist Menu
        Route::get('/m/seller/inventory', 'Admin\MemberController@getSellerInventory')->name('m_SellerInventory')->middleware('auth');
        Route::get('/m/seller/profile', 'Admin\MemberController@getSellerProfile')->name('m_SellerProfile')->middleware('auth');
        Route::post('/m/seller/add-profile', 'Admin\MemberController@postSellerAddProfile')->middleware('auth');
        Route::post('/m/seller/edit-profile', 'Admin\MemberController@postSellerEditProfile')->middleware('auth');
        Route::get('/m/image/upload', 'Admin\MemberController@getImageUpload')->name('m_ImageUpload')->middleware('auth');
        Route::post('/m/image/upload', 'Admin\MemberController@postImageUpload')->middleware('auth');
        Route::get('/m/view/uploads', 'Admin\MemberController@viewUploads')->middleware('auth');
        Route::post('/m/add/product', 'Admin\MemberController@postCreateProduct')->middleware('auth');
        Route::post('/m/edit/product', 'Admin\MemberController@postEditProduct')->middleware('auth');
        Route::post('/m/delete/product', 'Admin\MemberController@postDeleteProduct')->middleware('auth');
        Route::post('/m/payment-confirmation', 'Admin\MemberController@postPaymentConfirmation')->middleware('auth');
        Route::post('/m/reject-shopping', 'Admin\MemberController@postRejectShopping')->middleware('auth');

        //New Member Shopping
        Route::get('/m/shopping/{seller_id}', 'Admin\MemberController@getShopping')->name('m_Shopping')->middleware('auth');
        Route::get('/m/getpos', 'Admin\MemberController@getPos')->name('m_getPos')->middleware('auth');
        Route::get('/m/pos', 'Admin\MemberController@getPosShopping')->name('m_Pos')->middleware('auth');

        Route::post('/m/settlement', 'Admin\MemberController@postSettlement')->name('m_Settlement')->middleware('auth');
        Route::get('/m/shopping/payment/{masterSalesID}/{sellerType}', 'Admin\MemberController@getShoppingPayment')->name('m_ShoppingPayment')->middleware('auth');

        //legacy
        Route::post('/m/claim-old-royalti', 'Admin\MemberController@postClaimOldRoyalti')->middleware('auth');


        //New Member Shopping AJAX
        Route::get('/m/ajax/get-product-by-category', 'Admin\AjaxmemberController@getProductByCategory')->middleware('auth');
        Route::get('/m/ajax/get-product-by-id', 'Admin\AjaxmemberController@getProductById')->middleware('auth');
        Route::post('/m/ajax/add-to-cart', 'Admin\AjaxmemberController@postAddToCart')->middleware('auth');
        Route::get('m/ajax/get-cart-contents', 'Admin\AjaxmemberController@getCartContents')->middleware('auth');
        Route::get('m/ajax/delete-cart-item', 'Admin\AjaxmemberController@getDeleteCartItem')->middleware('auth');
        Route::get('/m/ajax/get-cart-total', 'Admin\AjaxmemberController@getCartTotal')->middleware('auth');
        Route::get('/m/ajax/cart-checkout', 'Admin\AjaxmemberController@getCartCheckout')->middleware('auth');
        Route::get('/m/ajax/pos-cart-checkout', 'Admin\AjaxmemberController@getPosCartCheckout')->middleware('auth');
        Route::post('/m/ajax/shopping-payment', 'Admin\AjaxmemberController@postShoppingPayment')->middleware('auth');
        Route::post('/m/ajax/cancel-shopping-payment-buyer', 'Admin\AjaxmemberController@postCancelShoppingPaymentBuyer')->middleware('auth');
        Route::post('/m/ajax/create-local-wallet', 'Admin\AjaxmemberController@postCreateLocalWallet')->middleware('auth');
        Route::post('/m/ajax/withdraw-local-wallet', 'Admin\AjaxmemberController@postWithdrawLocalWallet')->middleware('auth');
        Route::post('/m/ajax/pay-deposit-by-local-wallet', 'Admin\AjaxmemberController@postDepositLocalWalletPay')->middleware('auth');
        Route::post('/m/ajax/pay-by-local-wallet', 'Admin\AjaxmemberController@postPayByLocalWallet')->middleware('auth');
        Route::post('/m/ajax/pay-by-local-wallet-vendor', 'Admin\AjaxmemberController@postPayByLocalWalletVendor')->middleware('auth');
        Route::post('/m/ajax/toggle-local-wallet', 'Admin\AjaxmemberController@postToggleLocalWallet')->middleware('auth');

        Route::get('/m/ajax/get-shop-name', 'Admin\AjaxmemberController@getShopName')->name('member.ajax.getShopName')->middleware('auth');



        Route::get('/m/req/vendor', 'Admin\MemberController@getRequestMemberVendor')->name('m_reqMemberVendor')->middleware('auth');
        Route::post('/m/req/vendor', 'Admin\MemberController@postRequestMemberVendor')->middleware('auth');
        Route::get('/m/search/vendor', 'Admin\MemberController@getSearchVendor')->name('m_SearchVendor')->middleware('auth');
        Route::post('/m/s/vendor', 'Admin\MemberController@postSearchVendor')->middleware('auth');
        Route::get('/m/vshoping/{vendor_id}', 'Admin\MemberController@getMemberShopingVendor')->name('m_MemberShopingVendor')->middleware('auth');
        Route::post('/m/vshoping', 'Admin\MemberController@postMemberShopingVendor')->middleware('auth');
        Route::get('/m/purchase/input-vstock', 'Admin\MemberController@getVendorInputPurchase')->name('m_VendorInputPruchase')->middleware('auth');
        Route::post('/m/purchase/input-vstock', 'Admin\MemberController@postVendorInputPurchase')->middleware('auth');
        Route::get('/m/purchase/detail-vstock/{id}', 'Admin\MemberController@getVendorDetailRequestStock')->name('m_VendorDetailPruchase')->middleware('auth');
        Route::get('/m/purchase/list-vstock', 'Admin\MemberController@getVendorListPurchase')->name('m_VendorListPruchase')->middleware('auth');
        Route::post('/m/add/req-vstock', 'Admin\MemberController@postAddRequestVStock')->middleware('auth');
        Route::post('/m/add/req-vstock-tron', 'Admin\MemberController@postAddRequestVStockTron')->middleware('auth');
        Route::post('/m/reject/req-vstock', 'Admin\MemberController@postRejectRequestVStock')->middleware('auth');
        Route::get('/m/purchase/my-vstock', 'Admin\MemberController@getVendorMyStockPurchaseSisa')->name('m_VendorMyPruchaseSisa')->middleware('auth');
        Route::get('/m/vendor-report', 'Admin\MemberController@getMemberVendorReport')->name('m_MemberVendorReport')->middleware('auth');
        Route::get('/m/vpembayaran/{id}', 'Admin\MemberController@getMemberVPembayaran')->name('m_MemberVPembayaran')->middleware('auth');
        Route::post('/m/vpembayaran', 'Admin\MemberController@postMemberVPembayaran')->middleware('auth');
        Route::get('/m/history/vshoping', 'Admin\MemberController@getHistoryVShoping')->name('m_historyVShoping')->middleware('auth');
        Route::get('/m/detail/vendor-report/{id}', 'Admin\MemberController@getMemberDetailVendorReport')->name('m_MemberDetailVendorReport')->middleware('auth');
        Route::post('/m/add/confirm-vpembelian', 'Admin\MemberController@postAddConfirmVPembelian')->middleware('auth');
        Route::post('/m/add/reject-vpembelian', 'Admin\MemberController@postAddRejectVPembelian')->middleware('auth');

        Route::get('/m/explorer/statistic', 'Admin\MemberController@getExplorerStatistic')->name('m_ExplorerStatistic')->middleware('auth');
        Route::get('/m/explorer/user', 'Admin\MemberController@getExplorerUser')->name('m_ExplorerUser')->middleware('auth');

        //Vendor Deposit
        Route::get('/m/add/deposit', 'Admin\MemberController@getAddDeposit')->name('m_newDeposit')->middleware('auth');
        Route::post('/m/add/deposit', 'Admin\MemberController@postAddDeposit')->middleware('auth');
        Route::get('/m/list/deposit-transaction', 'Admin\MemberController@getListDepositTransactions')->name('m_listDepositTransactions')->middleware('auth');
        Route::get('/m/add/deposit-transaction/{id}', 'Admin\MemberController@getAddDepositTransaction')->name('m_addDepositTransaction')->middleware('auth');
        Route::post('/m/add/deposit-transaction', 'Admin\MemberController@postAddDepositTransaction')->middleware('auth');
        Route::post('/m/add/deposit-transaction-tron', 'Admin\MemberController@postAddDepositTransactionTron')->middleware('auth');
        Route::post('/m/reject/deposit-transaction', 'Admin\MemberController@postRejectDepositTransaction')->middleware('auth');
        //        Route::get('/m/pin/deposit-stock', 'Admin\MemberController@getMyDepositStock')->name('m_myDepositStock')->middleware('auth');
        Route::get('/m/deposit/history', 'Admin\MemberController@getMyDepositHistory')->name('m_myDepositHistory')->middleware('auth');
        Route::get('/m/tarik/deposit', 'Admin\MemberController@getTarikDeposit')->name('m_tarikDeposit')->middleware('auth');
        Route::post('/m/vendor-withdraw-deposit', 'Admin\MemberController@postVendorWithdrawDeposit')->middleware('auth');

        //PPOB
        Route::get('/m/list/emoney', 'Admin\MemberController@getListEmoney')->name('m_listEmoney')->middleware('auth');
        Route::get('/m/list/operator/{type}', 'Admin\MemberController@getListOperator')->name('m_listOperator')->middleware('auth');
        Route::get('/m/daftar-harga/{operator}', 'Admin\MemberController@getDaftarHargaOperator')->name('m_daftarHargaOperator')->middleware('auth');
        Route::get('/m/daftar-harga/data/{operator}', 'Admin\MemberController@getDaftarHargaDataOperator')->name('m_daftarHargaDataOperator')->middleware('auth');
        // Route::get('/m/daftar-harga/prepaid/pln', 'Admin\MemberController@getDaftarHargaPLNPrepaid')->name('m_daftarHargaPLNPrepaid')->middleware('auth');
        Route::get('/m/cek/pln-prepaid', 'Admin\MemberController@getCekPLNPrepaid')->name('m_cekPLNPrepaid')->middleware('auth');
        Route::get('/m/cek/inquiry-pln-prepaid', 'Admin\MemberController@getInquiryPLNPrepaid')->middleware('auth');

        //        Route::get('/m/prepare/buy/ppob', 'Admin\MemberController@getPreparingBuyPPOB')->middleware('auth');
        Route::post('/m/buy/ppob', 'Admin\MemberController@postBuyPPOB')->middleware('auth');
        Route::post('/m/quickbuy/ppob', 'Admin\MemberController@postQuickbuyPPOB')->middleware('auth');
        Route::get('/m/list/buy-ppob', 'Admin\MemberController@getListBuyPPOB')->name('m_listPPOBTransaction')->middleware('auth');
        Route::get('/m/detail/buy-ppob/{id}', 'Admin\MemberController@getDetailBuyPPOB')->name('m_detailPPOBMemberTransaction')->middleware('auth');
        Route::post('/m/confirm/buy-ppob', 'Admin\MemberController@postConfirmBuyPPOB')->middleware('auth');
        Route::get('/m/update/status-ppob/{id}', 'Admin\MemberController@getUpdateStatusPPOB')->middleware('auth');
        Route::get('/m/invoice/ppob/{id}', 'Admin\MemberController@getDetailVendorInvoicePPOB')->name('m_detailPPOBInvoice')->middleware('auth');
        Route::get('/m/vinvoice/ppob/{id}', 'Admin\MemberController@getDetailVendorInvoicePPOB')->name('m_detailVPPOBInvoice')->middleware('auth');
        //PPOB Pasca
        Route::get('/m/detail/pascabayar/{type}', 'Admin\MemberController@getPPOBPascabayar')->name('m_ppobPascabayar')->middleware('auth');
        Route::get('/m/cek/tagihan/pascabayar', 'Admin\MemberController@getPPOBPascabayarCekTagihan')->name('m_ppobPascabayarCekTagihan')->middleware('auth');
        Route::get('/m/list/hp-pascabayar', 'Admin\MemberController@getPPOBHPPascabayar')->name('m_ppobHPPascabayar')->middleware('auth');
        Route::get('/m/list/multifinance', 'Admin\MemberController@getPPOBMultifinance')->name('m_ppobMultifinance')->middleware('auth');
        Route::get('/m/list/pdam', 'Admin\MemberController@getPDAMPascabayar')->name('m_listPDAM')->middleware('auth');
        Route::get('/m/detail/hp-pascabayar/{sku}', 'Admin\MemberController@getDetailPPOBHpPascabayar')->name('m_detailppobHpPascabayar')->middleware('auth');
        Route::get('/m/detail/multifinance/{sku}', 'Admin\MemberController@getDetailPPOBMultifinance')->name('m_detailppobMultifinance')->middleware('auth');
        Route::get('/m/detail/pdam/{sku}', 'Admin\MemberController@getDetailPDAMPascabayar')->name('m_detailPDAMPascabayar')->middleware('auth');
        Route::get('/m/list/tagihan-pascabayar', 'Admin\MemberController@getListTagihanPascabayar')->name('m_TagihanPascabayar')->middleware('auth');
        Route::get('/m/emoney/{operator}', 'Admin\MemberController@getEmoneyByOperator')->name('m_emoneyOperator')->middleware('auth');

        //vendor
        Route::get('/m/list/vppob-transaction', 'Admin\MemberController@getListVendorPPOBTransactions')->name('m_listVendotPPOBTransactions')->middleware('auth');
        Route::get('/m/detail/vppob/{id}', 'Admin\MemberController@getDetailVendorPPOB')->name('m_vendorDetailPPOB')->middleware('auth');
        Route::post('/m/confirm/vppob', 'Admin\MemberController@postVendorConfirmPPOB')->middleware('auth');
        Route::post('/m/confirm/vppob-new', 'Admin\MemberController@postVendorConfirmPPOBnew')->middleware('auth');
        Route::post('/m/reject/vppob', 'Admin\MemberController@postVendorRejectPPOB')->middleware('auth');
        Route::get('/m/cek-status/transaction/{id}', 'Admin\MemberController@getCekStatusTransaksiApi')->middleware('auth');

        //Ajax

        Route::get('/m/cek/add-sponsor', 'Admin\AjaxmemberController@postCekAddSponsor')->middleware('auth');
        Route::get('/m/cek/add-package/{id_paket}/{setuju}', 'Admin\AjaxmemberController@getCekAddPackage')->middleware('auth');
        Route::get('/m/cek/add-pin', 'Admin\AjaxmemberController@postCekAddPin')->middleware('auth');
        Route::get('/m/cek/add-profile', 'Admin\AjaxmemberController@postCekAddProfile')->middleware('auth');
        Route::get('/m/cek/add-transaction', 'Admin\AjaxmemberController@postCekAddTransaction')->middleware('auth');
        Route::get('/m/cek/add-transaction-tron', 'Admin\AjaxmemberController@postCekAddTransactionTron')->middleware('auth');
        Route::get('/m/cek/reject-transaction', 'Admin\AjaxmemberController@postCekRejectTransaction')->middleware('auth');
        Route::get('/m/cek/confirm-order', 'Admin\AjaxmemberController@getCekConfirmOrderPackage')->middleware('auth');
        Route::get('/m/cek/reject-order', 'Admin\AjaxmemberController@getCekRejectOrderPackage')->middleware('auth');
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
        Route::get('/m/cek/confirm-wd-royalti-eidr', 'Admin\AjaxmemberController@getCekConfirmWDRoyaltieIDR')->middleware('auth');
        Route::get('/m/cek/req-stockist', 'Admin\AjaxmemberController@getCekRequestMemberStockist')->middleware('auth');
        Route::get('/m/stockist/cek/shoping', 'Admin\AjaxmemberController@getStockistCekSoping')->middleware('auth');
        Route::get('/m/cek/shoping', 'Admin\AjaxmemberController@getCekSoping')->middleware('auth');
        Route::get('/m/cek/edit-address', 'Admin\AjaxmemberController@getCekEditAddress')->middleware('auth');
        Route::get('/m/cek/edit-password', 'Admin\AjaxmemberController@getCekEditPassword')->middleware('auth');
        Route::get('/m/cek/2fa', 'Admin\AjaxmemberController@getCek2FA')->middleware('auth');
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
        Route::get('/m/cek/product-image', 'Admin\AjaxmemberController@getSearchProductImage')->middleware('auth');
        Route::get('/m/cek/product-image-edit', 'Admin\AjaxmemberController@getSearchProductImageEdit')->middleware('auth');
        Route::get('/m/cek/edit-product/{product_id}', 'Admin\AjaxmemberController@getEditProduct')->middleware('auth');
        Route::get('/m/explore/member', 'Admin\AjaxmemberController@getExplorerMemberByUserCode')->middleware('auth');
        Route::get('/m/cek/confirm-topup', 'Admin\AjaxmemberController@getCekConfirmTopUp')->middleware('auth');
        Route::get('/m/cek/topup-transaction', 'Admin\AjaxmemberController@getCekTopupTransaction')->middleware('auth');
        Route::get('/m/cek/topup-transaction-status', 'Admin\AjaxmemberController@getCekTopUpStatus')->middleware('auth');
        Route::get('/m/cek/reject-topup', 'Admin\AjaxmemberController@getCekRejectTopup')->middleware('auth');
        Route::get('/m/cek/req-vendor', 'Admin\AjaxmemberController@getCekRequestMemberVendor')->middleware('auth');
        Route::get('/m/cek/usercode-vendor', 'Admin\AjaxmemberController@getSearchUserCodeVendor')->middleware('auth');
        Route::get('/m/cek/usercode-buyer', 'Admin\AjaxmemberController@getSearchUserbyUsercode')->middleware('auth');
        Route::get('/m/cek/add-vstock', 'Admin\AjaxmemberController@postCekAddRequestVStock')->middleware('auth');
        Route::get('/m/cek/reject-vstock', 'Admin\AjaxmemberController@postCekRejectRequestVStock')->middleware('auth');
        Route::get('/m/cek/member-vpembayaran', 'Admin\AjaxmemberController@getCekMemberVPembayaran')->middleware('auth');
        Route::get('/m/cek/confirm-vpembelian', 'Admin\AjaxmemberController@postCekConfirmVPembelian')->middleware('auth');
        Route::get('/m/cek/reject-vpembelian', 'Admin\AjaxmemberController@postCekRejectVPembelian')->middleware('auth');
        Route::get('/m/cek/confirm-vpenjualan-reward', 'Admin\AjaxmemberController@getCekConfirmVPenjualanReward')->middleware('auth');
        Route::get('/m/cek/confirm-vbelanja-reward', 'Admin\AjaxmemberController@getCekConfirmVBelanjaReward')->middleware('auth');
        Route::get('/m/cek/confirm-2fa-ppob', 'Admin\AjaxmemberController@getCek2FAConfirmPPOB')->middleware('auth');

        Route::get('/m/search/{type}', 'Admin\AjaxmemberController@getSearchByType')->middleware('auth');
        Route::get('/m/search-new/{type}', 'Admin\AjaxmemberController@getSearchByTypeNew')->middleware('auth');
        Route::get('/m/cek/pulsa', 'Admin\AjaxmemberController@getCekPOBX')->middleware('auth');

        Route::get('/m/cek/add-deposit', 'Admin\AjaxmemberController@postCekAddDeposit')->middleware('auth');
        Route::get('/m/cek/add/deposit-transaction', 'Admin\AjaxmemberController@postCekAddDepositTransaction')->middleware('auth');
        Route::get('/m/cek/add/deposit-transaction-tron', 'Admin\AjaxmemberController@postCekAddDepositTransactionTron')->middleware('auth');
        Route::get('/m/cek/reject/deposit-transaction', 'Admin\AjaxmemberController@postCekRejectDepositTransaction')->middleware('auth');
        Route::get('/m/cek/tarik-deposit', 'Admin\AjaxmemberController@postCekTarikDeposit')->middleware('auth');
        Route::get('/m/cek/add/tarik-transaction', 'Admin\AjaxmemberController@postCekAddTarikTransaction')->middleware('auth');
        Route::get('/m/cek/reject/tarik-transaction', 'Admin\AjaxmemberController@postCekRejectTarikTransaction')->middleware('auth');
        Route::get('/m/cek/buy/ppob', 'Admin\AjaxmemberController@postCekBuyPPOBHP')->middleware('auth');
        Route::get('/m/cek/member-buy', 'Admin\AjaxmemberController@postMemberBuyPPOBHP')->middleware('auth');
        Route::get('/m/cek/reject/buy-ppob', 'Admin\AjaxmemberController@postRejectBuyPPOBHP')->middleware('auth');
        Route::get('/m/cek/buy/ppob-pasca', 'Admin\AjaxmemberController@postCekBuyPPOBPasca')->middleware('auth');

        //new PPOB AJAX
        Route::get('/m/confirm-vendor-quickbuy', 'Admin\AjaxmemberController@getVendorQuickBuy')->middleware('auth');
        Route::get('/m/confirm-vendor-quickbuy-postpaid', 'Admin\AjaxmemberController@getVendorQuickbuyPostpaid')->middleware('auth');
        Route::get('/m/check-order', 'Admin\AjaxmemberController@getCheckOrder')->middleware('auth');
        Route::get('/m/check-order-postpaid', 'Admin\AjaxmemberController@getCheckOrderPostpaid')->middleware('auth');
        Route::get('/m/check-ppob-status', 'Admin\AjaxmemberController@getCheckPPOBStatus')->middleware('auth');
        Route::post('/m/ajax/confirm-ppob-payment', 'Admin\AjaxmemberController@postConfirmPPOBPayment')->middleware('auth');
        Route::post('/m/ajax/cancel-ppob-payment', 'Admin\AjaxmemberController@postCancelPPOBPayment')->middleware('auth');

        //telegram AJAX
        Route::get('/m/ajax/create-telegram-link', 'Admin\AjaxmemberController@getCreateTelegramLink')->middleware('auth');
        Route::get('/m/ajax/remove-telegram-link', 'Admin\AjaxmemberController@getRemoveTelegramLink')->middleware('auth');

        //Staking AJAX
        Route::post('/m/ajax/confirm-staking', 'Admin\AjaxmemberController@postConfirmStake')->middleware('auth');
        Route::post('/m/ajax/confirm-unstaking', 'Admin\AjaxmemberController@postConfirmUnstake')->middleware('auth');
        Route::post('/m/ajax/claim-dividend', 'Admin\AjaxmemberController@postClaimDividend')->middleware('auth');

        //Resubscribe
        Route::post('/m/ajax/confirm-resubscribe', 'Admin\AjaxmemberController@postResubscribe')->middleware('auth');

        // Claim Bonus Royalty
        Route::post('/m/ajax/claim-royalty', 'Admin\AjaxmemberController@postClaimRoyalty')->name('ajax.postClaimRoyalty')->middleware('auth');
    });
});

Route::group(['domain' => 'finance.' . config('services.app.domain')], function () {
    Route::get('/', function () {
        return view('lumbung_finance')->with('title', 'Lumbung Finance');
    });

    Route::get('/login', 'FinanceAuth\LoginController@showLoginForm');
    Route::post('/login', 'FinanceAuth\LoginController@postLogin')->name('finance.login');
    Route::post('/logout', 'FinanceAuth\LoginController@logout');

    Route::get('/register', 'FinanceAuth\RegisterController@showRegistrationForm')->name('finance.register');
    Route::get('/ref/{referral}', 'FinanceAuth\RegisterController@getRegisterRef');
    Route::post('/register', 'FinanceAuth\RegisterController@postRegister');

    Route::post('/password/email', 'FinanceAuth\ForgotPasswordController@sendResetLinkEmail');
    Route::post('/password/reset', 'FinanceAuth\ResetPasswordController@reset');
    Route::get('/password/reset', 'FinanceAuth\ForgotPasswordController@showLinkRequestForm');
    Route::get('/password/reset/{token}', 'FinanceAuth\ResetPasswordController@showResetForm');

    // test zone
    Route::get('/test-zone', 'Finance\AppController@getTestZone')->name('testZone');


    // // Auth::routes();

    // Route::get('/dashboard', 'Finance\AppController@getFinanceDashboard')->name('finance.dashboard')->middleware('auth');
});


//main domain

Route::get('/', function () {
    return view('home')->with('title', 'Lumbung Network');
});
Route::get('/about-core', function () {
    return view('about-core')->with('title', 'Tentang Lumbung Network');
});
