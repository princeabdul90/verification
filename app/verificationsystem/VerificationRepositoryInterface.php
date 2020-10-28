<?php

namespace App\verificationsystem;



use Illuminate\Http\Request;

interface VerificationRepositoryInterface
{
    public function HandleRegister(Request $request);
    public function HandleLogin(Request $request);
    public function VerifyEmail($token);
}
