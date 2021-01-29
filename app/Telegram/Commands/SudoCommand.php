<?php

namespace App\Telegram\Commands;

use Telegram\Bot\Commands\Command;
use Telegram;
use Illuminate\Support\Facades\Cache;
use App\Model\Member;
use App\User;
use Illuminate\Support\Facades\Config;
use GuzzleHttp\Client;

/**
 * Class HelpCommand.
 */
class SudoCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = 'sudo';

    /**
     * @var array Command Aliases
     */
    protected $aliases = ['su'];

    /**
     * @var string Command Description
     */
    protected $description = 'SuperUser Commands';

    /**
     * {@inheritdoc}
     */
    public function handle()

    {
        $response = $this->getUpdate();
        $chat_id = $response->getMessage()->getChat()->getId();
        $textContent = $response->getMessage()->getText();

        $modelMember = new Member;

        if ($chat_id != Config::get('services.telegram.overlord')) {
            $text = 'unauthorized access!';
            $this->replyWithMessage(compact('text'));
            return;
        } else {
            $params = explode(' ', $textContent);
            if (!isset($params[1])) {
                $text = 'Bad Command! (param1)';
                $this->replyWithMessage(compact('text'));
                return;
            } elseif (!isset($params[2])) {
                $text = 'Bad Command! (param2)';
                $this->replyWithMessage(compact('text'));
                return;
            }

            if ($params[1] == 'topup' && $params[2] == 'df') {
                if (!isset($params[3])) {
                    $text = 'Topup amount needed!';
                    $this->replyWithMessage(compact('text'));
                    return;
                } elseif ($params[3] < 200000) {
                    $text = 'Topup amount insufficient!';
                    $this->replyWithMessage(compact('text'));
                    return;
                } else {
                    $username   = Config::get('services.digiflazz.user');
                    $apiKey   = Config::get('services.digiflazz.key');
                    $sign = md5($username . $apiKey . 'deposit');
                    $amount = (int) $params[3];

                    $url = 'https://api.digiflazz.com/v1/deposit';
                    $client = new Client;
                    try {
                        $response = $client->request('POST', $url, [
                            'json' => [
                                'username' => $username,
                                'amount' => $amount,
                                'Bank' => 'BRI',
                                'owner_name' => 'Lumbung',
                                'sign' => $sign
                            ]
                        ]);
                    } catch (\GuzzleHttp\Exception\ClientException $ex) {
                        $text = 'Bad Response!' . chr(10);
                        $text .= 'Exception: ' . $ex->getMessage() . chr(10);
                        $this->replyWithMessage(['text' => $text]);
                        return;
                    }

                    if ($response) {
                        $arrayData = json_decode($response->getBody()->getContents(), true);
                        $data = $arrayData['data'];

                        if ($data['rc'] != '00') {
                            $text = 'Topup Request Failed!';
                            $this->replyWithMessage(['text' => $text]);
                            return;
                        }

                        $text = 'Topup Details:';
                        $text2 = $data['amount'];
                        $text3 = $data['notes'];
                        $this->replyWithMessage(compact('text'));
                        $this->replyWithMessage(compact('text2'));
                        $this->replyWithMessage(compact('text3'));
                        return;
                    } else {
                        $text = 'Bad Response!';
                        $this->replyWithMessage(['text' => $text]);
                        return;
                    }
                }
            } else {
                $text = 'Bad commands!';
                $this->replyWithMessage(['text' => $text, 'parse_mode' => 'markdown']);
                return;
            }
        }
    }
}
