<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomesupportController extends Controller
{
    public function aboutus()
    {
    $stats = [
        'restaurants' => \App\Models\Supplier::verified()->active()->count(),
        'cities' => \App\Models\SupplierLocation::distinct('city')->count(),
        'users' => \App\Models\User::count(),
    ];

    return view('out.homepages.aboutus', compact('stats'));
    }

    public function contactus()
    {
    // You can hardcode these or pull from a 'Settings' table if you have one
    $contacts = [
        'email' => 'rstephano600@gmail.com',
        'phone' => '+255 657 856 790',
        'address' => '123 Kigoma Street, Kigoma, Tanzania',
        'whatsapp' => '255657856790', // Numbers only for the API link
        'socials' => [
            'facebook' => 'https://facebook.com/yourplatform',
            'instagram' => 'https://instagram.com/yourplatform',
            'twitter' => 'https://twitter.com/yourplatform',
        ]
    ];

    return view('out.homepages.contactus', compact('contacts'));
    }
}
