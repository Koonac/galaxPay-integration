<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class galaxPayAPI extends Controller
{
    public function generateAcessToken($galaxId, $galaxHash){
        
        $permissoesApi = 'customers.read customers.write plans.read plans.write transactions.read transactions.write webhooks.write cards.read cards.write card-brands.read subscriptions.read subscriptions.write charges.read charges.write boletos.read';

        $response = Http::withHeaders([
            'Authorization' => 'Basic '. base64_encode("$galaxId:$galaxHash"),
            'Content-Type' => 'application/json'
        ])->withBody(json_encode([
            'grant_type' => 'authorization_code',
            'scope' => $permissoesApi
        ]), 'json')->post('https://api.sandbox.cloud.galaxpay.com.br/v2/token');

        return $response['access_token'];
    }

    public function getClientesGalaxPay($token){
       
        $response = Http::withHeaders([
            'Authorization' => "Bearer $token", //accessToken
            'Content-Type' => 'application/json'
        ])->get("https://api.sandbox.cloud.galaxpay.com.br/v2/customers?startAt=0&limit=50");
        $response = json_decode($response);

        return view('login.login', ['response' => $response]);
    }
}
