<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class UserController extends Controller
{
    public function login()
    {
        return view('auth.user.login');
    }

    public function google()
    {
        return Socialite::driver('google')->redirect();
    }

    public function googleCallback()
    {
        $google = Socialite::driver('google')->user();

        $data = [
            'name' => $google->getName(),
            'email' => $google->getEmail(),
            'avatar' => $google->getAvatar(),
            'email_verified_at' => now(),
        ];

        $user = User::updateOrCreate([
            'email' => $data['email']
        ], $data);

        Auth::login($user);

        return redirect()->route('home');
    }
}
