@component('mail::message')
    # Ciao, si è appena iscritto un nuovo utente:
    ## {{ $user->name }} - {{ $user->email }}

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent