<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Checkout;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $checkouts = Checkout::with('Camp')->where('user_id', auth()->id())->get();
        return view('user.dashboard', [
            'checkouts' => $checkouts
        ]);
    }
}
