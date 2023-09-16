<?php
namespace App\Auth\Http\Controllers;

use App\Auth\Http\Requests\LoginRequest;
use App\Auth\Http\Requests\RegisterUserRequest;
use App\Auth\Http\Resources\JwtToken;
use App\Auth\Http\Resources\User;
use App\Auth\Services\AuthService;
use App\Http\Controllers\BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class AuthController extends BaseController
{
    public function __construct(
        private AuthService $authService
    )
    {}

    /**
    * @unauthenticated
    */
    public function login(LoginRequest $request) : JwtToken
    {
        $credentials = $request->validated();

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => __('auth.unauthorized')], 401);
        }

        return new JwtToken($token);
    }

    /**
    * @unauthenticated
    */
    public function register(RegisterUserRequest $request) : User
    {
        $userData = $request->validated();

        $userEmailExists = $this->authService->getUserByEmail($userData['email']);
        if (!empty($userEmailExists)) {
            abort(403, __('auth.user_email_exists'));
        }

        $userData['password'] = Hash::make($userData['password']);

        if (!$user = $this->authService->createUser($userData))
            abort(500, __('auth.not_create_user'));

        return new User($user);
    }

    public function me() : User
    {
        return new User(auth()->user());
    }

    public function logout() : JsonResponse
    {
        auth()->logout();

        return response()->json(['message' => __('auth.successfully_logged_out')]);
    }


    public function refresh() : JwtToken
    {
        return new JwtToken(auth()->refresh());
    }
}