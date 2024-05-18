<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Iankumu\Mpesa\Facades\Mpesa;

class payment extends Controller
{
    // New payment code using the mpesa laravel package
    public function stkPush()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Basic dENMZ3FzMUEwV3VQTW81SEhHTGc3Uk5MVUhqb1FCbVgzQTdDZmwwaTVUdWRqcTBTOmhyMmdKbk5ZeTE4S01TYU1kWjJBcDQzYU90QUxNbGF6TEFiMEZOVlBabzNzMjdHYVVIZ2R4Q3pBT1Y3TTBjVFI=',
                'Cookie: incap_ses_1018_2742146=RO/DHcXI3kmbHaI1qakgDs3tPGYAAAAAQw0a/AuPX6SRwKirfc0wgw==; incap_ses_889_2320573=0e47ViUb9B2jbpeC2lxWDAPuPGYAAAAAsb201D5MzMzTqEBECCBGzQ==; nlbi_2320573=NarxGaXKux+SPDlbuMqyPwAAAAC67KER2qNuOy3QtM0VP+iK; visid_incap_2320573=jHcjdCX7Roi5bNdFXrPqQgLuPGYAAAAAQUIPAAAAAAAHuvsVb//r98Z52FqtJCR+; visid_incap_2742146=KOUijeK6SnakWSPM0kER4n+xmWUAAAAAQUIPAAAAAAA8HFa8xSErkRR8gogmVty1'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $result = json_decode($response);
        $access_token = $result->access_token;
        $this->stkPusher($access_token);
    }
    public function stkPusher($access_token)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
    "BusinessShortCode": "4122547",
    "Password": "' . base64_encode("4122547" . "ad5a4ea4829f50b0fc507fe12d8207bcd37848a6b30a3872a496b96e7b3578ea" . time()) . '",
    "Timestamp": "' . time() . ' ",
    "TransactionType": "CustomerPayBillOnline",
    "Amount": "1",
    "PartyA": "25427750214",
    "PartyB": "4122547",
    "PhoneNumber": "25427750214",
    "CallBackURL": "https://ip_address:port/callback",
    "AccountReference": "This is the account",
    "TransactionDesc": "Test Description "
}',
            CURLOPT_HTTPHEADER => array(
                'Authorization: ' . $access_token,
                'Content-Type: application/json',
                'Cookie: incap_ses_1018_2742146=RO/DHcXI3kmbHaI1qakgDs3tPGYAAAAAQw0a/AuPX6SRwKirfc0wgw==; incap_ses_889_2320573=0e47ViUb9B2jbpeC2lxWDAPuPGYAAAAAsb201D5MzMzTqEBECCBGzQ==; nlbi_2320573=NarxGaXKux+SPDlbuMqyPwAAAAC67KER2qNuOy3QtM0VP+iK; visid_incap_2320573=jHcjdCX7Roi5bNdFXrPqQgLuPGYAAAAAQUIPAAAAAAAHuvsVb//r98Z52FqtJCR+; visid_incap_2742146=KOUijeK6SnakWSPM0kER4n+xmWUAAAAAQUIPAAAAAAA8HFa8xSErkRR8gogmVty1'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        dd($response);
    }
}
