<?php

namespace App\Http\Controllers;

use App\verificationsystem\VerificationRepositoryInterface;
use Illuminate\Http\Request;


class VerificationSystemController extends Controller
{
    public function __construct(VerificationRepositoryInterface $verificationRepository)
    {
        $this->vR = $verificationRepository;
    }


    public function showRegisterForm(){
        return view('authentication.register');
    }
    public function showLoginForm(){
        return view('authentication.login');
    }

    public function verifyEmail($token){
        return $this->vR->VerifyEmail($token);
    }
    public function handleRegister(Request $request){
        return $this->vR->HandleRegister( $request);
    }
    public function handleLogin(Request $request){
        return $this->vR->HandleLogin($request);
    }





}
