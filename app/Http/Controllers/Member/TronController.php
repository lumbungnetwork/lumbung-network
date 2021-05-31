<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Validator;
use Hash;
use App\Model\Member\Bank;
use RealRashid\SweetAlert\Facades\Alert;

class TronController extends Controller
{
    public function postSetTron(Request $request)
    {
        $user = Auth::user();

        // check if Tron Address already set
        if ($user->tron != null) {
            Alert::error('Oops', 'Alamat TRON sudah terpasang');
            return redirect()->back();
        }

        // input validation
        $validator = Validator::make($request->all(), [
            'tron' => ['required', 'string', 'size:34'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // record to user model
        $user->tron = $request->tron;
        $user->save();

        Alert::success('Berhasil!', 'Alamat TRON sudah terpasang');
        return redirect()->back();
    }

    public function postResetTron(Request $request)
    {
        $user = Auth::user();

        // 2fa input validation
        $validator = Validator::make($request->all(), [
            'password' => ['required', 'numeric', 'digits_between:4,9'],
        ]);

        if ($validator->fails()) {
            Alert::error('Oops', $validator->errors()->first());
            return redirect()->back();
        }

        // check 2fa
        $check = Hash::check($request->password, $user->{'2fa'});
        if (!$check) {
            Alert::error('Oops', 'Pin 2FA tidak tepat');
            return redirect()->back();
        }

        // Reset TRON address to null
        $user->tron = null;
        $user->save();

        Alert::success('Berhasil!', 'Alamat TRON sudah di Reset');
        return redirect()->back();
    }
    public function postSetBank(Request $request)
    {
        $user = Auth::user();

        // check if Bank already set
        if ($user->bank != null) {
            Alert::error('Oops', 'Bank sudah aktif');
            return redirect()->back();
        }

        // input validation
        $validator = Validator::make($request->all(), [
            'bank' => ['required', 'string', 'min:3', 'max:50'],
            'account_no' => ['required', 'numeric', 'digits_between:5,40'],
            'name' => ['required', 'string', 'min:3', 'max:70'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Check first_name against bank name, must be exactly same
        $full_name = explode(' ', $user->full_name);
        $first_name = $full_name[0];
        $r_full_name = explode(' ', $request->name);
        $r_first_name = $r_full_name[0];
        if ($first_name != $r_first_name) {
            Alert::error('Oops', 'Nama di Rekening tidak sama dengan Nama di akun');
            return redirect()->back();
        }

        // record to bank model
        $bank = new Bank;
        $bank->user_id = $user->id;
        $bank->bank = $request->bank;
        $bank->account_no = $request->account_no;
        $bank->name = $request->name;
        $bank->save();

        Alert::success('Berhasil!', 'No Rekening sudah terpasang');
        return redirect()->back();
    }

    public function postResetBank(Request $request)
    {
        $user = Auth::user();

        // check if Bank already reset
        if ($user->bank = null) {
            Alert::error('Oops', 'Bank sudah di Reset, silakan isi dengan data yang baru');
            return redirect()->back();
        }

        // 2fa input validation
        $validator = Validator::make($request->all(), [
            'password' => ['required', 'numeric', 'digits_between:4,9'],
        ]);

        if ($validator->fails()) {
            Alert::error('Oops', $validator->errors()->first());
            return redirect()->back();
        }

        // check 2fa
        $check = Hash::check($request->password, $user->{'2fa'});
        if (!$check) {
            Alert::error('Oops', 'Pin 2FA tidak tepat');
            return redirect()->back();
        }

        // Remove user's bank record
        Bank::where('user_id', $user->id)->delete();

        Alert::success('Berhasil!', 'Data Bank sudah di Reset, silakan isi dengan data baru');
        return redirect()->back();
    }
}
