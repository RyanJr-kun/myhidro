<?php

namespace App\Http\Controllers\pages;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AccountManagementController extends Controller
{
   public function index()
  {
    $users = User::all();
    return view('content.pages.pages-account-management',[
      'users' => $users,
    ]);
  }
}
