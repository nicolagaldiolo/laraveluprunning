<!--
Uso delle direttive {{ $title }} e {!! $title !!}
Usando le doppie parentesi viene eseguito l'escape dei caratteri con la funzione htmlspecialchars per prevenire CrossSiteScripting (XSS).
Nel secondo caso non viene fatto l'escape dei caratteri.
NB: Usare sempre le doppie parentesi quando si mostra codice fornito dall'utente e non dal db ad esempio
-->

@section('title', $title)

@extends('layouts.app')

@section('content')
    <h2>{{$singleViewVariable}}</h2>
    <h2>{{$multipleViewVariable}}</h2>

    <!-- Strutture di controllo -->
    @unless(false)
        <h1>Stampo solo se la condizione è falsa, equivalente di @@if(!true)</h1>
    @endunless


    <!-- Variabile $loop all'interno del ciclo -->

    {{--dd($tasks)--}}

    <h1>Da notare la variabile loop (da info relative ai dati che sto ciclando)</h1>
    <h2>{{$title}}</h2>
    <ul>
        @foreach($tasks as $task)
            {{--dd($task->hasChildren)--}}
            <li>
                <strong>{{$task->title}}</strong><br>
                <strong>{{$task->description}}</strong><br>
                ( <span>Elemento: {{$loop->iteration}}</span> |
                    <span>Index: {{$loop->index}}</span> |
                    <span>remaining: {{$loop->remaining}}</span> |
                    <span>count: {{$loop->count}}</span> |
                    <span>first: {{$loop->first}}</span> |
                    <span>last: {{$loop->last}}</span> |
                    <span>odd: {{$loop->odd}}</span> |
                    <span>even: {{$loop->even}}</span> |
                    <span>depth: {{$loop->depth}}</span> |
                    @if(count($task->actions))
                        <ul>
                            @foreach($task->actions as $child)
                                <li>
                                    <span>Title: {{$child->title}}</span> |
                                    <span>Iteration: {{$loop->iteration}}</span> |
                                    <span>Parent iteration: {{$loop->parent->iteration}}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif

            </li>
        @endforeach
    </ul>

    <h1>Posso ciclare la collection tasks e per ogni elemento chiamare un parziale iniettando l'iteratore corrente (non ho a disposizione l'oggetto loop come nel foreach)</h1>
    <ul>
        @each('partials.tasks', $tasks, 'task', 'partials.no-tasks')
    </ul>
@stop