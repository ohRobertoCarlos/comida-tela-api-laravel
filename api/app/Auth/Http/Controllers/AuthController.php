<?php
namespace App\Auth\Http\Controllers;

use App\Auth\Services\AuthService;
use App\Http\Controllers\BaseController;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends BaseController
{
    public function __construct(
        private AuthService $authService
    )
    {
        //
    }

    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => __('auth.unauthorized')], 401);
        }

        return $this->respondWithToken($token);
    }

    public function register(Request $request)
    {
        $userData = $request->validate([
            'email' => ['required','email'],
            'password' => ['required','confirmed','regex:/^(?=.*\d)(?=.*\W)[\da-zA-Z\W]{6,}$/'],
            'name' => ['required', 'min:2']
        ]);

        // $userEmailExists = $this->repository->findByEmail($userData['email']);
        // $userEmailExists = User::where('email', $userData['email'])->first();
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

    public function me()
    {
        return response()->json(auth()->user());
    }

    public function logout() : JsonResponse
    {
        auth()->logout();

        return response()->json(['message' => __('auth.successfully_logged_out')]);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
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