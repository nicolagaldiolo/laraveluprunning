@extends('layouts.app')

@section('content')
    <form method="post" action="/post-route?utm=12345"/>
        @csrf

        <label>User</label>
        <input type="text" name="firstname" value="Nicola">
        <input type="text" name="lastname" value="Galdiolo">
        <br>
        <label>User</label>
        <input type="text" name="employed[0][firstname]" value="Erica">
        <input type="text" name="employed[0][lastname]" value="Frigo">
        <br>
        <label>User</label>
        <input type="text" name="employed[1][firstname]" value="Chloe">
        <input type="text" name="employed[1][lastname]" value="Galdiolo">
        <br>
        <input type="submit">
    </form>
@endsection
