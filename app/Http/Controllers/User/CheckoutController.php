<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Checkout\Store;
use App\Models\Camp;
use App\Models\Checkout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\Checkout\AfterCheckout;
use App\Models\Discount;
use Exception;
use Illuminate\Support\Str;
use Midtrans;

class CheckoutController extends Controller
{

    public function __construct()
    {
        Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION');
        Midtrans\Config::$isSanitized = env('MIDTRANS_IS_SANITIZED');
        Midtrans\Config::$is3ds = env('MIDTRANS_IS_3DS');
    }

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
    public function create(Request $request, Camp $camp)
    {
        if ($camp->isRegistered) {
            $request->session()->flash('error', "You already registered on {$camp->title} camp.");
            return redirect()->route('user.dashboard');
        }
        return view('checkout/create', [
            'camp' => $camp
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Store $request, Camp $camp)
    {
        // Mapping request data
        $data = $request->all();
        $data['user_id'] = Auth::id();
        $data['camp_id'] = $camp->id;
        $data['name'] = $data['name'];
        $data['email'] = $data['email'];
        $data['occupation'] = $data['occupation'];
        $data['phone'] = $data['phone'];
        $data['address'] = $data['address'];

        // Update User Data
        $user = Auth::user();
        $user->email = $data['email'];
        $user->name = $data['name'];
        $user->occupation = $data['occupation'];
        $user->save();

        // Check Discount
        if ($data['discount']) {
            $discount = Discount::where('code', $data['discount'])->first();
            $data['discount_id'] = $discount->id;
            $data['discount_precentage'] = $discount->percentage;
        }

        // Create Checkout
        $checkout = Checkout::create($data);

        $this->getSnapRedirect($checkout);

        // Send Email
        Mail::to(Auth::user()->email)->send(new AfterCheckout($checkout));

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

    // Midtarns Handler

    public function getSnapRedirect(Checkout $checkout)
    {
        $orderId = $checkout->id . '-' . Str::random(5);
        $price = $checkout->Camp->price * 1000;

        $checkout->midtrans_booking_code = $orderId;

        $items_details[] = [
            'id' => $orderId,
            'price' => $price,
            'quantity' => 1,
            'name' => "Payment for {$checkout->Camp->title}",
        ];

        $discountPrice = 0;
        if($checkout->Discount) {
            $discountPrice = $price * $checkout->Discount->percentage / 100;
            $items_details[] = [
                'id' => $checkout->Discount->code,
                'price' => -$discountPrice,
                'quantity' => 1,
                'name' => "Discount {$checkout->Discount->name} ({$checkout->Discount->percentage}%)",
            ];
        }

        $total = $price - $discountPrice;
        $transaction_details = [
            'order_id' => $orderId,
            'gross_amount' => $total,
        ];

        $userData = [
            'first_name' => $checkout->User->name,
            'last_name' => "",
            'address' => $checkout->User->address,
            'city' => "",
            'postal_code' => "",
            'phone' => $checkout->User->phone,
            'country_code' => 'IDN'
        ];

        $customer_details = [
            'first_name' => $checkout->User->name,
            'last_name' => "",
            'email' => $checkout->User->email,
            'phone' => $checkout->User->phone,
            'billing_address' => $userData,
            'shipping_address' => $userData
        ];

        $midtrans_params = [
            'transaction_details' => $transaction_details,
            'item_details' => $items_details,
            'customer_details' => $customer_details
        ];

        try {
            //Get Snap Payment Url

            $paymentUrl = Midtrans\Snap::createTransaction($midtrans_params)->redirect_url;
            $checkout->midtrans_url = $paymentUrl;
            $checkout->total = $total;
            $checkout->save();

            return $paymentUrl;
        } catch (Exception $e) {
            return false;
        }
    }

    public function midtransCallback(Request $request)
    {
        $notif = $request->method() == 'POST' ? new Midtrans\Notification() : Midtrans\Transaction::status($request->order_id);

        $transaction_status = $notif->transaction_status;
        $fraud = $notif->fraud_status;

        $checkout_id = explode('-', $notif->order_id)[0];

        $checkout = Checkout::find($checkout_id);

        if($transaction_status == 'capture') {
            if($fraud == 'challenge') {
                // TODO set payment status in merchant's database to 'challenge'
                $checkout->payment_status = 'pending';
            } else if ($fraud == 'accept') {
                // TODO set payment status in merchant's database to 'success'
                $checkout->payment_status = 'paid';
            }
        } else if($transaction_status == 'cancel') {
            if($fraud == 'challenge') {
                // TODO set payment status in merchant's database to 'failure'
                $checkout->payment_status = 'failed';
            } else if ($fraud == 'accept') {
                // TODO set payment status in merchant's database to 'failure'
                $checkout->payment_status = 'failed';
            }
        } else if ($transaction_status == 'settlement') {
            // TODO set payment status in merchant's database to 'success'
            $checkout->payment_status = 'paid';
        } else if ($transaction_status == 'deny') {
            // TODO you can ignore 'deny', because most of the time it allows payment retries
            $checkout->payment_status = 'failed';
        } elseif($transaction_status == 'pending') {
            // TODO set payment status in merchant's database to 'pending' / waiting payment
            $checkout->payment_status = 'pending';
        } else if ($transaction_status == 'expire') {
            // TODO set payment status in merchant's database to 'failure'
            $checkout->payment_status = 'failed';
        }

        $checkout->save();

        return view('checkout.success');
    }
}
