<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Iankumu\Mpesa\Facades\Mpesa;

class payment extends Controller
{
    // New payment code using the mpesa laravel package
    public function stkPush()
    {
        $response = Mpesa::stkpush('0727750214', 1, '12345');
        return json_decode((string)$response);
    }
}
