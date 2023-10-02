<?php


use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

test('Email must have the correct subject', function () {
    $user = createUser();
    $mailable = buildVerifyEmail($user);

    $mailable->assertHasSubject(__('auth.email.verify_email.verify_email_user'));
});

test('Email body must have the user name', function () {
    $user = createUser();
    $mailable = buildVerifyEmail($user);

    $mailable->assertSeeInHtml($user->name);
});

test('Email body must have button verify email', function () {
    $user = createUser();
    $mailable = buildVerifyEmail($user);

    $mailable->assertSeeInHtml(__('auth.email.verify_email.verify_email_text_button'));
});

test('Email must have the correct sender address', function () {
    $user = createUser();
    $mailable = buildVerifyEmail($user);

    $mailable->assertFrom(env('MAIL_FROM_ADDRESS'));
});

test('Email must have the correct recipient address', function () {
    $user = createUser();
    $mailable = buildVerifyEmail($user);

    $mailable->assertTo($user->email);
});

test('Notification verify email must be sent', function () {
    Notification::fake();

    $user = createUser();
    $user->sendEmailVerificationNotification();

    Notification::assertSentTo(
        [$user],
        \Illuminate\Auth\Notifications\VerifyEmail::class
    );
});
