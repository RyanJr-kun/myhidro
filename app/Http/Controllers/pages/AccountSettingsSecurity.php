<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class AccountSettingsSecurity extends Controller
{
  public function index()
  {
    return view('content.pages.account-settings-security');
  }

  public function updatePassword(Request $request)
  {
    # Validasi
    $request->validate([
      'current_password' => 'required',
      'new_password' => ['required', 'string', Password::min(5)->mixedCase()->symbols(), 'confirmed', 'different:current_password'],
    ]);

    # Cek apakah password lama cocok
    if (!Hash::check($request->current_password, auth()->user()->password)) {
      return back()->with("error", "Password lama tidak cocok!");
    }

    # Update password baru
    \App\Models\User::whereId(auth()->user()->id)->update([
      'password' => Hash::make($request->new_password)
    ]);

    return back()->with("status", "Password berhasil diubah!");
  }

}
