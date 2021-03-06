<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//API
Route::prefix('/v2')->group(function () {
    Route::get('/main/statistic/membership-revenue/{since?}/{until?}', 'APIController@getPremiumMembershipRevenue')->name('api.v2.main.statistic.membership');
    Route::get('/main/statistic/membership-details/{year}/{month}', 'APIController@getPremiumMembershipDetailbyMonth')->name('api.v2.main.statistic.membership_details');
    Route::get('/main/statistic/total-store-sales/{year?}/{month?}', 'APIController@getAllStoreTotalSalesbyMonth')->name('api.v2.main.statistic.store_sales');
    Route::get('/main/statistic/total-digital-sales/{year?}/{month?}', 'APIController@getDigitalProductTotalSales')->name('api.v2.main.statistic.digital_sales');
    Route::get('/main/statistic/profit-sharing-pool/{year?}/{month?}', 'APIController@getProfitSharingPoolDetails')->name('api.v2.main.statistic.profit_sharing_pool');
    Route::get('/main/statistic/lmb-dividend', 'APIController@getAllTimeLMBDividend')->name('api.v2.main.statistic.lmb_dividend');
    Route::get('/main/statistic/network-bonus', 'APIController@getAllTimeNetworkBonus')->name('api.v2.main.statistic.network_bonus');
    Route::get('/main/statistic/overview', 'APIController@getStatisticOverview')->name('api.v2.main.statistic.overview');

    Route::get('/finance/platform-liquidity', 'APIController@getFinancePlatformLiquidity')->name('finance.platform-liquidity');
});
