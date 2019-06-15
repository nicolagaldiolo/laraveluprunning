<?php


namespace App\MyClasses;


class TestLoremIpsum implements LoremIpsumInterface
{
    public function sentence()
    {
        return "Questa è una frase per il test";
    }
}