<?php

namespace App\Http\Controllers;

use App\Task;
use http\Cookie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class ActionsController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //dd(Auth::User());
        //dd(\Illuminate\Support\Facades\Request::session());

        //dd(response()->cookie('provaCookie'));
        return response()->view('validations.create')->cookie('provaCookie', 'pippo');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // se la validazione fallisce viene sollevata una ValidationException, se la richiesta è ajax viene tornato un json,
        // altrimenti si viene rediretti alla schermata precedente, in entrambi i casi vengono forniti tutti i messaggi di errore

        // gli errori di validazione vengono automaticamente iniettati in sessione e resi disponibili nella view in una variabile
        // $error, possono anche

        $this->validate($request, [
            'title' => 'required|unique:actions|max:125',
            'body' => 'required'
        ]);

        dd("Ok, validate, do somethings");
    }
}