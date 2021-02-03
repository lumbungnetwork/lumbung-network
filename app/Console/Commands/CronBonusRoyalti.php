<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\Sales;
use App\Jobs\ProcessRoyaltiBonusJob;


class CronBonusRoyalti extends Command
{

    protected $signature = 'bonus_royalti';
    protected $description = '(Cron) Sort eligible member for Royalti Bonus and Dispatch the Processing Job';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        ini_set("memory_limit", -1);
        ini_set('max_execution_time', 1500);
        $modelSales = new Sales;
        $getPreviousMonth = (object) array(
            'startDay' => date("Y-m-01", strtotime("first day of previous month")),
            'endDay' => date("Y-m-t", strtotime("last day of previous month"))
        );

        $getData = $modelSales->getCronrSalesHistoryMonth($getPreviousMonth);
        $min_belanja = 100000;

        if ($getData != null) {
            foreach ($getData as $row) {
                if ($row->month_sale_price > $min_belanja) {

                    ProcessRoyaltiBonusJob::dispatch($row->id)->onQueue('bonus');
                }
            }
        }
    }
}
