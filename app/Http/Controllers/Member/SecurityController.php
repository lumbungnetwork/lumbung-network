<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Hash;
use Validator;
use RealRashid\SweetAlert\Facades\Alert;

class SecurityController extends Controller
{
    public function postCreate2FA(Request $request)
    {
        $user = Auth::user();
        // check if 2fa already exist
        if ($user->{'2fa'} !== null) {
            Alert::error('Gagal', 'Pin 2FA sudah dibuat');
            return redirect()->back();
        }

        // input validation
        $validator = Validator::make($request->all(), [
            'password' => ['required', 'digits_between:4,9', 'confirmed'],
        ]);

        if ($validator->fails()) {
            Alert::error('Gagal', $validator->errors()->first());
            return redirect()->back();
        }

        // prevent easy guessed password
        $shame = '1111 1234 12345 123456 1234567 123123 1212 654321 4321 7777 8888 9999 0000';
        if (strpos($shame, $request->password) !== false) {
            Alert::error('Gagal', 'Pin yang anda buat terlalu mudah ditebak');
            return redirect()->back();
        }

        // record to user model
        $user->{'2fa'} = bcrypt($request->password);
        $user->save();

        Alert::success('Berhasil', '2FA Pin telah dibuat dan bisa dipergunakan.');
        return redirect()->back();
    }

    public function postEdit2FA(Request $request)
    {
        $user = Auth::user();

        // input validation
        $validator = Validator::make($request->all(), [
            'old_password' => ['required', 'digits_between:4,9'],
            'password' => ['required', 'digits_between:4,9', 'confirmed'],
        ]);

        if ($validator->fails()) {
            Alert::error('Gagal', $validator->errors()->first());
            return redirect()->back();
        }

        // Check old 2FA
        $check = Hash::check($request->old_password, $user->{'2fa'});
        if (!$check) {
            Alert::error('Gagal', 'Pin 2FA yang lama tidak tepat!');
            return redirect()->back();
        }

        // prevent easy guessed password
        $shame = '1111 1234 12345 123456 1234567 123123 1212 654321 4321 7777 8888 9999 0000';
        if (strpos($shame, $request->password) !== false) {
            Alert::error('Gagal', 'Pin yang anda buat terlalu mudah ditebak');
            return redirect()->back();
        }

        // record to user model
        $user->{'2fa'} = bcrypt($request->password);
        $user->save();

        Alert::success('Berhasil', '2FA Pin telah diganti dengan yang baru.');
        return redirect()->back();
    }

    public function postChangePassword(Request $request)
    {
        $user = Auth::user();

        // input validation
        $validator = Validator::make($request->all(), [
            'old_password' => ['required'],
            'password' => ['required', 'string', 'min:4', 'max:30', 'confirmed'],
        ]);

        if ($validator->fails()) {
            Alert::error('Gagal', $validator->errors()->first());
            return redirect()->back();
        }

        // Check old Password
        $check = Hash::check($request->old_password, $user->password);
        if (!$check) {
            Alert::error('Gagal', 'Password lama tidak tepat!');
            return redirect()->back();
        }

        // record to user model
        $user->password = bcrypt($request->password);
        $user->save();

        Alert::success('Berhasil', 'Password telah diganti dengan yang baru.');
        return redirect()->back();
    }
}
