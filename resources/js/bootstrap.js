
window._ = require('lodash');

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    window.Popper = require('popper.js').default;
    window.$ = window.jQuery = require('jquery');

    require('bootstrap');
} catch (e) {}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}


/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */


/*
    LARAVEL ECHO VS VANILLA JS
    Per andare parlare con il websocket e ricevere eventi broadcast posso procedere utilizzando Laravel Echo o Vanilla JS,
    entrambi mi permettono di fare le stesse cose, con echo ho il vantaggio che essendo un wrapper posso cambiare il connettore (Redis, Pusher)
    e tutto funziona correttamente.

    Se NON utilizzo Laravel Echo devo prestare alcuni accorgimenti:
    - se mi iscrivo a canali privati o presence ho necessità di autenticarmi quindi viene fatta una chiamata post alla rotta
        /broadcasting/auth (protetta da CSRF-TOKEN) a cui devo aggiungere l'header con il X-CSRF-TOKEN.
        in alterantiva posso escludere la rotta dal controllo aggiungendo il path della rotta all'array exclude nel VerifyCsrfToken
    - Quando specifico un canale devo aggiungere il suffisso private- presence-
    - Quando bindo il nome dell'evento devo mettere il fully qualifies name: App\Events\UserSuscribedBroadcast

    ESEMPIO DI APPLICAZIONE: https://pusher.com/tutorials/presence-channels-laravel


    ESCLUDERE LO USER CORRENTE DALLA NOTIFICA DELL'EVENTO EMESSO DA Sè STESSO
    Se eseguo un azione che aggiunge dei dati, voglio informare gli altri iscritti al canale in modo che possano
    recuperare i dati inviati, ovviamente io sono iscritto al canale ma non voglio ricevere i nuovi dati perchè li ho già
    quindi lato backend emetto l'evento ma indico solo di avvisare gli altri iscritti:
    broadcast(new \App\Events\UserSuscribedBroadcast($user, $plan))->toOthers();

    Quando si inizializza un'istanza di Laravel Echo, alla connessione viene assegnato un ID socket.
    Se si utilizza Vue e Axios, l'ID socket verrà automaticamente associato a ogni richiesta in uscita come
    header X-Socket-ID. Quindi, quando si chiama il metodo toOthers, Laravel estrarrà l'ID socket
    dall'intestazione e istruirà l'emittente (pusher o altro) a non trasmettere a nessuna connessione con quell'ID socket.

    Se NON si utilizza Vue e Axios, sarà necessario configurare manualmente l'applicazione JavaScript per inviare l'intestazione X-Socket-ID.
    È possibile recuperare l'ID socket utilizzando il metodo Echo.socketId:

    ESEMPIO CON JQUERY
    $.ajaxSetup({
        headers: {
            'X-Socket-Id' : Echo.socketId()
        }
    })

 */




var currentUserId = 21; // Likely set elsewhere

/*
IMPORTO LE DIPENDENZE
 */
import Echo from 'laravel-echo'
window.Pusher = require('pusher-js');






// 1- NON UTILIZZANDO LARAVEL ECHO
/*
var pusher = new Pusher('b7eb697ae374fe38b0ba', {
    cluster: 'eu',
    forceTLS: true,
    authEndpoint: '/broadcasting/auth',
    // Aggiungo all'header il CSRF-TOKEN per la rotta è protetta da middleware VerifyCsrfToken, in alterantiva posso
    // escludere la rotta dal controllo aggiungendo il path della rotta all'array exclude nel VerifyCsrfToken (ovviamente è meglio includere il token nell'header)
    auth: {
        headers: {
            'X-CSRF-TOKEN' : token.content
        },
    },
});

// mi iscrivo a questo canale
var pusherChannel = pusher.subscribe('users.' + currentUserId);

// e dico di stare in ascolto di questo evento
pusherChannel.bind('App\\Events\\UserSuscribedBroadcast', (data) => {
    console.log("Canale Utente vanilla JS");
    console.log(data.userId, data.plan);
})

var pusherChannelAdmin = pusher.subscribe('admins');

// e dico di stare in ascolto di questo evento
pusherChannelAdmin.bind('App\\Events\\UserSuscribedBroadcast', (data) => {
    console.log("Canale Admins vanilla JS");
    console.log(data.userId, data.plan);
})

// mi iscrivo ad un canale privato (dato che sono autenticato avendo inviato il CSRFtoken per autenticarmi e avendo le caratteristiche(verifico che il currentUserId sia io utente loggato) che unirmi a questo canale)
var privateChannel = pusher.subscribe('private-teams.' + currentUserId);

privateChannel.bind('App\\Events\\UserSuscribedBroadcast', (data)=> {
    console.log("Canale privato vanilla JS");
    console.log(data.userId, data.plan);
})

var presenceChannel = pusher.subscribe('presence-rooms.' + currentUserId);
console.log(presenceChannel.members);
*/


// 2- UTILIZZANDO LARAVEL ECHO
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    encrypted: true
});

window.Echo.channel('users.' + currentUserId)
    .listen('UserSuscribedBroadcast', (data) => {
        console.log("Canale pubblico");
        console.log(data.userId, data.plan);
    });
window.Echo.private('teams.' + currentUserId)
    .listen('UserSuscribedBroadcast', (data) => {
        console.log("Canale privato");
        console.log(data.userId, data.plan);
    });

window.Echo.join('rooms.' + currentUserId)
    .here((users) => { // metodo invocato quando accesso (mi viene passato l'array di tutti gli utenti)
        console.log(users);
    }).joining((user) => { // metodo invocato quanto qualcuno entra, mi viene tornato l'oggetto tornato dal metodo definito nel BroadcastServiceProvider
        console.log("Joining", user);
    })
    .leaving((user) => { // metodo invocato quanto qualcuno lascia il canale, mi viene tornato l'oggetto tornato dal metodo definito nel BroadcastServiceProvider
        console.log("Leaving", user);
    });
    //.listen('UserSuscribedBroadcast', (data) => {
    //    console.log("Canale pubblico");
    //    console.log(data.userId, data.plan);
    //});


// Canale usato per le notifiche che vengono sparate tramite broadcast
window.Echo.private('App.User.' + currentUserId)
    .notification((notification) => {
        console.log(notification);
    });