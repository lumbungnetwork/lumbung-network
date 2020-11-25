<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use IEXBase\TronAPI\Tron;
use IEXBase\TronAPI\Provider\HttpProvider;
use IEXBase\TronAPI\Exception\TronException;

class TronModel extends Model
{
    public $fullNode;
    public $solidityNode;
    public $eventServer;
    public $tron;

    public function __construct()
    {
        $fullNode = new HttpProvider('https://super.guildchat.io');
        $solidityNode = new HttpProvider('https://solidity.guildchat.io');
        $eventServer = new HttpProvider('https://api.trongrid.io');


        try {
            $tron = new Tron($fullNode, $solidityNode, $eventServer);
        } catch (TronException $e) {
            exit($e->getMessage());
        }

        $this->tron = $tron;
        $this->fullNode = $fullNode;
        $this->solidityNode = $solidityNode;
        $this->eventServer = $eventServer;
    }

    public function getTokenBalance($tokenID, $address)
    {
        $tokenBalance = $this->tron->getTokenBalance($tokenID, $address, $fromTron = false);
        return $tokenBalance;
    }

    public function checkTransaction($hash)
    {
        $detail = $this->tron->getTransaction($hash);
        $timestamp = $detail['raw_data']['timestamp'];
        $sender = $this->tron->fromHex($detail['raw_data']['contract'][0]['parameter']['value']['owner_address']);
        $receiver = $this->tron->fromHex($detail['raw_data']['contract'][0]['parameter']['value']['to_address']);
        $asset = $this->tron->fromHex($detail['raw_data']['contract'][0]['parameter']['value']['asset_name']);
        $amount = $detail['raw_data']['contract'][0]['parameter']['value']['amount'];

        $data = array(
            'timestamp' => $timestamp,
            'sender' => $sender,
            'receiver' => $receiver,
            'asset' => $asset,
            'amount' => $amount
        );

        return $data;
    }
}
