<?php


namespace App\MyClasses;

use Illuminate\Support\Facades\Facade;

class LoremIpsumFacade extends Facade
{
    protected static function getFacadeAccessor()
    {

        // Dobbiamo associare la Facade alla classe a cui si riferisce
        // se abbiamo associato nel service provider una chiave alla classe LoremIpsum, possiamo riferirci ad essa con la
        // chiave utilizzata altrimenti dobbiamo riferirci alla classe con il FQCN (Fully Qualified Classname)

        return 'loremIpsum';
        //return LoremIpsum::class;
    }

}