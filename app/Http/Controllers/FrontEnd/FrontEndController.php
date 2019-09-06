<?php

namespace App\Http\Controllers\FrontEnd;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Model\Member;

class FrontEndController extends Controller {

    public function __construct(){
        
    }
    
    public function getIndex(){
        return view('frontend.index');
    }
    
    public function getForgotPassword(){
        $dataUser = Auth::user();
        if($dataUser != null){
            return redirect()->route('mainDashboard');
        }
        return view('member.forgot-passwd');
    }
    
    public function postForgotPassword(Request $request){
        //validasi disini
        $dataUser = Auth::user();
        if($dataUser != null){
            return redirect()->route('mainDashboard');
        }
        $modelmember = New Member;
        $getData = $modelmember->getUsers('user_code', $request->user_id);
        if($getData == null){
            return redirect()->route('forgotPasswd')
                    ->with('message', 'Data tidak ditemukan')
                    ->with('messageclass', 'danger');
        }
        $uniqueCode = uniqid();
        $rand = rand(10, 99);
        $code = $rand.$uniqueCode.$getData->user_code;
        $dataEmail = array(
            'dataLink' => 'auth/passwd/'.$code,
            'email' => $getData->email,
        );
        $emailSend = $getData->email;
        Mail::send('member.email.forgot', $dataEmail, function($message) use($emailSend){
            $message->to($emailSend, 'Lumbung Reset Password')
                    ->subject('Reset Password');
        });
        return redirect()->route('forgotPasswd')
                    ->with('message', 'Email verifikasi forgot password terkirim')
                    ->with('messageclass', 'success');
    }
    
    public function getAuthPassword($code, $email){
        $dataUser = Auth::user();
        if($dataUser != null){
            return redirect()->route('mainDashboard');
        }
        $getUserCode = substr($code, 15);
        $modelMember = New Member;
        $getData = $modelMember->getUsersCodeEmail($getUserCode, $email);
        if($getData == null){
            return redirect()->route('areaLogin')
                    ->with('message', 'Data tidak ditemukan')
                    ->with('messageclass', 'danger');
        }
        return view('member.auth-passwd')
                ->with('hiddenCode', $code)
                ->with('data', $getData);
    }
    
    public function postAuthPassword(Request $request){
        $dataUser = Auth::user();
        if($dataUser != null){
            return redirect()->route('mainDashboard');
        }
        if($request->password == null){
            return redirect()->route('passwdauth', array($request->authCode, $request->emailCheck))
                    ->with('message', 'Password harus diisi')
                    ->with('messageclass', 'danger');
        }
        if($request->repassword == null){
            return redirect()->route('passwdauth', array($request->authCode, $request->emailCheck))
                    ->with('message', 'Ketik ulang password harus diisi')
                    ->with('messageclass', 'danger');
        }
        if($request->password != $request->repassword){
            return redirect()->route('passwdauth', array($request->authCode, $request->emailCheck))
                    ->with('message', 'Password dan ketik ulang password tidak sama')
                    ->with('messageclass', 'danger');
        }
        if(strlen($request->password) < 6){
            return redirect()->route('passwdauth', array($request->authCode, $request->emailCheck))
                    ->with('message', 'Password terlalu pendek, minimal 6 karakter')
                    ->with('messageclass', 'danger');
        }
        $modelMember = New Member;
        $dataUpdate = array(
            'password' => bcrypt($request->password)
        );
        $modelMember->getUpdateUsers('user_code', $request->userID, $dataUpdate);
        return redirect()->route('areaLogin')
                    ->with('message', 'Data Password telah anda reset. silakan login')
                    ->with('messageclass', 'success');
    }


}
