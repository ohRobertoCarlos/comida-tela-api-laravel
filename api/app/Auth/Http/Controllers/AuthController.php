<?php
namespace App\Auth\Http\Controllers;

use App\Auth\Http\Requests\LoginRequest;
use App\Auth\Http\Requests\RegisterUserRequest;
use App\Auth\Http\Resources\JwtToken;
use App\Auth\Http\Resources\User;
use App\Auth\Services\AuthService;
use App\Http\Controllers\BaseController;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class AuthController extends BaseController
{
    public function __construct(
        private AuthService $authService
    )
    {}

    /**
    * @unauthenticated
    */
    public function login(LoginRequest $request) : JwtToken|JsonResponse
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

    /**
    * @unauthenticated
    */
    public function refresh() : JwtToken
    {
        return new JwtToken(auth()->refresh());
    }

    /**
     * @unauthenticated
     */
    public function forgotPassword(Request $request) : JsonResponse
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status !== Password::RESET_LINK_SENT) {
            return response()->json(['status' => __($status)], 400);
        }

        return response()->json(['status' => __($status)]);
    }

    /**
     * @unauthenticated
     */
    public function resetPassword(Request $request) : JsonResponse
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|regex:/^(?=.*\d)(?=.*\W)[\da-zA-Z\W]{6,}$/|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (\App\Models\User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                if (!$user->hasVerifiedEmail()) {
                    $user->markEmailAsVerified();
                }

                event(new PasswordReset($user));
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            return response()->json(['status' => __($status)], 400);
        }

        return response()->json(['status' => __($status)]);
    }
}
