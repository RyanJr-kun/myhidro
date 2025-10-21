<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyDeviceToken
{
    public function handle(Request $request, Closure $next)
    {
        // Ambil token dari header ATAU dari query parameter
        $token = $request->header('X-Device-Token') ?? $request->query('device_token');

        if ($token !== 'hidroponik_device_001') {
            return response()->json([
                'success' => false,
                'message' => 'Invalid device token'
            ], 401); // 401 Unauthorized
        }

        return $next($request);
    }
}
