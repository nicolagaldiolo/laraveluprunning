@if($errors->any())
    <h2>Errors</h2>
    <ul>
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

<h1>Using Validate()</h1>
<form action="{{url('actions')}}" method="POST">
    @csrf
    @method('POST')
    <input type="text" name="title" value="{{old('title')}}">
    <input type="text" name="body" value="{{old('body')}}">
    <button>Submit</button>
</form>

<hr>
{{--
<h1>Manual Validation</h1>
<form action="{{url('actions_manual')}}" method="POST">
    @csrf
    @method('POST')
    <input type="text" name="title" value="{{old('title')}}">
    <input type="text" name="body" value="{{old('body')}}">
    <button>Submit</button>
</form>

<hr>

<h1>Form Request</h1>
<form action="{{url('tasks/1/actions_form_request')}}" method="POST">
    @csrf
    @method('POST')
    <input type="text" name="title" value="{{old('title')}}">
    <button>Submit</button>
</form>
--}}