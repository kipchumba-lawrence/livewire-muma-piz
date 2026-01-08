<?php

namespace App\Http\Controllers;

use App\Models\pipeline;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class Callback extends Controller
{
    public function confirm_payment(Request $request)
    {
        // Log the full callback for debugging
        Log::channel('mpesa')->info('M-Pesa callback received', [
            'payload' => $request->all()
        ]);

        try {
            // Get the JSON payload from the callback
            $callbackData = $request->input('Body.stkCallback');

            if (!$callbackData) {
                Log::channel('mpesa')->error('M-Pesa callback: Invalid payload structure');
                return response()->json(['status' => 'error', 'message' => 'Invalid payload'], 400);
            }

            // Extract the desired values
            $merchantRequestID = $callbackData['MerchantRequestID'] ?? null;
            $checkoutRequestID = $callbackData['CheckoutRequestID'] ?? null;
            $resultCode = $callbackData['ResultCode'] ?? null;
            $resultDesc = $callbackData['ResultDesc'] ?? null;

            Log::channel('mpesa')->info('M-Pesa callback parsed', [
                'merchant_request_id' => $merchantRequestID,
                'checkout_request_id' => $checkoutRequestID,
                'result_code' => $resultCode,
                'result_desc' => $resultDesc
            ]);

            // Get the record with the above values from the pipeline model
            $record = pipeline::where('merchant_request_id', $merchantRequestID)
                ->where('checkout_request_id', $checkoutRequestID)
                ->first();

            if (!$record) {
                Log::channel('mpesa')->warning('M-Pesa callback: No matching record found', [
                    'merchant_request_id' => $merchantRequestID,
                    'checkout_request_id' => $checkoutRequestID
                ]);
                return response()->json(['status' => 'error', 'message' => 'Record not found'], 404);
            }

            // Check if payment was successful (ResultCode 0 means success)
            if ($resultCode == 0) {
                $record->payment_status = 'paid';
                Log::channel('mpesa')->info('M-Pesa payment confirmed', [
                    'pipeline_id' => $record->id,
                    'customer_name' => $record->customer_name,
                    'amount' => $record->paid_amount
                ]);
            } else {
                $record->payment_status = 'failed';
                Log::channel('mpesa')->warning('M-Pesa payment failed', [
                    'pipeline_id' => $record->id,
                    'result_code' => $resultCode,
                    'result_desc' => $resultDesc
                ]);
            }

            $record->save();

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            Log::channel('mpesa')->error('M-Pesa callback error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json(['status' => 'error', 'message' => 'Internal error'], 500);
        }
    }
}
