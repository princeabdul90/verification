<?php

namespace App\verificationsystem;

use App\Mail\VerificationEmail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VerificationRepository implements VerificationRepositoryInterface
{
    use RegisterRequest;


     /*
      * Method that handles user registration
       * */
    public function HandleRegister(Request $request) {
        $this->inputDataSanitization($request->all());

        $user = User::create([
            'name' => trim($request->input('name')),
            'email' => strtolower($request->input('email')),
            'password' => bcrypt($request->input('password')),
            'email_verification_token' => Str::random(32)
        ]);

        \Mail::to($user->email)->send(new VerificationEmail($user));

        session()->flash('message', 'Please check your email to activate your account');

        return redirect()->back();
    }


    /*
     * Method that handles user authentication login
     * */
    public function HandleLogin(Request $request){
        $this->loginDataSanitization($request->except(['_token']));

        $credentials = $request->except(['_token']);

        $user = User::where('email', $request->email)->first();

        if ($user->email_verified == 1) {

            if (auth()->attempt($credentials)) {

                $user = auth()->user();

                $user->last_login = Carbon::now();

                $user->save();

                return redirect()->route('home');
            }
        }

        session()->flash('message', 'Invalid Credentials');

        session()->flash('type', 'danger');

        return redirect()->back();
    }

    /*
     * Method ti verify user email with a token
     * */
    public function VerifyEmail($token = null)
    {
       if ($token == null) {
           session()->flash('message', 'Invalid Login attempt');

           return redirect()->route('login');
       }

       $user = User::where('email_verification_token', $token)->first();

       if ($user == null) {
           session()->flash('message', 'Invalid Login attempt');

           return redirect()->route('login');
       }
       $user->update([
          'email_verified' => 1,
          'email_verified_at' => Carbon::now(),
          'email_verification_token' => ''
       ]);

       session()->flash('message', 'Your account is activated, you can login now');

       return redirect()->route('login');

    }
}
