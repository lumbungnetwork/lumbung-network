<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Model\Member;
use App\Model\Bonussetting;


class CronPeringkat extends Command {

    protected $signature = 'peringkat {ke}';
    protected $description = 'Cron Change User Peringkat';

    public function __construct() {
        parent::__construct();
    }

    public function handle() {
        ini_set("memory_limit",-1);
        ini_set('max_execution_time', 1500);
        $modelMember = New Member;
        $ke = $this->argument('ke');
        $cek = array();
        $date = date('Y-m-d H:i:s');
        $maxTotalSponsorHave = 4;
        
        if($ke == 1){
            $memberType = 1;
            $upgradeMemberType  = 10;
            $getData = $modelMember->getAllMemberHasMoreSponsor($memberType, $maxTotalSponsorHave);
            if($getData != null){
                foreach($getData as $row){
                    $getCounttype = $modelMember->getCountMemberHasSponsorMemberType($row->id, $memberType);
                    if($getCounttype >= $maxTotalSponsorHave){
                        $getUpdate = array(
                            'member_type' => $upgradeMemberType,
                            'member_status_at' => $date
                        );
                        $modelMember->getUpdateUsers('id', $row->id, $getUpdate);
                        $dataHistory = array(
                            'user_id' => $row->id,
                            'member_type_old' => $memberType,
                            'member_type_new' => $upgradeMemberType,
                            'type' => 1
                        );
                        $modelMember->getInsertHistoryMembership($dataHistory);
                    }
                }
            }
            dd('done type '.$ke);
        }
        
        if($ke == 2){
            $memberType = 10;
            $upgradeMemberType  = 11;
            $getData = $modelMember->getAllMemberHasMoreSponsor($memberType, $maxTotalSponsorHave);
            if($getData != null){
                foreach($getData as $row){
                    $getCounttype = $modelMember->getCountMemberHasSponsorMemberType($row->id, $memberType);
                    if($getCounttype >= $maxTotalSponsorHave){
                        $getUpdate = array(
                            'member_type' => $upgradeMemberType,
                            'member_status_at' => $date
                        );
                        $modelMember->getUpdateUsers('id', $row->id, $getUpdate);
                        $dataHistory = array(
                            'user_id' => $row->id,
                            'member_type_old' => $memberType,
                            'member_type_new' => $upgradeMemberType,
                            'type' => 1
                        );
                        $modelMember->getInsertHistoryMembership($dataHistory);
                    }
                }
            }
            dd('done type '.$ke);
        }
        
        if($ke == 3){
            $memberType = 11;
            $upgradeMemberType  = 12;
            $getData = $modelMember->getAllMemberHasMoreSponsor($memberType, $maxTotalSponsorHave);
            if($getData != null){
                foreach($getData as $row){
                    $getCounttype = $modelMember->getCountMemberHasSponsorMemberType($row->id, $memberType);
                    if($getCounttype >= $maxTotalSponsorHave){
                        $getUpdate = array(
                            'member_type' => $upgradeMemberType,
                            'member_status_at' => $date
                        );
                        $modelMember->getUpdateUsers('id', $row->id, $getUpdate);
                        $dataHistory = array(
                            'user_id' => $row->id,
                            'member_type_old' => $memberType,
                            'member_type_new' => $upgradeMemberType,
                            'type' => 1
                        );
                        $modelMember->getInsertHistoryMembership($dataHistory);
                    }
                }
            }
            dd('done type '.$ke);
        }
        
        if($ke == 4){
            $memberType = 12;
            $upgradeMemberType  = 13;
            $getData = $modelMember->getAllMemberHasMoreSponsor($memberType, $maxTotalSponsorHave);
            if($getData != null){
                foreach($getData as $row){
                    $getCounttype = $modelMember->getCountMemberHasSponsorMemberType($row->id, $memberType);
                    if($getCounttype >= $maxTotalSponsorHave){
                        $getUpdate = array(
                            'member_type' => $upgradeMemberType,
                            'member_status_at' => $date
                        );
                        $modelMember->getUpdateUsers('id', $row->id, $getUpdate);
                        $dataHistory = array(
                            'user_id' => $row->id,
                            'member_type_old' => $memberType,
                            'member_type_new' => $upgradeMemberType,
                            'type' => 1
                        );
                        $modelMember->getInsertHistoryMembership($dataHistory);
                    }
                }
            }
            dd('done type '.$ke);
        }
        
        if($ke == 5){
            $memberType = 13;
            $upgradeMemberType  = 14;
            $getData = $modelMember->getAllMemberHasMoreSponsor($memberType, $maxTotalSponsorHave);
            if($getData != null){
                foreach($getData as $row){
                    $getCounttype = $modelMember->getCountMemberHasSponsorMemberType($row->id, $memberType);
                    if($getCounttype >= $maxTotalSponsorHave){
                        $getUpdate = array(
                            'member_type' => $upgradeMemberType,
                            'member_status_at' => $date
                        );
                        $modelMember->getUpdateUsers('id', $row->id, $getUpdate);
                        $dataHistory = array(
                            'user_id' => $row->id,
                            'member_type_old' => $memberType,
                            'member_type_new' => $upgradeMemberType,
                            'type' => 1
                        );
                        $modelMember->getInsertHistoryMembership($dataHistory);
                    }
                }
            }
            dd('done type '.$ke);
        }
        
        if($ke == 5){
            $memberType = 14;
            $upgradeMemberType  = 15;
            $getData = $modelMember->getAllMemberHasMoreSponsor($memberType, $maxTotalSponsorHave);
            if($getData != null){
                foreach($getData as $row){
                    $getCounttype = $modelMember->getCountMemberHasSponsorMemberType($row->id, $memberType);
                    if($getCounttype >= $maxTotalSponsorHave){
                        $getUpdate = array(
                            'member_type' => $upgradeMemberType,
                            'member_status_at' => $date
                        );
                        $modelMember->getUpdateUsers('id', $row->id, $getUpdate);
                        $dataHistory = array(
                            'user_id' => $row->id,
                            'member_type_old' => $memberType,
                            'member_type_new' => $upgradeMemberType,
                            'type' => 1
                        );
                        $modelMember->getInsertHistoryMembership($dataHistory);
                    }
                }
            }
            dd('done type '.$ke);
        }
        
        if($ke == 6){
            $memberType = 15;
            $upgradeMemberType  = 16;
            $getData = $modelMember->getAllMemberHasMoreSponsor($memberType, $maxTotalSponsorHave);
            if($getData != null){
                foreach($getData as $row){
                    $getCounttype = $modelMember->getCountMemberHasSponsorMemberType($row->id, $memberType);
                    if($getCounttype >= $maxTotalSponsorHave){
                        $getUpdate = array(
                            'member_type' => $upgradeMemberType,
                            'member_status_at' => $date
                        );
                        $modelMember->getUpdateUsers('id', $row->id, $getUpdate);
                        $dataHistory = array(
                            'user_id' => $row->id,
                            'member_type_old' => $memberType,
                            'member_type_new' => $upgradeMemberType,
                            'type' => 1
                        );
                        $modelMember->getInsertHistoryMembership($dataHistory);
                    }
                }
            }
            dd('done type '.$ke);
        }
        
        if($ke == 7){
            $memberType = 16;
            $upgradeMemberType  = 17;
            $getData = $modelMember->getAllMemberHasMoreSponsor($memberType, $maxTotalSponsorHave);
            if($getData != null){
                foreach($getData as $row){
                    $getCounttype = $modelMember->getCountMemberHasSponsorMemberType($row->id, $memberType);
                    if($getCounttype >= $maxTotalSponsorHave){
                        $getUpdate = array(
                            'member_type' => $upgradeMemberType,
                            'member_status_at' => $date
                        );
                        $modelMember->getUpdateUsers('id', $row->id, $getUpdate);
                        $dataHistory = array(
                            'user_id' => $row->id,
                            'member_type_old' => $memberType,
                            'member_type_new' => $upgradeMemberType,
                            'type' => 1
                        );
                        $modelMember->getInsertHistoryMembership($dataHistory);
                    }
                }
            }
            dd('done type '.$ke);
        }
        
        dd('done');
        
    }
}
