<?php

// ============================================
// File: app/Helpers/GuestSessionHelper.php
// ============================================

namespace App\Helpers;

use App\Models\GuestSession;
use Illuminate\Http\Request;

class GuestSessionHelper
{
    /**
     * Get current guest session from request
     */
    public static function current()
    {
        return request()->attributes->get('guest_session');
    }

    /**
     * Check if guest session exists
     */
    public static function exists()
    {
        return self::current() !== null;
    }

    /**
     * Get session token
     */
    public static function token()
    {
        $session = self::current();
        return $session ? $session->session_token : null;
    }

    /**
     * Get session preferences
     */
    public static function preferences($key = null, $default = null)
    {
        $session = self::current();
        
        if (!$session) {
            return $default;
        }

        $preferences = $session->preferences ?? [];

        if ($key === null) {
            return $preferences;
        }

        return $preferences[$key] ?? $default;
    }

    /**
     * Set session preference
     */
    public static function setPreference($key, $value)
    {
        $session = self::current();
        
        if (!$session) {
            return false;
        }

        $preferences = $session->preferences ?? [];
        $preferences[$key] = $value;
        
        $session->update(['preferences' => $preferences]);
        
        return true;
    }
}