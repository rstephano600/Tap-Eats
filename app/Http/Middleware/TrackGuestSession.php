<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\GuestSession;
use Carbon\Carbon;

class TrackGuestSession
{
    public function handle(Request $request, Closure $next)
    {
        $sessionToken = $request->cookie('guest_session_token') 
            ?? Str::random(64);

        $guestSession = GuestSession::where('session_token', $sessionToken)->first();

        if (!$guestSession) {
            $guestSession = GuestSession::create([
                'session_token'   => $sessionToken,
                'ip_address'      => $request->ip(),
                'user_agent'      => substr($request->userAgent(), 0, 500),
                'last_activity_at'=> now(),
                'expires_at'      => now()->addDays(7),
                'status'          => 'active',
            ]);
        } else {
            $guestSession->update([
                'last_activity_at' => now(),
            ]);
        }

        // Attach token to request
        $request->attributes->set('guest_session', $guestSession);

        $response = $next($request);

        // Persist cookie
        return $response->cookie(
            'guest_session_token',
            $sessionToken,
            60 * 24 * 7 // 7 days
        );
    }
}
