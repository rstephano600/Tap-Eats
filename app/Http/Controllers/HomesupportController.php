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
}
