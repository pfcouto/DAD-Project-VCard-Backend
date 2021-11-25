<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

const PASSPORT_SERVER_URL = "http://projetoDAD.test";
const CLIENT_ID = 2;
const CLIENT_SECRET = 'xvSzDbe4ZXgQ6AWzcrLsl3aj30gMrKwZjJKgwQ3H';

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $bodyHttpRequest = [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => CLIENT_ID,
                'client_secret' => CLIENT_SECRET,
                'username' => $request->username,
                'password' => $request->password,
                'scope' => ''
            ],
            'exceptions' => false,
        ];
        $url = PASSPORT_SERVER_URL . '/oauth/token';
        $http = new \GuzzleHttp\Client;
        $response = $http->post($url, $bodyHttpRequest);
        $errorCode = $response->getStatusCode();
        if ($errorCode == '200') {
            return json_decode((string) $response->getBody(), true);
        } else {
            return response()->json(
                ['msg' => 'User credentials are invalid'],
                $errorCode
            );
        }
    }

    public function logout(Request $request)
    {
        $accessToken = $request->user()->token();
        $token = $request->user()->tokens->find($accessToken);
        $token->revoke();
        $token->delete();
        return response(['msg' => 'Token revoked'], 200);
    }
}
