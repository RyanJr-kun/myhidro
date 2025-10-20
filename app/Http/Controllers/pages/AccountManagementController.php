<?php

namespace App\Http\Controllers\pages;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AccountManagementController extends Controller
{
   public function index()
  {
    return view('content.pages.pages-account-management',[
      'users' => User::latest()->paginate(10),
      'roles' => Role::all()
    ]);
  }
}
