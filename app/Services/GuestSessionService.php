<?php

namespace App\Services;

use App\Models\GuestSession;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class GuestSessionService
{
    protected $sessionDuration = 24; // hours

    /**
     * Get or create a guest session
     */
    public function getOrCreateSession(Request $request)
    {
        $sessionToken = $request->cookie('guest_session_token');
        
        // Try to find existing valid session
        if ($sessionToken) {
            $session = GuestSession::where('session_token', $sessionToken)
                ->where('expires_at', '>', now())
                ->where('status', 'active')
                ->first();
                
            if ($session) {
                $this->updateSession($session, $request);
                return $session;
            }
        }
        
        // Create new session
        return $this->createSession($request);
    }

    /**
     * Create a new guest session
     */
    public function createSession(Request $request)
    {
        $locationData = $this->getLocationData($request->ip());
        
        $session = GuestSession::create([
            'session_token' => Str::random(64),
            'device_id' => $this->getDeviceId($request),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'latitude' => $locationData['latitude'] ?? null,
            'longitude' => $locationData['longitude'] ?? null,
            'location_address' => $locationData['address'] ?? null,
            'city' => $locationData['city'] ?? null,
            'country' => $locationData['country'] ?? null,
            'preferences' => [],
            'last_activity_at' => now(),
            'expires_at' => now()->addHours($this->sessionDuration),
            'status' => 'active'
        ]);

        return $session;
    }

    /**
     * Update existing session activity
     */
    public function updateSession(GuestSession $session, Request $request)
    {
        // Only update if last activity was more than 5 minutes ago
        if ($session->last_activity_at->diffInMinutes(now()) >= 5) {
            $session->update([
                'last_activity_at' => now(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        return $session;
    }

    /**
     * Generate or retrieve device ID from cookie
     */
    protected function getDeviceId(Request $request)
    {
        $deviceId = $request->cookie('device_id');
        
        if (!$deviceId) {
            $deviceId = Str::uuid()->toString();
        }
        
        return $deviceId;
    }

    /**
     * Get location data from IP address
     * Using ip-api.com (free tier)
     */
    protected function getLocationData($ipAddress)
    {
        // Skip for local/private IPs
        if ($this->isPrivateIP($ipAddress)) {
            return [];
        }

        try {
            $response = Http::timeout(3)->get("http://ip-api.com/json/{$ipAddress}");
            
            if ($response->successful()) {
                $data = $response->json();
                
                return [
                    'latitude' => $data['lat'] ?? null,
                    'longitude' => $data['lon'] ?? null,
                    'city' => $data['city'] ?? null,
                    'country' => $data['country'] ?? null,
                    'address' => ($data['city'] ?? '') . ', ' . ($data['regionName'] ?? '') . ', ' . ($data['country'] ?? ''),
                ];
            }
        } catch (\Exception $e) {
            \Log::warning('Failed to fetch location data: ' . $e->getMessage());
        }

        return [];
    }

    /**
     * Check if IP is private/local
     */
    protected function isPrivateIP($ip)
    {
        return in_array($ip, ['127.0.0.1', '::1', 'localhost']) || 
               filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false;
    }

    /**
     * Update session preferences
     */
    public function updatePreferences(GuestSession $session, array $preferences)
    {
        $currentPreferences = $session->preferences ?? [];
        $mergedPreferences = array_merge($currentPreferences, $preferences);
        
        $session->update(['preferences' => $mergedPreferences]);
        
        return $session;
    }

    /**
     * Add item to cart in session preferences
     */
    public function addToCart(GuestSession $session, array $item)
    {
        $preferences = $session->preferences ?? [];
        $cart = $preferences['cart'] ?? [];
        
        $cart[] = $item;
        $preferences['cart'] = $cart;
        
        $session->update(['preferences' => $preferences]);
        
        return $session;
    }

    /**
     * Add item to favorites in session preferences
     */
    public function addToFavorites(GuestSession $session, $itemId)
    {
        $preferences = $session->preferences ?? [];
        $favorites = $preferences['favorites'] ?? [];
        
        if (!in_array($itemId, $favorites)) {
            $favorites[] = $itemId;
            $preferences['favorites'] = $favorites;
            
            $session->update(['preferences' => $preferences]);
        }
        
        return $session;
    }

    /**
     * Clean up expired sessions
     */
    public function cleanupExpiredSessions()
    {
        return GuestSession::where('expires_at', '<', now())
            ->where('status', 'active')
            ->update(['status' => 'inactive']);
    }

    /**
     * Get session by token
     */
    public function getSessionByToken($token)
    {
        return GuestSession::where('session_token', $token)
            ->where('expires_at', '>', now())
            ->where('status', 'active')
            ->first();
    }
}