<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Validator;
use Hash;
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
}
