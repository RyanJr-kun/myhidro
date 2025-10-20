<?php

namespace App\Http\Controllers\pages;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use RealRashid\SweetAlert\Facades\Alert;

class AccountSettingsAccount extends Controller
{
  public function index()
  {
    $user = Auth::user();
    return view('content.pages.account-settings',compact('user'));
  }

  public function update(Request $request)
    {
        $user = Auth::user();

        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'email:dns', Rule::unique('users')->ignore($user->id)],
            'organisasi' => 'nullable|string|max:255',
            'nomer_telepon' => ['nullable', 'min:9', 'max:14', Rule::unique('users')->ignore($user->id)],
            'alamat' => 'nullable|string|max:255',
            'upload' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:800', // Validasi file upload
        ]);

        $userData = [
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'organisasi' => $validatedData['organisasi'],
            'nomer_telepon' => $validatedData['nomer_telepon'],
            'alamat' => $validatedData['alamat'],
        ];

         if ($request->hasFile('upload')) {
            // Hapus gambar lama jika ada
            if ($user->img_user) {
                $oldImagePath = public_path('assets/img/users/' . $user->img_user);
                if (File::exists($oldImagePath)) {
                    File::delete($oldImagePath);
                }
            }

            // Simpan gambar baru
            $image = $request->file('upload');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('assets/img/users'), $imageName);

            // Perbarui nama gambar di database
            $user->img_user = $imageName;
        }

        $user->update($userData);

        Alert::success('Berhasil', 'Profil berhasil diperbarui.');
        return redirect()->route('account-settings');
    }
}
