<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Camp;
use App\Models\Checkout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Camp $camp)
    {
        return view('checkout/create', [
            'camp' => $camp
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Camp $camp)
    {
        // Mapping request data
        $data = $request->all();
        $data['user_id'] = Auth::id();
        $data['camp_id'] = $camp->id;
        $data['name'] = $data['name'];
        $data['email'] = $data['email'];
        $data['occupation'] = $data['occupation'];
        $data['card_number'] = $data['card_number'];
        $data['expired'] = $data['expired'];
        $data['cvc'] = $data['cvc'];

        // Update User Data
        $user = Auth::user();
        $user->email = $data['email'];
        $user->name = $data['name'];
        $user->occupation = $data['occupation'];
        $user->save();

        // Create Checkout
        $checkout = Checkout::create($data);

        return redirect()->route('checkout.success');
    }

    /**
     * Display the specified resource.
     */
    public function show(Checkout $checkout)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Checkout $checkout)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Checkout $checkout)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Checkout $checkout)
    {
        //
    }

    public function success()
    {
        return view('checkout/success');
    }
}
