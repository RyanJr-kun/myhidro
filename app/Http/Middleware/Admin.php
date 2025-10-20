<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Role;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
     public function handle(Request $request, Closure $next): Response
    {
        $role_id = $request->user()->role_id;
        $adminId = Role::where('nama','admin')->first()->id;

        if ($role_id != $adminId) {
            Alert::error('Gagal','Anda Tidak Memiliki Akses Ke Halaman Ini!');
            return redirect()->route('dashboard-analytics');
        }
        return $next($request);
    }
}
