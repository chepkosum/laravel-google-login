<?php

namespace App\Http\Controllers;

use App\Models\User;
use Laravel\Socialite\Facades\Socialite;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GoogleController extends Controller
{
    public function redirectToGoogle(){
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallBack(){

        $user = Socialite::driver('google')->user();
        $findUser = User::where('google_id', $user->id)->first();
        if($findUser){
            Auth::login($findUser);
            return redirect()->intended('dashboard');
        } else{
            $user = User::updateOrCreate([
                'email' => $user->email,
            ], [
                'name' => $user->name,
                'google_id' => $user->id,
                'password' => encrypt('987654321'),
            ]);

            Auth::login($user);


        }

        return redirect()->intended('dashboard');
    }
}
