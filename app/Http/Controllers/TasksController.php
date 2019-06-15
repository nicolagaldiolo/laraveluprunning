<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Tasks page';
        $tasks = Task::with('actions')->get();
        return view('bladeExample', compact('title','tasks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('tasks.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // per estrarre dati dalla request posso usare la facade Input, la facade request, oppure utilizzare la dependency Injection.

        // DEPENDENCY INJECTION
        // Dal momento che tutti i metodi del controller sono risolti dal container di laravel il quale ha tutte le classi in tiro,
        // possiamo passare una variabile al metodo es: $request e facciamo il Type Hinting ossia suggeriamo a laravel che la variabile è di tipo Request,
        // quindi ce la inietta nella variabile $request che divphp artienta un istanza della request
        if(is_null($request->get('title')) || is_null($request->get('description')) ){
            return redirect()->route('tasks.create')->withInput();
        }

        $task = new Task;
        $task->title = Input::get('title');
        $task->description = $request->get('description');
        $task->save();

        return redirect('tasks/create');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        return view('tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
