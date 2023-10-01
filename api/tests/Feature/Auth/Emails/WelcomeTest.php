<?php

use App\Auth\Mail\Welcome;
use Illuminate\Support\Facades\Mail;

test('Email must have the correct subject', function () {
    $user = createUser();
    $mailable = buildWelcomeEmail($user);

    $mailable->assertHasSubject(__('auth.email.welcome_user_subject'));
});

test('Email body must have the user name', function () {
    $user = createUser();
    $mailable = buildWelcomeEmail($user);

    $mailable->assertSeeInHtml($user->name);
});

test('Email body must have button create password', function () {
    $user = createUser();
    $mailable = buildWelcomeEmail($user);

    $mailable->assertSeeInHtml(__('auth.email.welcome.create_password_text_button'));
});

test('Email must have the correct sender address', function () {
    $user = createUser();
    $mailable = buildWelcomeEmail($user);

    $mailable->assertFrom(env('MAIL_FROM_ADDRESS'));
});

test('Email must have the correct recipient address', function () {
    $user = createUser();
    $mailable = buildWelcomeEmail($user);

    $mailable->assertTo($user->email);
});

test('Email must be sent', function () {
    Mail::fake();

    $user = createUser();
    $user->sendWelcomeEmail();

    Mail::assertSent(Welcome::class);
});
