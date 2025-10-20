<?php

namespace App\Http\Controllers\pages;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AccountManagementController extends Controller
{
   public function index()
  {
    return view('content.pages.pages-account-management',[
      'users' => User::latest()->paginate(10),
      'roles' => Role::all()
    ]);
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'email' => 'required|string|email|max:255|unique:users',
      'password' => 'required|string|min:5',
      'role_id' => 'required|exists:roles,id',
      'status' => 'required|boolean',
      'organisasi' => 'nullable|string|max:255',
      'nomer_telepon' => 'nullable|string|max:20',
      'alamat' => 'nullable|string',
    ]);

    $validated['password'] = Hash::make($validated['password']);

    User::create($validated);

    return redirect()->route('account-management')->with('success', 'User created successfully.');
  }

  public function update(Request $request, $id)
  {
    $user = User::findOrFail($id);

    $validated = $request->validate([
      'name' => 'required|string|max:255',
      'email' => 'required|string|email|max:255|unique:users,email,' . $id,
      'password' => 'nullable|string|min:5',
      'role_id' => 'required|exists:roles,id',
      'status' => 'required|boolean',
      'organisasi' => 'nullable|string|max:255',
      'nomer_telepon' => 'nullable|string|max:20',
      'alamat' => 'nullable|string',
    ]);

    if (!empty($validated['password'])) {
      $validated['password'] = Hash::make($validated['password']);
    } else {
      unset($validated['password']);
    }

    $user->update($validated);

    return redirect()->route('account-management')->with('success', 'User updated successfully.');
  }

  public function destroy($id)
  {
    $user = User::findOrFail($id);
    $user->delete();
    return redirect()->route('account-management')->with('success', 'User deleted successfully.');
  }
}
