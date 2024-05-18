<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Iankumu\Mpesa\Facades\Mpesa;

class mpesatest extends Controller
{
    public function fired()
    {
        $response = Mpesa::stkpush('0727750214', 1, '4122547', 'https://mumaapix.com');
        echo json_encode($response);
    }
}
