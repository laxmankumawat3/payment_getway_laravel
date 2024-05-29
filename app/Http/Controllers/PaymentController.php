<?php
// app/Http/Controllers/PaymentController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Razorpay\Api\Api;

class PaymentController extends Controller
{
    private $razorpayId;
    private $razorpayKey;

    public function __construct()
    {
        $this->razorpayId = env('RAZORPAY_KEY');
        $this->razorpayKey = env('RAZORPAY_SECRET');
    }

    public function checkout()
    {
        // Create an instance of Razorpay API
        $api = new Api($this->razorpayId, $this->razorpayKey);
        
        // Create an order
        $orderData = [
            'receipt'         => 'rcptid_11',
            'amount'          => 50000, // amount in the smallest currency unit
            'currency'        => 'INR',
            'payment_capture' => 1 // auto capture
        ];

        $order = $api->order->create($orderData);

        // Pass the order and Razorpay ID to the view
        return view('payment', ['order' => $order, 'razorpayId' => $this->razorpayId]);
    }

    public function processPayment(Request $request)
    {
        // Handle payment processing logic
        $api = new Api($this->razorpayId, $this->razorpayKey);
       
        try {
            $attributes = [
                'razorpay_order_id' => $request->input('razorpay_order_id'),
                'razorpay_payment_id' => $request->input('razorpay_payment_id'),
                'razorpay_signature' => $request->input('razorpay_signature')
            ];

            $api->utility->verifyPaymentSignature($attributes);

            return view('success');
        } catch (\Exception $e) {
            return redirect()->route('payment.failure');
        }
    }

    public function paymentSuccess(Request $request)
    {
        return view('success');
    }

    public function paymentFailure()
    {
        return view('failure');
    }
}
