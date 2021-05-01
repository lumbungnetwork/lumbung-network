<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class API extends Model
{
    public function getLastMonthOverview()
    {
        $date = (object) array(
            'start_day' => date("Y-m-d", strtotime("first day of previous month")),
            'end_day' => date("Y-m-d", strtotime("last day of previous month"))
        );

        $activations = DB::table('member_pin')
            ->selectRaw('id')
            ->where('pin_status', '=', 1)
            ->whereDate('used_at', '>=', $date->start_day)
            ->whereDate('used_at', '<=', $date->end_day)
            ->count();
        $claimedLMBfromMarketplace = DB::table('belanja_reward')
            ->selectRaw('sum(belanja_reward.reward) as total')
            ->where('belanja_reward.status', '=', 1)
            ->whereDate('tuntas_at', '>=', $date->start_day)
            ->whereDate('tuntas_at', '<=', $date->end_day)
            ->first();
        $rewardLMB = DB::table('claim_reward')
            ->selectRaw('sum(case when reward_id = 1 then 100 else 0 end) as silver3, '
                . 'sum(case when reward_id = 2 then 200 else 0 end) as silver2,'
                . 'sum(case when reward_id = 3 then 500 else 0 end) as silver1,'
                . 'sum(case when reward_id = 4 then 2000 else 0 end) as gold3')
            ->where('status', '=', 1)
            ->whereIn('reward_id', array(1, 2, 3, 4))
            ->whereDate('claim_date', '>=', $date->start_day)
            ->whereDate('claim_date', '<=', $date->end_day)
            ->first();
        $networkBonus = DB::table('transfer_wd')
            ->selectRaw('sum(case when status = 1 then wd_total + admin_fee else 0 end) as total')
            ->where('transfer_at', '>=', $date->start_day)
            ->where('transfer_at', '<=', $date->end_day)
            ->first();
        $stockistSales = $this->getStockistSales($date);
        $vendorSales = $this->getVendorSales($date);

        return [
            'activations' => $activations,
            'lmb_claimed' => $claimedLMBfromMarketplace->total + $rewardLMB->silver3 + $rewardLMB->silver2 + $rewardLMB->silver1 + $rewardLMB->gold3,
            'network_bonus' => (int) $networkBonus->total,
            'stockist_sales' => (int) $stockistSales['total'],
            'vendor_sales' => (int) $vendorSales['total']
        ];
    }
    public function getAllTimeActivations()
    {
        $newMember = DB::table('member_pin')
            ->selectRaw('id')
            ->where('pin_status', '=', 1)
            ->where('is_ro', '=', 0)
            ->count();

        $resubscribe = DB::table('member_pin')
            ->selectRaw('id')
            ->where('pin_status', '=', 1)
            ->where('is_ro', '=', 1)
            ->count();

        return [
            'new_member' => $newMember,
            'resubscribe' => $resubscribe,
            'total' => $newMember + $resubscribe
        ];
    }

    public function getActivations($date)
    {
        $newMember = DB::table('member_pin')
            ->selectRaw('id')
            ->where('pin_status', '=', 1)
            ->where('is_ro', '=', 0)
            ->whereDate('used_at', '>=', $date->start_day)
            ->whereDate('used_at', '<=', $date->end_day)
            ->count();

        $resubscribe = DB::table('member_pin')
            ->selectRaw('id')
            ->where('pin_status', '=', 1)
            ->where('is_ro', '=', 1)
            ->whereDate('used_at', '>=', $date->start_day)
            ->whereDate('used_at', '<=', $date->end_day)
            ->count();

        $detailNewMember = DB::table('users')
            ->selectRaw('users.active_at, u1.username as sp_name, '
                . 'users.username')
            ->leftJoin('users as u1', 'users.sponsor_id', '=', 'u1.id')
            ->where('users.is_active', '=', 1)
            ->where('users.user_type', '=', 10)
            ->where('users.pin_activate_at', '=', null)
            ->where('users.active_at', '>=', $date->start_day)
            ->where('users.active_at', '<=', $date->end_day)
            ->orderBy('users.active_at', 'ASC')
            ->get();

        $detailResubscribe = DB::table('users')
            ->selectRaw('users.active_at, u1.username as sp_name, '
                . 'users.username')
            ->leftJoin('users as u1', 'users.sponsor_id', '=', 'u1.id')
            ->where('users.is_active', '=', 1)
            ->where('users.user_type', '=', 10)
            ->where('users.pin_activate_at', '>=', $date->start_day)
            ->where('users.pin_activate_at', '<=', $date->end_day)
            ->orderBy('users.pin_activate_at', 'ASC')
            ->get();

        return [
            'new_member' => $newMember,
            'detail_new_member' => $detailNewMember,
            'resubscribe' => $resubscribe,
            'detail_resubscribe' => $detailResubscribe,
            'total' => $newMember + $resubscribe
        ];
    }

    public function getAllTimeClaimedLMBfromMarketplace()
    {
        $total = DB::table('belanja_reward')
            ->selectRaw('sum(belanja_reward.reward) as total')
            ->where('belanja_reward.status', '=', 1)
            ->first();

        $sql = DB::table('belanja_reward')
            ->selectRaw('sum(case when type = 1 then reward else 0 end) as buyOnStockist, '
                . 'sum(case when type = 2 then reward else 0 end) as sellOnStockist,'
                . 'sum(case when type = 3 then reward else 0 end) as buyOnVendor,'
                . 'sum(case when type = 4 then reward else 0 end) as sellOnVendor')
            ->where('status', '=', 1)
            ->whereIn('type', array(1, 2, 3, 4))
            ->first();

        return [
            'buy_on_stockist' => floor($sql->buyOnStockist),
            'sell_on_stockist' => floor($sql->sellOnStockist),
            'buy_on_vendor' => floor($sql->buyOnVendor),
            'sell_on_vendor' => floor($sql->sellOnVendor),
            'total' => floor($total->total)
        ];
    }

    public function getClaimedLMBfromMarketplace($date)
    {
        $sql = DB::table('belanja_reward')
            ->selectRaw('sum(case when type = 1 then reward else 0 end) as buyOnStockist, '
                . 'sum(case when type = 2 then reward else 0 end) as sellOnStockist,'
                . 'sum(case when type = 3 then reward else 0 end) as buyOnVendor,'
                . 'sum(case when type = 4 then reward else 0 end) as sellOnVendor,'
                . 'sum(reward) as total')
            ->where('status', '=', 1)
            ->whereIn('type', array(1, 2, 3, 4))
            ->whereDate('tuntas_at', '>=', $date->start_day)
            ->whereDate('tuntas_at', '<=', $date->end_day)
            ->first();

        return [
            'buy_on_stockist' => floor($sql->buyOnStockist),
            'sell_on_stockist' => floor($sql->sellOnStockist),
            'buy_on_vendor' => floor($sql->buyOnVendor),
            'sell_on_vendor' => floor($sql->sellOnVendor),
            'total' => floor($sql->total)
        ];
    }

    public function getAllTimeClaimedLMBfromNetwork()
    {
        $sql = DB::table('claim_reward')
            ->selectRaw('sum(case when reward_id = 1 then 100 else 0 end) as silver3, '
                . 'sum(case when reward_id = 2 then 200 else 0 end) as silver2,'
                . 'sum(case when reward_id = 3 then 500 else 0 end) as silver1,'
                . 'sum(case when reward_id = 4 then 2000 else 0 end) as gold3')
            ->where('status', '=', 1)
            ->whereIn('reward_id', array(1, 2, 3, 4))
            ->first();
        return [
            'silver3' => (int) $sql->silver3,
            'silver2' => (int) $sql->silver2,
            'silver1' => (int) $sql->silver1,
            'gold3' => (int) $sql->gold3,
            'total' => $sql->silver3 + $sql->silver2 + $sql->silver1 + $sql->gold3
        ];
    }

    public function getClaimedLMBfromNetwork($date)
    {
        $sql = DB::table('claim_reward')
            ->selectRaw('sum(case when reward_id = 1 then 100 else 0 end) as silver3, '
                . 'sum(case when reward_id = 2 then 200 else 0 end) as silver2,'
                . 'sum(case when reward_id = 3 then 500 else 0 end) as silver1,'
                . 'sum(case when reward_id = 4 then 2000 else 0 end) as gold3')
            ->where('status', '=', 1)
            ->whereIn('reward_id', array(1, 2, 3, 4))
            ->whereDate('claim_date', '>=', $date->start_day)
            ->whereDate('claim_date', '<=', $date->end_day)
            ->first();
        return [
            'silver3' => (int) $sql->silver3,
            'silver2' => (int) $sql->silver2,
            'silver1' => (int) $sql->silver1,
            'gold3' => (int) $sql->gold3,
            'total' => $sql->silver3 + $sql->silver2 + $sql->silver1 + $sql->gold3
        ];
    }

    public function getAllTimeStockistSales()
    {
        $sql = DB::table('master_sales')
            ->selectRaw('sum(master_sales.total_price) as total, DATE_FORMAT(master_sales.sale_date, "%M - %Y") as monthly')
            ->where('master_sales.status', '=', 2)
            ->whereNull('master_sales.deleted_at')
            ->groupBy('monthly')
            ->get();

        $data = array();
        $total = 0;
        foreach ($sql as $row) {
            $total += $row->total;
            $data[$row->monthly] = floor($row->total);
        }

        return [
            'total' => $total,
            'monthly' => $data
        ];
    }

    public function getStockistSales($date)
    {
        $sql = DB::table('master_sales')
            ->selectRaw('sum(master_sales.total_price) as total')
            ->where('master_sales.status', '=', 2)
            ->whereNull('master_sales.deleted_at')
            ->whereDate('sale_date', '>=', $date->start_day)
            ->whereDate('sale_date', '<=', $date->end_day)
            ->first();

        return [
            'total' => (int) $sql->total
        ];
    }

    public function getAllTimeVendorSales()
    {
        $physical = DB::table('vmaster_sales')
            ->selectRaw('sum(vmaster_sales.total_price) as total, DATE_FORMAT(vmaster_sales.sale_date, "%M - %Y") as monthly')
            ->where('vmaster_sales.status', '=', 2)
            ->whereNull('vmaster_sales.deleted_at')
            ->groupBy('monthly')
            ->get();

        $digital = DB::table('ppob')
            ->selectRaw('sum(ppob.ppob_price) as total, DATE_FORMAT(ppob.ppob_date, "%M - %Y") as monthly')
            ->where('ppob.status', '=', 2)
            ->whereNull('ppob.deleted_at')
            ->groupBy('monthly')
            ->get();

        $physicalSales = array();
        $digitalSales = array();
        $totalPhysical = 0;
        $totalDigital = 0;
        foreach ($physical as $row) {
            $totalPhysical += $row->total;
            $physicalSales[$row->monthly] = floor($row->total);
        }

        foreach ($digital as $row) {
            $totalDigital += $row->total;
            $digitalSales[$row->monthly] = floor($row->total);
        }

        return [
            'total' => $totalPhysical + $totalDigital,
            'total_physical' => $totalPhysical,
            'total_digital' => $totalDigital,
            'physical' => $physicalSales,
            'digital' => $digitalSales
        ];
    }

    public function getVendorSales($date)
    {
        $physical = DB::table('vmaster_sales')
            ->selectRaw('sum(vmaster_sales.total_price) as total')
            ->where('vmaster_sales.status', '=', 2)
            ->whereNull('vmaster_sales.deleted_at')
            ->whereDate('sale_date', '>=', $date->start_day)
            ->whereDate('sale_date', '<=', $date->end_day)
            ->first();

        $digital = DB::table('ppob')
            ->selectRaw('sum(ppob.ppob_price) as total')
            ->where('ppob.status', '=', 2)
            ->whereNull('ppob.deleted_at')
            ->whereDate('ppob.ppob_date', '>=', $date->start_day)
            ->whereDate('ppob.ppob_date', '<=', $date->end_day)
            ->first();

        $physicalSales = (int) $physical->total;
        $digitalSales = (int) $digital->total;

        return [
            'total' => $physicalSales + $digitalSales,
            'physical' => $physicalSales,
            'digital' => $digitalSales
        ];
    }

    public function getAllTimeProfitSharingPool()
    {
        $sql = DB::table('ppob')
            ->selectRaw('sum(CASE WHEN ppob.type <= 2 THEN ppob.ppob_price * 2 / 100 ELSE 0 END) as pulsa_data,'
                . 'sum(CASE WHEN ppob.type = 3 THEN 955 ELSE 0 END) as pln_prepaid,'
                . 'sum(CASE WHEN ppob.type = 4 THEN 800 ELSE 0 END) as telkom,'
                . 'sum(CASE WHEN ppob.type = 5 THEN 1000 ELSE 0 END) as pln_postpaid,'
                . 'sum(CASE WHEN ppob.type = 6 THEN 800 ELSE 0 END) as hp_postpaid,'
                . 'sum(CASE WHEN ppob.type = 7 THEN 450 ELSE 0 END) as bpjs,'
                . 'sum(CASE WHEN ppob.type = 8 THEN 450 ELSE 0 END) as pdam,'
                . 'sum(CASE WHEN ppob.type = 9 THEN 600 ELSE 0 END) as pgn,'
                . 'sum(CASE WHEN ppob.type = 10 THEN 2000 ELSE 0 END) as multifinance,'
                . 'sum(CASE WHEN ppob.type >= 21 THEN 400 ELSE 0 END) as emoney')
            ->where('ppob.status', '=', 2)
            ->whereNull('ppob.deleted_at')
            ->first();

        $vendorSales = $this->getAllTimeVendorSales();
        $physicalSalesContribution = $vendorSales['total_physical'] * 2 / 100;

        return [
            'detail' => [
                'pulsa_data' => floor($sql->pulsa_data),
                'pln_prepaid' => (int) $sql->pln_prepaid,
                'telkom' => (int) $sql->telkom,
                'pln_postpaid' => (int) $sql->pln_postpaid,
                'hp_postpaid' => (int) $sql->hp_postpaid,
                'bpjs' => (int) $sql->bpjs,
                'pdam' => (int) $sql->pdam,
                'pgn' => (int) $sql->pgn,
                'multifinance' => (int) $sql->multifinance,
                'emoney' => (int) $sql->emoney,
                'physical' => (int) $physicalSalesContribution
            ],

            'total' => floor($sql->pulsa_data) + $sql->pln_prepaid + $sql->telkom + $sql->pln_postpaid + $sql->hp_postpaid + $sql->bpjs + $sql->pdam + $sql->pgn + $sql->multifinance + $sql->emoney + $physicalSalesContribution
        ];
    }

    public function getProfitSharingPool($date)
    {
        $sql = DB::table('ppob')
            ->selectRaw('sum(CASE WHEN ppob.type <= 2 THEN ppob.ppob_price * 2 / 100 ELSE 0 END) as pulsa_data,'
                . 'sum(CASE WHEN ppob.type = 3 THEN 955 ELSE 0 END) as pln_prepaid,'
                . 'sum(CASE WHEN ppob.type = 4 THEN 800 ELSE 0 END) as telkom,'
                . 'sum(CASE WHEN ppob.type = 5 THEN 1000 ELSE 0 END) as pln_postpaid,'
                . 'sum(CASE WHEN ppob.type = 6 THEN 800 ELSE 0 END) as hp_postpaid,'
                . 'sum(CASE WHEN ppob.type = 7 THEN 450 ELSE 0 END) as bpjs,'
                . 'sum(CASE WHEN ppob.type = 8 THEN 450 ELSE 0 END) as pdam,'
                . 'sum(CASE WHEN ppob.type = 9 THEN 600 ELSE 0 END) as pgn,'
                . 'sum(CASE WHEN ppob.type = 10 THEN 2000 ELSE 0 END) as multifinance,'
                . 'sum(CASE WHEN ppob.type >= 21 THEN 400 ELSE 0 END) as emoney')
            ->whereDate('ppob.ppob_date', '>=', $date->start_day)
            ->whereDate('ppob.ppob_date', '<=', $date->end_day)
            ->where('ppob.status', '=', 2)
            ->whereNull('ppob.deleted_at')
            ->first();

        $vendorSales = $this->getVendorSales($date);
        $physicalSalesContribution = $vendorSales['physical'] * 2 / 100;

        return [
            'detail' => [
                'pulsa_data' => floor($sql->pulsa_data),
                'pln_prepaid' => (int) $sql->pln_prepaid,
                'telkom' => (int) $sql->telkom,
                'pln_postpaid' => (int) $sql->pln_postpaid,
                'hp_postpaid' => (int) $sql->hp_postpaid,
                'bpjs' => (int) $sql->bpjs,
                'pdam' => (int) $sql->pdam,
                'pgn' => (int) $sql->pgn,
                'multifinance' => (int) $sql->multifinance,
                'emoney' => (int) $sql->emoney,
                'physical' => (int) $physicalSalesContribution
            ],

            'total' => floor($sql->pulsa_data) + $sql->pln_prepaid + $sql->telkom + $sql->pln_postpaid + $sql->hp_postpaid + $sql->bpjs + $sql->pdam + $sql->pgn + $sql->multifinance + $sql->emoney + $physicalSalesContribution

        ];
    }

    public function getAllTimeNetworkBonus()
    {
        $sql = DB::table('transfer_wd')
            ->selectRaw('sum(case when status = 1 then wd_total + admin_fee else 0 end) as total, '
                . 'sum(case when status = 1 AND type = 1 then wd_total + admin_fee else 0 end) as total_wd_bank,'
                . 'sum(case when status = 1 AND type = 3 then wd_total + admin_fee else 0 end) as  total_royalti_bonus,'
                . 'sum(case when status = 1 AND type = 5 then wd_total + admin_fee else 0 end) as total_konversi_eidr')
            ->first();
        $total = 0;
        if ($sql->total != null) {
            $total = (int) $sql->total;
        }
        $total_wd_bank = 0;
        if ($sql->total_wd_bank != null) {
            $total_wd_bank = (int) $sql->total_wd_bank;
        }
        $total_royalti_bonus = 0;
        if ($sql->total_royalti_bonus != null) {
            $total_royalti_bonus = (int) $sql->total_royalti_bonus;
        }
        $total_konversi_eidr = 0;
        if ($sql->total_konversi_eidr != null) {
            $total_konversi_eidr = (int) $sql->total_konversi_eidr;
        }

        return [
            'total' => $total,
            'total_wd_bank' => $total_wd_bank,
            'total_royalti_bonus' => $total_royalti_bonus,
            'total_konversi_eidr' => $total_konversi_eidr
        ];
    }

    public function getNetworkBonus($date)
    {
        $sql = DB::table('transfer_wd')
            ->selectRaw('sum(case when status = 1 then wd_total + admin_fee else 0 end) as total, '
                . 'sum(case when status = 1 AND type = 1 then wd_total + admin_fee else 0 end) as total_wd_bank,'
                . 'sum(case when status = 1 AND type = 3 then wd_total + admin_fee else 0 end) as  total_royalti_bonus,'
                . 'sum(case when status = 1 AND type = 5 then wd_total + admin_fee else 0 end) as total_konversi_eidr')
            ->where('transfer_at', '>=', $date->start_day)
            ->where('transfer_at', '<=', $date->end_day)
            ->first();
        $total = 0;
        if ($sql->total != null) {
            $total = (int) $sql->total;
        }
        $total_wd_bank = 0;
        if ($sql->total_wd_bank != null) {
            $total_wd_bank = (int) $sql->total_wd_bank;
        }
        $total_royalti_bonus = 0;
        if ($sql->total_royalti_bonus != null) {
            $total_royalti_bonus = (int) $sql->total_royalti_bonus;
        }
        $total_konversi_eidr = 0;
        if ($sql->total_konversi_eidr != null) {
            $total_konversi_eidr = (int) $sql->total_konversi_eidr;
        }

        return [
            'total' => $total,
            'total_wd_bank' => $total_wd_bank,
            'total_royalti_bonus' => $total_royalti_bonus,
            'total_konversi_eidr' => $total_konversi_eidr
        ];
    }
}
