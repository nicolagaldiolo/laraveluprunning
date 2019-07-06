<?php

/*
 * STORAGE
 * i driver per connettersi a filesystem remoti come S3 o Rackspace vengono forniti da flysystem:
 * https://github.com/thephpleague/flysystem
 *
 * Storage::get() || Storage::put() - puntano già alla cartella corretta, es:  /Users/chloe/Projects/Valet/laraveluprunning/storage/app/public
 * storage_path e public_path puntano rispettivamente alle seguenti cartelle quindi deve essere specificato il percorso mancante ('app/public/my_folder') e ('storage/my_folder')
 * storage_path() - /Users/chloe/Projects/Valet/laraveluprunning/storage
 * public_path() - /Users/chloe/Projects/Valet/laraveluprunning/public
 *
 */

use \Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\UploadedFile;

Route::get('storage', function(){

    /*
     * PATH ASSOLUTO ALLO STORAGE (interno a Laravel)
     * tutto ciò che viene passato alla funzione viene aggiunto al path
    */
    $path = storage_path(); // /Users/chloe/Projects/Valet/laraveluprunning/storage
    $public_path = storage_path('public'); // /Users/chloe/Projects/Valet/laraveluprunning/storage/public

    $path = storage_path('public');
    logger($path);

    /*
     * FACADE STORAGE
     * posso accedere allo storage con la facade Storage, se non specifico nulla i file
     * vengono prelevati dal disco di default (ENV) altrimenti posso specificare un disco
     */

    $file = Storage::get('file.xml');
    // $file = Storage::disk('s3')->get('file.xml');


    /*
     * FACADE STORAGE METHODS
     * Metodi per lavorare con i file
     */

    // OTTENERE IL CONTENUTO DEL FILE (equivalente di file_get_contents(public_path() . '/storage/file.xml'))
    $file_get = Storage::get('file_saved/file.xml');


    // SALVARE UN FILE(Accetta una risorsa/handler o uno stream, gestisco io lo stream)
    Storage::put('file_saved/file.xml', $file);


        /* GENERO UN FILE MANUALMENTE SOLO PER AVERE UN FILE SU CUI LAVORARE*/
        $url = public_path() . '/storage/file.xml';
        $info = pathinfo($url);
        $contents = file_get_contents($url);
        $file = '/tmp/' . $info['basename'];
        file_put_contents($file, $contents);
        $uploaded_file = new \Illuminate\Http\UploadedFile($file, $info['basename']);

    // SALVARE UN FILE AUTOMATIC STREAM(Accetta un instanza di Illuminate\Http\File or Illuminate\Http\UploadedFile)
    // larael gestisce in autonomia lo stream del file

    Storage::putFile('file_saved', $uploaded_file); //nome file generato automaticamente
    Storage::putFileAs('file_saved', $uploaded_file, 'mio_nuovo_file.' . $uploaded_file->getClientOriginalExtension()); //nome file generato manualmente


    // SALVARE UN FILE SU DISCO REMOTO (DROPBOX)
    // https://github.com/benjamincrozat/laravel-dropbox-driver
    Storage::disk('dropbox')->put('/', $uploaded_file, $uploaded_file->getClientOriginalName());

    // VERIFICARE SE IL FILE ESISTE
    $file_exist = Storage::exists('file_saved/file.xml');

    // COPIARE IN FILE
    if(!Storage::exists('file_saved/file_copy.xml')) Storage::copy('file_saved/file.xml', 'file_saved/file_copy.xml');


    // SPOSTARE UN FILE
    if(!Storage::exists('file_saved/file_moved.xml')) Storage::move('file_saved/file_copy.xml', 'file_saved/file_moved.xml');


    // PREPENDERE CONTENUTO AD UN FILE
    Storage::prepend('file_saved/file_moved.xml', '<tag>prepended_tag</tag>');


    // APPENDERE CONTENUTO AD UN FILE
    Storage::append('file_saved/file_moved.xml', '<tag>appended_tag</tag>');

    // CANCELLARE UN FILE
    Storage::copy('file_saved/file_moved.xml', 'file_saved/file_to_delete.xml');
    Storage::delete('file_saved/file_to_delete.xml');

    // CREARE/ELIMINARE DIRECTORY
    Storage::makeDirectory('file_saved/directory2delete');
    Storage::deleteDirectory('file_saved/directory2delete');

    // DIMENSIONE DI UN FILE (IN BYTES)
    $file_size = Storage::size('file_saved/file.xml');

    // UNIXTIMESTAMP DI ULTIMA MODIFICA
    $last_modified = Storage::lastModified('file_saved/file.xml');

    // FILE IN DIRECTORY
    $files = Storage::files('file_saved');

    // FILE IN DIRECTORY RECURSIVE
    $all_files = Storage::allFiles();

    // DIRECTORY IN DIRECTORY
    $directories = Storage::directories('file_saved');

    // DIRECTORIES IN DIRECTORY RECURSIVE
    $all_directories = Storage::allDirectories('file_saved');

    $data = [
        'Storage::get' => $file,
        'Storage::exists' => $file_exist,
        'Storage::size' => $file_size,
        'Storage::lastModified' => $last_modified,
        'Storage::files' => $files,
        'Storage::allFiles' => $all_files,
        'Storage::directories' => $directories,
        'Storage::allDirectories' => $all_directories
    ];

    return $data;

});

Route::post('upload-route', function(\Illuminate\Http\Request $request){
    // lancio una validazione manuale dato che sono fuori da un controller e non ho il traits ValidateRequests
    $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
        'picture' => 'required|file'
    ]);

    if($validator->fails()){
        return redirect('/')
            ->withErrors($validator)
            ->withInput();
    }
});
