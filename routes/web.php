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

    // Forgot Password
    Route::get('/password/forgot', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('member.password.forgot');
    Route::post('/password/forgot', 'Auth\ForgotPasswordController@postRequestResetPassword');
    Route::get('/password/reset/{token}', 'Auth\ResetPasswordController@getResetPassword')->name('member.password.reset');
    Route::post('/password/reset', 'Auth\ResetPasswordController@postResetPassword')->name('member.password.postReset');

    Route::get('/auth/passwd/{code}/{email}', 'FrontEnd\FrontEndController@getAuthPassword')->name('passwdauth');
    Route::post('/auth/passwd', 'FrontEnd\FrontEndController@postAuthPassword');

    //telegram bot webhook
    Route::post('/' . config('services.telegram.webhook') . '/webhook', 'TelegramBotController@handleRequest');
    // Digiflazz webhook
    Route::post('/digiflazz/webhook', 'Member\DigiflazzController@handleRequest');


    // New Member Routes
    Route::get('/home', 'Member\AppController@getHome')->name('member.home')->middleware('auth');
    Route::get('/stake', 'Member\AppController@getStake')->name('member.stake')->middleware('auth');
    Route::get('/notifications', 'Member\AppController@getNotifications')->name('member.notifications')->middleware('auth');
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
    Route::get('/store/apply', 'Member\StoreController@getApplyStore')->name('member.store.apply')->middleware('auth');
    Route::post('/store/apply', 'Member\StoreController@postApplyStore')->name('member.store.postApplyStore')->middleware('auth');
    Route::get('/store/info', 'Member\StoreController@getStoreInfo')->name('member.store.info')->middleware('auth');
    Route::get('/store/pos', 'Member\StoreController@getPOS')->name('member.store.pos')->middleware('auth');
    Route::post('/store/pos', 'Member\StoreController@postPOS')->name('member.store.postPos')->middleware('auth');
    Route::post('/store/pos-checkout', 'Member\StoreController@postCheckoutPOS')->name('member.store.postCheckoutPOS')->middleware('auth');
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
    Route::get('/stake/div-pool-history', 'Member\AppController@getDividendHistory')->name('member.stakeDivPoolHistory')->middleware('auth');
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
    Route::post('/shopping/search-product', 'Member\ShoppingController@postSearchProduct')->name('member.shopping.postSearchProduct')->middleware('auth');

    // Network
    Route::get('/network', 'Member\NetworkController@getNetwork')->name('member.network')->middleware('auth');
    Route::get('/network/binary-tree/{placing}', 'Member\NetworkController@getBinaryTree')->where('placing', '(0|1)')->name('member.network.binaryTree')->middleware('auth');
    Route::get('/network/sponsor-tree', 'Member\NetworkController@getSponsorTree')->name('member.network.sponsorTree')->middleware('auth');
    Route::get('/network/royalty', 'Member\NetworkController@getRoyalty')->name('member.network.royalty')->middleware('auth');
    Route::get('/network/pairing', 'Member\NetworkController@getPairing')->name('member.network.pairing')->middleware('auth');
    Route::get('/network/affiliate-register/{affiliate_code}', 'Member\NetworkController@getAffiliateRegister')->where('affiliate_code', '(3|6)')->name('member.network.affiliateRegister')->middleware('auth');
    Route::post('/network/affiliate-register/{affiliate_code}', 'Member\NetworkController@postAffiliateRegister')->where('affiliate_code', '(3|6)')->name('member.network.postAffiliateRegister')->middleware('auth');

    Route::post('/network/claim-reward', 'Member\NetworkController@postClaimNetworkReward')->name('member.network.postClaimNetworkReward')->middleware('auth');
    Route::post('/network/binary-placement', 'Member\NetworkController@postBinaryPlacement')->name('member.network.postBinaryPlacement')->middleware('auth');

    // Claims
    Route::get('/claim/shopping-reward', 'Member\AppController@getClaimShoppingReward')->name('member.claim.shoppingReward')->middleware('auth');

    // AJAX
    Route::group(['prefix' => 'ajax'], function () {
        // Network
        Route::get('/network/search-downline-by-username', 'Member\AjaxController@getDownlineUsername')->name('ajax.network.getDownlineUsername')->middleware('auth');
        Route::get('/network/check-placing', 'Member\AjaxController@getCheckPlacing')->name('ajax.network.getCheckPlacing')->middleware('auth');

        // Membership
        Route::post('membership/resubscsribe', 'Member\AjaxController@postResubscribeConfirm')->name('ajax.postResubscribeConfirm')->middleware('auth');

        // Reward Claims
        Route::post('/claim/shopping-reward', 'Member\AjaxController@postClaimShoppingReward')->name('ajax.claim.shoppingReward')->middleware('auth');
        Route::post('/claim/staking-dividend', 'Member\AjaxController@postClaimStakingDividend')->name('ajax.claim.stakingDividend')->middleware('auth');
        Route::post('/claim/bonus-royalty', 'Member\AjaxController@postClaimRoyalty')->name('ajax.claim.royalty')->middleware('auth');
        Route::post('/stake/shopping-reward', 'Member\AjaxController@postStakeShoppingReward')->name('ajax.stake.shoppingReward')->middleware('auth');
        Route::post('/stake/bonus-royalty', 'Member\AjaxController@postStakeRoyalty')->name('ajax.stake.royalty')->middleware('auth');

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

});

//main domain

Route::get('/', function () {
    return view('home')->with('title', 'Lumbung Network');
});
Route::get('/about-core', function () {
    return view('about-core')->with('title', 'Tentang Lumbung Network');
});

// Market
Route::group(['prefix' => 'market'], function () {
    Route::get('/transaction/{type}/{transaction_id}', 'Market\PublicTransactionsController@getTransaction')->where('type', '(physical|digital)')->whereNumber('transaction_id')->name('market.transaction');
});
