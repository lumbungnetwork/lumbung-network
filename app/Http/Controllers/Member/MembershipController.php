<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Model\Member\LMBdividend;
use Illuminate\Http\Request;
use App\Model\Member\EidrBalance;
use App\User;
use Auth;
use Cache;
use Hash;
use Validator;
use RealRashid\SweetAlert\Facades\Alert;

class MembershipController extends Controller
{
    public function getUpgradeMembership()
    {
        $user = Auth::user();

        if ($user->user_type == 10) {
            Alert::success('Berhasil', 'Akun anda telah di-upgrade menjadi Premium');
            return redirect()->route('member.account');
        }

        if ($user->{'2fa'} == null) {
            Alert::warning('Tunggu dulu...', 'Anda perlu membuat Pin 2FA terlebih dahulu');
            return redirect()->route('member.security');
        }
        $EidrBalance = new EidrBalance;
        $netBalance = $EidrBalance->getUserNeteIDRBalance($user->id);

        return view('member.app.account.membership_upgrade')
            ->with('title', 'Premium Membership')
            ->with(compact('netBalance'))
            ->with(compact('user'));
    }

    public function postUpgradeMembership(Request $request)
    {
        $user = Auth::user();

        // validate input
        $validator = Validator::make($request->all(), [
            'password' => 'required|numeric|digits_between:4,9',
            'sponsor_id' => 'nullable|integer|exists:users,id'
        ]);

        if ($validator->fails()) {
            Alert::error('Error', $validator->errors()->first());
            return redirect()->back();
        }

        // Check 2FA
        $check = Hash::check($request->password, $user->{'2fa'});
        if (!$check) {
            Alert::error('Error', 'Pin 2FA salah!');
            return redirect()->back();
        }

        // Check sponsor
        $sponsor_id = $user->sponsor_id;
        if ($request->filled('sponsor_id')) {
            $sponsor_check = User::where('id', $request->sponsor_id)->where('member_type', 1)->exists();
            if ($sponsor_check) {
                $sponsor_id = $request->sponsor_id;
            }
        }

        // Use atomic lock to prevent race condition
        $lock = Cache::lock('upgrade_' . $user->id, 60);

        if ($lock->get()) {
            // Deduct eIDR
            $EidrBalance = new EidrBalance;
            $netBalance = $EidrBalance->getUserNeteIDRBalance($user->id);
            $remaining = $netBalance - 100000;
            if ($remaining < 0) {
                $lock->release();
                Alert::error('Error', 'Saldo eIDR tidak mencukupi!');
                return redirect()->back();
            }

            $balance = new EidrBalance;
            $balance->user_id = $user->id;
            $balance->amount = 100000;
            $balance->type = 0;
            $balance->source = 0;
            $balance->note = 'Upgrade to Premium Membership';
            $balance->save();

            // Change User model
            $user->sponsor_id = $sponsor_id;
            $user->member_type = 1;
            $user->user_type = 10;
            $user->premium_at = date('Y-m-d H:i:s');
            $user->expired_at = date('Y-m-d 00:00:00', strtotime('+365 days'));
            $user->save();

            if ($sponsor_id != 5) {
                // Send Referral Bonus
                $bonus = new EidrBalance;
                $bonus->user_id = $sponsor_id;
                $bonus->amount = 19750; //after tax
                $bonus->type = 1;
                $bonus->source = 2;
                $bonus->tx_id = $user->id;
                $bonus->note = 'Bonus Sponsor dari ' . $user->username;
                $bonus->save();

                // Add sponsors count
                $sponsor = User::find($sponsor_id);
                $sponsor->total_sponsor += 1;
                $sponsor->save();
            }

            // Create LMBdividend
            $dividend = new LMBdividend;
            $dividend->amount = 19750;
            $dividend->type = 4;
            $dividend->status = 1;
            $dividend->source_id = $user->id;
            $dividend->save();

            $lock->release();

            Alert::success('Berhasil', 'Akun anda telah di-upgrade menjadi Premium');
            return redirect()->route('member.account');
        }

        Alert::error('Gagal', 'Proses Upgrade Gagal');
        return redirect()->route('member.account');
    }
}
