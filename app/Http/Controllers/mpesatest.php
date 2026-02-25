<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Iankumu\Mpesa\Facades\Mpesa;

class mpesatest extends Controller
{
    public function fired(Request $request)
    {
        $phone = $request->input('phone', '0727750214');
        $amount = $request->input('amount', 1);
        $shortcode = config('mpesa.shortcode');
        $callbackUrl = config('mpesa.callback_url');

        $response = Mpesa::stkpush($phone, $amount, $shortcode, $callbackUrl);
        $result = json_decode((string) $response);

        return response()->json($result);
    }
}
