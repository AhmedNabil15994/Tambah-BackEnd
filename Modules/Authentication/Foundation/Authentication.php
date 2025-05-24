<?php

namespace Modules\Authentication\Foundation;

use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;
use Laravel\Passport\Client as OClient;
use Illuminate\Support\Facades\Http;

trait Authentication
{

    public function baseUrl()
    {
        $app_url = config('app.url');
        $baseurl = !str_ends_with($app_url, '/') ? $app_url.'/' : $app_url . 'oauth/token';

        if( App::environment()=="production" )
        {
            $baseurl = url('oauth/token');
        }

        return $baseurl;
    }

    public static function authentication($credentials)
    {
        $auth = null;

        // LOGIN BY : Mobile & Password
        if (is_numeric($credentials->email)):

            $auth = Auth::attempt([
                'calling_code' => $credentials->calling_code ?? '965',
                'mobile' => $credentials->email,
                'password' => $credentials->password,
            ], $credentials->has('remember')
            );

        // LOGIN BY : Email & Password
        elseif (filter_var($credentials->email, FILTER_VALIDATE_EMAIL)):

            $auth = Auth::attempt([
                'email' => $credentials->email,
                'password' => $credentials->password,
            ],
                $credentials->has('remember')
            );

        endif;

        return $auth;
    }

    public function login($credentials)
    {
        try {

            if (self::authentication($credentials)) {
                return false;
            }

            $errors = new MessageBag([
                'password' => __('authentication::dashboard.login.validations.failed'),
            ]);

            return $errors;

        } catch (Exception $e) {

            return $e;

        }
    }

    public function loginAfterRegister($credentials)
    {
        try {
            self::authentication($credentials);
        } catch (Exception $e) {
            return $e;
        }
    }

    public function generateToken($user)
    {
        $tokenResult = $user->createToken('Personal Access Token');

        $token = $tokenResult->token;

        $token->save();

        return $tokenResult;
    }

    public function tokenExpiresAt($token)
    {
        return Carbon::parse($token->token->expires_at)->toDateTimeString();
    }

    public function getTokenAndRefreshToken($email, $password)
    {
        $oClient = OClient::where('password_client', 1)->first();
        $response = Http::asForm()->post($this->baseUrl(), [
            'grant_type' => 'password',
            'client_id' => $oClient->id,
            'client_secret' => $oClient->secret,
            'username' => $email,
            'password' => $password,
            'scope' => '',
        ]);

        return $response->json();
    }

    public function saveRefreshToken($request)
    {
        $oClient = OClient::where('password_client', 1)->first();
        $http = new Client;
        $response = $http->request('POST', $this->baseUrl(), [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => $oClient->id,
                'client_secret' => $oClient->secret,
                'username' => $request->email,
                'password' => $request->password,
                'scope' => '*',
            ],
        ]);
        return json_decode((string) $response->getBody(), true);
    }

}
