<x-mail::message>
# Order Shipped

Hello <strong>{{ $user->name }}</strong>,<br><br>
Welcome on Comida tela<br>
Create your password on link below:

<x-mail::button :url="$url">
    Create password
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
