<?php

namespace App\Http\Controllers\auth;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request;

class Token
{

    public static function createToken(string $email)
    {

        $response = Http::post(route('passport.token'), [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => config('passport.client_id'),
                'client_secret' => config('passport.client_secret'),
                'username' => $email,
                'password' => '',
            ],
        ]);

        $responseData = $response->json();

        return $responseData['access_token'];
    }
}
