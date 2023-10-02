<?php

namespace App\Auth\Http\Controllers;

use App\Auth\Http\Requests\EmailVerificationRequest;
use App\Auth\Repositories\UserRepository;
use App\Establishments\Http\Requests\UserIsAdminRequest;
use App\Http\Controllers\BaseController;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VerifyEmailController extends BaseController
{
    /**
     * @unauthenticated
     */
    public function verifyEmail(EmailVerificationRequest $request)
    {
        $user = (new UserRepository())->findById($request->route('id'));

        if (
            empty($user) ||
            ! hash_equals(sha1($user->getEmailForVerification()), (string) $request->route('hash'))
        ) {
            return redirect(env('APP_CLIENT_URL') . '/email/verify/error');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect(env('APP_CLIENT_URL') . '/email/verify/already-success');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return redirect(env('APP_CLIENT_URL') . '/email/verify/success');
    }

    public function resendEmaiVerification(Request $request) : JsonResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json([
                'message' => __('auth.user_already_verified')
            ]);
        }

        $request->user()->sendEmailVerificationNotification();

        return response()->json([
            'message' => __('auth.resend_email_verification')
        ]);
    }

    public function resendEmaiVerificationUser(UserIsAdminRequest $request, $id) : JsonResponse
    {
        $user = (new UserRepository())->findById($id);
        if (empty($user)) {
            return response()->json([
                'message' => __('auth.user_not_found')
            ], 404);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'message' => __('auth.user_already_verified')
            ], 400);
        }

        $user->sendEmailVerificationNotification();

        return response()->json([
            'message' => __('auth.resend_email_verification')
        ]);
    }
}
