<?php

namespace Modules\User\Http\Controllers\WebService;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Modules\Apps\Http\Controllers\WebService\WebServiceController;
use Modules\User\Http\Requests\WebService\ChangePasswordRequest;
use Modules\User\Http\Requests\WebService\UpdateProfileRequest;
use Modules\User\Repositories\WebService\UserRepository as User;
use Modules\User\Transformers\WebService\UserResource;

class UserController extends WebServiceController
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function profile(Request $request)
    {
        $user = $this->user->userProfile();
        return $this->response(new UserResource($user));
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        /*if (!empty($request->mobile) && empty($request->firebase_id) && $request->mobile != auth('api')->user()->mobile) {
        return $this->error(__('authentication::api.register.validation.firebase_id.required'));
        }*/
        $this->user->update($request);
        $user = $this->user->userProfile();
        return $this->response(new UserResource($user));
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $this->user->changePassword($request);
        $user = $this->user->findById(auth()->id());
        return $this->response(new UserResource($user));
    }

    public function getVerifidCode(Request $request)
    {
        $columns = [
            'calling_code' => $request->calling_code ?? '965',
            'mobile' => $request->mobile,
        ];
        $user = $this->user->findUserByMultipleColumns($columns);
        return $this->response(["code" => optional($user)->code_verified ?? ""]);
    }

    public function deleteUserAccount(Request $request)
    {
        $user = $this->user->findById(auth('api')->id());
        if (!$user) {
            return $this->error(__('user::api.users.alerts.user_not_found'));
        }
        $prefix = 'toc_' . $user->id . '_';

        if (Str::startsWith($user->email, $prefix) || Str::startsWith($user->mobile, $prefix)) {
            return $this->error(__('user::api.users.alerts.user_deleted_before'));
        }

        $email = $prefix . $user->email;
        $mobile = $prefix . $user->mobile;

        $user->update([
            'email' => $email,
            'mobile' => $mobile,
        ]);
        // $user->fresh();
        auth()->user()->token()->revoke(); // logout user
        return $this->response([], __('user::api.users.alerts.user_deleted_successfully'));
    }

    public function getRefreshToken(Request $request)
    {
        $result = $this->user->getRefreshToken($request->refresh_token);
        $data = [
            'access_token' => $result['access_token'],
            'token_type' => 'Bearer',
        ];
        return $this->response($data);
    }

    private function tokenExpiresAt($value)
    {
        return date("Y-m-d H:i:s", $value);
    }

    public function checkTokenValid()
    {
        return $this->response((auth('api')->check() ? true:false));
    }
}