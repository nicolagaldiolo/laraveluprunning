@component('mail::message')
    # Ciao {{ $user->name }}, benvenuto nel nostro portale

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent