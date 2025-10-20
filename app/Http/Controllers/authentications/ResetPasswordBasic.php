<?php

namespace App\Http\Controllers\authenticatons;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ResetPasswordBasic extends Controller
{
    public function showResetForm(Request $request, $token = null)
    {
        return view('content.authentications.auth-reset-password-basic')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }
}
