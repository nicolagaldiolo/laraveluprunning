<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QueryBuilderExampleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Il query Builder di laravel sta alla base di ogni funzionalità di accesso al db

        //
        // PANORAMICA
        //

            // RAW SQL (utilizzando la facade DB)
                // utilizzando il metodo statement
                    DB::statement("DROP TABLE IF EXISTS table_name");

                // utilizzando i metodi specifici + parameter o named binding
                    $user = DB::select('select * from users where id = ?', [1]);
                    $user = DB::select('select * from users where id = :id', ['id' => 1]);
                    //dd($user); // torna un array di risultati

                    $insert = DB::insert('insert into actions (title, body) values(?,?)', ['title', 'body']);
                    //dd($insert); // Torna un boleano

                    $update = DB::update('update actions set title = ? where id = ?', ['nuovo titolo', 1]);
                    //dd($update); // Torna il numero di righe modificate

                    $delete = DB::delete('delete from actions where created_at is null');
                    //dd($delete); // Torna il numero di righe eliminate

                    //DB::delete('SQL Query here...'); // Torna il numero di righe eliminate

            // QUERY BUILDER (utilizzando la facade DB)
                $users = DB::table('users')->get();
                //dd($users); // torna un oggetto collection che include un array di risultati

                $actions = DB::table('tasks')->join('actions', 'tasks.id' , 'actions.task_id')
                    ->where('actions.body', '!=', '')->get();

                $actions = DB::table('tasks')->join('actions', function($join){
                    $join->on('tasks.id' , 'actions.task_id')->where('actions.body', '!=', '');
                })->get(); //dd($actions);

                $data = DB::table('tasks')
                    ->select(DB::raw('*, (priority * 100) AS priority_score'))->get(); //dd($data);


        //
        // METODI DEL QUERY BUILDER
        //

            // METODI DI VINCOLO
                // select()
                $data = DB::table('users')->select('email')->get(); //dd($data);

                // addSelect() - utile per aggiungere condizioni di select
                $data = DB::table('users')->select('email');
                if(true){
                    $data = $data->addSelect('name')->get(); //dd($data);
                }

                // where() V.1
                $data = DB::table('users')
                    ->where('id', '>',  100)
                    ->where('name', 'like', '%Mr.%')->get(); //dd($data);

                // where() V.2
                $data = DB::table('users')->where([
                    ['id', '>',  100],
                    ['name', 'like', '%Mr.%']
                ])->get(); //dd($data);

                // orWhere()
                $data = DB::table('users')
                    ->where('name', 'Nicola')
                    ->orWhere('email', 'galdiolo.nicola@gmail.com')->get(); //dd($data);

                $data = DB::table('users')
                    ->whereNull('email_verified_at')
                    ->orWhere(function($query) {
                        $query->where('id', '>', 100)->where('name', 'like', '%Mr.%');
                    })->get(); //dd($data);

                // whereBetween() || whereNOTBetween()
                $data = DB::table('users')
                    ->whereBetween('id', [1,10])->get(); //dd($data);

                // whereIn() || whereNotIn()
                $data = DB::table('users')->whereIn('id', [1,3,5])->get(); //dd($data);

                // whereNull() || whereNotNull()
                $data = DB::table('users')->whereNull('email_verified_at')->get(); //dd($data);

                // whereRaw() //attenzione che la query non viene escapata quindi c'è il pericolo di sql injection
                $data = DB::table('users')->whereRaw("email = 'galdiolo.nicola@gmail.com'")->get(); //dd($data);

                // whereExists() // utilizza lo statement exists di sql
                $tasks = DB::table('tasks')->whereExists(function ($query){
                    $query->select('id')->from('actions')->whereRaw('actions.task_id = tasks.id');
                })->get(); //dd($tasks);

                // distinct
                $data = DB::table('actions')->select(['title', 'body'])->distinct()->get(); //dd($data);


            // METODI MODIFICATORI
                // orderBy(colName, direction)

                //groupBy() having() havingRaw()
                /*select count(id), title from tasks group by title having count(id) > 4*/
                $tasks = DB::table('tasks')
                    ->selectRaw('count(id) AS id, title')->groupBy('title')
                    ->havingRaw('count(id) > 4')->get(); //dd($tasks);

                //skip() take()
                $data = DB::table('users')->skip(10)->take(10)->get(); //dd($data);


                //latest(colname) oldest(colname) ordinata per la colonna passata, created_at in caso colname non viene passato
                $data = DB::table('users')->latest()->get(); //dd($data);

                //inRandomOrder()
                //$data = DB::table('users')->inRandomOrder()->get(); //dd($data);

            // METODI DI RITORNO / ENDING
                // questi metodi interrompono il query builder e lanciano la query

                // get()

                // first() firstOrFail() // passare i nomi di colonna al metodo first() se vogliamo che vengano tornati solo alcuni campi
                $data = DB::table('users')->first('name'); //dd($data);

                // value() // uguale a find ma torna solo la colonna indicata della prima riga
                $data = DB::table('users')->value('email'); //dd($data);

                // find() findOrFail()
                $data = DB::table('users')->find(1); //dd($data);

                // count()
                $data = DB::table('users')->count(); //dd($data);

                // min() max()
                $data = DB::table('tasks')->max('priority'); //dd($data);

                // sum()
                $data = DB::table('tasks')->sum('priority'); //dd($data);

                // avg()
                $data = DB::table('tasks')->avg('priority'); //dd($data);

        //
        // JOINS / UNIONS / INSERTS / UPDATEDS / DELETES / JSON
        //

            //JOINS
            $data = DB::table('tasks')->join('actions', 'tasks.id', 'actions.task_id')
                ->select('tasks.title AS tasks_title', 'actions.title')->get(); //dd($data);

            $data = DB::table('tasks')->join('actions', function($join){
                $join->on('tasks.id', 'actions.task_id')->orOn('tasks.id', 'actions.task_id2');
            })->get(); //dd($data);

            //UNIONS
            $first = DB::table('tasks')->whereNull('priority');
            $data = DB::table('tasks')->whereNotNull('priority')->union($first)->get(); //dd($data);

            //INSERTS
                // insert e torna l'id appena inserito
                    $id = DB::table('tasks')
                        ->insertGetId(['title' => 'Prepare dinner', 'description' => 'Cook Pizza']); //dd($id);

                // insert singola riga
                    DB::table('tasks')->insert(['title' => 'Go to market', 'description' => 'Buy milk']);

                // insert riga multipla
                    DB::table('tasks')->insert([
                        ['title' => 'play tennis', 'description' => 'at 20.00'],
                        ['title' => 'go to home', 'description' => 'for dinner']
                    ]);

            //UPDATEDS
                DB::table('tasks')->inRandomOrder()->take(1)->update(['priority' => 1000]);
                DB::table('tasks')->increment('priority', 5);
                DB::table('tasks')->decrement('priority');

            //DELETES
                DB::table('tasks')->whereNull('priority')->take(2)->delete();
                DB::table('logs')->truncate();

            //JSON
                DB::table('tasks')->insert([
                    'title' => 'Insert a json field',
                    'description' => 'Insert a json field',
                    'options' => json_encode(['isAdmin' => true])
                ]);

                $data = DB::table('tasks')->where('options->isAdmin', true)->get(); //dd($data);

                DB::table('tasks')->inRandomOrder()->take(5)->update(['options' => json_encode(['isAdmin' => true])]);

        return "Query Builder Example";
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
