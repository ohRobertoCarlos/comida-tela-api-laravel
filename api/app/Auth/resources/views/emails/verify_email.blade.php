<x-mail::message>
## {{ __('auth.email.verify_email.greeting', ['name' => $user->name]), }}<br><br>
{{ __('auth.email.verify_email.message') }}<br>

<x-mail::button :url="$url">
    {{ __('auth.email.verify_email.verify_email_text_button') }}
</x-mail::button>

{{ __('auth.email.verify_email.alert_no_create_account_message') }}<br>

{{ __('auth.email.verify_email.thanks') }},<br>
{{ config('app.name') }}
</x-mail::message>
