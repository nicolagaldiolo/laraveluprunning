<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

/*
 * Autheticatable è la classe che rende il modello "Autenticabile", con il sistema di autenticazione di Laravel, (volendo si potrebbe usare qualsiasi altro modello basta che sia "autenticabile")
 * La classe Autheticatable implementa 3 diverse interfaccie e fornisce tutti i relativi traits per soddisfare le interfaccie:
 *  use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;   || permette al modello di autenticare l'instanza di questo modello con il sistema di autenticazione (richiede ad esempio il metodo getAuthIdentifier())
 *  use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;  || richiede i metodi (can() cannot()) che permettono al framework di autorizzare o meno l'istanza in differenti contesti dell'applicazione
 *  use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract; || richiede un metodo (getEmailForPasswordReset()) che permette al framework di resettare la password di ogni instanza che soddisfa il contratto
 */

class User extends Authenticatable
{
    use Notifiable,

        // SUPPORTO OAUTH Aggiunge la relazione Oauth Client e token related a ogni utente + fornire alcuni metodi di supporto al
        // modello che consentono di ispezionare il token e gli ambiti dell'utente autenticato
        HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'admin',
        'api_token' // aggiungo il campo api_token per lìautenticazione con token "semplice"
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function Tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    public function phoneNumbers()
    {
        return $this->hasManyThrough(PhoneNumber::class, Contact::class);
    }

    public function accounts()
    {
        // Laravel si aspetta che la tabella pivot si chiami account_user (nomi al singolare, in ordine alfabetico, separato da _)
        // Laravel si aspetta che la tabella pivot abbia le colonne account_id e user_id
        return $this->belongsToMany(Account::class)
            ->withTimestamps() // dichiaro di voler ricevere anche i dati di creazione e aggiornamento della tabella pivot (laravel me li popola automaticamente)
            ->withPivot('status'); // passo tutte le colonne di cui necessito estrarre i dati dalla colonna pivot per questa relazione
    }

    public function stars()
    {
        return $this->hasMany(Star::class);
    }

    public function friends()
    {
        return $this->hasMany(Friend::class);
    }

    public function conferences()
    {
        return $this->hasMany(Conference::class);
    }

    public function isAdmin()
    {
        return $this->admin == 1;
    }
}
