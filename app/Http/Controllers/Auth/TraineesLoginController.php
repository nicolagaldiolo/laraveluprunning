<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class TraineesLoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = 'trainees/home'; // proprietà che viene invocata in mancanza del metodo redirectTo()

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        /*
         * middleware che scatta quando tento di accedere a pagine disponibili solo per utenti NON LOGGATI, es login, registrazione...
         * passo un secondo parametro che indica il link del redirect (è stata fatta una custimmazione al middleware per questo
         * per rendere utilizzatibile da tutti i "tipi di login" altrimenti verrei rediretto alla home standard).
         *
         */

        $this->middleware('guest:trainees,trainees/home')->except('logout');
    }

    public function redirectTo() // metodo che sovrascrive la proprietà $redirectTo
    {
        return 'trainees/home';
    }

    protected function guard() // definisco la guard standard per questo "tipo di login"
    {
        return \Auth::guard('trainees');
    }

    public function showLoginForm()
    {
        return view('auth.traineeslogin');
    }
}
