@extends('layouts.app')

@section('content')
    <form method="post" action="/upload_file" enctype="multipart/form-data"/>
        @csrf

        <label>Picture</label>
        <input type="file" name="picture">
        <br>
        <label>Picture 2</label>
        <input type="file" name="picture2">
        <br>
        <label>Picture 3</label>
        <input type="file" name="picture3">
        <br>
        <input type="submit">
    </form>
@endsection
