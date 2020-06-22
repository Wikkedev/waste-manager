<?php

namespace App\services;


class composterClass extends servicesAbstractClass
{
    protected int $foyers;
    protected int $capacite;
    protected string $type;

    public function __construct(string $type, int $capacite, int $foyers)
    {
        $this->capacite = $capacite;
        $this->type = $type;
        $this->foyers = $foyers;
    }

    public function getCapacite()
    {
        return $this->capacite;
    }

    public function getFoyers()
    {
        return $this->foyers;
    }
}