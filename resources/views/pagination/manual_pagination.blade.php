@extends('layouts.app')

@section('content')

    <h2>Manual pagination</h2>
    <ul>
        @foreach($tasks_paginate as $task)
            <li>{{$task->title}}</li>
        @endforeach
    </ul>

    {{ $paginator->links() }}
    {{ $lengthAwarePaginator->links() }}
@stop