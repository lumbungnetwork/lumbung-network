<?php

namespace App\Http\Controllers\FrontEnd;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Member;
use App\Model\Validation;
use Illuminate\Support\Facades\Mail;

class ReferalController extends Controller
{

    public function __construct()
    {
    }
    public function getAddReferalLink($code_referal)
    {
        $modelMember = new Member;
        $getDataSponsor = $modelMember->getUsers('username', $code_referal);
        if ($getDataSponsor == null) {
            return redirect()->to('page-not-found');
        }
        $dataValue = (object) array(
            'name' => null,
            'email' => null,
            'hp' => null
        );
        return view('member.sponsor.referal')
            ->with('dataValue', $dataValue)
            ->with('dataUser', $getDataSponsor);
    }

    public function postAddReferalLink(Request $request)
    {
        $modelValidasi = new Validation;
        $dataRequest = (object) array(
            'email' => $request->email,
            'hp' => $request->hp,
            'name' => $request->username,
            'username' => $request->username,
            'password' => $request->password,
            'repassword' => $request->repassword,
        );
        $canInsert = $modelValidasi->getCheckNewSponsor($dataRequest);
        $modelMember = new Member;
        $getCheck = $modelMember->getCheckUsercode($request->username);
        if ($getCheck->cekCode == 1) {
            $canInsert = (object) array('can' => false,  'pesan' => 'Username sudah terpakai');
        }
        if ($canInsert->can == false) {
            return redirect()->route('referalLink', $request->ref)
                ->with('message', $canInsert->pesan)
                ->with('messageclass', 'danger')
                ->with('email', $request->email)
                ->with('hp', $request->hp)
                ->with('username', $request->username);
        }
        $getDataSponsor = $modelMember->getUsers('username', $request->ref);
        if ($getDataSponsor == null) {
            return redirect()->to('page-not-found');
        }
        $dataInsertNewMember = array(
            'name' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'hp' => $request->hp,
            'username' => $request->username,
            'sponsor_id' => $getDataSponsor->id,
            'is_referal_link' => 1
        );
        $modelMember->getInsertUsers($dataInsertNewMember);
        $dataEmail = array(
            'name' => $request->name,
            'password' => $request->password,
            'hp' => $request->hp,
            'username' => $request->username,
            'email' => $request->email
        );
        $emailSend = $request->email;
        Mail::send('member.email.email', $dataEmail, function ($message) use ($emailSend) {
            $message->to($emailSend, 'Lumbung Network Registration')
                ->subject('Welcome to Lumbung Network');
        });
        return redirect()->route('areaLogin')
            ->with('message', 'Registrasi melalui referal link berhasil, silakan login')
            ->with('messageclass', 'success');
    }
}
