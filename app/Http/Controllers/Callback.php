<?php

namespace App\Http\Controllers;

use App\Models\pipeline;
use Illuminate\Http\Request;

class Callback extends Controller
{
    public function confirm_payment(Request $request)
    {
        // Get the JSON payload from the callback
        $callbackData = $request->input('Body.stkCallback');

        // Extract the desired values
        $merchantRequestID = $callbackData['MerchantRequestID'];
        $checkoutRequestID = $callbackData['CheckoutRequestID'];

        // Get the record where the has the above values from the pipeline model
        $record = pipeline::where('merchant_request_id', $merchantRequestID)
            ->where('checkout_request_id', $checkoutRequestID)
            ->first();

        // Update the payment_status to paid
        $record->payment_status = 'paid';
        $record->save();
    }
}
