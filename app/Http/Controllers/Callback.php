<?php

namespace App\Http\Controllers;

use App\Models\pipeline;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class Callback extends Controller
{
    public function confirm_payment(Request $request)
    {
        $payload = $request->all();

        Log::channel('stack')->info('M-Pesa callback received', ['payload' => $payload]);

        try {
            $callbackData = data_get($payload, 'Body.stkCallback');

            if (!$callbackData) {
                Log::channel('stack')->error('M-Pesa callback: missing stkCallback data');
                return response()->json(['ResultCode' => 1, 'ResultDesc' => 'Missing callback data']);
            }

            $resultCode = data_get($callbackData, 'ResultCode');
            $merchantRequestID = data_get($callbackData, 'MerchantRequestID');
            $checkoutRequestID = data_get($callbackData, 'CheckoutRequestID');

            $record = pipeline::where('merchant_request_id', $merchantRequestID)
                ->where('checkout_request_id', $checkoutRequestID)
                ->first();

            if (!$record) {
                Log::channel('stack')->warning('M-Pesa callback: no matching pipeline record', [
                    'merchant_request_id' => $merchantRequestID,
                    'checkout_request_id' => $checkoutRequestID,
                ]);
                return response()->json(['ResultCode' => 1, 'ResultDesc' => 'Record not found']);
            }

            if ($resultCode == 0) {
                $record->payment_status = 'paid';
                $record->save();

                Log::channel('stack')->info('M-Pesa payment confirmed', [
                    'pipeline_id' => $record->id,
                    'merchant_request_id' => $merchantRequestID,
                ]);
            } else {
                $resultDesc = data_get($callbackData, 'ResultDesc', 'Payment failed');
                $record->payment_status = 'failed';
                $record->save();

                Log::channel('stack')->warning('M-Pesa payment failed', [
                    'pipeline_id' => $record->id,
                    'result_code' => $resultCode,
                    'result_desc' => $resultDesc,
                ]);
            }

            return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
        } catch (\Exception $e) {
            Log::channel('stack')->error('M-Pesa callback error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['ResultCode' => 1, 'ResultDesc' => 'Error processing callback']);
        }
    }
}
