<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\GuestSession;
use Illuminate\Http\Request;
use Carbon\Carbon;

class GuestSessionController extends Controller
{
    public function index()
    {
        $guestSessions = GuestSession::with(['creator', 'updater'])
            ->orderBy('last_activity_at', 'desc')
            ->paginate(15);

        return view('in.guest-sessions.index', compact('guestSessions'));
    }

    public function create()
    {
        return view('in.guest-sessions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'device_id' => 'nullable|string|max:100',
            'ip_address' => 'required|ip|max:45',
            'user_agent' => 'nullable|string|max:500',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'location_address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'preferences' => 'nullable|json',
            'last_activity_at' => 'required|date',
            'expires_at' => 'required|date|after:last_activity_at',
            'status' => 'required|in:active,inactive,locked,deleted'
        ]);

        // Parse preferences if it's a JSON string
        if (isset($validated['preferences'])) {
            $validated['preferences'] = json_decode($validated['preferences'], true);
        }

        $guestSession = GuestSession::create($validated);

        return redirect()
            ->route('guest-sessions.index')
            ->with('success', 'Guest session created successfully.');
    }

    public function show(GuestSession $guestSession)
    {
        $guestSession->load(['creator', 'updater']);
        return view('in.guest-sessions.show', compact('guestSession'));
    }

    public function edit(GuestSession $guestSession)
    {
        return view('in.guest-sessions.edit', compact('guestSession'));
    }



    public function update(Request $request)
    {
        $sessionToken = $request->cookie('guest_session_token');

        if (!$sessionToken) {
            return response()->json(['error' => 'No session'], 400);
        }

        $guestSession = GuestSession::where('session_token', $sessionToken)->first();

        if ($guestSession) {
            $guestSession->update([
                'device_id' => $request->device_id,
                'latitude'  => $request->latitude,
                'longitude' => $request->longitude,
            ]);
        }

        return response()->json(['status' => 'ok']);
    }


    public function destroy(GuestSession $guestSession)
    {
        $guestSession->delete();

        return redirect()
            ->route('guest-sessions.index')
            ->with('success', 'Guest session deleted successfully.');
    }
}