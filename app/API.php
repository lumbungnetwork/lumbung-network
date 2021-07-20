<?php

namespace App;

use App\Model\Member\DigitalSale;
use App\Model\Member\EidrBalance;
use App\Model\Member\MasterSales;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class API extends Model
{ 
    // v.1 Legacy All Time Network Bonus
    public function getLegacyAllTimeNetworkBonus()
    {
        $sql = DB::table('transfer_wd')
            ->selectRaw('sum(case when status = 1 then wd_total + admin_fee else 0 end) as total')
            ->first();
        return (int) $sql->total;
    }

    // v.2.0
    /**
     * Get Total Count of Premium Membership bought, use empty default params for all-time count
     * @param object $date
     * @return array
     */ 
    public function getPremiumMembershipCount($date = null)
    {
        // Default parameters
        if (!$date) {
            $date = (object) [
                'start_day' => '2021-06-01 00:00:01',
                'end_day' => date('Y-m-d H:i:s', strtotime('today'))
            ];
        }

        // Count it
        $total = User::where('premium_at', '>=', $date->start_day)
                ->where('premium_at', '<=', $date->end_day)
                ->count();

        return ['total' => $total];
    }

    /**
     * Get Details on Premium Membership Revenue Data
     * @param object $date
     * @return array
     */ 
    public function getPremiumMembershipDetails($date)
    {
        $data = User::where('users.premium_at', '>=', $date->start_day)
                ->where('users.premium_at', '<=', $date->end_day)
                ->selectRaw('users.premium_at, u1.username as sponsor, '
                . 'users.username')
                ->leftJoin('users as u1', 'users.sponsor_id', '=', 'u1.id')
                ->orderBy('users.premium_at')
                ->get();

        return ['details' => $data];
    }

    /**
     * Get Profit Sharing Pool data, use empty default params for all-time count
     * @param object $date
     * @return array
     */ 
    public function getProfitSharingPool($date = null)
    {
        // Default param
        if (!$date) {
            $date = (object) [
                'start_day' => '2019-10-01 00:00:01',
                'end_day' => date('Y-m-d H:i:s', strtotime('today'))
            ];
        }
        // Get from Digital Sales
        $pln_prepaid = config('services.lmb_div.pln_prepaid');
        $telkom = config('services.lmb_div.telkom');
        $pln_postpaid = config('services.lmb_div.pln_postpaid');
        $hp_postpaid = config('services.lmb_div.hp_postpaid');
        $bpjs = config('services.lmb_div.bpjs');
        $pdam = config('services.lmb_div.pdam');
        $pgn = config('services.lmb_div.pgn');
        $multifinance = config('services.lmb_div.multifinance');
        $emoney = config('services.lmb_div.emoney');
        $digital = DigitalSale::selectRaw("sum(CASE WHEN type <= 2 THEN ppob_price * 2 / 100 ELSE 0 END) as pulsa_data,"
            . "sum(CASE WHEN type = 3 THEN $pln_prepaid ELSE 0 END) as pln_prepaid,"
            . "sum(CASE WHEN type = 4 THEN $telkom ELSE 0 END) as telkom,"
            . "sum(CASE WHEN type = 5 THEN $pln_postpaid ELSE 0 END) as pln_postpaid,"
            . "sum(CASE WHEN type = 6 THEN $hp_postpaid ELSE 0 END) as hp_postpaid,"
            . "sum(CASE WHEN type = 7 THEN $bpjs ELSE 0 END) as bpjs,"
            . "sum(CASE WHEN type = 8 THEN $pdam ELSE 0 END) as pdam,"
            . "sum(CASE WHEN type = 9 THEN $pgn ELSE 0 END) as pgn,"
            . "sum(CASE WHEN type = 10 THEN $multifinance ELSE 0 END) as multifinance,"
            . "sum(CASE WHEN type >= 21 THEN $emoney ELSE 0 END) as emoney")
            ->whereDate('ppob_date', '>=', $date->start_day)
            ->whereDate('ppob_date', '<=', $date->end_day)
            ->where('status', 2)
            ->whereNull('deleted_at')
            ->first();

        return [
            'detail' => [
                'pulsa_data' => floor($digital->pulsa_data),
                'pln_prepaid' => (int) $digital->pln_prepaid,
                'telkom' => (int) $digital->telkom,
                'pln_postpaid' => (int) $digital->pln_postpaid,
                'hp_postpaid' => (int) $digital->hp_postpaid,
                'bpjs' => (int) $digital->bpjs,
                'pdam' => (int) $digital->pdam,
                'pgn' => (int) $digital->pgn,
                'multifinance' => (int) $digital->multifinance,
                'emoney' => (int) $digital->emoney
            ],
    
            'total' => floor($digital->pulsa_data) + $digital->pln_prepaid + $digital->telkom + $digital->pln_postpaid + $digital->hp_postpaid + $digital->bpjs + $digital->pdam + $digital->pgn + $digital->multifinance + $digital->emoney
    
        ];
    }

    /**
     * Get All Store monthly sales amount (physical products), use empty param to get All time data
     * @param object $date
     * @return int|float
     */ 
    public function getAllStoreMonthlySales($date = null)
    {
        // Default param
        if (!$date) {
            $date = (object) [
                'start_day' => '2019-10-01 00:00:01',
                'end_day' => date('Y-m-d H:i:s', strtotime('today'))
            ];
        }
        return MasterSales::whereDate('created_at', '>=', $date->start_day)
                            ->whereDate('created_at', '<=', $date->end_day)
                            ->where('status', 2)
                            ->sum('total_price');
    }

    /**
     * Get (monthly) sales amount of all Digital products, use empty param to get All time data
     * @param object $date
     * @return int|float
     */ 
    public function getAllDigitalSales($date = null)
    {
        // Default param
        if (!$date) {
            $date = (object) [
                'start_day' => '2019-10-01 00:00:01',
                'end_day' => date('Y-m-d H:i:s', strtotime('today'))
            ];
        }
        return DigitalSale::whereDate('created_at', '>=', $date->start_day)
                            ->whereDate('created_at', '<=', $date->end_day)
                            ->where('status', 2)
                            ->sum('ppob_price');
    }

    /**
     * Get All Time Network Bonus
     * Adding Legacy value to new model (Internal eIDR)
     * 
     * @return int|float
     */ 
    public function getAllTimeNetworkBonus()
    {
        $legacyTotal = $this->getLegacyAllTimeNetworkBonus();
        $newTotal = EidrBalance::where('type', 1)
                    ->whereIn('source', [2,3])
                    ->sum('amount');
        return $legacyTotal + $newTotal;
    }

}
