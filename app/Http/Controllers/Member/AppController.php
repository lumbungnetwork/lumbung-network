<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Support\Facades\Validator;
use App\Model\Member\MasterSales;
use App\Model\Bonus;
use App\Model\Member\LMBreward;
use App\Model\Member\EidrBalance;
use App\Model\Member\EidrBalanceTransaction;
use App\Http\Controllers\TelegramBotController;
use RealRashid\SweetAlert\Facades\Alert;
use DB;

class AppController extends Controller
{
    public function getHome()
    {
        $user = Auth::user();

        // get user's current month spending
        $MasterSales = new MasterSales;
        $spending = $MasterSales->getMemberSpending($user->id, date('m'), date('Y'));

        // get dividend pool and user's staked LMB
        $Bonus = new Bonus;
        $LMBDividendPool = $Bonus->getLMBDividendPool();
        $userStakedLMB = $Bonus->getUserStakedLMB($user->id);

        return view('member.app.home')
            ->with('title', 'Home')
            ->with(compact('user'))
            ->with(compact('LMBDividendPool'))
            ->with(compact('userStakedLMB'))
            ->with(compact('spending'));
    }

    public function getClaimShoppingReward()
    {
        $user = Auth::user();

        // Get Net LMB Reward ($is_store = 0 for Shopping Reward)
        $LMBreward = new LMBreward;
        $netLMBReward = $LMBreward->getUserNetLMBReward($user->id, 0);
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
        $onlyUser  = array(10);
        if (!in_array($user->user_type, $onlyUser)) {
            Alert::error('Oops!', 'Fitur Staking hanya untuk Premium Member!');
            return redirect()->route('member.home');
        }

        if ($user->expired_at < date('Y-m-d', strtotime('Today +1 minute'))) {
            Alert::error('Oops!', 'Keanggotaan anda sudah EXPIRED!');
            return redirect()->route('member.home');
        }

        $modelBonus = new Bonus;
        $LMBDividendPool = $modelBonus->getLMBDividendPool();
        $totalStakedLMB = $modelBonus->getStakedLMB();
        $userStakedLMB = $modelBonus->getUserStakedLMB($user->id);
        $userDividend = $modelBonus->getUserDividend($user->id);
        $userUnstaking = $modelBonus->getUserUnstakeProgress($user->id);

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

    public function getTron()
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
        $data = EidrBalanceTransaction::where('user_id', $user->id)->orderByDesc('created_at')->paginate(10);
        return view('member.app.deposit')
            ->with(compact('data'))
            ->with('title', 'Deposit');
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
        $unique_digits = rand(39, 148);
        $check = EidrBalanceTransaction::where('created_at', '>=', date('Y-m-d 00:00:00', strtotime('Today')))->where('unique_digits', $unique_digits)->exists();
        do {
            $unique_digits = rand(39, 148);
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
}
