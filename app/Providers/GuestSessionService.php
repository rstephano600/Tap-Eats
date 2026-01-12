<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\GuestSessionService;
use Symfony\Component\HttpFoundation\Response;

class TrackGuestSession
{
    protected $guestSessionService;

    public function __construct(GuestSessionService $guestSessionService)
    {
        $this->guestSessionService = $guestSessionService;
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip tracking for authenticated users if desired
        // Uncomment the following lines to skip tracking for logged-in users
        // if (auth()->check()) {
        //     return $next($request);
        // }

        // Skip tracking for certain routes (API, admin, etc.)
        $skipRoutes = [
            'admin/*',
            'api/*',
            '_debugbar/*',
        ];

        foreach ($skipRoutes as $pattern) {
            if ($request->is($pattern)) {
                return $next($request);
            }
        }

        // Get or create guest session
        $guestSession = $this->guestSessionService->getOrCreateSession($request);

        // Store session in request for use in controllers
        $request->attributes->set('guest_session', $guestSession);

        // Get response
        $response = $next($request);

        // Set cookies for session tracking
        $response->withCookie(cookie(
            'guest_session_token',
            $guestSession->session_token,
            60 * 24 * 30, // 30 days
            '/',
            null,
            false, // secure (set to true in production with HTTPS)
            true   // httpOnly
        ));

        $response->withCookie(cookie(
            'device_id',
            $guestSession->device_id,
            60 * 24 * 365, // 1 year
            '/',
            null,
            false, // secure
            true   // httpOnly
        ));

        return $response;
    }
}