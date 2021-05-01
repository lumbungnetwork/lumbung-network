<?php

namespace App\Http\Controllers;

use App\Model\Member;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Laravel\Facades\Telegram;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use App\User;
use App\Jobs\ManualTopUpeIDRjob;

class TelegramBotController extends Controller
{

    public function handleRequest()
    {
        $telegram = Telegram::commandsHandler(true);

        if (!$telegram->hasCommand()) {

            if ($telegram->isType('callback_query')) {
                $callback = $telegram->getCallbackQuery();
                $callback_id = $callback->getId();
                $split_callback_data = explode(' ', $callback->getData());
                $command = $split_callback_data[0];
                $request_id = $split_callback_data[1];
                $type = $split_callback_data[2];
                $username1 = $split_callback_data[3];
                $chat_id = $callback->getMessage()->getChat()->getId();
                $message_id = $callback->getMessage()->getMessageId();
                $message_text = $callback->getMessage()->getText();
                $message_reply_markup = $callback->getMessage()->getReplyMarkup();

                if ($type == 'topup') {
                    if ($chat_id == Config::get('services.telegram.overlord')) {
                        if ($command == 'accept') {

                            Telegram::answerCallbackQuery([
                                'callback_query_id' => $callback_id,
                                'text' => 'Request Approved!'
                            ]);

                            $message_text .= chr(10) . '*APPROVED*';
                            Telegram::editMessageText([
                                'chat_id' => $chat_id,
                                'message_id' => $message_id,
                                'text' => $message_text,
                                'parse_mode' => 'markdown'
                            ]);

                            // prevent double call
                            sleep(15);

                            ManualTopUpeIDRjob::dispatch(1, $request_id)->onQueue('tron');

                            return;
                        } elseif ($command == 'reject') {
                            ManualTopUpeIDRjob::dispatch(0, $request_id)->onQueue('tron');
                            Telegram::answerCallbackQuery([
                                'callback_query_id' => $callback_id,
                                'text' => 'Request Rejected!'
                            ]);

                            $message_text .= chr(10) . '*REJECTED*';
                            Telegram::editMessageText([
                                'chat_id' => $chat_id,
                                'message_id' => $message_id,
                                'text' => $message_text,
                                'parse_mode' => 'markdown'
                            ]);

                            return;
                        }
                    } else {
                        Telegram::answerCallbackQuery([
                            'callback_query_id' => $callback_id,
                            'text' => 'Who are you?',
                            'show_alert' => true
                        ]);
                        return;
                    }
                }

                $name = $callback->getFrom()->getFirstName();
                $voter_id = $callback->getFrom()->getId();
                $cache_key = $callback->getData();
                $voters = Cache::get('voters' . $request_id . $type);
                $votersName = Cache::get('votersname' . $request_id . $type);

                if (empty($voters)) {
                    $message_text .= chr(10) . chr(10) . 'Proses Moderasi:';
                }
                if (in_array($voter_id, $voters)) {
                    Telegram::answerCallbackQuery([
                        'callback_query_id' => $callback_id,
                        'text' => 'Anda sudah memberikan tanggapan',
                        'show_alert' => true
                    ]);
                    return;
                } else {
                    $voters[] = $voter_id;
                    $votersName[] = $name;
                }

                $voteCount = Cache::get($cache_key);
                $voteCount++;
                if ($voteCount > 2) {
                    $callback_data = [
                        'chat_id' => $chat_id,
                        'message_id' => $message_id,
                        'callback_query_id' => $callback_id,
                        'request_id' => $request_id,
                        'voters' => json_encode($votersName),
                        'username1' => $username1
                    ];
                }
                switch ($command) {
                    case 'accept':
                        if ($voteCount > 2) {
                            if ($type == 'stockist') {
                                $this->finalizeStockistRequest($callback_data, 1);
                            } elseif ($type == 'vendor') {
                                $this->finalizeVendorRequest($callback_data, 1);
                            } elseif ($type == 'tron') {
                                $this->finalizeResetTronRequest($callback_data, 1);
                            }

                            return;
                        } else {
                            $callback_response_text = 'Anda menyetujui pengajuan ini';
                            $message_text .= chr(10) . $name . ' *menyetujui* pengajuan ini';
                        }

                        break;

                    case 'reject':
                        if ($voteCount > 2) {
                            if ($type == 'stockist') {
                                $this->finalizeStockistRequest($callback_data, 0);
                            } elseif ($type == 'vendor') {
                                $this->finalizeVendorRequest($callback_data, 0);
                            } elseif ($type == 'tron') {
                                $this->finalizeResetTronRequest($callback_data, 0);
                            }
                            return;
                        } else {
                            $callback_response_text = 'Anda menolak pengajuan ini';
                            $message_text .= chr(10) . $name . ' *menolak* pengajuan ini';
                        }

                        break;
                }
                Telegram::answerCallbackQuery([
                    'callback_query_id' => $callback_id,
                    'text' => $callback_response_text
                ]);

                $response = Telegram::editMessageText([
                    'chat_id' => $chat_id,
                    'message_id' => $message_id,
                    'text' => $message_text,
                    'parse_mode' => 'markdown',
                    'reply_markup' => $message_reply_markup
                ]);

                Cache::put($cache_key, $voteCount, 172800);
                Cache::put('voters' . $request_id . $type, $voters, 172800);
                Cache::put('votersname' . $request_id . $type, $votersName, 172800);


                return;
            }
        }
        return 'ok';
    }

    public function sendStockistApplyRequest($data)
    {
        $nama1 = $data['nama1'];
        $username1 = $data['username1'];
        $delegate = $data['delegate'];
        $request_id = $data['request_id'];

        $acceptKey = 'accept ' . $request_id . ' stockist ' . $username1;
        $rejectKey = 'reject ' . $request_id . ' stockist ' . $username1;

        Cache::put($acceptKey, 0, 172800);
        Cache::put($rejectKey, 0, 172800);
        Cache::put('voters' . $request_id . 'stockist', [], 172800);
        Cache::put('votersname' . $request_id . 'stockist', [], 172800);

        $keyboard = Keyboard::make()
            ->inline()
            ->row(
                Keyboard::inlineButton(['text' => 'Setuju', 'callback_data' => $acceptKey]),
                Keyboard::inlineButton(['text' => 'Tolak', 'callback_data' => $rejectKey])
            );

        $message_text = '*Permohonan Pengajuan Stockist*' . chr(10) . chr(10);
        $message_text .= 'Username: ' . $username1 . ' (' . $nama1 . ')' . chr(10) . chr(10);
        $message_text .= 'Delegasi: ' . $delegate . chr(10);

        $response = Telegram::sendMessage([
            'chat_id' => Config::get('services.telegram.delegates'),
            'text' => $message_text,
            'parse_mode' => 'markdown',
            'reply_markup' => $keyboard
        ]);
    }

    public function sendVendorApplyRequest($data)
    {
        $nama1 = $data['nama1'];
        $username1 = $data['username1'];
        $delegate = $data['delegate'];
        $request_id = $data['request_id'];

        $acceptKey = 'accept ' . $request_id . ' vendor ' . $username1;
        $rejectKey = 'reject ' . $request_id . ' vendor ' . $username1;

        Cache::put($acceptKey, 0, 172800);
        Cache::put($rejectKey, 0, 172800);
        Cache::put('voters' . $request_id . 'vendor', [], 172800);
        Cache::put('votersname' . $request_id . 'vendor', [], 172800);

        $keyboard = Keyboard::make()
            ->inline()
            ->row(
                Keyboard::inlineButton(['text' => 'Setuju', 'callback_data' => $acceptKey]),
                Keyboard::inlineButton(['text' => 'Tolak', 'callback_data' => $rejectKey])
            );

        $message_text = '*Permohonan Pengajuan Vendor*' . chr(10) . chr(10);
        $message_text .= 'Username Vendor: ' . $username1 . ' (' . $nama1 . ')' . chr(10) . chr(10);
        $message_text .= 'Delegasi: ' . $delegate . chr(10);

        $response = Telegram::sendMessage([
            'chat_id' => Config::get('services.telegram.delegates'),
            'text' => $message_text,
            'parse_mode' => 'markdown',
            'reply_markup' => $keyboard
        ]);
    }

    public function sendResetTronRequest($data)
    {
        $name = $data['name'];
        $username = $data['username'];
        $delegate = $data['delegate'];
        $request_id = $data['request_id'];

        $acceptKey = 'accept ' . $request_id . ' tron ' . $username;
        $rejectKey = 'reject ' . $request_id . ' tron ' . $username;

        Cache::put($acceptKey, 0, 172800);
        Cache::put($rejectKey, 0, 172800);
        Cache::put('voters' . $request_id . 'tron', [], 172800);
        Cache::put('votersname' . $request_id . 'tron', [], 172800);

        $keyboard = Keyboard::make()
            ->inline()
            ->row(
                Keyboard::inlineButton(['text' => 'Setuju', 'callback_data' => $acceptKey]),
                Keyboard::inlineButton(['text' => 'Tolak', 'callback_data' => $rejectKey])
            );

        $message_text = '*Permohonan Pengajuan Reset Alamat TRON*' . chr(10) . chr(10);
        $message_text .= 'Username: ' . $username . ' (' . $name . ')' . chr(10);
        $message_text .= 'Delegasi: ' . $delegate . chr(10);

        Telegram::sendMessage([
            'chat_id' => Config::get('services.telegram.delegates'),
            'text' => $message_text,
            'parse_mode' => 'markdown',
            'reply_markup' => $keyboard
        ]);
    }

    public function sendeIDRTopupRequest($data)
    {
        $username = $data['username'];
        $bank = $data['bank'];
        $amount = $data['amount'];
        $request_id = $data['request_id'];

        $acceptKey = 'accept ' . $request_id . ' topup ' . $username;
        $rejectKey = 'reject ' . $request_id . ' topup ' . $username;

        $keyboard = Keyboard::make()
            ->inline()
            ->row(
                Keyboard::inlineButton(['text' => 'Approve', 'callback_data' => $acceptKey]),
                Keyboard::inlineButton(['text' => 'Reject', 'callback_data' => $rejectKey])
            );

        $message_text = '*Top-up eIDR Request*' . chr(10) . chr(10);
        $message_text .= 'Bank: ' . $bank . chr(10);
        $message_text .= 'Username: ' . $username . chr(10);
        $message_text .= 'Amount: Rp' . number_format($amount) . chr(10);

        Telegram::sendMessage([
            'chat_id' => Config::get('services.telegram.overlord'),
            'text' => $message_text,
            'parse_mode' => 'markdown',
            'reply_markup' => $keyboard
        ]);
    }

    public function finalizeStockistRequest($callback_data, $approval)
    {
        $modelMember = new Member;

        if ($approval > 0) {
            Telegram::answerCallbackQuery([
                'callback_query_id' => $callback_data['callback_query_id'],
                'text' => 'Pengajuan Stokis Disetujui Penuh',
                'show_alert' => false
            ]);

            $response = Telegram::editMessageText([
                'chat_id' => $callback_data['chat_id'],
                'message_id' => $callback_data['message_id'],
                'text' => '*Pengajuan Stockist ' . $callback_data['username1'] . ' Berhasil Disetujui oleh mufakat Delegasi*' . chr(10) . chr(10) . $callback_data['voters'],
                'parse_mode' => 'markdown'
            ]);

            $data = [
                'status' => 1,
                'active_at' => date("Y-m-d H:i:s"),
                'reason' => $callback_data['voters']
            ];

            $modelMember->getUpdateStockist('id', $callback_data['request_id'], $data);

            $user = User::where('username', $callback_data['username1'])->first();
            $user->is_stockist = 1;
            $user->stockist_at = date("Y-m-d H:i:s");
            $user->save();
        } else {
            Telegram::answerCallbackQuery([
                'callback_query_id' => $callback_data['callback_query_id'],
                'text' => 'Pengajuan Stokis Ditolak',
                'show_alert' => false
            ]);

            $response = Telegram::editMessageText([
                'chat_id' => $callback_data['chat_id'],
                'message_id' => $callback_data['message_id'],
                'text' => '*Pengajuan Stockist ' . $callback_data['username1'] . ' telah DITOLAK oleh mufakat Delegasi*' . chr(10) . chr(10) . $callback_data['voters'],
                'parse_mode' => 'markdown'
            ]);

            $modelMember->deleteRequestStockist($callback_data['request_id']);
        }

        return;
    }

    public function finalizeVendorRequest($callback_data, $approval)
    {
        $modelMember = new Member;


        if ($approval > 0) {
            Telegram::answerCallbackQuery([
                'callback_query_id' => $callback_data['callback_query_id'],
                'text' => 'Pengajuan Vendor Disetujui Penuh',
                'show_alert' => false
            ]);

            $response = Telegram::editMessageText([
                'chat_id' => $callback_data['chat_id'],
                'message_id' => $callback_data['message_id'],
                'text' => '*Pengajuan Vendor ' . $callback_data['username1'] . ' Berhasil Disetujui oleh mufakat Delegasi*' . chr(10) . chr(10) . $callback_data['voters'],
                'parse_mode' => 'markdown'
            ]);

            $data = [
                'status' => 1,
                'active_at' => date("Y-m-d H:i:s"),
                'reason' => $callback_data['voters']
            ];

            $modelMember->getUpdateVendor('id', $callback_data['request_id'], $data);

            $user = User::where('username', $callback_data['username1'])->first();
            $user->is_vendor = 1;
            $user->vendor_at = date("Y-m-d H:i:s");
            $user->save();
        } else {
            Telegram::answerCallbackQuery([
                'callback_query_id' => $callback_data['callback_query_id'],
                'text' => 'Pengajuan Vendor Ditolak',
                'show_alert' => false
            ]);

            $response = Telegram::editMessageText([
                'chat_id' => $callback_data['chat_id'],
                'message_id' => $callback_data['message_id'],
                'text' => '*Pengajuan Vendor ' . $callback_data['username1'] . ' telah DITOLAK oleh mufakat Delegasi*' . chr(10) . chr(10) . $callback_data['voters'],
                'parse_mode' => 'markdown'
            ]);

            $modelMember->deleteRequestVendor($callback_data['request_id']);
        }

        return;
    }

    public function finalizeResetTronRequest($callback_data, $approval)
    {
        $modelMember = new Member;


        if ($approval > 0) {
            Telegram::answerCallbackQuery([
                'callback_query_id' => $callback_data['callback_query_id'],
                'text' => 'Pengajuan Vendor Disetujui Penuh',
                'show_alert' => false
            ]);

            $response = Telegram::editMessageText([
                'chat_id' => $callback_data['chat_id'],
                'message_id' => $callback_data['message_id'],
                'text' => '*Pengajuan Reset Alamat TRON ' . $callback_data['username1'] . ' Berhasil Disetujui oleh mufakat Delegasi*' . chr(10) . chr(10) . $callback_data['voters'],
                'parse_mode' => 'markdown'
            ]);

            $data = [
                'status' => 1,
                'voters' => $callback_data['voters']
            ];

            $modelMember->getUpdateResetTron('id', $callback_data['request_id'], $data);

            $user = User::where('username', $callback_data['username1'])->first();
            $user->is_tron = 0;
            $user->tron = null;
            $user->save();
        } else {
            Telegram::answerCallbackQuery([
                'callback_query_id' => $callback_data['callback_query_id'],
                'text' => 'Pengajuan Vendor Ditolak',
                'show_alert' => false
            ]);

            $response = Telegram::editMessageText([
                'chat_id' => $callback_data['chat_id'],
                'message_id' => $callback_data['message_id'],
                'text' => '*Pengajuan Vendor ' . $callback_data['username1'] . ' telah DITOLAK oleh mufakat Delegasi*' . chr(10) . chr(10) . $callback_data['voters'],
                'parse_mode' => 'markdown'
            ]);

            $data = [
                'status' => 2,
                'voters' => $callback_data['voters']
            ];

            $modelMember->getUpdateResetTron('id', $callback_data['request_id'], $data);
        }

        return;
    }
}
