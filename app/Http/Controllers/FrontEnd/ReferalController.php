<?php

namespace App\Http\Controllers\FrontEnd;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Member;
use App\Model\Validation;
use Illuminate\Support\Facades\Mail;

class ReferalController extends Controller {

    public function __construct(){
        
    }
    public function getAddReferalLink($code_referal){
        $modelMember = New Member;
        $getDataSponsor = $modelMember->getUsers('user_code', $code_referal);
        if($getDataSponsor == null){
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
    
    public function postAddReferalLink(Request $request){
        $modelValidasi = New Validation;
        $dataRequest = (object) array(
            'email' => $request->email,
            'hp' =>$request->hp,
            'name' =>$request->user_code,
            'user_code' =>$request->user_code,
            'password' =>$request->password,
            'repassword' =>$request->repassword,
        );
        $canInsert = $modelValidasi->getCheckNewSponsor($dataRequest);
        $modelMember = New Member;
        $getCheck = $modelMember->getCheckUsercode($request->user_code);
        if($getCheck->cekCode == 1){
            $canInsert = (object) array('can' => false,  'pesan' => 'Username sudah terpakai');
        }
        if($canInsert->can == false){
            return redirect()->route('referalLink', $request->ref)
                    ->with('message', $canInsert->pesan)
                    ->with('messageclass', 'danger')
                    ->with('email', $request->email)
                    ->with('hp', $request->hp)
                    ->with('user_code', $request->user_code);
        }
        $getDataSponsor = $modelMember->getUsers('user_code', $request->ref);
        if($getDataSponsor == null){
            return redirect()->to('page-not-found');
        }
        $dataInsertNewMember = array(
            'name' => $request->user_code,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'hp' => $request->hp,
            'user_code' => $request->user_code,
            'sponsor_id' => $getDataSponsor->id,
            'is_referal_link' => 1
        );
        $modelMember->getInsertUsers($dataInsertNewMember);
        $dataEmail = array(
            'name' => $request->name,
            'password' => $request->password,
            'hp' => $request->hp,
            'user_code' => $request->user_code,
            'email' => $request->email
        );
        $emailSend = $request->email;
        Mail::send('member.email.email', $dataEmail, function($message) use($emailSend){
            $message->to($emailSend, 'Lumbung Network Registration')
                    ->subject('Welcome to Lumbung Network');
        });
        return redirect()->route('areaLogin')
                ->with('message', 'Registrasi melalui referal link berhasil, silakan login')
                ->with('messageclass', 'success');
    }


}
