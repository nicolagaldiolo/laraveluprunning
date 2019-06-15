@extends('layouts.app')

@section('content')

    @if($messages->has('errors'))
        <h2>Errors</h2>
        <ul>
            @foreach($messages->get('errors') as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    @if($errors->any())
        <h2>Errors</h2>
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

@stop