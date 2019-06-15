{{--dd($task->hasChildren)--}}
<li>
    <strong>{{$task->title}}</strong><br>
    @if(count($task->actions))
        <ul>
            @foreach($task->actions as $child)
                <li>{{$child->title}}</li>
            @endforeach
        </ul>
    @endif

</li>