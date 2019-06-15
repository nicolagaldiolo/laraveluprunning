@extends('layouts.app')

@section('content')

    <h2>Gate Policies</h2>
    @can('edit-contact', $contact)
        <h4>Autorizzato</h4>
    @else
        <h4>NON Autorizzato</h4>
    @endcan

    @cannot('edit-contact', $contact)
        <h4>NON Autorizzato</h4>
    @else
        <h4>Autorizzato</h4>
    @endcan
@stop