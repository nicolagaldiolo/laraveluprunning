@extends('layouts.app')

@section('content')

    <h2>Pagination</h2>
    <ul>
        @foreach($tasks_paginate as $task)
            <li>{{$task->title}}</li>
        @endforeach
    </ul>

    {{ $tasks_paginate->links() }}

    <h2>Simple Pagination</h2>
    <ul>
        @foreach($tasks_simple_paginate as $task)
            <li>{{$task->title}}</li>
        @endforeach
    </ul>

    {{ $tasks_simple_paginate->links() }}
@stop