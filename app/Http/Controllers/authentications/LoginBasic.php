<?php

namespace App\Http\Controllers\authentications;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LoginBasic extends Controller
{
  public function index()
  {
    return view('content.authentications.auth-login-basic');
  }

  public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email-username'=>'required|email:dns',
            'password' => 'required'
        ]);

        $loginCredentials = [
            'email' => $credentials['email-username'],
            'password' => $credentials['password'],
        ];

        if(Auth::attempt($loginCredentials)){
            $request->session()->regenerate();

            return redirect()->intended('/');
        }

        return back()->with('loginError', 'login gagal, mohon periksa kembali email dan pasword anda.');

    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
