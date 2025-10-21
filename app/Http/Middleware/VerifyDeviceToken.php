<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyDeviceToken
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('X-Device-Token') ?? $request->query('device_token');

        if ($token !== 'sijul') {
            return response()->json(['success' => false, 'message' => 'Invalid device token'], 401);
        }

        return $next($request);
    }
}
