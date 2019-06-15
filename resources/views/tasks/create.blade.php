<form action="{{url('tasks')}}" method="POST">

    <!-- Le due dichiarazioni seguenti fanno la stessa cosa,
    all'interno di laravel tutte le richieste http al di fuori di quelle read only (get|head|options) sono protette da
    un token che viene valorizzato a inizio sessione per evitare richieste crosssite
    -->
    @csrf
    <input type="hidden" name="_token" value="{{ csrf_token() }}">

    <!-- Le due dichiarazioni seguenti fanno la stessa cosa,
    per richieste http diverse da get|post (dato che il form html mi permette di fare solo get post request)
    devo utilizzare questo tag altrimenti laravel non mi permette di fare richieste con verbi riversi da get e post -->
    @method('POST')
    <input type="hidden" name="_method" value="POST">


    <input type="text" name="title" value="{{old('title')}}">
    <input type="text" name="description" value="{{old('description')}}">
    <button>Submit</button>
</form>