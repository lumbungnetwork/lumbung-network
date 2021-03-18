<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Member;
use App\Model\Transferwd;
use App\Model\Sales;
use App\Model\Bonus;
use App\API;

class APIController extends Controller
{
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
                $membershipRevenue = $modelAPI->getAllTimeActivations();
                $stockistContribution = $modelAPI->getAllTimeStockistSales();
                $profitSharingPool = $modelAPI->getAllTimeProfitSharingPool();
                return response()->json([
                    'data' => [
                        'membership_revenue' => $membershipRevenue['total'] * 20000,
                        'profit_share' => $profitSharingPool['total'] * 80 / 100,
                        'profit_share_details' => $profitSharingPool['detail'],
                        'stockist_contribution' => $stockistContribution['total'] * 1 / 100,
                        'total' => ($membershipRevenue['total'] * 20000) + ($profitSharingPool['total'] * 80 / 100) + ($stockistContribution['total'] * 1 / 100)
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
                $membershipRevenue = $modelAPI->getActivations($last_month);
                $stockistContribution = $modelAPI->getStockistSales($last_month);
                $profitSharingPool = $modelAPI->getProfitSharingPool($last_month);
                return response()->json([
                    'data' => [
                        'membership_revenue' => $membershipRevenue['total'] * 20000,
                        'profit_share' => $profitSharingPool['total'] * 70 / 100,
                        'profit_share_details' => $profitSharingPool['detail'],
                        'stockist_contribution' => $stockistContribution['total'] * 1 / 100,
                        'total' => ($membershipRevenue['total'] * 20000) + ($profitSharingPool['total'] * 70 / 100) + ($stockistContribution['total'] * 1 / 100)
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
}
