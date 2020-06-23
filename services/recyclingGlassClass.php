<?php

namespace App\services;


class recyclingGlassClass extends servicesAbstractClass
{
    protected int $capacite;
    protected string $type;

    public function __construct(string $type, int $capacite)
    {
        $this->capacite = $capacite;
        $this->type = $type;
    }

    public function getCapacite()
    {
        return $this->capacite;
    }
  
    public function recyclingTreatment($co2, $weight)
    {
        return $co2*$weight;
    }
}