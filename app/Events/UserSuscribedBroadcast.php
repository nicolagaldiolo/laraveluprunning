<?php

namespace App\Events;

use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Facades\Auth;

/*
 * DICHIARAZIONE DELL'EVENTO BROADCAST
 * Implementando l'interfaccia ShouldBroadcast dichiaro che l'evento viene dispachatato tramite broadcast
 * e di conseguenza ci deve essere il metodo broadcastOn()
 */

class UserSuscribedBroadcast implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $plan;

    /*
     * SPECIFICARE QUEUE SPECIFICA
     * posso dichiarare una coda specifica per questi eventi (ha senso se questa coda la definisco come prioritaria)
     */
    //public $broadcastQueue = 'websockets-for-faster-processing';

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, $plan)
    {
        $this->user = $user;
        $this->plan = $plan;
    }

    /*
     * CUSTOMIZZARE NOME DELL'EVENTO
     * Per impostazione predefinita, Laravel trasmetterà l'evento utilizzando il nome della classe dell'evento.
     * Tuttavia, è possibile personalizzare il nome della trasmissione definendo un metodo broadcastAs sull'evento:
     */
    //public function broadcastAs()
    //{
    //    return 'user.subscribed';
    //}

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    /*
     * CANALI SU CUI TRASMETTERE
     * dichiaro su quali canali pushare l'evento
     */
    public function broadcastOn()
    {
        // SE I CANALI SONO PRIVATI O PRESENCE occorre specificarlo nella forma seguente e occorre attivare il BroadcastServiceProvider:
        //'private-admins', 'presence-admins'
        // altrimenti se uso i metodi PrivateChannel() o PresenceChannel() laravel aggiunge i prefissi per noi

        logger($this->user->id);

        // SINTASSI STRINGA
        return [
            //'users.' . $this->user->id,
            //'admins',
            new PrivateChannel('teams.' . $this->user->id),
            //new PresenceChannel('rooms.' . $this->user->id)
        ];


        // SINTASSI OGGETTO
        //return [
        //    new Channel('users.' . $this->user->id),
        //    new Channel('admins')
        //    //new PrivateChannel('admins') - il risultato sarà private-admins
        //    //new PresenceChannel('admins') - il risultato sarà presence-admins
        //];
    }

    /*
     * DATI TRASMESSI
     * TUTTE LE PROPRIETà PUBBLICHE della classe che l'evento esporrà verranno inviate come json.
     */

    /*
     * DATI TORNATI DI DEFAULT
    {
        'user': {
            'id': 5,
            'name': 'name',
            ...
        },
        'plan' : 'silver'
    }

    * in alternativa con il metodo broadcastWith() posso tornare un array con i dati che preferisco
    */
    public function broadcastWith()
    {
        return [
            'userId' => $this->user->id,
            'plan' => $this->plan
        ];

    }
}
