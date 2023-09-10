<?php
namespace App\Auth\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends BaseController
{
    public function __construct()
    {
        //
    }

    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function register(Request $request)
    {
        $userData = $request->validate([
            'email' => ['required','email'],
            'password' => ['required','confirmed','regex:/^(?=.*\d)(?=.*\W)[\da-zA-Z\W]{6,}$/'],
            'name' => 'min:2'
        ]);

        // $userEmailExists = $this->repository->findByEmail($userData['email']);
        $userEmailExists = User::where('email', $userData['email'])->first();
        if (!empty($userEmailExists)) {
            abort(403, 'Já existe um usuário com esse e-mail!');
        }

        $userData['password'] = Hash::make($userData['password']);

        if (!$user = User::create($userData))
            abort(500, 'Could not create user');

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

        return response()->json(['message' => 'Successfully logged out']);
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