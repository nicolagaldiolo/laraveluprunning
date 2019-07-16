<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

/*
 * QUEUE
 * Implementando l'interfaccia ShouldQueue la classe diventa "Accodabile" e così le notifiche vengono messe in coda
 *
 *  CANALI DI NOTIFICA (third-parts)
 * http://laravel-notification-channels.com/
 */

class WorkoutAvailable extends Notification implements ShouldQueue
{
    use Queueable;

    public $workout;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($workout)
    {
        $this->workout = $workout;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    /*
     * OTTENGO ATTRAVERSO QUALE CANALE NOTIFICARE L'ENTITA' IN QUESTIONE
     * $notifiable sarà l'entità che vogliamo notificare, può essere un utente, un azienda, u gruppo, un server ecc
     */
    public function via($notifiable)
    {
        // il canale di notifica può variare da utente a utente quindi mi faccio tornare il canale scelto dall'entità in questione
        // il valore tornato può essere variare es: durante il giorno via slack, la sera per mail ecc....
        //return $notifiable->preferredNotificationChannel();
        //oppure posso passare il canale scelto al costruttore quando creo l'istanza

        // può essere singolo es: "mail" oppure multiplo come array ['mail','database']

        return [
            'mail',
            'database',
            'slack',
            'broadcast'
        ];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */


    /*
     * CHANNEL
     * https://laravel.com/docs/5.8/notifications#markdown-mail-notifications
     *
     * DEFINISCO TANTI METODI QUANTI CANALI DI NOTIFICA HO, SE HO MAIL E SLACK AVRò toMail() e toSlack()
     * $notifiable sarà l'entità che vogliamo notificare, può essere un utente, un azienda, u gruppo, un server ecc
     */

    // NOTIIFCHE PER MAIL
    public function toMail($notifiable)
    {
        // CAMPO EMAIL
        // automatico la mail viene presa dalla proprietà mail del modello, ma può essere modificato il comportamento aggiungendo
        // il metodo routeNotificationForMail() nel modello che torna l'indirizzo mail da prendere in considerazione per l'invio

        // TEMPLATE
        // per modificare il template delle mail: php artisan vendor:publish --tag=laravel-notifications
        return (new MailMessage)
                    //->subject('') definisco il soggetto altrimenti viene generato dal nome della classe
                    ->line('You have a new workout available!')
                    ->action('Check it out now', route('home'))
                    ->line('Thank you for trainers with us!');
                    //->error(); se vogliamo trasformare questa notifica in un errore (CAMBIA TITOLO E STILE DEI BOTTONI)
    }

    // NOTIFICHE A DB
    // per salvare le notifiche a db lanciare php artisan notifications:table per creare la tabella
    // se la notifica deve essere inviata al db, perchè tra i canali da notificare (via() method) c'è anche il database,
    // viene invocato il metodo toDatabase() che salva un record sulla tabella notifications con un json preso dall'array tornato dal metodo
    // e viene automaticamente associata all'entità in questione
    public function toDatabase($totifiable)
    {
        return [
            'titolo_notifica' => $this->workout,
            'desc_notifica' => 'desc notifica',
            'tipo_notifica' => 'tipo notifica',
        ];
    }

    // NOTIFICHE BROADCUSTING (LARAVEL ECHO)
    public function toBroadcast($totifiable)
    {
        return new BroadcastMessage([
            'titolo_notifica' => $this->workout,
            'desc_notifica' => 'desc notifica',
            'tipo_notifica' => 'tipo notifica',
        ]);
    }

    // NOTIFICHE SMS (VIA NEXMO)
    public function toNexmo($totifiable)
    {
        return [
            'titolo_notifica' => $this->workout,
            'desc_notifica' => 'desc notifica',
            'tipo_notifica' => 'tipo notifica',
        ];
    }

    // NOTIFICHE SLACK
    // settare il metodo routeNotificationForSlack
    public function toSlack($totifiable)
    {
        return (new SlackMessage)->content($this->workout);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    // Questo metodo dovrebbe essere una sorta di fallback
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
