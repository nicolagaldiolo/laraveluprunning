<h1>Ciao {{ $trainee->name }}, sei stato assegnato a questo evento</h1>
<h2>{{$event}}</h2>

<!-- Immagine embeddata -->
<img src="{{ $message->embed(storage_path('app/public/images/customName.png')) }}">

<!-- Immagine embeddata da contenuto immagine -->
<img src="{{ $message->embedData(file_get_contents(storage_path('app/public/images/customName.png')), 'embed.png') }}">