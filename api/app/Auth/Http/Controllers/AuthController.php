<?php
namespace App\Auth\Http\Controllers;

use App\Auth\Http\Requests\LoginRequest;
use App\Auth\Http\Requests\RegisterUserRequest;
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

    public function login(LoginRequest $request) : JsonResponse
    {
        $credentials = $request->validated();

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => __('auth.unauthorized')], 401);
        }

        return $this->respondWithToken($token);
    }

    public function register(RegisterUserRequest $request) : JsonResponse
    {
        $userData = $request->validated();

        $userEmailExists = $this->authService->getUserByEmail($userData['email']);
        if (!empty($userEmailExists)) {
            abort(403, __('auth.user_email_exists'));
        }

        $userData['password'] = Hash::make($userData['password']);

        if (!$user = $this->authService->createUser($userData))
            abort(500, __('auth.not_create_user'));

        return response()->json([
            'data' => [
                'user' => $user
            ]
        ]);
    }

    public function me() : JsonResponse
    {
        return response()->json(auth()->user());
    }

    public function logout() : JsonResponse
    {
        auth()->logout();

        return response()->json(['message' => __('auth.successfully_logged_out')]);
    }


    public function refresh() : JsonResponse
    {
        return $this->respondWithToken(auth()->refresh());
    }

    protected function respondWithToken($token) : JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}