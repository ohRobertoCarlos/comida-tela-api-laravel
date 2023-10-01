<x-mail::message>
## {{ __('auth.email.welcome.greeting', ['name' => $user->name]), }}<br><br>
{{ __('auth.email.welcome.greetings', ['name' => env('APP_NAME')]) }}<br>
{{ __('auth.email.welcome.create_password_message') }}

<x-mail::button :url="$url">
    {{ __('auth.email.welcome.create_password_text_button') }}
</x-mail::button>

{{ __('auth.email.welcome.thanks') }},<br>
{{ config('app.name') }}
</x-mail::message>
