<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\API;
use App\Http\Controllers\Finance\AjaxController;
use Illuminate\Support\Facades\Cache;

class APIController extends Controller
{
    public function getStatisticOverviewCached()
    {
        $stats = Cache::remember('overview_statistic', 3600, function () {
            return $this->getStatisticOverview();
        });

        return $stats;
    }
    public function getStatisticOverview()
    {
        $modelAPI = new API;

        //all time
        $activations = $modelAPI->getAllTimeActivations();
        $totalWD = $modelAPI->getAllTimeNetworkBonus();
        $stockistSales = $modelAPI->getAllTimeStockistSales();
        $vendorSales = $modelAPI->getAllTimeVendorSales();
        $LMBclaimedFromMarketplace = $modelAPI->getAllTimeClaimedLMBfromMarketplace();
        $LMBclaimedFromNetwork = $modelAPI->getAllTimeClaimedLMBfromNetwork();
        $lmbClaimed = $LMBclaimedFromMarketplace['total'] + $LMBclaimedFromNetwork['total'];
        $getLmbDividend = $this->getStatisticDetail('all', 'dividend');
        $lmbDividend = json_decode($getLmbDividend->getContent(), true);

        //last month
        $lastMonthData = $modelAPI->getLastMonthOverview();
        $getLastMonthLmbDividend = $this->getStatisticDetail('last-month', 'dividend');
        $lastMonthLMBDividend = json_decode($getLastMonthLmbDividend->getContent(), true);

        $data = [
            'alltime' => [
                'account_activations' => $activations['total'],
                'network_bonus' => $totalWD['total'],
                'stockist_sales' => $stockistSales['total'],
                'vendor_sales' => $vendorSales['total'],
                'lmb_claimed' => floor($lmbClaimed),
                'lmb_dividend' => $lmbDividend['data']['total']
            ],
            'last-month' => [
                'account_activations' => $lastMonthData['activations'],
                'network_bonus' => $lastMonthData['network_bonus'],
                'stockist_sales' => $lastMonthData['stockist_sales'],
                'vendor_sales' => $lastMonthData['vendor_sales'],
                'lmb_claimed' => floor($lastMonthData['lmb_claimed']),
                'lmb_dividend' => $lastMonthLMBDividend['data']['total']
            ]
        ];

        return response()->json([
            'data' => $data
        ], 200);
    }

    public function getStatisticDetail($time, $name)
    {
        $modelAPI = new API;
        if ($time == 'all') {
            if ($name == 'activations') {
                $data = $modelAPI->getAllTimeActivations();
                return response()->json([
                    'data' => $data
                ], 200);
            } elseif ($name == 'lmb') {
                $LMBclaimedFromMarketplace = $modelAPI->getAllTimeClaimedLMBfromMarketplace();
                $LMBclaimedFromNetwork = $modelAPI->getAllTimeClaimedLMBfromNetwork();
                return response()->json([
                    'data' => [
                        'claimed_from_marketplace' => $LMBclaimedFromMarketplace,
                        'claimed_from_network' => $LMBclaimedFromNetwork
                    ]
                ], 200);
            } elseif ($name == 'stockist-sales') {
                $data = $modelAPI->getAllTimeStockistSales();
                return response()->json([
                    'data' => $data
                ], 200);
            } elseif ($name == 'vendor-sales') {
                $data = $modelAPI->getAllTimeVendorSales();
                return response()->json([
                    'data' => $data
                ], 200);
            } else if ($name == 'dividend') {
                $stockistContribution = $modelAPI->getAllTimeStockistSales();
                $profitSharingPool = $modelAPI->getAllTimeProfitSharingPool();
                return response()->json([
                    'data' => [
                        'profit_share' => $profitSharingPool['total'] * 80 / 100,
                        'profit_share_details' => $profitSharingPool['detail'],
                        'stockist_contribution' => $stockistContribution['total'] * 1 / 100,
                        'total' => ($profitSharingPool['total'] * 80 / 100) + ($stockistContribution['total'] * 1 / 100)
                    ]
                ], 200);
            } elseif ($name == 'network-bonus') {
                $data = $modelAPI->getAllTimeNetworkBonus();
                return response()->json([
                    'data' => $data
                ]);
            }
        } elseif ($time == 'last-month') {
            $last_month = (object) array(
                'start_day' => date("Y-m-d", strtotime("first day of previous month")),
                'end_day' => date("Y-m-d", strtotime("last day of previous month"))
            );
            if ($name == 'activations') {
                $data = $modelAPI->getActivations($last_month);
                return response()->json([
                    'data' => $data
                ], 200);
            } elseif ($name == 'dividend') {
                $stockistContribution = $modelAPI->getStockistSales($last_month);
                $profitSharingPool = $modelAPI->getProfitSharingPool($last_month);
                return response()->json([
                    'data' => [
                        'profit_share' => $profitSharingPool['total'] * 70 / 100,
                        'profit_share_details' => $profitSharingPool['detail'],
                        'stockist_contribution' => $stockistContribution['total'] * 1 / 100,
                        'total' => ($profitSharingPool['total'] * 70 / 100) + ($stockistContribution['total'] * 1 / 100)
                    ]
                ], 200);
            } elseif ($name == 'vendor-sales') {
                $data = $modelAPI->getVendorSales($last_month);
                return response()->json([
                    'data' => $data
                ], 200);
            } elseif ($name == 'stockist-sales') {
                $data = $modelAPI->getStockistSales($last_month);
                return response()->json([
                    'data' => $data
                ], 200);
            } elseif ($name == 'network-bonus') {
                $data = $modelAPI->getNetworkBonus($last_month);
                return response()->json([
                    'data' => $data
                ], 200);
            } elseif ($name == 'lmb') {
                $LMBclaimedFromMarketplace = $modelAPI->getClaimedLMBfromMarketplace($last_month);
                $LMBclaimedFromNetwork = $modelAPI->getClaimedLMBfromNetwork($last_month);
                return response()->json([
                    'data' => [
                        'claimed_from_marketplace' => $LMBclaimedFromMarketplace,
                        'claimed_from_network' => $LMBclaimedFromNetwork
                    ]
                ], 200);
            }
        }
    }

    // Lumbung Network API v.2.0
    /**
     * Get Total Count of Premium Membership bought, use empty default params for all-time count
     * @param string $since
     * @param string $until
     * @return JSON
     */ 
    public function getPremiumMembershipRevenue($since = null, $until = null)
    {
        $modelAPI = new API;
        if ($since && $until) {
            // Check the parameter (if exists and valid)
            if (!strtotime($since) || !strtotime($until)) {
                return response()->json([], 400);
            }
            // Create date object to pass the time constraint
            $date = (object) [
                'start_day' => date('Y-m-d H:i:s', strtotime($since)),
                'end_day' => date('Y-m-d H:i:s', strtotime($until))
            ];
            return response()->json($modelAPI->getPremiumMembershipCount($date), 200);
        } elseif (!$since && !$until) {
            // Return all time date when no parameter included
            return response()->json($modelAPI->getPremiumMembershipCount(), 200);
        } else {
            return response()->json([], 400);
        }
    }

    /**
     * Get Details on Premium Membership Data by Monthly query
     * @param string $year
     * @param string $month
     * @return JSON
     */ 
    public function getPremiumMembershipDetailbyMonth($year, $month)
    {
        $modelAPI = new API;
        // Validate params
        $timeScope = strtotime($year . '-' . $month);
        if (!$timeScope) {
            return response()->json([], 400);
        }
        // Build monthly date object
        $date = (object) [
            'start_day' => date('Y-m-01 00:00:00', $timeScope),
            'end_day' => date('Y-m-t 23:59:59', $timeScope)
        ];
        return response()->json($modelAPI->getPremiumMembershipDetails($date), 200);
    }

    /**
     * Get All Store total sales by Monthly query
     * @param string $year
     * @param string $month
     * @return JSON
     */ 
    public function getAllStoreTotalSalesbyMonth($year = null, $month = null)
    {
        $modelAPI = new API;
        // Validate params, use default when no param passed in
        if (!$year && !$month) {
            return response()->json([
                'total' => $modelAPI->getAllStoreMonthlySales()
            ], 200);
        }
        $timeScope = strtotime($year . '-' . $month);
        if (!$timeScope) {
            return response()->json([], 400);
        }
        // Build monthly date object
        $date = (object) [
            'start_day' => date('Y-m-01 00:00:00', $timeScope),
            'end_day' => date('Y-m-t 23:59:59', $timeScope)
        ];
        return response()->json([
            'total' => $modelAPI->getAllStoreMonthlySales($date)
        ], 200);
    }

    /**
     * Get Monthly data of Profit Sharing Pool, use empty params to get alltime data
     * @param string $year
     * @param string $month
     * @return JSON
     */ 
    public function getProfitSharingPoolDetails($year = null, $month = null)
    {
        $modelAPI = new API;
        // Validate params, use default when no param passed in
        if (!$year && !$month) {
            return response()->json($modelAPI->getProfitSharingPool(), 200);
        }
        $timeScope = strtotime($year . '-' . $month);
        if (!$timeScope) {
            return response()->json([], 400);
        }
        // Build monthly date object
        $date = (object) [
            'start_day' => date('Y-m-01 00:00:00', $timeScope),
            'end_day' => date('Y-m-t 23:59:59', $timeScope)
        ];
        return response()->json($modelAPI->getProfitSharingPool($date), 200);
    }

    public function getFinancePlatformLiquidity()
    {
        $ajaxController = new AjaxController;
        return $ajaxController->getPlatformLiquidity();
    }
}
