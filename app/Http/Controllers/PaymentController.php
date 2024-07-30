<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Charge;
use App\Models\Payment;
use App\Models\Appointment;
use App\Mail\PaymentConfirmed;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PaymentController extends Controller
{

    public function index(){
        return view('index');
    }

    public function checkout(){
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $session = \Stripe\Checkout\Session::create([
           "line_items" =>[
            [
                'price_data'=>[
                    'currency' => 'usd',
                    'product_data' =>[
                        'name'=> 'Pay to confirm your appointment'
                    ],
                    'unit_amount' => 100,
                ],
                'quantity' => 1,
            ]
           ],
            'mode' =>'payment',
            'success_url' => route('success'),
            'cancel_url' => route('index'),
          

        ]);

        return redirect()->away($session->url);
    }

    public function success(){
        return view('index');
    }
    public function processPayment(Request $request)
    {
       
        // dd($request->all());
        $amount = (float) $request->amount;
        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $charge = Charge::create([
                'amount' => 100, 
                'currency' => 'usd',
                'source' => $request->stripeToken,
                'description' => 'Appointment Payment',
            ]);

            Log::info('Authenticated user:', ['user_id' => auth()->id()]);

            $payment = Payment::create([
                'user_id' => auth()->id(),
                'appointment_id' => $request->appointment_id,
                'amount' => $request->amount,
                'status' => 'completed'
            ]);

            $appointment = Appointment::find($request->appointment_id);
            $appointment->status = 'paid';
            $appointment->save();

          
            Mail::to(auth()->user()->email)->send(new PaymentConfirmed($appointment));

            return response()->json(['msg' => 'Payment successful and appointment confirmed']);
        } catch (\Exception $e) {
            return response()->json(['msg' => 'Payment failed', 'error' => $e->getMessage()]);
        }
    }
}
