<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Iankumu\Mpesa\Facades\Mpesa;
use Illuminate\Support\Facades\Log;

class payment extends Controller
{
    public function stkPush(Request $request)
    {
        $request->validate([
            'phone' => 'required',
            'amount' => 'required|numeric|min:1',
            'account_reference' => 'required|string|max:12',
        ]);

        $phone = $request->input('phone');
        $amount = $request->input('amount');
        $accountReference = $request->input('account_reference');

        try {
            $response = Mpesa::stkpush($phone, $amount, $accountReference);
            $result = json_decode((string) $response);

            Log::channel('stack')->info('M-Pesa STK Push initiated', [
                'phone' => $phone,
                'amount' => $amount,
                'account_reference' => $accountReference,
                'response' => $result,
            ]);

            return response()->json($result);
        } catch (\Exception $e) {
            Log::channel('stack')->error('M-Pesa STK Push failed', [
                'phone' => $phone,
                'amount' => $amount,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'error' => 'Failed to initiate payment. Please try again.',
            ], 500);
        }
    }
}
