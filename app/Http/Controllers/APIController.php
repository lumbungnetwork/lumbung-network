<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\API;
use App\Http\Controllers\Finance\AjaxController;
use App\User;
use Illuminate\Support\Facades\Cache;

class APIController extends Controller
{
    // Lumbung Network API v.2.0

    /**
     * Get Statistic Overview
     * All time Dividend, Network Bonus, Total Sales, Users count, Stores count
     * @return JSON
     */ 
    public function getStatisticOverview()
    {
        return Cache::remember('statistic_overview', 3600, function () {
            $modelAPI = new API;
            // Get All Sales
            $physicalSales = $modelAPI->getAllStoreMonthlySales();
            $digitalSales = $modelAPI->getAllDigitalSales();
            // Calculate Dividend
            $dividend = 0.02 * ($physicalSales + $digitalSales) * config('services.lmb_div.proportion');
            // Get Network Bonus
            $bonus = $modelAPI->getAllTimeNetworkBonus();
            // Get Users and Stores count
            $users = User::whereIn('user_type', [9, 10])->count();
            $stores = User::where('is_store', 1)->count();

            return response()->json([
                'dividend' => round($dividend, 2),
                'bonus' => round($bonus, 2),
                'sales' => $physicalSales + $digitalSales,
                'users' => (int) $users,
                'stores' => (int) $stores
            ], 200);
        });
    }

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
     * Get All Store total sales by Monthly query, use empty param to get All time data
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
     * Get All Digital Product sales by Monthly query, use empty param to get All time data
     * @param string $year
     * @param string $month
     * @return JSON
     */ 
    public function getDigitalProductTotalSales($year = null, $month = null)
    {
        $modelAPI = new API;
        // Validate params, use default when no param passed in
        if (!$year && !$month) {
            return response()->json([
                'total' => $modelAPI->getAllDigitalSales()
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
            'total' => $modelAPI->getAllDigitalSales($date)
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

    /**
     * Get All Time LMB Dividend from All Sales
     * Store contribution for every sales = 2% (0.02)
     * LMB Proportion set by community consensus
     * @return JSON
     */ 
    public function getAllTimeLMBDividend()
    {
        $modelAPI = new API;
        $physicalSales = $modelAPI->getAllStoreMonthlySales();
        $digitalSales = $modelAPI->getAllDigitalSales();

        $dividend = 0.02 * ($physicalSales + $digitalSales) * config('services.lmb_div.proportion');

        return response()->json(['total' => $dividend], 200);
    }

    /**
     * Get All Time Network Bonus (Including Legacy)
     * Sponsor Bonus and Binary Bonus
     * @return JSON
     */ 
    public function getAllTimeNetworkBonus()
    {
        $modelAPI = new API;
        return response()->json(['total' => $modelAPI->getAllTimeNetworkBonus()], 200);
    }

    // Lumbung Finance related APIs
    public function getFinancePlatformLiquidity()
    {
        $ajaxController = new AjaxController;
        return $ajaxController->getPlatformLiquidity();
    }
}
