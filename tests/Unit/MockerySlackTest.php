<?php

namespace Tests\Unit;

use App\MyClasses\TestClass\Notifier;
use App\MyClasses\TestClass\SlackClient;
use App\User;
use function foo\func;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/*
 * 	MOCKING: Per testare le nostre classi non possiamo SEMPRE basarci sulle classi "reali" in quando dal potrebbe essere
 *  molto difficile utilizzarle nei nostri test (per le loro dipendenze, caratteristiche o perché parlano con servizi
 *  esterni) allora dovremmo creare delle classi FAKE che imitano il comportamento delle classi attuali ma questo sarebbe
 *  un doppio lavoro ed è qui che il framework Mockery ci viene in aiuto facendo questo per noi.
 *
 * NB LARAVEL INCORAGGIO MOLTO L'UTILIZZO DELL'APPLICAZIONE REALE E NON DIPENDENDERE ECCESSIVAMENTE DAI MOCK
 */

class MockerySlackTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testSlack()
    {
        // usare il metodo ->shouldIgnoreMissing(); che fa in modo che se vengono chiamati metodi che non esistono viene tornato null
        // anzichè un accezzione
        $slackMock = \Mockery::mock(SlackClient::class)->shouldIgnoreMissing();
        $slackMock->shouldReceive('send')->once(); // dichiariamo che il metodo deve essere chiamato una sola volta
        //$slackMock->shouldReceive('send')->times(1); // dichiariamo che il metodo deve essere chiamato una sola volta
        //$slackMock->shouldReceive('send')->never(); // dichiariamo che il metodo non deve essere mai chiamato

        $notifier = new Notifier($slackMock);
        $notifier->notifyAdmin("Test Message");
    }

    public function testSlackIoC()
    {
        $slackMock = \Mockery::mock(SlackClient::class); // creo l'istanza
        $slackMock->shouldReceive('send')->once(); // mi assicuro che venga chiamato il metodo

        app()->instance(SlackClient::class, $slackMock);
        app(Notifier::class)->notifyAdmin('Test');

    }


    /*
     * MOCKING SULLE FACADE
     * Posso trattare la facade come fosse un oggetto Mokkato e chiamare i metodi es shouldReceive direttamente sulla facade
     */
    public function test_all_user_route_should_be_cached()
    {

        $user = factory(User::class)->make();
        Cache::shouldReceive('remember')
            ->once()
            ->andReturn(collect([$user])); // sto dichiarando cosa verrà tornato

        $this->get('users')->assertJsonFragment(['name' => $user->name]);

    }

}
