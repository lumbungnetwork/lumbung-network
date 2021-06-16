<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use App\Model\Member\MasterSales;
use App\Model\Member\DigitalSale;
use App\Model\Bonus;
use App\Model\Member\LMBreward;
use App\Model\Member\EidrBalance;
use App\Model\Member\EidrBalanceTransaction;
use Telegram\Bot\Laravel\Facades\Telegram;
use App\Jobs\WithdrawInternalEidrViaTronJob;
use RealRashid\SweetAlert\Facades\Alert;
use DB;
use Illuminate\Support\Facades\Hash;
use IEXBase\TronAPI\Exception\TronException;
use App\Http\Controllers\TelegramBotController;

class AppController extends Controller
{
    public function getHome()
    {
        $user = Auth::user();

        // get user's current month spending
        $MasterSales = new MasterSales;
        $spending = $MasterSales->getMemberSpending(1, $user->id, date('m'), date('Y'));

        // get dividend pool and user's staked LMB
        $Bonus = new Bonus;
        $LMBDividendPool = $Bonus->getLMBDividendPool();
        $userStakedLMB = $Bonus->getUserStakedLMB($user->id);
        $total_notifs = 0;

        // if user is_store check relevant unconfirmed transactions
        if ($user->is_store) {
            // Physical goods
            $physical_tx = MasterSales::where('stockist_id', $user->id)
                ->where('status', 1)
                ->where('buy_metode', 1)
                ->count();

            // Digital goods
            $digital_tx = DigitalSale::where('vendor_id', $user->id)
                ->where('status', 1)
                ->where('buy_metode', 1)
                ->count();

            $sum = $physical_tx + $digital_tx;

            // Total notifications
            if ($sum > 0) {
                $total_notifs = $sum;

                // Alert about notifs once per session
                if (!session()->has('notif')) {
                    Alert::info('Ada Notifikasi baru!', 'Silakan cek Notifikasi anda dengan klik ikon lonceng di kanan atas');
                }
                session(['notif' => $sum]);
            }
        }

        return view('member.app.home')
            ->with('title', 'Home')
            ->with(compact('user'))
            ->with(compact('LMBDividendPool'))
            ->with(compact('userStakedLMB'))
            ->with(compact('total_notifs'))
            ->with(compact('spending'));
    }

    public function getNotifications()
    {
        $user = Auth::user();
        $physical_tx = null;
        $digital_tx = null;

        if ($user->is_store) {
            // Physical goods
            $physical_tx = MasterSales::where('stockist_id', $user->id)
                ->where('status', 1)
                ->where('buy_metode', 1)
                ->with('buyer')
                ->get();

            // Digital goods
            $digital_tx = DigitalSale::where('vendor_id', $user->id)
                ->where('status', 1)
                ->where('buy_metode', 1)
                ->with('buyer')
                ->get();
        }

        return view('member.app.notifications')
            ->with(compact('physical_tx'))
            ->with(compact('digital_tx'))
            ->with('title', 'Notifikasi');
    }

    public function getClaimShoppingReward()
    {
        $user = Auth::user();

        // Get Net LMB Reward ($is_store = 0 for Shopping Reward)
        $LMBreward = new LMBreward;
        $netLMBReward = $LMBreward->getUserNetLMBReward($user->id);
        // Get Reward History (both credit and claimed)
        $rewardHistory = LMBreward::where('user_id', $user->id)->get();

        return view('member.claim.shopping_reward')
            ->with('title', 'Claim Reward')
            ->with(compact('user'))
            ->with(compact('netLMBReward'))
            ->with(compact('rewardHistory'));
    }

    public function getStake()
    {
        $user = Auth::user();

        $modelBonus = new Bonus;
        $LMBDividendPool = $modelBonus->getLMBDividendPool();
        $totalStakedLMB = $modelBonus->getStakedLMB();
        $userStakedLMB = $modelBonus->getUserStakedLMB($user->id);
        $userDividend = $modelBonus->getUserDividend($user->id);
        $userUnstaking = $modelBonus->getUserUnstakeProgress($user->id);

        if ($user->user_type == 9) {
            Alert::warning('Membership Limitation', 'Untuk menerima Dividen harian dari Staking LMB, anda memerlukan Premium Membership')->persistent(true);
        }

        return view('member.app.stake')
            ->with('title', 'Staking')
            ->with(compact('LMBDividendPool'))
            ->with(compact('totalStakedLMB'))
            ->with(compact('userStakedLMB'))
            ->with(compact('userDividend'))
            ->with(compact('userUnstaking'))
            ->with(compact('user'));
    }

    public function getStakeHistory()
    {
        $user = Auth::user();
        $Bonus = new Bonus;
        $data = $Bonus->getUserStakingHistory($user->id);

        return view('member.app.stake_history')
            ->with('title', 'Stake History')
            ->with(compact('data'));
    }

    public function getStakeDivHistory()
    {
        $user = Auth::user();
        $Bonus = new Bonus;
        $data = $Bonus->getUserDividendHistory($user->id);

        return view('member.app.stake_div_history')
            ->with('title', 'Dividend History')
            ->with(compact('data'));
    }

    public function getStakeClaimedDivHistory()
    {
        $user = Auth::user();
        $Bonus = new Bonus;
        $data = $Bonus->getUserClaimedDividend($user->id);

        return view('member.app.stake_history')
            ->with('title', 'Claimed Dividend')
            ->with(compact('data'));
    }

    public function getStakeLeaderboard()
    {
        $user = Auth::user();
        $Bonus = new Bonus;
        $totalStakedLMB = $Bonus->getStakedLMB();
        $stakers = $Bonus->getAllStakersLeaderboard();

        return view('member.app.stake_leaderboard')
            ->with('title', 'Staking Leaderboard')
            ->with(compact('totalStakedLMB'))
            ->with(compact('stakers'));
    }

    public function getShopping()
    {
        return view('member.app.shopping')
            ->with('title', 'Shopping');
    }

    public function getWallet()
    {
        $user = Auth::user();
        $EidrBalance = new EidrBalance;
        $netBalance = $EidrBalance->getUserNeteIDRBalance($user->id);
        $history = EidrBalance::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('member.app.wallet')
            ->with(compact('netBalance'))
            ->with(compact('history'))
            ->with('title', 'Wallet');
    }

    public function getAccount()
    {
        $user = Auth::user();
        return view('member.app.account')
            ->with(compact('user'))
            ->with('title', 'Account');
    }

    public function getProfile()
    {
        $user = Auth::user();
        $provinsi = DB::table('provinsi')->get();
        return view('member.app.account.profile')
            ->with(compact('user'))
            ->with(compact('provinsi'))
            ->with('title', 'Profile');
    }

    public function getEditProfile()
    {
        $user = Auth::user();
        if ($user->is_profile == 0) {
            return redirect()->route('member.profile');
        }
        $provinsi = DB::table('provinsi')->get();
        return view('member.app.account.profile-edit')
            ->with(compact('user'))
            ->with(compact('provinsi'))
            ->with('title', 'Ubah Alamat');
    }

    public function getSecurity()
    {
        $user = Auth::user();
        return view('member.app.account.security')
            ->with(compact('user'))
            ->with('title', 'Security');
    }

    public function getAccountTron()
    {
        $user = Auth::user();
        return view('member.app.account.tron')
            ->with(compact('user'))
            ->with('title', 'TRON');
    }

    public function getBank()
    {
        $user = Auth::user();
        // check if profile completed
        if ($user->is_profile == 0) {
            Alert::error('Oops', 'Untuk menggunakan fitur Bank, anda perlu melengkapi Data Profil terlebih dahulu')->persistent(true);
            return redirect()->route('member.profile');
        }

        return view('member.app.account.bank')
            ->with(compact('user'))
            ->with('title', 'Bank');
    }

    public function getWalletDeposit()
    {
        $user = Auth::user();
        $data = EidrBalanceTransaction::where('user_id', $user->id)->where('type', 1)->orderByDesc('created_at')->paginate(10);
        return view('member.app.deposit')
            ->with(compact('data'))
            ->with('title', 'Deposit');
    }

    public function getWalletWithdraw()
    {
        $user = Auth::user();
        $EidrBalance = new EidrBalance;
        $netBalance = $EidrBalance->getUserNeteIDRBalance($user->id);
        $data = EidrBalanceTransaction::where('user_id', $user->id)->where('type', 0)->orderByDesc('created_at')->paginate(10);
        return view('member.app.wallet.withdraw')
            ->with(compact('data'))
            ->with(compact('netBalance'))
            ->with('title', 'Withdraw');
    }

    public function postWalletDeposit(Request $request)
    {
        $user = Auth::user();
        // validate input
        $validator = Validator::make($request->all(), [
            'type' => 'required|integer|in:1,2',
            'amount' => 'required|integer'
        ]);

        if ($validator->fails()) {
            Alert::error('Oops', $validator->errors()->first());
            return redirect()->back();
        }

        if ($request->type == 1) {
            if ($request->amount < 10000) {
                Alert::error('Oops', 'Minimal Rp10.000 untuk deposit via Bank');
                return redirect()->back();
            }
        }

        do {
            $unique_digits = rand(39, 148);
            $check = EidrBalanceTransaction::where('created_at', '>=', date('Y-m-d 00:00:00'))
                ->where('unique_digits', $unique_digits)
                ->where('amount', $request->amount)
                ->where('method', $request->type)
                ->exists();
        } while ($check);

        // create transaction record
        $tx = new EidrBalanceTransaction;
        $tx->user_id = $user->id;
        $tx->amount = $request->amount;
        $tx->unique_digits = $unique_digits;
        $tx->type = 1;
        $tx->status = 0;
        $tx->method = $request->type;
        $tx->save();

        return redirect()->route('member.depositPayment', ['transaction_id' => $tx->id]);
    }

    public function postWalletWithdraw(Request $request)
    {
        $user = Auth::user();
        $EidrBalance = new EidrBalance;
        $netBalance = $EidrBalance->getUserNeteIDRBalance($user->id);

        if ($request->method == 1 && $user->bank == null) {
            Alert::error('Error', 'Anda belum mengisi data bank, silakan melengkapinya terlebih dulu');
            return redirect()->route('member.account.bank');
        }

        if ($request->method == 2 && $user->tron == null) {
            Alert::error('Error', 'Anda belum mengisi alamat TRON, silakan melengkapinya terlebih dulu');
            return redirect()->route('member.tron');
        }

        // validate input
        $validator = Validator::make($request->all(), [
            'method' => 'required|integer|in:1,2',
            'amount' => "required|integer|max:$netBalance",
            'password' => 'required|numeric|digits_between:4,9'
        ]);

        if ($validator->fails()) {
            Alert::error('Error', $validator->errors()->first());
            return redirect()->back();
        }

        // Check 2FA
        $check = Hash::check($request->password, $user->{'2fa'});
        if (!$check) {
            Alert::error('Error', 'Pin 2FA yang anda masukkan salah!');
            return redirect()->back();
        }

        // check minimum for Bank transfer method
        if ($request->method == 1 && $request->amount < 20000) {
            Alert::error('Error', 'Minimum Withdraw adalah Rp20.000,-');
            return redirect()->back();
        }

        // double check balance
        $remaining = $netBalance - $request->amount;
        if ($remaining < 0) {
            Alert::error('Error', 'Saldo tidak mencukupi');
            return redirect()->back();
        }

        // create record
        $data = new EidrBalanceTransaction;
        $data->user_id = $user->id;
        $data->amount = $request->amount;
        $data->unique_digits = 0;
        $data->type = 0;
        $data->status = 1;
        $data->method = $request->method;
        $data->save();

        // Deduct balance
        $method = 'via Bank';
        if ($request->method == 2) {
            $method = 'via TRON';
        }
        $balance = new EidrBalance;
        $balance->user_id = $user->id;
        $balance->amount = $request->amount;
        $balance->type = 0;
        $balance->source = 5;
        $balance->tx_id = $data->id;
        $balance->note = 'Withdraw ' . $method;
        $balance->save();

        if ($request->method == 1) {
            $sendAmount = $request->amount - 5500;
            $text = 'Lumbung Network Withdraw Request' . chr(10);
            $text .= 'Name: ' . $user->bank->name . chr(10);
            $text .= 'Bank: ' . $user->bank->bank . chr(10);
            $text .= 'Acc No: ' . $user->bank->account_no . chr(10);
            $text .= 'Amount: ' . $sendAmount . chr(10) . chr(10);
            $text .= 'transaction_id: ' . $data->id;

            Telegram::sendMessage([
                'chat_id' => config('services.telegram.overlord'),
                'text' => $text
            ]);

            Alert::success('Berhasil', 'Permintaan anda sedang diproses, akan masuk dalam beberapa jam ke depan');
            return redirect()->back();
        } else {
            WithdrawInternalEidrViaTronJob::dispatch($data->id)->onQueue('tron');
            sleep(2);

            Alert::success('Berhasil', 'Saldo eIDR telah berhasil ditarik ke alamat TRON anda');
            return redirect()->back();
        }
    }

    public function getDepositPayment($transaction_id)
    {
        $user = Auth::user();
        $data = EidrBalanceTransaction::findOrFail($transaction_id);
        if ($data->user_id != $user->id) {
            Alert::error('Oops', 'Access Denied!');
            return redirect()->route('member.home');
        }

        return view('member.app.wallet.confirm_deposit')
            ->with('title', 'Deposit')
            ->with(compact('data'));
    }

    public function postDepositPayment(Request $request)
    {
        $user = Auth::user();
        $data = EidrBalanceTransaction::findOrFail($request->transaction_id);
        if ($data->user_id != $user->id || $data->status != 0) {
            Alert::error('Oops', 'Access Denied!');
            return redirect()->route('member.home');
        }
        $bankName = 'BCA';
        if ($request->bank == 2) {
            $bankName = 'BRI';
        } elseif ($request->bank == 3) {
            $bankName = 'Mandiri';
        }

        $data->tx_id = $bankName;
        $data->status = 1;
        $data->save();

        $requestData = [
            'username' => $user->username,
            'bank' => $bankName,
            'amount' => $data->amount + $data->unique_digits,
            'request_id' => $data->id
        ];
        $telegramBotController = new TelegramBotController;
        $telegramBotController->sendeIDRTopupRequest($requestData);

        return redirect()->back();
    }

    public function postDepositPaymentTron(Request $request)
    {
        $user = Auth::user();
        $data = EidrBalanceTransaction::findOrFail($request->transaction_id);
        if ($data->user_id != $user->id || $data->status != 0) {
            Alert::error('Oops', 'Access Denied!');
            return redirect()->route('member.home');
        }

        // validate input
        $validator = Validator::make($request->all(), [
            'transaction_id' => 'required|integer|exists:eidr_balance_transactions,id',
            'hash' => 'required|string|size:64|unique:deposit_transaction,tron_transfer|unique:eidr_balance_transactions,tx_id'
        ]);

        if ($validator->fails()) {
            Alert::error('Oops', $validator->errors()->first());
            return redirect()->back();
        }

        // use Atomic lock to prevent race condition
        $lock = Cache::lock('deposit_' . $user->id, 60);

        if ($lock->get()) {
            $hash = $request->hash;

            $amount = $data->amount + $data->unique_digits;

            $tron = $this->getTron();
            $i = 1;
            do {
                try {
                    sleep(1);
                    $response = $tron->getTransaction($hash);
                } catch (TronException $e) {
                    $i++;
                    continue;
                }
                break;
            } while ($i < 23);

            if ($i == 23) {
                $lock->release();
                Alert::error('Gagal', 'Hash Transaksi Bermasalah!');
                return redirect()->back();
            };

            // Parse from response

            $hashReceiver = $tron->fromHex($response['raw_data']['contract'][0]['parameter']['value']['to_address']);
            $hashAsset = $tron->fromHex($response['raw_data']['contract'][0]['parameter']['value']['asset_name']);
            $hashAmount = $response['raw_data']['contract'][0]['parameter']['value']['amount'];

            // Checking
            if ($hashReceiver != 'TC1o89VSHMSPno2FE6SgoCsuy8i4mVSWge') {
                $lock->release();
                Alert::error('Gagal', 'Alamat tujuan salah!');
                return redirect()->back();
            }

            if ($hashAsset != '1002652') {
                $lock->release();
                Alert::error('Gagal', 'Bukan token eIDR yang benar!');
                return redirect()->back();
            }

            if ($hashAmount != ($amount * 100)) {
                $lock->release();
                Alert::error('Gagal', 'Jumlah transfer tidak tepat!');
                return redirect()->back();
            }

            // Create EidrBalance
            $balance = new EidrBalance;
            $balance->user_id = $user->id;
            $balance->amount = $amount;
            $balance->type = 1;
            $balance->source = 5;
            $balance->tx_id = $data->id;
            $balance->note = 'Deposit via eIDR TRON';
            $balance->save();

            // Update transaction record
            $data->status = 2;
            $data->tx_id = $hash;
            $data->save();

            $lock->release();

            Alert::success('Berhasil', 'Deposit eIDR telah berhasil!');
            return redirect()->route('member.wallet');
        }
        Alert::error('Failed', 'Access Denied');
        return redirect()->route('member.wallet');
    }
}
