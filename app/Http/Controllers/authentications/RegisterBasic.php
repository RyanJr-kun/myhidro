<?php

namespace App\Http\Controllers\authentications;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class RegisterBasic extends Controller
{
  public function index()
  {
    return view('content.authentications.auth-register-basic');
  }

  public function store(Request $request)
  {
    $request->validate([
      'username' => 'required|string|max:255|unique:users,name',
      'email' => 'required|string|email:rfc,dns|max:255|unique:users',
      'password' => 'required|string|min:8',
    ]);

    $pengawasRole = Role::where('nama', 'pengawas')->first();
    if (!$pengawasRole) {
        Alert::error(['email' => 'Role "pengawas" tidak ditemukan. Silakan hubungi administrator.']);
        return back()->withInput();
    }

    $user = User::create([
      'name' => $request->username,
      'email' => $request->email,
      'password' => Hash::make($request->password),
      'role_id' => $pengawasRole->id,
    ]);

    Auth::login($user);
    Alert::success('Berhasil', 'Selamat datang di My-Hidro, aplikasi pengawas tanaman cerdas anda.');
    return redirect('/');
  }
}
