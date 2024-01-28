<?php

namespace App\Http\Controllers;

use App\Models\Checkout;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function dashboard()
    {
        $checkouts = Checkout::with('Camp')->where('user_id', auth()->id())->get();
        return view('user.dashboard', [
            'checkouts' => $checkouts
        ]);
    }
}
