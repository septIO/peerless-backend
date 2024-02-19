<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function BattleNetRedirect()
    {
        session()->put('return', url()->previous());
        return Socialite::driver('battlenet')->redirect();
    }

    public function BattleNetCallback()
    {
        $user = Socialite::driver('battlenet')->user();
        $user = User::createOrFirst([
            'id' => $user->getId(),
            'name' => $user->getNickname(),
        ]);

        \Auth::login($user);

        $user->tokens()->delete();

        $token = $user->createToken('api')->plainTextToken;
        $url = session()->pull('return', '/');
        $url = 'http://localhost:5173/?token=' . $token;
        return redirect($url);
    }

    public function login()
    {
        return redirect()->route('battlenet.redirect');
    }

    public function logout()
    {
        \Auth::user()->tokens()->delete();
        \Auth::logout();
        return redirect()->back();
    }
}
