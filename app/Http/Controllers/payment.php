<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class payment extends Controller
{
    public $headers;
    public function index()
    {
        // code for the quikk api intergration
        $this->headers = $this->gen_signature();
        // save the headers into seperate variables
        // $date = $headers[0];
        // $authorization = $headers[1];
        // add the curl request now
        $this->quikkCharge();
    }
    function gen_signature()
    {
        $key = "9ef2cceda91e7f8c443140563fef3094";
        $secret = "93a606175357cf7ee3675201a4d453e6";
        // Get the current time
        // Example date: Wed, 04 May 2022 12:43:36 GMT
        $timestamp = gmdate('D, d M Y H:i:s T', time());
        $signature_payload = "date: $timestamp";
        // generate hmac hash
        $hash = hash_hmac('sha256', $signature_payload, $secret, true); // this returns in binary format
        // base64 encode the hash
        $b64 = base64_encode($hash);
        // url encode the signature
        $signature = urlencode($b64);
        // format the value of the authorization header
        $authorization = "keyId=\"$key\",algorithm=\"hmac-sha256\",signature=\"$signature\"";
        return [$timestamp, $authorization];
    }
    public function quikkCharge()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://tryapi.quikk.dev/v1/mpesa/charge',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
                "data": {
                    "type": "charge",
                    "id": "gid",
                    "attributes": {
                      "amount": 100,
                      "customer_type": "msisdn",
                      "customer_no": "254727750214",
                      "short_code": "4122547",
                      "paybill_no": "4122547",
                      "posted_at": "2019-03-18T17:22:09.651011Z",
                      "reference": "1234"
                    }
              }
          }',
            CURLOPT_HTTPHEADER => array(
                'Accept: application/vnd.api+json',
                'Content-Type: application/vnd.api+json',
                'Authorization: ' . $this->headers[1] . '',
                'Date: ' . $this->headers[0] . '',
                'X-Custom: custom'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;
    }
}
