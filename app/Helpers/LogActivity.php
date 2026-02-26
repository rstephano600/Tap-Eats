<?php

namespace App\Helpers;

use App\Models\ActivityLog;

class LogActivity
{
    public static function addToLog($activity)
    {
        ActivityLog::create([
            'user_id'    => auth()->check() ? auth()->id() : null,
            'activity'   => $activity,
            'url'        => request()->fullUrl(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'method'     => request()->method(),
        ]);
    }
}