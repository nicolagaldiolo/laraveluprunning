<?php


namespace App\MyClasses;


class LoremIpsum implements LoremIpsumInterface
{
    public function __construct()
    {
        $this->faker = \Faker\Factory::create();
    }

    public function sentence($nbWords = 6, $variableNbWords = true)
    {
        return $this->faker->sentence($nbWords, $variableNbWords);
    }
}