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
        $code = $modelMember->getCountLastMember();
        return view('member.sponsor.referal')
                ->with('dataValue', $dataValue)
                ->with('kode', $code)
                ->with('dataUser', $getDataSponsor);
    }
    
    public function postAddReferalLink(Request $request){
        $modelValidasi = New Validation;
        $canInsert = $modelValidasi->getCheckNewSponsor($request);
        $modelMember = New Member;
        $getCheck = $modelMember->getCheckEmailPhoneUsercode($request->email, $request->hp, $request->user_code);
        if($getCheck->cekEmail > 3){
            $canInsert = (object) array('can' => false,  'pesan' => 'Email Sudah terpakai lebih dari 3 kali');
        }
        if($getCheck->cekHP > 3){
            $canInsert = (object) array('can' => false,  'pesan' => 'No HP Sudah terpakai lebih dari 3 kali');
        }
        if($getCheck->cekCode == 1){
            $canInsert = (object) array('can' => false,  'pesan' => 'Username sudah terpakai');
        }
        if($canInsert->can == false){
            $dataValue = (object) array(
                'name' => $request->name,
                'email' => $request->email,
                'hp' => $request->hp
            );
            return redirect()->route('referalLink', $request->ref)
                    ->with('message', $canInsert->pesan)
                    ->with('messageclass', 'danger')
                    ->with('dataValue', $dataValue);
        }
        $getDataSponsor = $modelMember->getUsers('user_code', $request->ref);
        if($getDataSponsor == null){
            return redirect()->to('page-not-found');
        }
        
        $dataInsertNewMember = array(
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'hp' => $request->hp,
            'user_code' => $request->user_code,
            'sponsor_id' => $getDataSponsor->id,
            'is_referal_link' => 1
            
        );
        $modelMember->getInsertUsers($dataInsertNewMember);
        $total_sponsor = $getDataSponsor->total_sponsor + 1;
        $dataUpdateSponsor = array(
            'total_sponsor' => $total_sponsor,
        );
        $modelMember->getUpdateUsers('id', $getDataSponsor->id, $dataUpdateSponsor);
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
