<?php

namespace Modules\User\Repositories\WebService;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Client as OClient;
use Modules\User\Entities\User;
use Modules\User\Entities\UserFavourite;

class UserRepository
{
    protected $user;
    protected $favourite;

    public function __construct(User $user, UserFavourite $favourite)
    {
        $this->user = $user;
        $this->favourite = $favourite;
    }

    public function update($request)
    {
        $user = auth()->user();

        if ($request['password'] == null) {
            $password = $user['password'];
        } else {
            $password = Hash::make($request['password']);
        }

        DB::beginTransaction();

        try {

            $user->update([
                'name' => $request['name'],
                'email' => $request['email'],
                'calling_code' => $request['calling_code'] ?? null,
                'mobile' => $request['mobile'],
                'country_id' => $request['country_id'] ?? null,
//                'firebase_id' => $request['firebase_id'] ?? null,
                'password' => $password,
            ]);

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function findById($id)
    {
        $user = $this->user->find($id);
        return $user;
    }

    public function findByEmail($email)
    {
        $user = $this->user->where('email', $email)->first();
        return $user;
    }

    public function findUserByMultipleColumns($columns = [])
    {
        $query = $this->user->query();
        foreach ($columns as $key => $column) {
            $query = $query->where($key, $column);
        }
        return $query->first();
    }

    public function changePassword($request)
    {
        $user = $this->findById(auth()->id());

        if ($request['password'] == null) {
            $password = $user['password'];
        } else {
            $password = Hash::make($request['password']);
        }

        DB::beginTransaction();

        try {

            $user->update([
                'password' => $password,
            ]);

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function userProfile()
    {
        return auth()->user();
    }

    public function findFavourite($userId, $prdId)
    {
        return $this->favourite->where(function ($q) use ($userId, $prdId) {
            $q->where('user_id', $userId);
            $q->where('product_id', $prdId);
        })->first();
    }

    public function createFavourite($userId, $prdId)
    {
        return $this->favourite->create([
            'user_id' => $userId,
            'product_id' => $prdId,
        ]);
    }

    public function getRefreshToken($refreshToken)
    {
        $oClient = OClient::where('password_client', 1)->first();
        $http = new Client;
        $response = $http->post(url('oauth/token'), [
            'form_params' => [
                'grant_type' => 'refresh_token',
                'refresh_token' => $refreshToken,
                'client_id' => $oClient->id,
                'client_secret' => $oClient->secret,
                'scope' => '*',
            ],
        ]);

        return json_decode((string) $response->getBody(), true);
    }
}
