<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke()
    {
        return view('admin.dashboard', [
            'stats' => [
                'users' => User::count(),
                'services' => DB::table('services')->count(),
                'priceItems' => DB::table('service_items')->count(),
                'bookings' => DB::table('bookings')->count(),
            ],
            'recentUsers' => User::latest()->take(5)->get(),
        ]);
    }
}
