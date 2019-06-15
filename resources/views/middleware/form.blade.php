@extends('layouts.app')

@section('content')
    <form method="post" action="/delete_method_action"/>
        @csrf
        @method('DELETE')
        <input type="submit">
    </form>
@endsection
